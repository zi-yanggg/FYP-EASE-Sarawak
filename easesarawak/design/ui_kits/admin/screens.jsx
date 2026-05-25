/* Admin UI kit — screens. */

const { useState: useState_AS, useMemo: useMemo_AS } = React;

/* ── Seed data ───────────────────────────────────────────── */
const SEED_ORDERS = [
  { id: "ORD-26-0518-A", customer: "Jane Tang",       phone: "+60 16 222 1010", from: "Hilton Kuching",        to: "Aiman Boutique",       service: "delivery", etaTime: "11:42", etaLabel: "Drop-off", status: 1, total: 48,  urgent: true,  bags: 2, weight: "approx. 18 kg" },
  { id: "ORD-26-0518-B", customer: "Marco Antoine",   phone: "+33 6 73 04 11 22", from: "Plaza Aurora",        to: "Hold 24 h",            service: "storage",  etaTime: "12:10", etaLabel: "Pickup",   status: 0, total: 20,  urgent: false, bags: 1 },
  { id: "ORD-26-0518-C", customer: "Liam Khoo",       phone: "+60 19 877 4422", from: "Kuching Airport",     to: "Pullman Hotel",        service: "delivery", etaTime: "09:30", etaLabel: "Drop-off", status: 2, total: 84,  urgent: false, bags: 3 },
  { id: "ORD-26-0518-D", customer: "Priya Naidu",     phone: "+60 12 345 7788", from: "Aiman Boutique",      to: "Damai Beach Resort",   service: "delivery", etaTime: "14:00", etaLabel: "Drop-off", status: 0, total: 56,  urgent: false, bags: 2 },
  { id: "ORD-26-0518-E", customer: "Aung Zin Htet",   phone: "+60 14 002 1199", from: "Kuching Waterfront",  to: "Hold 8 h",             service: "storage",  etaTime: "10:55", etaLabel: "Pickup",   status: 1, total: 30,  urgent: false, bags: 4 },
  { id: "ORD-26-0518-F", customer: "Sophia Lim",      phone: "+60 11 909 5566", from: "Riverside Majestic",  to: "Kuching International",service: "delivery", etaTime: "15:30", etaLabel: "Drop-off", status: 0, total: 64,  urgent: true,  bags: 2 },
  { id: "ORD-26-0518-G", customer: "Daniel Reyes",    phone: "+63 917 220 3344", from: "The Waterfront Hotel",to: "Damai Central",       service: "delivery", etaTime: "13:15", etaLabel: "Drop-off", status: 1, total: 72,  urgent: false, bags: 3 },
  { id: "ORD-26-0517-Z", customer: "Mei Wong",        phone: "+60 18 224 6677", from: "Sarawak Museum",      to: "Imperial Hotel",       service: "delivery", etaTime: "Yest", etaLabel: "Completed",status: 2, total: 38,  urgent: false, bags: 1 },
];

/* ════════════════════════════════════════════════════════════
   DASHBOARD
   ═══════════════════════════════════════════════════════════ */
