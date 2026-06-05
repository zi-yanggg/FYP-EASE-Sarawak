/* Marketing UI kit — full screens.
   Each screen returns a fragment that lives below the global navbar. */

const { useState: useState_S, useEffect: useEffect_S } = React;

/* ════════════════════════════════════════════════════════════
   HOME
   ═══════════════════════════════════════════════════════════ */
function HomeScreen({ onNavigate }) {
  const services = [
    { tier: "Basic",      price: 10, icon: "../../assets/icon-storage.png",  desc: "Looking for short-term storage? Our Kuching Luggage Storage service keeps your luggage safe while you explore the city worry-free." },
    { tier: "Standard",   price: 20, icon: "../../assets/icon-baggage.png",  desc: "Enjoy complimentary in-town transfer with 24 hours of secure storage — seamless transfers between selected locations." },
    { tier: "On-demand",  price: 30, icon: "../../assets/icon-suitcase.png", desc: "Carrying oversized luggage or need a specific pickup / drop-off location? Flexible and hassle-free delivery." },
  ];
  const steps = [
    { num: "01", title: "Book Online",     desc: "Reserve the luggage services you need in Kuching with just a few clicks." },
    { num: "02", title: "Get Confirmation", desc: "Receive an instant confirmation with all the details you need." },
    { num: "03", title: "Drop Off",        desc: "Store your luggage with us or schedule a pick-up whenever it suits you." },
    { num: "04", title: "Enjoy Your Trip", desc: "Explore Kuching without the extra weight." },
  ];
  return (
    <>
      {/* Hero */}
      <section className="mk-hero" style={{ marginTop: 0 }}>
        <div
          className="mk-hero-bg"
          style={{ backgroundImage: 'url("../../assets/photo-hero-1.webp")' }}
        ></div>
        <div
          className="mk-hero-bg b"
          style={{ backgroundImage: 'url("../../assets/photo-hero-2.webp")' }}
        ></div>
        <div className="mk-hero-overlay"></div>
        <div className="mk-hero-content">
          <PillEyebrow>Ease Baggage Solutions</PillEyebrow>
          <h1>KUCHING HANDS-FREE TRAVEL</h1>
          <p>
            Discover the best of Kuching — we ensure you a smooth, hassle-free journey
            with our easy-to-use Kuching luggage storage and delivery service.
          </p>
          <div className="mk-hero-btns">
            <BtnPrimary onClick={() => document.getElementById("contact").scrollIntoView({ behavior: "smooth" })}>
              Contact now
            </BtnPrimary>
            <BtnPrimary onClick={() => onNavigate("booking")}>Book now</BtnPrimary>
          </div>
        </div>
      </section>

      {/* Intro */}
      <section className="mk-intro">
        <div className="mk-intro-l">
          <PillEyebrow>Introducing EASE</PillEyebrow>
          <h3>STREAMLINING YOUR TRAVEL</h3>
          <p>
            Every moment in Kuching is an opportunity for discovery. With EASE, you're free to seize each one.
            Our seamless luggage storage and delivery services let you explore without limits — no bags to hold you back,
            no burdens to slow you down.
          </p>
          <p>
            Imagine wandering through vibrant markets, indulging in local cuisine, or uncovering hidden gems,
            all with your hands free and your mind at ease.
          </p>
        </div>
        <div className="mk-intro-r">
          <div className="placeholder" style={{
            background: "url(../../assets/photo-banner-1.webp) center/cover",
          }}></div>
        </div>
      </section>

      {/* Services */}
      <section className="mk-section services" id="services">
        <div className="mk-section-head">
          <PillEyebrow>Our Services</PillEyebrow>
          <h3>TRAVEL LIGHT WITH EASE</h3>
          <p>
            Whether you need secure storage or prompt delivery, we provide reliable and convenient
            solutions to ensure your journey is as smooth as possible.
          </p>
        </div>
        <div className="mk-services-cards">
          {services.map((s) => (
            <ServiceCard key={s.tier} {...s} onBook={() => onNavigate("booking")} />
          ))}
        </div>
      </section>

      {/* How */}
      <section className="mk-section" style={{ background: "#fff" }}>
        <div className="mk-section-head">
          <PillEyebrow>How it works</PillEyebrow>
          <h3>EASE TRAVEL PROCESS</h3>
          <p>Our process is designed to take the stress out of your travel experience.</p>
        </div>
        <div className="mk-steps">
          {steps.map((s) => <StepCard key={s.num} {...s} />)}
        </div>
        <div style={{ marginTop: 40 }}>
          <BtnPrimary onClick={() => onNavigate("booking")}>Book now</BtnPrimary>
        </div>
      </section>

      {/* Why choose */}
      <section className="mk-why">
        <div className="mk-why-content">
          <div className="mk-why-l">
            <PillEyebrow>Why choose EASE?</PillEyebrow>
            <h3>YOUR TRAVEL, OUR COMMITMENT</h3>
            <p>
              We understand that carrying your luggage through the city can be one of the biggest hassles
              when traveling. Let us lift that burden off your shoulders, making your travel in Kuching
              relaxing and enjoyable from start to end.
            </p>
          </div>
          <div className="mk-why-r">
            <WhyQuadrant title="Easy to Use"        body="Our user-friendly website lets you arrange the service you need with just a few clicks." />
            <WhyQuadrant title="Safe Assured"        body="Travel with peace of mind — your luggage is protected by our top-notch security measures." />
            <WhyQuadrant title="Optimal Flexibility" body="Choose when and where to store or retrieve your luggage. Ultimate freedom, on your schedule." />
            <WhyQuadrant title="Fast & Reliable"     body="Experience quick check-in for storage and on-time, prompt delivery." />
          </div>
        </div>
      </section>

      {/* Connect */}
      <section className="mk-connect" id="contact">
        <div className="mk-connect-content">
          <div className="mk-connect-l">
            <PillEyebrow>Connect with us</PillEyebrow>
            <h3>CONTACT US TODAY!</h3>
            <p>Have any questions? Ready to book your baggage storage or delivery service in Kuching?</p>
            <p>Reach us today through our form or the following contact information.</p>
            <div style={{ marginTop: 20 }}>
              <ContactItem icon="bi bi-telephone"    label="Phone Number"   value="+60 187773618" />
              <ContactItem icon="bi bi-envelope"     label="Email Address"  value="easesarawak@gmail.com" />
              <ContactItem icon="bi bi-geo-alt"      label="Office Address" value="No.118, Level 1, Plaza Aurora, Jalan McDougall, 93000 Kuching, Sarawak" />
            </div>
          </div>
          <ConnectForm onSubmit={() => onNavigate("booking")} />
        </div>
      </section>

      {/* CTA */}
      <section className="mk-section cta">
        <h2>AT EASE, WE PROMISE YOU A WONDERFUL AND<br />MEMORABLE JOURNEY IN KUCHING.</h2>
        <p>Travel Light. Travel Smart. Travel with EASE.</p>
        <BtnPrimary onClick={() => onNavigate("booking")}>Schedule today</BtnPrimary>
      </section>
    </>
  );
}

