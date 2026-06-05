/* Admin UI kit — reusable components. */

const { useState: useState_C, useMemo: useMemo_C } = React;

/* ── Sidebar (Direction A) ───────────────────────────────── */
function Sidebar({ current, onNavigate, badges = {} }) {
  const nav = [
    { sec: "Overview" },
    { id: "dashboard", icon: "fas fa-home",                label: "Dashboard" },
    { sec: "Orders" },
    { id: "orders",    icon: "fas fa-layer-group",         label: "Order Management", badge: badges.orders },
    { id: "calendar",  icon: "fas fa-calendar-alt",        label: "Booking Calendar" },
    { sec: "Users" },
    { id: "users",     icon: "fas fa-th-list",             label: "User Management" },
    { id: "createuser",icon: "fas fa-user-plus",           label: "Add User" },
    { sec: "Reports" },
    { id: "revenue",   icon: "fas fa-pen-square",          label: "Revenue" },
    { id: "txn",       icon: "fas fa-file-invoice",        label: "Transaction History" },
    { sec: "Management" },
    { id: "service",   icon: "fas fa-table",               label: "Service Management" },
    { id: "promo",     icon: "fas fa-tag",                 label: "Promo Code" },
    { id: "contact",   icon: "fas fa-envelope",            label: "Contact" },
    { id: "refund",    icon: "fas fa-file-invoice-dollar", label: "Refund Request", badge: badges.refund },
  ];
  return (
    <div className="sb">
      <a className="sb__brand" onClick={() => onNavigate("dashboard")} style={{ cursor: "pointer" }}>
        <img src="../../assets/logo-ease-mark.png" alt="EASE" />
        <div>
          <div className="name">EASE</div>
          <div className="tag">Operations</div>
        </div>
      </a>
      {nav.map((item, i) => item.sec ? (
        <div key={i} className="sb__sec">{item.sec}</div>
      ) : (
        <ul key={item.id} className="sb__nav">
          <li>
            <a
              className={current === item.id ? "act" : ""}
              onClick={() => onNavigate(item.id)}
            >
              <i className={item.icon}></i>
              {item.label}
              {item.badge && <span className="bub">{item.badge}</span>}
            </a>
          </li>
        </ul>
      ))}
      <a className="sb__foot" onClick={() => onNavigate("profile")} style={{ cursor: "pointer" }}>
        <div className="av">BH</div>
        <div className="info">
          <div className="uname">Benjamin Hii</div>
          <div className="role">Super Admin</div>
        </div>
      </a>
    </div>
  );
}

/* ── Topbar ──────────────────────────────────────────────── */
function Topbar({ crumb, action }) {
  return (
    <div className="topbar">
      <div className="crumb">{crumb}</div>
      <div className="cmd">
        <i className="fas fa-search"></i>
        <span>Search orders, users, transactions…</span>
        <kbd>⌘K</kbd>
      </div>
      {action}
    </div>
  );
}

/* ── Page header ─────────────────────────────────────────── */
function PageHead({ crumb, title, meta }) {
  return (
    <div className="page-head">
      <div>
        <div className="crumb">{crumb}</div>
        <h1 dangerouslySetInnerHTML={{ __html: title }}></h1>
        {meta && <div className="meta">{meta}</div>}
      </div>
    </div>
  );
}

/* ── KPI grid + card ─────────────────────────────────────── */
function KpiGrid({ children }) { return <div className="kpis">{children}</div>; }

function KpiCard({ icon, label, value, currency, delta, deltaDir = "up", footer }) {
  return (
    <div className="kpi">
      <div className="eb">
        <span className="ic"><i className={icon}></i></span>
        <span>{label}</span>
      </div>
      <div className="v">{currency && <span style={{ fontFamily: "var(--font-admin)", fontSize: "1rem", fontWeight: 700, color: "var(--fg3)", marginRight: 4 }}>{currency}</span>}{value}</div>
      <div className="m">
        {delta != null && (
          <span className={`dt ${deltaDir === "down" ? "dn" : ""}`}>
            {deltaDir === "down" ? "▼" : "▲"} {Math.abs(delta)}%
          </span>
        )}
        {footer}
      </div>
    </div>
  );
}