function DashboardScreen({ onOpenOrder }) {
  const queue = SEED_ORDERS.filter((o) => o.status < 2).slice(0, 5);
  const [tab, setTab] = useState_AS("all");
  const filtered = useMemo_AS(() => {
    if (tab === "all") return queue;
    if (tab === "delivery") return queue.filter((o) => o.service === "delivery");
    if (tab === "storage")  return queue.filter((o) => o.service === "storage");
    if (tab === "urgent")   return queue.filter((o) => o.urgent);
    return queue;
  }, [tab]);

  return (
    <>
      <Topbar
        crumb={<>EASE Admin &middot; <b>Dashboard</b></>}
        action={<button className="btn"><i className="fas fa-plus"></i> New order</button>}
      />
      <div className="content">
        <PageHead
          crumb={<>EASE Admin &middot; <b>Dashboard</b></>}
          title={"Good morning, <b>Benjamin</b>"}
          meta={
            <>
              <span><i className="fas fa-calendar-alt"></i>Monday, 18 May 2026</span>
              <span className="live"><span className="pulse"></span> Live · 4 in progress</span>
            </>
          }
        />

        <KpiGrid>
          <KpiCard icon="fas fa-coins"        label="Today's revenue" value="1,284.50" currency="RM" delta={12} deltaDir="up"   footer="vs yesterday" />
          <KpiCard icon="fas fa-shopping-bag" label="Today's orders"  value="38"                       delta={3}  deltaDir="down" footer="vs yesterday" />
          <KpiCard icon="fas fa-hourglass-half" label="Pending"        value="7"                                                  footer="2 in progress" />
          <KpiCard icon="fas fa-warehouse"    label="Storage holds"   value="24"                                                  footer="9 awaiting pickup" />
        </KpiGrid>

        <SectionHead title="Today's operations" meta="Live · auto-refreshes every 60 s" />
        <Card>
          <CardHead
            pill="Live queue"
            chips={
              <>
                <span className={`chip ${tab === "all"      ? "act" : ""}`} onClick={() => setTab("all")}>All <span className="num">{queue.length}</span></span>
                <span className={`chip ${tab === "delivery" ? "act" : ""}`} onClick={() => setTab("delivery")}>Delivery</span>
                <span className={`chip ${tab === "storage"  ? "act" : ""}`} onClick={() => setTab("storage")}>Storage</span>
                <span className={`chip ${tab === "urgent"   ? "act" : ""}`} onClick={() => setTab("urgent")}>Urgent</span>
              </>
            }
            meta={`${filtered.length} of ${queue.length}`}
          />
          <div className="queue">
            {filtered.map((o) => (
              <QueueRow key={o.id} order={o} onOpen={onOpenOrder} />
            ))}
            {filtered.length === 0 && (
              <div style={{ padding: 30, textAlign: "center", color: "var(--fg4)", fontSize: 13 }}>
                Nothing matches this filter.
              </div>
            )}
          </div>
        </Card>

        <SectionHead title="Recent activity" meta="Last 3 events" />
        <ActivityFeed />
      </div>
    </>
  );
}

function ActivityFeed() {
  const items = [
    { icon: "fas fa-check",       dot: "gold",  body: <>Order <b>ORD-26-0518-C</b> completed by <b>Marco A.</b></>, meta: "11:38 · 4 min ago" },
    { icon: "fas fa-coins",       dot: "green", body: <>Payment of <b>RM 84.00</b> captured · Stripe ch_3O…</>,    meta: "11:38 · 4 min ago" },
    { icon: "fas fa-undo",        dot: "red",   body: <>Refund request submitted by <b>Mei W.</b></>,              meta: "10:21 · 1 h ago" },
  ];
  return (
    <div style={{ display: "grid", gridTemplateColumns: "repeat(3, 1fr)", gap: 0, background: "#fff", border: "1px solid var(--gold-line)", borderRadius: 18, overflow: "hidden" }}>
      {items.map((a, i) => (
        <div key={i} style={{
          display: "flex", gap: 10, padding: "14px 18px",
          borderRight: i < items.length - 1 ? "1px solid #F2EAC4" : "none",
        }}>
          <div style={{
            width: 30, height: 30, borderRadius: 8,
            display: "flex", alignItems: "center", justifyContent: "center",
            color: a.dot === "gold" ? "var(--ink-1)" : "#fff",
            fontSize: 12, flexShrink: 0,
            background: a.dot === "gold" ? "var(--gold)" : a.dot === "green" ? "var(--done)" : "var(--danger)",
          }}>
            <i className={a.icon}></i>
          </div>
          <div style={{ minWidth: 0 }}>
            <div style={{ fontSize: 12, color: "var(--ink-1)", lineHeight: 1.35 }}>{a.body}</div>
            <div style={{ fontFamily: "var(--font-admin)", fontSize: 10, color: "var(--fg4)", marginTop: 3, letterSpacing: ".04em" }}>{a.meta}</div>
          </div>
        </div>
      ))}
    </div>
  );
}

/* ════════════════════════════════════════════════════════════
   ORDER MANAGEMENT (table)
   ═══════════════════════════════════════════════════════════ */