function ConnectForm({ onSubmit }) {
  const [tab, setTab] = useState_S("message");
  return (
    <div className="mk-connect-r">
      <div className="button-row">
        <span
          className={`pill-title ${tab === "message" ? "active" : ""}`}
          onClick={() => setTab("message")}
        >
          <span className="dot"></span>Message us today<span className="dot"></span>
        </span>
        <span
          className={`pill-title ${tab === "refund" ? "active" : ""}`}
          onClick={() => setTab("refund")}
        >
          <span className="dot"></span>Refund form<span className="dot"></span>
        </span>
      </div>

      {tab === "message" ? (
        <>
          <p className="tagline">FILL THE FORM BELOW</p>
          <p className="message-desc">Travel Light. Travel Smart. Travel with EASE.</p>
          <form className="contact-form" onSubmit={(e) => { e.preventDefault(); onSubmit(); }}>
            <div className="row-inputs">
              <input type="email" placeholder="Your email" required />
              <input type="text" placeholder="Your phone number" required />
            </div>
            <input type="text" placeholder="Subject" required />
            <textarea placeholder="Your message" rows="3"></textarea>
            <button type="submit">Submit form</button>
          </form>
        </>
      ) : (
        <>
          <p className="tagline">REFUND REQUEST</p>
          <p className="message-desc">Submit your refund request quickly and easily.</p>
          <form className="contact-form" onSubmit={(e) => { e.preventDefault(); onSubmit(); }}>
            <input type="text"  placeholder="Full name" required />
            <input type="text"  placeholder="Order ID"  required />
            <input type="email" placeholder="Email address" required />
            <textarea placeholder="Reason for refund" rows="2"></textarea>
            <button type="submit">Submit form</button>
          </form>
        </>
      )}
    </div>
  );
}