/* ── Section header (editorial rule) ─────────────────────── */
function SectionHead({ title, meta }) {
  return (
    <div className="shead">
      <span className="ttl">{title}</span>
      <span className="rule"></span>
      {meta && <span className="meta">{meta}</span>}
    </div>
  );
}

/* ── Generic card shell ──────────────────────────────────── */
function Card({ children, className = "" }) {
  return <div className={`card ${className}`}>{children}</div>;
}

function CardHead({ pill, chips, meta, right }) {
  return (
    <div className="card__head">
      {pill && (
        <span className="ttl">
          <span className="dot"></span>
          {pill}
        </span>
      )}
      {chips && <div className="chips">{chips}</div>}
      {meta && <span className="meta">{meta}</span>}
      {right}
    </div>
  );
}

/* ── Status pill ─────────────────────────────────────────── */
function StatusPill({ status, onClick }) {
  const map = { 0: ["pending", "Pending"], 1: ["progress", "In Progress"], 2: ["done", "Completed"], 3: ["refund", "Refunded"], 4: ["urgent", "Urgent"] };
  const [cls, label] = map[status] || ["pending", String(status)];
  return <span className={`st ${cls}`} onClick={onClick}>{label}</span>;
}

/* ── Service pill ────────────────────────────────────────── */
function SvcPill({ type }) {
  const isDelivery = type === "delivery";
  return <span className={`svc-pill ${isDelivery ? "delivery" : "storage"}`}>{isDelivery ? "Delivery" : "Storage"}</span>;
}

/* ── Avatar (deterministic colour) ───────────────────────── */
function Av({ name, size = 36 }) {
  const palette = ["#5B532C", "#1A6CB0", "#2BA869", "#B8860B", "#6A4FBB", "#C04545", "#2E7D32", "#F2BE00"];
  const initials = useMemo_C(() => {
    if (!name) return "??";
    const parts = name.trim().split(/\s+/);
    return (parts[0][0] + (parts[1]?.[0] || "")).toUpperCase();
  }, [name]);
  const hue = useMemo_C(() => {
    let h = 0;
    for (const c of name || "") h = (h * 31 + c.charCodeAt(0)) % palette.length;
    return palette[h];
  }, [name]);
  return (
    <div className="av" style={{
      width: size, height: size, background: hue,
      color: hue === "#F2BE00" ? "var(--ink-1)" : "#fff",
      fontSize: size * 0.34,
    }}>
      {initials}
    </div>
  );
}

/* ── Queue row ───────────────────────────────────────────── */
function QueueRow({ order, onOpen }) {
  return (
    <div className={`qrow ${order.urgent ? "urg" : ""}`} onClick={() => onOpen(order)}>
      <div className="eta">
        {order.etaTime}
        <small>{order.etaLabel}</small>
      </div>
      <div className="who">
        <Av name={order.customer} />
        <div>
          <div className="nm">{order.customer}</div>
          <div className="sub">{order.id} · <SvcPill type={order.service} /></div>
        </div>
      </div>
      <div className="route">
        {order.from}
        <span className="arr">→</span>
        {order.to}
      </div>
      <div className="right">
        <span className="price">RM {order.total}</span>
        <StatusPill status={order.status} />
      </div>
    </div>
  );
}

/* ── Filters bar ─────────────────────────────────────────── */
function FiltersBar({ search, onSearch, children }) {
  return (
    <div className="filters">
      <div className="srch">
        <i className="fas fa-search"></i>
        <input
          type="text"
          placeholder="Search orders…"
          value={search}
          onChange={(e) => onSearch(e.target.value)}
        />
      </div>
      {children}
    </div>
  );
}