function OrdersScreen({ onOpenOrder }) {
  const [search, setSearch] = useState_AS("");
  const [status, setStatus] = useState_AS("all");
  const [service, setService] = useState_AS("all");

  const rows = useMemo_AS(() => SEED_ORDERS.filter((o) => {
    if (search && !(o.id + " " + o.customer).toLowerCase().includes(search.toLowerCase())) return false;
    if (status !== "all" && String(o.status) !== status) return false;
    if (service !== "all" && o.service !== service) return false;
    return true;
  }), [search, status, service]);

  return (
    <>
      <Topbar
        crumb={<>EASE Admin &middot; <b>Orders</b></>}
        action={
          <>
            <button className="btn ghost"><i className="fas fa-file-export"></i> Export CSV</button>
            <button className="btn"><i className="fas fa-plus"></i> New order</button>
          </>
        }
      />
      <div className="content">
        <PageHead
          crumb={<>EASE Admin &middot; <b>Orders</b></>}
          title="Order Management"
          meta={<><span><i className="fas fa-shopping-bag"></i>{SEED_ORDERS.length} total orders</span></>}
        />

        <FiltersBar search={search} onSearch={setSearch}>
          <select
            value={status} onChange={(e) => setStatus(e.target.value)}
            style={{ padding: "6px 12px", border: "1px solid var(--gold-line)", borderRadius: 999, background: "var(--paper)", fontFamily: "var(--font-admin)", fontSize: 11, fontWeight: 700, textTransform: "uppercase", letterSpacing: ".04em", outline: "none" }}
          >
            <option value="all">All statuses</option>
            <option value="0">Pending</option>
            <option value="1">In Progress</option>
            <option value="2">Completed</option>
          </select>
          <select
            value={service} onChange={(e) => setService(e.target.value)}
            style={{ padding: "6px 12px", border: "1px solid var(--gold-line)", borderRadius: 999, background: "var(--paper)", fontFamily: "var(--font-admin)", fontSize: 11, fontWeight: 700, textTransform: "uppercase", letterSpacing: ".04em", outline: "none" }}
          >
            <option value="all">All services</option>
            <option value="delivery">Delivery</option>
            <option value="storage">Storage</option>
          </select>
          <span style={{ marginLeft: "auto", fontFamily: "var(--font-admin)", fontSize: 11, color: "var(--fg4)", letterSpacing: ".06em", textTransform: "uppercase", fontWeight: 700 }}>
            Showing {rows.length}
          </span>
        </FiltersBar>

        <Card>
          <table className="tbl tbl-striped">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Service</th>
                <th>Customer</th>
                <th>Route</th>
                <th>ETA</th>
                <th className="right">Total</th>
                <th>Status</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              {rows.map((o) => (
                <tr key={o.id} onClick={() => onOpenOrder(o)}>
                  <td><span className="id">{o.id}</span></td>
                  <td><SvcPill type={o.service} /></td>
                  <td>
                    <div style={{ display: "flex", alignItems: "center", gap: 9 }}>
                      <Av name={o.customer} size={28} />
                      <span style={{ fontWeight: 700 }}>{o.customer}</span>
                    </div>
                  </td>
                  <td style={{ fontSize: 12, color: "var(--fg3)" }}>{o.from} <span style={{ color: "var(--gold)" }}>→</span> {o.to}</td>
                  <td style={{ fontFamily: "var(--font-display)", color: "var(--gold)", fontSize: 14 }}>{o.etaTime}</td>
                  <td className="right"><span className="price">RM {o.total}</span></td>
                  <td><StatusPill status={o.status} /></td>
                  <td className="right"><i className="fas fa-chevron-right" style={{ color: "var(--fg5)" }}></i></td>
                </tr>
              ))}
              {rows.length === 0 && (
                <tr><td colSpan={8} style={{ textAlign: "center", padding: 30, color: "var(--fg4)" }}>No orders match.</td></tr>
              )}
            </tbody>
          </table>
        </Card>
      </div>
    </>
  );
}

/* ════════════════════════════════════════════════════════════
   CALENDAR (mini week view)
   ═══════════════════════════════════════════════════════════ */