/* ════════════════════════════════════════════════════════════
   ABOUT
   ═══════════════════════════════════════════════════════════ */
function AboutScreen({ onNavigate }) {
  return (
    <>
      <div className="mk-page-title-band"><h1>About Us</h1></div>

      <section className="mk-section">
        <div className="mk-section-head">
          <PillEyebrow>What we offer</PillEyebrow>
          <h3>YOUR LUGGAGE, OUR PRIORITY</h3>
          <p>
            EASE Sarawak is your trusted partner for stress-free travel in Kuching. We handle the heavy
            lifting so you can spend your time experiencing the city, not lugging suitcases around it.
          </p>
        </div>
        <div style={{ maxWidth: 1100, margin: "0 auto", display: "grid", gridTemplateColumns: "1fr 1fr", gap: 40, textAlign: "left" }}>
          <img src="../../assets/photo-traveler.webp" alt="" style={{ width: "100%", borderRadius: 8 }} />
          <div>
            <h3 style={{ fontSize: "2rem", marginBottom: 16 }}>Built for the modern traveller</h3>
            <p style={{ marginBottom: 14 }}>
              Whether you've just landed at Kuching International, you're checking out of your hotel,
              or you're heading off on a city tour, EASE meets you where you are. Secure storage hubs,
              flexible in-town delivery, on-demand pickups — everything you need to keep moving.
            </p>
            <BtnPrimary onClick={() => onNavigate("booking")}>Book now</BtnPrimary>
          </div>
        </div>
      </section>

      <section className="mk-why">
        <div className="mk-why-content">
          <div className="mk-why-l">
            <PillEyebrow>Our impact</PillEyebrow>
            <h3>TRUSTED BY THOUSANDS OF TRAVELLERS</h3>
            <p>Our customers say it best — and our numbers back them up.</p>
          </div>
          <div className="mk-why-r">
            <WhyQuadrant title="12,000+ bags handled" body="Across storage, in-town delivery, and on-demand pickups since 2024." />
            <WhyQuadrant title="98% on-time"          body="Our delivery operations team meets the agreed window 98 % of the time." />
            <WhyQuadrant title="4.9 ★ average"        body="Customer satisfaction averaged across all booking platforms and languages." />
            <WhyQuadrant title="3 languages"          body="The booking experience is available in English, Mandarin, and Bahasa Malaysia." />
          </div>
        </div>
      </section>

      <section className="mk-section cta">
        <h2>READY TO TRAVEL LIGHTER?</h2>
        <p>Travel Light. Travel Smart. Travel with EASE.</p>
        <BtnPrimary onClick={() => onNavigate("booking")}>Schedule today</BtnPrimary>
      </section>
    </>
  );
}

/* ════════════════════════════════════════════════════════════
   BOOKING (form)
   ═══════════════════════════════════════════════════════════ */
