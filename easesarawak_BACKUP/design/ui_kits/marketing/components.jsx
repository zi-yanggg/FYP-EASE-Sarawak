/* Marketing UI kit — core small components.
   All component names exported to window for cross-file scope. */

const { useState } = React;

/* ── Pill Eyebrow (signature) ─────────────────────────────── */
function PillEyebrow({ children, className = "" }) {
  return (
    <span className={`pill-title ${className}`}>
      <span className="dot"></span>
      {children}
      <span className="dot"></span>
    </span>
  );
}

/* ── Primary square CTA ──────────────────────────────────── */
function BtnPrimary({ children, onClick, type = "button" }) {
  return (
    <button className="btn-primary" type={type} onClick={onClick}>
      {children}
    </button>
  );
}

/* ── Big pill CTA ────────────────────────────────────────── */
function BtnPill({ children, onClick, type = "button" }) {
  return (
    <button className="btn-pill" type={type} onClick={onClick}>
      {children}
    </button>
  );
}

/* ── Navbar (fixed, white) ───────────────────────────────── */
function MkNav({ activePage, onNavigate }) {
  return (
    <nav className="mk-nav">
      <a className="logo" onClick={() => onNavigate("home")} style={{ cursor: "pointer" }}>
        <img src="../../assets/logo-ease-black.png" alt="EASE Sarawak" />
      </a>
      <div className="menu">
        <a className="dropdown" onClick={() => onNavigate("home")}>Menu</a>
        <a onClick={() => onNavigate("about")}>About Us</a>
        <a
          className={`btn-book ${activePage === "booking" ? "active" : ""}`}
          onClick={() => onNavigate("booking")}
        >
          Book Now
        </a>
      </div>
    </nav>
  );
}

/* ── Footer ──────────────────────────────────────────────── */
function MkFooter({ onNavigate }) {
  return (
    <footer className="mk-footer">
      <div className="mk-footer-logo">
        <img src="../../assets/logo-ease-black.png" alt="EASE Sarawak" />
      </div>
      <hr />
      <ul className="mk-footer-links">
        <li><a onClick={() => onNavigate("home")}>Our Services</a></li>
        <li><a onClick={() => onNavigate("home")}>How It Works</a></li>
        <li><a onClick={() => onNavigate("home")}>Why Us</a></li>
        <li><a onClick={() => onNavigate("home")}>Contact Us</a></li>
        <li><a onClick={() => onNavigate("about")}>About Us</a></li>
        <li><a>Privacy Policy</a></li>
        <li><a>Terms &amp; Conditions</a></li>
      </ul>
      <div className="mk-footer-icons">
        <a title="Instagram"><i className="fab fa-instagram"></i></a>
        <a title="TikTok"><i className="fab fa-tiktok"></i></a>
        <a title="Facebook"><i className="fab fa-facebook-f"></i></a>
      </div>
      <p className="mk-footer-copy">© 2026 EASE SARAWAK. All rights reserved.</p>
    </footer>
  );
}

/* ── Language switcher (decorative) ──────────────────────── */
function LangSwitcher() {
  const [open, setOpen] = useState(false);
  const [lang, setLang] = useState({ code: "EN", flag: "../../assets/flag-gb.png" });
  const langs = [
    { code: "EN", label: "English",            flag: "../../assets/flag-gb.png" },
    { code: "中文", label: "Chinese (Simplified)", flag: "../../assets/flag-cn.png" },
    { code: "BM", label: "Malay",              flag: "../../assets/flag-my.png" },
  ];
  return (
    <>
      <button className="lang-toggle" onClick={() => setOpen(!open)}>
        <img src={lang.flag} alt={lang.code} />
        <span>{lang.code}</span>
        <span style={{ marginLeft: 4 }}>{open ? "˅" : "^"}</span>
      </button>
      {open && (
        <div style={{
          position: "fixed", bottom: 64, left: 18,
          background: "#fff", borderRadius: 6, padding: 6, zIndex: 91,
          boxShadow: "0 4px 12px rgba(0,0,0,0.14)", minWidth: 200,
        }}>
          {langs.map((l) => (
            <button
              key={l.code}
              onClick={() => { setLang(l); setOpen(false); }}
              style={{
                width: "100%", display: "flex", alignItems: "center", gap: 10,
                padding: "10px 12px", border: "none", background: "transparent",
                fontSize: 15, textAlign: "left", cursor: "pointer", borderRadius: 4,
              }}
              onMouseEnter={(e) => e.currentTarget.style.background = "#f5f5f5"}
              onMouseLeave={(e) => e.currentTarget.style.background = "transparent"}
            >
              <img src={l.flag} alt={l.code} style={{ width: 28, height: 16, objectFit: "cover" }} />
              <span>{l.label}</span>
            </button>
          ))}
        </div>
      )}
    </>
  );
}

/* ── Service card ────────────────────────────────────────── */
function ServiceCard({ tier, price, icon, desc, onBook }) {
  return (
    <div className="mk-svc">
      <div className="ic"><img src={icon} alt={tier} /></div>
      <h4>{tier}</h4>
      <p className="price">Starts from <b>RM{price}</b></p>
      <p className="desc">{desc}</p>
      <button className="cta" onClick={onBook}>Book now</button>
    </div>
  );
}

/* ── Step card ───────────────────────────────────────────── */
function StepCard({ num, title, desc }) {
  return (
    <div className="mk-step">
      <h4>{num}</h4>
      <h5>{title}</h5>
      <p>{desc}</p>
    </div>
  );
}

/* ── Why-choose quadrant ─────────────────────────────────── */
function WhyQuadrant({ title, body }) {
  return (
    <div className="mk-quadrant">
      <h4>{title}</h4>
      <p>{body}</p>
    </div>
  );
}

/* ── Contact item (phone / email / address) ─────────────── */
function ContactItem({ icon, label, value }) {
  return (
    <div className="contact-item">
      <div className="icon-circle"><i className={icon}></i></div>
      <div className="contact-text">
        <div className="label">{label}</div>
        <div className="value">{value}</div>
      </div>
    </div>
  );
}

/* ── Booking service toggle button ───────────────────────── */
function SvcToggleBtn({ active, name, from, icon, onClick }) {
  return (
    <button className={active ? "active" : ""} onClick={onClick}>
      <div className="ic"><img src={icon} alt={name} /></div>
      <div>
        <div className="name">{name}</div>
        <div className="from">Starts from RM{from}</div>
      </div>
    </button>
  );
}

/* ── Form field ──────────────────────────────────────────── */
function Field({ label, children, full = false }) {
  return (
    <div className={`mk-field ${full ? "full" : ""}`}>
      {label && <label>{label}</label>}
      {children}
    </div>
  );
}

Object.assign(window, {
  PillEyebrow, BtnPrimary, BtnPill,
  MkNav, MkFooter, LangSwitcher,
  ServiceCard, StepCard, WhyQuadrant, ContactItem,
  SvcToggleBtn, Field,
});