function CalendarScreen() {
  const days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
  const cells = [
    { d: 18, count: 12, has: true,  active: true  },
    { d: 19, count: 8,  has: true,  active: false },
    { d: 20, count: 14, has: true,  active: false },
    { d: 21, count: 7,  has: true,  active: false },
    { d: 22, count: 11, has: true,  active: false },
    { d: 23, count: 3,  has: true,  active: false },
    { d: 24, count: 0,  has: false, active: false },
  ];
  const hours = ["09:00", "10:00", "11:00", "12:00", "13:00", "14:00"];
  const blocks = [
    { d: 0, h: 0, len: 1, label: "ORD-26-0518-A · Jane Tang",   svc: "delivery", urgent: true },
    { d: 0, h: 2, len: 1, label: "ORD-26-0518-B · Marco A.",     svc: "storage" },
    { d: 2, h: 1, len: 2, label: "ORD-26-0520-X · Group of 4",   svc: "delivery" },
    { d: 4, h: 3, len: 1, label: "ORD-26-0522-Q · Sophia Lim",   svc: "delivery", urgent: true },
    { d: 1, h: 4, len: 1, label: "ORD-26-0519-K · Mei Wong",     svc: "storage" },
  ];
  return (
    <>
      <Topbar
        crumb={<>EASE Admin &middot; <b>Booking Calendar</b></>}
        action={<button className="btn"><i className="fas fa-plus"></i> Block time</button>}
      />
      <div className="content">
        <PageHead
          crumb={<>EASE Admin &middot; <b>Booking Calendar</b></>}
          title="Booking Calendar"
          meta={<><span><i className="fas fa-calendar-alt"></i>Week of 18 May 2026</span></>}
        />

        <Card>
          <CardHead
            pill="This week"
            meta="55 bookings · 9 unassigned"
            right={
              <div style={{ display: "flex", gap: 6, marginLeft: "auto" }}>
                <button className="btn-pill ghost sm">‹</button>
                <button className="btn-pill sm">Today</button>
                <button className="btn-pill ghost sm">›</button>
              </div>
            }
          />
          <div style={{ padding: 18 }}>
            <div style={{ display: "grid", gridTemplateColumns: "60px repeat(7, 1fr)", gap: 8 }}>
              <div></div>
              {cells.map((c, i) => (
                <div key={i} style={{
                  textAlign: "center",
                  padding: "10px 0",
                  borderRadius: 10,
                  background: c.active ? "var(--gold-pale)" : c.has ? "#fff" : "transparent",
                  border: c.has ? "1px solid var(--gold-line)" : "1px dashed var(--gold-line)",
                }}>
                  <div style={{ fontFamily: "var(--font-admin)", fontSize: 10, fontWeight: 800, letterSpacing: 1, color: "var(--fg4)", textTransform: "uppercase" }}>{days[i]}</div>
                  <div style={{ fontFamily: "var(--font-display)", fontSize: 22, color: "var(--ink-1)", lineHeight: 1.1, marginTop: 2 }}>{c.d}</div>
                  <div style={{ fontFamily: "var(--font-admin)", fontSize: 10, color: "var(--fg4)" }}>{c.has ? `${c.count} bookings` : "Off"}</div>
                </div>
              ))}

              {hours.map((h, hi) => (
                <React.Fragment key={hi}>
                  <div style={{ fontFamily: "var(--font-mono)", fontSize: 10, color: "var(--fg4)", padding: "8px 4px", textAlign: "right" }}>{h}</div>
                  {[0,1,2,3,4,5,6].map((di) => {
                    const block = blocks.find((b) => b.d === di && b.h === hi);
                    return (
                      <div key={di} style={{
                        height: 44,
                        border: "1px solid #F2EAC4",
                        borderRadius: 8,
                        background: cells[di].has ? "#fff" : "#FAF6E5",
                        position: "relative",
                        overflow: "hidden",
                      }}>
                        {block && (
                          <div style={{
                            position: "absolute",
                            inset: 4,
                            background: block.urgent ? "rgba(255,139,90,0.15)" : block.svc === "delivery" ? "#FFFDF0" : "#FBF7E3",
                            border: `1px solid ${block.urgent ? "var(--urgent)" : block.svc === "delivery" ? "var(--gold)" : "var(--progress)"}`,
                            borderRadius: 6,
                            padding: "4px 8px",
                            fontFamily: "var(--font-admin)",
                            fontSize: 10,
                            fontWeight: 700,
                            color: block.urgent ? "#8a4220" : block.svc === "delivery" ? "#8A6E00" : "var(--progress)",
                            overflow: "hidden",
                            whiteSpace: "nowrap",
                            textOverflow: "ellipsis",
                          }}>
                            {block.label}
                          </div>
                        )}
                      </div>
                    );
                  })}
                </React.Fragment>
              ))}
            </div>
          </div>
        </Card>
      </div>
    </>
  );
}

/* ════════════════════════════════════════════════════════════
   LOGIN
   ═══════════════════════════════════════════════════════════ */