function BookingScreen({ booking, setBooking, onNext }) {
  const services = [
    { id: "storage",  name: "Luggage Storage", from: 10, icon: "../../assets/icon-storage.png" },
    { id: "delivery", name: "In-town Delivery", from: 20, icon: "../../assets/icon-baggage.png" },
    { id: "ondemand", name: "On-demand Pickup", from: 30, icon: "../../assets/icon-suitcase.png" },
  ];
  const setField = (k, v) => setBooking({ ...booking, [k]: v });
  return (
    <div className="mk-page">
      <div className="mk-page-title-band"><h1>Booking</h1></div>
      <div className="mk-booking">
        <div className="mk-booking-info">
          <PillEyebrow>Step 1 of 2</PillEyebrow>
          <h1 style={{ marginTop: 14 }}>LET US HANDLE THE HEAVY LIFTING.</h1>
          <p>
            Choose your service, tell us where to pick up and drop off, and we'll do the rest.
            You'll receive an instant confirmation with everything you need.
          </p>
          <p>Need help? Reach our team on <b>+60 187773618</b>.</p>
        </div>
        <div className="mk-booking-illust">
          <img src="../../assets/photo-parcels.webp" alt="" />
        </div>

        <div className="mk-booking-form">
          <h2>Service details</h2>
          <div className="mk-svc-toggle">
            {services.map((s) => (
              <SvcToggleBtn
                key={s.id}
                active={booking.service === s.id}
                name={s.name}
                from={s.from}
                icon={s.icon}
                onClick={() => setField("service", s.id)}
              />
            ))}
          </div>

          <div className="mk-form-grid">
            <Field label="First name">
              <input value={booking.firstName} onChange={(e) => setField("firstName", e.target.value)} placeholder="Jane" />
            </Field>
            <Field label="Last name">
              <input value={booking.lastName} onChange={(e) => setField("lastName", e.target.value)} placeholder="Tang" />
            </Field>
            <Field label="Email">
              <input type="email" value={booking.email} onChange={(e) => setField("email", e.target.value)} placeholder="jane@example.com" />
            </Field>
            <Field label="Phone number">
              <input value={booking.phone} onChange={(e) => setField("phone", e.target.value)} placeholder="+60 18 777 3618" />
            </Field>
            <Field label="Send from">
              <input value={booking.from} onChange={(e) => setField("from", e.target.value)} placeholder="Hotel Pullman, Jalan Mathies" />
            </Field>
            <Field label="Deliver to">
              <input value={booking.to} onChange={(e) => setField("to", e.target.value)} placeholder="Aiman Boutique Hotel" />
            </Field>
            <Field label="Pickup date">
              <input type="date" value={booking.date} onChange={(e) => setField("date", e.target.value)} />
            </Field>
            <Field label="Pickup time">
              <input type="time" value={booking.time} onChange={(e) => setField("time", e.target.value)} />
            </Field>
            <Field label="Number of bags">
              <select value={booking.bags} onChange={(e) => setField("bags", e.target.value)}>
                <option value="1">1 bag</option>
                <option value="2">2 bags</option>
                <option value="3">3 bags</option>
                <option value="4">4 bags</option>
                <option value="5">5+ bags</option>
              </select>
            </Field>
            <Field label="Promo code">
              <input value={booking.promo} onChange={(e) => setField("promo", e.target.value)} placeholder="Optional" />
            </Field>
            <Field label="Special instructions" full={true}>
              <textarea rows="3" value={booking.notes} onChange={(e) => setField("notes", e.target.value)} placeholder="Anything we should know about your bags or the drop-off?" />
            </Field>
          </div>

          <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginTop: 24, flexWrap: "wrap", gap: 14 }}>
            <div style={{ fontSize: "1.1rem" }}>
              Estimated total: <b style={{ color: "#f2be00", fontSize: "1.4rem" }}>RM {bookingTotal(booking)}.00</b>
            </div>
            <BtnPrimary onClick={onNext}>Continue to payment</BtnPrimary>
          </div>
        </div>
      </div>
    </div>
  );
}

function bookingTotal(b) {
  const base = b.service === "storage" ? 10 : b.service === "delivery" ? 20 : 30;
  const bags = parseInt(b.bags || "1", 10);
  return base + (bags - 1) * 8;
}

/* ════════════════════════════════════════════════════════════
   PAYMENT (Stripe-styled mock)
   ═══════════════════════════════════════════════════════════ */