/* ── Drawer ──────────────────────────────────────────────── */
function Drawer({ open, order, onClose, onAdvance }) {
  if (!order) return null;
  const stages = [
    { label: "Booked",     ts: "Apr 26 · 09:14", state: "done" },
    { label: "Confirmed",  ts: "Apr 26 · 09:14", state: "done" },
    { label: "In Progress",ts: order.status >= 1 ? "Apr 26 · 11:02" : "—", state: order.status >= 1 ? "done" : "" },
    { label: "Completed",  ts: order.status >= 2 ? "Apr 26 · 14:30" : "—", state: order.status >= 2 ? "done" : "muted" },
  ];
  return (
    <>
      <div className={`drawer-scrim ${open ? "open" : ""}`} onClick={onClose}></div>
      <div className={`drawer ${open ? "open" : ""}`}>
        <div className="drawer__h">
          <div>
            <div className="id">Order detail</div>
            <div className="ttl">{order.id}</div>
          </div>
          <button className="x" onClick={onClose}><i className="fas fa-times"></i></button>
        </div>
        <div className="drawer__b">
          <div className="drawer__top">
            <div className="drawer__kv">
              <div className="k">Customer</div>
              <div className="v">{order.customer}</div>
            </div>
            <div className="drawer__kv">
              <div className="k">Phone</div>
              <div className="v">{order.phone || "—"}</div>
            </div>
            <div className="drawer__kv">
              <div className="k">Service</div>
              <div className="v"><SvcPill type={order.service} /></div>
            </div>
            <div className="drawer__kv">
              <div className="k">Status</div>
              <div className="v"><StatusPill status={order.status} /></div>
            </div>
            <div className="drawer__kv">
              <div className="k">Bags</div>
              <div className="v">{order.bags || 2} · {order.weight || "approx. 18 kg"}</div>
            </div>
            <div className="drawer__kv">
              <div className="k">Total</div>
              <div className="v" style={{ fontFamily: "var(--font-display)", fontSize: "1.4rem" }}>RM {order.total}</div>
            </div>
          </div>

          <div className="drawer__route">
            <div className="ic"><i className="fas fa-map-marker-alt"></i></div>
            <div style={{ flex: 1 }}>
              <div className="lab">Send from</div>
              <div className="lo">{order.from}</div>
              <div className="tm">{order.etaTime} · {order.etaLabel}</div>
            </div>
          </div>
          <div className="drawer__route">
            <div className="ic"><i className="fas fa-flag-checkered"></i></div>
            <div style={{ flex: 1 }}>
              <div className="lab">Deliver to</div>
              <div className="lo">{order.to}</div>
              <div className="tm">Expected drop-off · within 90 min</div>
            </div>
          </div>

          <div className="drawer__rule">
            <span className="lbl">Timeline</span>
            <span className="line"></span>
          </div>
          <div className="drawer__tl">
            {stages.map((s, i) => (
              <div key={i} className={`row ${s.state}`}>
                <div className="bul">{i + 1}</div>
                <div className="body">
                  <div className="t">{s.label}</div>
                  <div className="d">{s.ts}</div>
                </div>
              </div>
            ))}
          </div>
        </div>
        <div className="drawer__f">
          <button className="btn-pill ghost" onClick={onClose}>Close</button>
          {order.status < 2 && (
            <button className="btn-pill" onClick={() => onAdvance(order)}>
              {order.status === 0 ? "Start delivery" : "Mark completed"}
            </button>
          )}
        </div>
      </div>
    </>
  );
}

/* ── Toast ───────────────────────────────────────────────── */
function Toast({ open, children, icon = "fas fa-check-circle" }) {
  return (
    <div className={`toast ${open ? "open" : ""}`}>
      <i className={icon}></i>
      {children}
    </div>
  );
}

Object.assign(window, {
  Sidebar, Topbar, PageHead,
  KpiGrid, KpiCard, SectionHead,
  Card, CardHead,
  StatusPill, SvcPill, Av,
  QueueRow, FiltersBar, Drawer, Toast,
});