function LoginScreen({ onLogin }) {
  const [email, setEmail] = useState_AS("admin@easesarawak.fyp");
  const [pwd, setPwd] = useState_AS("••••••••");
  return (
    <div className="login-shell">
      <div className="login-card">
        <div className="brand">
          <img src="../../assets/logo-ease-black.png" alt="EASE Sarawak" />
        </div>
        <h2>Sign in</h2>
        <p className="sub">Welcome back. Login to the operations portal.</p>
        <form onSubmit={(e) => { e.preventDefault(); onLogin(); }}>
          <label>Email</label>
          <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} placeholder="you@easesarawak.fyp" />
          <label>Password</label>
          <input type="password" value={pwd} onChange={(e) => setPwd(e.target.value)} />
          <div className="row">
            <label><input type="checkbox" defaultChecked /> Remember me</label>
            <a>Forgot password?</a>
          </div>
          <button className="submit" type="submit">Login</button>
          <p className="helper">EASE Admin · v2.4 · operations@easesarawak.fyp</p>
        </form>
      </div>
    </div>
  );
}

/* ════════════════════════════════════════════════════════════
   PROFILE
   ═══════════════════════════════════════════════════════════ */
function ProfileScreen() {
  return (
    <>
      <Topbar
        crumb={<>EASE Admin &middot; <b>Profile</b></>}
        action={<button className="btn"><i className="fas fa-edit"></i> Edit profile</button>}
      />
      <div className="content">
        <PageHead
          crumb={<>EASE Admin &middot; <b>Profile</b></>}
          title="Profile"
        />

        <div className="detail-grid">
          <div>
            <div className="detail-block" style={{ display: "flex", alignItems: "center", gap: 22, marginBottom: 18 }}>
              <div style={{
                width: 110, height: 110, borderRadius: "50%",
                background: "var(--ink-1)", border: "3px solid var(--gold)",
                display: "flex", alignItems: "center", justifyContent: "center",
                color: "var(--gold)", fontFamily: "var(--font-display)",
                fontSize: 38, flexShrink: 0,
              }}>BH</div>
              <div style={{ flex: 1 }}>
                <h2 style={{ fontFamily: "var(--font-display)", fontSize: "1.8rem", fontWeight: 400, color: "var(--ink-1)", letterSpacing: "-0.01em", margin: 0 }}>Benjamin Hii</h2>
                <div style={{ fontFamily: "var(--font-admin)", fontSize: 12, color: "var(--fg3)", marginTop: 4 }}>benjamin.hii@easesarawak.fyp</div>
                <div style={{ display: "flex", gap: 8, marginTop: 12 }}>
                  <span style={{ display: "inline-flex", alignItems: "center", padding: "4px 10px", background: "var(--ink-1)", color: "var(--gold)", borderRadius: 999, fontFamily: "var(--font-admin)", fontSize: 10, fontWeight: 800, letterSpacing: ".08em", textTransform: "uppercase" }}>Super admin</span>
                  <span style={{ display: "inline-flex", alignItems: "center", padding: "4px 10px", background: "var(--done)", color: "#fff", borderRadius: 999, fontFamily: "var(--font-admin)", fontSize: 10, fontWeight: 800, letterSpacing: ".08em", textTransform: "uppercase" }}>Active</span>
                </div>
              </div>
            </div>

            <div className="detail-block">
              <h4>Account details</h4>
              <div className="kv-grid">
                <div className="kv"><div className="k">Full name</div><div className="v">Benjamin Hii</div></div>
                <div className="kv"><div className="k">Email</div><div className="v">benjamin.hii@easesarawak.fyp</div></div>
                <div className="kv"><div className="k">Role</div><div className="v">Super Administrator</div></div>
                <div className="kv"><div className="k">Phone</div><div className="v">+60 18 777 3618</div></div>
                <div className="kv"><div className="k">Joined</div><div className="v">14 March 2024</div></div>
                <div className="kv"><div className="k">Last login</div><div className="v">Today · 08:42</div></div>
              </div>
            </div>
          </div>

          <div>
            <div className="detail-block">
              <h4>Activity (last 7 days)</h4>
              <div className="kv" style={{ marginBottom: 12 }}><div className="k">Orders handled</div><div className="v" style={{ fontFamily: "var(--font-display)", fontSize: "1.8rem" }}>112</div></div>
              <div className="kv" style={{ marginBottom: 12 }}><div className="k">Refunds processed</div><div className="v" style={{ fontFamily: "var(--font-display)", fontSize: "1.8rem" }}>4</div></div>
              <div className="kv"><div className="k">Hours logged</div><div className="v" style={{ fontFamily: "var(--font-display)", fontSize: "1.8rem" }}>38 <small style={{ fontSize: "0.7em", color: "var(--fg4)" }}>hrs</small></div></div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}

Object.assign(window, {
  DashboardScreen, OrdersScreen, CalendarScreen, LoginScreen, ProfileScreen,
  SEED_ORDERS,
});