function PaymentScreen({ booking, onComplete, onBack }) {
  const [card, setCard] = useState_S({ number: "4242 4242 4242 4242", expiry: "06 / 28", cvc: "123", name: "" });
  return (
    <div className="mk-page">
      <div className="mk-page-title-band"><h1>Payment</h1></div>
      <div className="mk-booking">
        <div>
          <PillEyebrow>Step 2 of 2</PillEyebrow>
          <h1 style={{ marginTop: 14, fontSize: "2.4rem" }}>SECURE PAYMENT</h1>
          <p>Your payment is processed by Stripe. We never see or store your full card number.</p>
          <form
            className="mk-stripe-form"
            style={{ margin: 0, maxWidth: "none" }}
            onSubmit={(e) => { e.preventDefault(); onComplete(); }}
          >
            <Field label="Card number">
              <input className="stripe-input" value={card.number} onChange={(e) => setCard({ ...card, number: e.target.value })} />
            </Field>
            <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 12 }}>
              <Field label="Expiry">
                <input className="stripe-input" value={card.expiry} onChange={(e) => setCard({ ...card, expiry: e.target.value })} />
              </Field>
              <Field label="CVC">
                <input className="stripe-input" value={card.cvc} onChange={(e) => setCard({ ...card, cvc: e.target.value })} />
              </Field>
            </div>
            <Field label="Name on card">
              <input value={card.name} onChange={(e) => setCard({ ...card, name: e.target.value })} placeholder="As shown on card" />
            </Field>
            <div style={{ display: "flex", gap: 12, marginTop: 8 }}>
              <button type="button" className="btn-primary" style={{ background: "#fff", color: "#333", border: "1px solid #ddd" }} onClick={onBack}>
                Back
              </button>
              <BtnPrimary type="submit">Pay RM {bookingTotal(booking)}.00</BtnPrimary>
            </div>
          </form>
        </div>

        <div className="mk-summary">
          <h3>Order summary</h3>
          <div className="row"><span>Service</span><span>{serviceLabel(booking.service)}</span></div>
          <div className="row"><span>Customer</span><span>{booking.firstName || "—"} {booking.lastName}</span></div>
          <div className="row"><span>Send from</span><span>{booking.from || "—"}</span></div>
          <div className="row"><span>Deliver to</span><span>{booking.to || "—"}</span></div>
          <div className="row"><span>Pickup</span><span>{booking.date || "—"} · {booking.time || "—"}</span></div>
          <div className="row"><span>Bags</span><span>{booking.bags}</span></div>
          {booking.promo && <div className="row"><span>Promo</span><span style={{ color: "#1E8E3E" }}>{booking.promo}</span></div>}
          <div className="row total"><span>Total</span><b>RM {bookingTotal(booking)}.00</b></div>
        </div>
      </div>
    </div>
  );
}

function serviceLabel(s) {
  return s === "storage" ? "Luggage Storage" : s === "delivery" ? "In-town Delivery" : "On-demand Pickup";
}

/* ════════════════════════════════════════════════════════════
   CONFIRMATION
   ═══════════════════════════════════════════════════════════ */
function ConfirmationScreen({ booking, onHome }) {
  const orderId = "ORD-2026-0518-" + Math.floor(Math.random() * 900 + 100);
  return (
    <div className="mk-page">
      <div className="mk-confirm">
        <div className="seal"><i className="bi bi-check-lg"></i></div>
        <h1>BOOKING CONFIRMED</h1>
        <p className="sub">
          Thank you, {booking.firstName || "traveller"}! Your luggage is in safe hands.
          A confirmation email is on its way to <b>{booking.email || "your inbox"}</b>.
        </p>

        <div className="mk-confirm-details">
          <div className="row"><span className="lbl">Order ID</span><span className="v">{orderId}</span></div>
          <div className="row"><span className="lbl">Service</span> <span className="v">{serviceLabel(booking.service)}</span></div>
          <div className="row"><span className="lbl">Pickup</span>  <span className="v">{booking.date || "TBD"} · {booking.time || "—"}</span></div>
          <div className="row"><span className="lbl">From</span>    <span className="v">{booking.from || "—"}</span></div>
          <div className="row"><span className="lbl">To</span>      <span className="v">{booking.to || "—"}</span></div>
          <div className="row"><span className="lbl">Total paid</span><span className="v">RM {bookingTotal(booking)}.00</span></div>
        </div>

        <div style={{ display: "flex", gap: 12, justifyContent: "center" }}>
          <BtnPrimary onClick={onHome}>Back to home</BtnPrimary>
        </div>
      </div>
    </div>
  );
}

Object.assign(window, {
  HomeScreen, AboutScreen, BookingScreen, PaymentScreen, ConfirmationScreen,
  bookingTotal, serviceLabel, ConnectForm,
});
