const fs = require("fs");
const {
  Document, Packer, Paragraph, TextRun, AlignmentType, LevelFormat,
  HeadingLevel, BorderStyle, WidthType, ShadingType, Table, TableRow,
  TableCell, PageBreak, TableOfContents, PageNumber, Header, Footer
} = require("docx");

// ---------- palette ----------
const NAVY = "1F3864";
const BLUE = "2E5496";
const ACCENT = "2E75B6";
const LIGHTBLUE = "DEEAF6";
const GREYBOX = "F2F2F2";
const AMBER = "FFF2CC";
const GREEN = "E2EFDA";

// ---------- small helpers ----------
const t = (text, opts = {}) => new TextRun({ text, ...opts });

function para(text, opts = {}) {
  return new Paragraph({
    spacing: { after: opts.after ?? 120, before: opts.before ?? 0, line: 276 },
    alignment: opts.align,
    children: [new TextRun({ text, bold: opts.bold, italics: opts.italics, color: opts.color, size: opts.size })],
  });
}

// a labelled callout box (single cell table)
function callout(label, lines, fill) {
  const kids = [];
  if (label) {
    kids.push(new Paragraph({
      spacing: { after: 60 },
      children: [new TextRun({ text: label, bold: true, size: 19, color: NAVY })],
    }));
  }
  lines.forEach((ln, i) => {
    kids.push(new Paragraph({
      numbering: ln.bullet ? { reference: "callout-bullets", level: 0 } : undefined,
      spacing: { after: i === lines.length - 1 ? 0 : 60, line: 264 },
      children: [
        ln.head ? new TextRun({ text: ln.head, bold: true, size: 19, color: BLUE }) : null,
        new TextRun({ text: ln.text, size: 19 }),
      ].filter(Boolean),
    }));
  });
  return new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [9360],
    borders: {
      top: { style: BorderStyle.SINGLE, size: 1, color: fill },
      bottom: { style: BorderStyle.SINGLE, size: 1, color: fill },
      left: { style: BorderStyle.SINGLE, size: 18, color: ACCENT },
      right: { style: BorderStyle.SINGLE, size: 1, color: fill },
      insideHorizontal: { style: BorderStyle.NONE },
      insideVertical: { style: BorderStyle.NONE },
    },
    rows: [new TableRow({
      children: [new TableCell({
        width: { size: 9360, type: WidthType.DXA },
        shading: { fill, type: ShadingType.CLEAR },
        margins: { top: 120, bottom: 120, left: 160, right: 160 },
        children: kids,
      })],
    })],
  });
}

// a full Q block
let qNum = 0;
function question(qText) {
  qNum += 1;
  return new Paragraph({
    heading: HeadingLevel.HEADING_2,
    spacing: { before: 300, after: 120 },
    children: [new TextRun({ text: `Q${qNum}.  ${qText}`, bold: true, size: 24, color: NAVY })],
  });
}

function probe(text) {
  return new Paragraph({
    spacing: { after: 100, line: 264 },
    children: [
      new TextRun({ text: "Why the panel asks this:  ", bold: true, italics: true, color: ACCENT, size: 19 }),
      new TextRun({ text, italics: true, color: "595959", size: 19 }),
    ],
  });
}

function answerLabel() {
  return new Paragraph({
    spacing: { after: 60, before: 40 },
    children: [new TextRun({ text: "Your answer", bold: true, size: 20, color: BLUE })],
  });
}

// answer paragraph(s)
function ans(text) {
  return new Paragraph({ spacing: { after: 120, line: 276 }, children: [new TextRun({ text, size: 21 })] });
}

const spacer = () => new Paragraph({ spacing: { after: 80 }, children: [] });

// ============================================================
// CONTENT
// ============================================================
const children = [];

// ---- Title page ----
children.push(
  new Paragraph({ spacing: { before: 1400, after: 0 }, alignment: AlignmentType.CENTER,
    children: [new TextRun({ text: "EASE SARAWAK", bold: true, size: 60, color: NAVY })] }),
  new Paragraph({ alignment: AlignmentType.CENTER, spacing: { after: 40 },
    children: [new TextRun({ text: "Final Year Project — Viva / Defense Preparation", size: 28, color: BLUE })] }),
  new Paragraph({ alignment: AlignmentType.CENTER, spacing: { after: 400 },
    children: [new TextRun({ text: "Jury Questions, Defenses & Model Answers", size: 24, italics: true, color: "595959" })] }),
  new Paragraph({ alignment: AlignmentType.CENTER, spacing: { after: 0 },
    border: { top: { style: BorderStyle.SINGLE, size: 6, color: ACCENT, space: 6 },
              bottom: { style: BorderStyle.SINGLE, size: 6, color: ACCENT, space: 6 } },
    children: [new TextRun({ text: "  A booking & delivery management system for Sarawak — CodeIgniter 4, Stripe, MySQL  ", size: 20, color: "595959" })] }),
  new Paragraph({ spacing: { before: 600 }, alignment: AlignmentType.CENTER,
    children: [new TextRun({ text: "Prepared for: Benjamin Hii (Backend / Full-Stack)", size: 22, bold: true })] }),
  new Paragraph({ alignment: AlignmentType.CENTER, spacing: { after: 0 },
    children: [new TextRun({ text: "Team: Benjamin Hii · Lim Zi Yang · Aung Zin Htet · Jostin Chok Yaw Seng", size: 18, color: "595959" })] }),
  new Paragraph({ children: [new PageBreak()] }),
);

// ---- How to use ----
children.push(
  new Paragraph({ heading: HeadingLevel.HEADING_1, children: [new TextRun({ text: "How to use this guide", bold: true, size: 30, color: NAVY })] }),
  ans("Treat this as a sparring partner, not a script. A good panel can tell when an answer is memorised. Read each question, understand why it is being asked, then say the answer in your own words. The single most persuasive thing you can do in a defense is admit a limitation honestly and immediately follow it with how you would address it — that signals engineering maturity far more than pretending the system is perfect."),
  ans("Each question below has three parts: the question as a juror might phrase it, why the panel is really asking it (the skill or judgement they are testing), and a model answer written from your perspective as the backend / full-stack member. Where it helps, a coloured box gives concrete facts from your codebase you can cite, or an honest weakness you should be ready to own."),
  callout("Three rules for the room", [
    { bullet: true, head: "Be concrete. ", text: "Name the file, the model, the Stripe object. “The PaymentIntent is created in CardPayment::createIntent” beats “we handle payments securely.”" },
    { bullet: true, head: "Own the gaps. ", text: "If you know a weakness, say it before they find it. It removes the sting and shows you understand your own system." },
    { bullet: true, head: "Bridge to impact. ", text: "Tie technical choices back to the Sarawak user — a small business owner who needs a reliable, low-friction way to book deliveries." },
  ], LIGHTBLUE),
  new Paragraph({ spacing: { before: 200 }, children: [new TextRun({ text: "Contents", bold: true, size: 24, color: NAVY })] }),
  new TableOfContents("Contents", { hyperlink: true, headingStyleRange: "1-1" }),
  new Paragraph({ children: [new PageBreak()] }),
);

// ============================================================
// SECTION 1 — TECHNICAL DEPTH
// ============================================================
children.push(new Paragraph({ heading: HeadingLevel.HEADING_1, children: [new TextRun({ text: "Part 1 — Technical Depth", bold: true, size: 30, color: NAVY })] }));
children.push(ans("These questions test whether you understand the system you built rather than assembled. Expect the panel to drill into payments and security, because that is where real money and real risk live."));

// Q1 architecture
children.push(question("Walk us through your architecture. What happens, end to end, when a customer makes a booking and pays?"));
children.push(probe("They want to see if you hold the whole system in your head, not just the screen you worked on."));
children.push(answerLabel());
children.push(ans("EASE Sarawak is an MVC application on CodeIgniter 4. The customer moves through the booking views — booking, booking detail, customer detail, then confirmation. On submit, the request reaches Home::saveOrder, which delegates to OrderModel::processAndSaveOrder so all the order-building logic lives in the model rather than the controller. For payment, the browser calls CardPayment::createIntent, which talks to Stripe and returns a client secret; Stripe collects the card details directly so card data never touches our server. After Stripe confirms, the front end calls CardPayment::store, which re-retrieves the PaymentIntent from Stripe and writes the charge ID, amount, currency and status into the payments table. Stripe also calls our webhook independently as a second, trusted confirmation. The admin side then reads orders and payments for fulfilment and reporting."));
children.push(callout("Cite this", [
  { bullet: true, text: "Controllers: Home (booking + orders), CardPayment (Stripe), Admin (1,700-line back office), AuthController/Login, PromoCodeController, Receipt." },
  { bullet: true, text: "Models: OrderModel, PaymentModel, User_model, PromoCodeModel, ServiceManagementModel, ActivityLogModel, MessageModel." },
  { bullet: true, text: "Pattern: thin controllers, business logic in models — e.g. processAndSaveOrder lives in OrderModel, not the controller." },
], LIGHTBLUE));

// Q2 why CI4
children.push(question("Why CodeIgniter 4 and a server-rendered PHP stack rather than a modern framework like Laravel or a React/Node setup?"));
children.push(probe("They are checking that your stack was a decision, not a default."));
children.push(answerLabel());
children.push(ans("The choice was deliberate for the constraints we had. CodeIgniter 4 is lightweight, runs comfortably on standard XAMPP/MySQL shared hosting that is cheap and common in Malaysia, and has a gentle learning curve so all four of us could be productive across the codebase. A heavier framework like Laravel brings power we did not need and more moving parts to deploy; a React/Node SPA would have added a build pipeline and a second runtime for a project whose core is form-driven booking and an admin dashboard — server-rendered pages serve that well. I would happily defend Laravel for a larger team, but for a four-person FYP targeting low-cost Malaysian hosting, CI4 was the pragmatic fit."));
children.push(callout("If pushed on the trade-off", [
  { bullet: true, text: "Honest cost: CI4 has a smaller ecosystem and fewer first-party packages than Laravel, so we wrote more glue code ourselves. That was an acceptable price for deployability and team ramp-up." },
], AMBER));

// Q3 payment security — the big one
children.push(question("Your createIntent endpoint reads the payment amount from the request body. What stops a user from opening dev tools and paying RM1 for an RM200 booking?"));
children.push(probe("This is the sharpest technical question they can ask, and it is a real issue in your current code. They want to see if you understand server-side trust boundaries."));
children.push(answerLabel());
children.push(ans("That is exactly the right thing to scrutinise, and I will be straight about it. In the current build createIntent accepts an amount from the client, which means the amount is only as trustworthy as the browser sending it. The correct design — and the fix I would prioritise — is to never trust a client-supplied price. The server should recompute the amount from authoritative data: look up the service price from the service_management table, apply any promo discount server-side, and build the PaymentIntent from that figure. The client should send the order contents — service, quantity, promo code — not the price. I would also assert the paid amount equals the order total before marking an order paid."));
children.push(ans("What does already protect us is that money settlement goes through Stripe, card data never touches our server, and we re-retrieve the PaymentIntent from Stripe in store() rather than trusting the browser's word that payment succeeded. The webhook gives a second independent confirmation. So the integrity of “did they pay” is sound; the gap is “did they pay the right amount,” and that is a server-side recalculation away."));
children.push(callout("Own it, then close it", [
  { bullet: true, head: "Weakness: ", text: "amount is client-supplied in CardPayment::createIntent." },
  { bullet: true, head: "Fix: ", text: "recompute price + discount server-side from service_management and promo_code tables; verify amount_received against the stored order total before fulfilment." },
], AMBER));

// Q4 webhook vs store
children.push(question("You write to the payments table both in store() after the client confirms and again in the Stripe webhook. Why both, and how do you avoid duplicates or race conditions?"));
children.push(probe("Tests whether you understand idempotency and why webhooks exist at all."));
children.push(answerLabel());
children.push(ans("The two paths exist for different reasons. store() gives the customer immediate feedback the moment Stripe confirms in the browser, so the UI can move on. The webhook is the trustworthy path: Stripe calls it server-to-server and we verify its signature with the webhook secret, so it is authoritative even if the user closes the tab mid-redirect. The webhook uses save() keyed on payment_intent_id, so it updates the existing row rather than blindly inserting a second one. To make this fully robust I would enforce a unique constraint on payment_intent_id at the database level and treat the webhook as the single source of truth for status, with store() only used for the optimistic UI update. That removes any race between the two paths."));
children.push(callout("Cite this", [
  { bullet: true, text: "Webhook verifies authenticity with Webhook::constructEvent and the STRIPE_WEBHOOK_SECRET — a forged callback is rejected." },
  { bullet: true, text: "Handles payment_intent.succeeded and payment_intent.payment_failed; other events are logged." },
], LIGHTBLUE));

// Q5 promo code
children.push(question("Walk me through the promo code logic. Can a code be reused indefinitely, and is the discount validated on the server?"));
children.push(probe("Probes a second client-trust gap and your validation thinking."));
children.push(answerLabel());
children.push(ans("checkPromoCode looks the code up in the promo_code table, filters out soft-deleted codes, and validates the active window — it rejects a code whose validation_date is in the future or whose expired_date has passed. It then returns the discount type, either a fixed amount or a percentage. Two things I would tighten. First, there is currently no usage cap — no per-code or per-user redemption limit — so a code is reusable until it expires; I would add a max-uses column and a redemptions table to enforce that. Second, the discount is validated on the server but, like the price, it must be re-applied to the total on the server at payment time, not trusted from the client. Both are small schema-and-logic additions on top of the validation that already works."));
children.push(callout("Own it, then close it", [
  { bullet: true, head: "Works today: ", text: "active-window validation (validation_date / expired_date), soft-delete aware, amount vs percentage types." },
  { bullet: true, head: "To add: ", text: "usage limits + redemption tracking; server-side application of the discount to the final charge." },
], AMBER));

// Q6 security broadly
children.push(question("How do you protect the admin area and the application generally — auth, roles, and the common web vulnerabilities?"));
children.push(probe("A breadth check on security fundamentals."));
children.push(answerLabel());
children.push(ans("Access control is enforced in initController on the Admin controller: every admin action checks the session for a valid access flag and a role of 0 (super admin) or 1 (admin), and redirects to login otherwise, so there is no admin route you can reach unauthenticated. CodeIgniter's query builder is used throughout, which parameterises queries and protects against SQL injection, and the framework escapes output in views to mitigate XSS. CSRF protection is available as a framework filter. Passwords are hashed, not stored in plaintext. For honesty, two hardening items I would do before production: I noticed saveOrder sets an open CORS header (Access-Control-Allow-Origin: *) that was added for debugging and should be removed, and I would move authorisation into a route filter rather than a header() redirect inside the controller, which is cleaner and harder to bypass."));
children.push(callout("Own it, then close it", [
  { bullet: true, head: "Strong: ", text: "role-gated admin (roles 0/1), query-builder parameterisation, hashed passwords, soft deletes preserve an audit trail, ActivityLogModel records admin actions." },
  { bullet: true, head: "Harden: ", text: "remove the wildcard CORS header on saveOrder; enforce auth via a CI filter; confirm CSRF filter is enabled on all state-changing POSTs." },
], AMBER));

// Q7 PII / refunds
children.push(question("Your refund form collects bank name, account holder and account number. How is that sensitive financial data stored and protected?"));
children.push(probe("Tests data-protection awareness — increasingly expected, and relevant to Malaysia's PDPA."));
children.push(answerLabel());
children.push(ans("The refund request captures bank details so an admin can process a manual payout, and the data is stored in the refund table. I will be honest that in the current build those fields are stored as entered, which is a real data-protection concern. The improvements I would make are concrete: encrypt the account number at rest using CodeIgniter's Encryption service so a database leak does not expose raw account numbers, restrict refund-table access to the relevant admin role, mask the number in any admin view to the last few digits, and add a retention rule so details are purged once a refund is settled. Under Malaysia's PDPA we are also obliged to collect only what we need and tell users how it is used, which our policy and T&C pages begin to cover. This is the kind of finding I would want surfaced in a security review rather than hidden."));
children.push(callout("Own it, then close it", [
  { bullet: true, head: "Concern: ", text: "bank account numbers stored without encryption in the refund table." },
  { bullet: true, head: "Fix: ", text: "encrypt at rest, role-restrict access, mask in UI, add retention/purge, align with PDPA minimisation." },
], AMBER));

// Q8 testing
children.push(question("You have a tests directory and phpunit configured. What is your actual test coverage, and what is tested?"));
children.push(probe("They will check whether “we have tests” means meaningful tests or scaffolding."));
children.push(answerLabel());
children.push(ans("I want to be accurate rather than oversell this. We set up the PHPUnit harness — phpunit.xml.dist is configured and we have unit, database and session test scaffolding — but a good portion of what is committed is still close to the framework's example tests, so our real coverage is thin. If asked what I would test first given the time, I would prioritise by risk: the payment path (amount calculation, the store/webhook idempotency), promo-code validation including the expiry boundaries, and the access-control checks on admin routes. Those are the areas where a regression costs money or leaks data, so they earn the first tests. I would rather tell you our coverage is a known gap than claim a green bar we do not have."));
children.push(callout("Own it, then close it", [
  { bullet: true, head: "Reality: ", text: "harness configured (unit/database/session), but coverage is limited and partly example-based." },
  { bullet: true, head: "Plan: ", text: "risk-first tests — payment amount + idempotency, promo expiry boundaries, admin authorisation." },
], AMBER));

// Q9 data model / two order models
children.push(question("I see both an OrderModel and an Order_model, and similar duplication elsewhere. Is that intentional?"));
children.push(probe("A sharp reader spots inconsistency; they want to see if you know your own codebase and can talk about technical debt."));
children.push(answerLabel());
children.push(ans("Good catch — that is honest technical debt from a four-person team evolving the code over a semester. We have a newer OrderModel (PascalCase, the convention we standardised on) alongside an older Order_model, and similarly User_model predates our naming convention. They arose because different members started work in parallel before we agreed conventions. It is not causing bugs, but it is the kind of inconsistency I would clean up: consolidate to one order model, settle on CodeIgniter's naming convention across all models, and remove the dead one once I have confirmed nothing references it. I would not ship that duplication to production, but I would not hide it from you either."));

// ============================================================
// SECTION 2 — PROBLEM & IMPACT
// ============================================================
children.push(new Paragraph({ children: [new PageBreak()] }));
children.push(new Paragraph({ heading: HeadingLevel.HEADING_1, children: [new TextRun({ text: "Part 2 — Problem & Impact", bold: true, size: 30, color: NAVY })] }));
children.push(ans("These questions test whether you solved a real problem for real people or built a generic CRUD app with a Sarawak label on it. Connect every answer to the user."));

// Q10
children.push(question("What specific problem does EASE Sarawak solve, and for whom? Why does Sarawak need this rather than an existing delivery app?"));
children.push(probe("The heart of the project. They want a crisp problem statement and a real user, not a vague “digitalisation” pitch."));
children.push(answerLabel());
children.push(ans("EASE Sarawak addresses the gap between large national logistics platforms and the small, local delivery and errand needs that those platforms serve poorly here. Sarawak is geographically large and less densely covered than peninsular Malaysia, and many small businesses and individuals still arrange deliveries by phone, WhatsApp and manual record-keeping — which is error-prone, hard to track, and gives the operator no booking history or revenue visibility. Our system gives a local delivery operator a proper online booking front end with card payment, and a back office to manage orders, customers, pricing, promotions and revenue. The target user is a small-to-mid local delivery business and its customers, not someone competing with a national courier. The value is operational: fewer missed bookings, payment collected up front, and a clear record of what was ordered and earned."));

// Q11
children.push(question("How did you validate that this is a genuine need and not an assumption?"));
children.push(probe("Tests research rigour and intellectual honesty about evidence."));
children.push(answerLabel());
children.push(ans("Our strongest validation is the structural reality: the friction of manual, phone-based booking for local operators is well documented and observable, and the absence of affordable, self-managed booking software at this scale is real. I will be honest that as a student project our primary-research sample was limited rather than a large formal study, so I would frame our evidence as indicative. If I were taking this further, the next step would be to put it in front of a handful of real local operators, watch them take bookings through it for a week, and measure concrete things — bookings captured versus lost, time per booking, payment-collection rate — and let that data, not our assumption, drive the roadmap."));
children.push(callout("Honest framing", [
  { bullet: true, text: "Say “indicative evidence + a clear validation plan,” not “we proved the market.” Panels respect calibrated claims and distrust overclaiming." },
], AMBER));

// Q12
children.push(question("Who are your competitors, and what is your actual differentiation?"));
children.push(probe("Checks commercial awareness and whether you can position honestly."));
children.push(answerLabel());
children.push(ans("At the consumer end there are national platforms and courier apps; at the tooling end there are generic booking and POS systems. We do not out-scale the former or out-feature the latter. Our differentiation is fit: a focused, low-cost, self-hostable booking-and-admin tool tuned to a local Sarawak delivery operator's workflow, with localisation in mind — we built a language switcher (LanguageController) because serving customers in more than one language matters here. The honest positioning is “right-sized and local,” not “better than Grab.” For a small operator, a tool they can afford and run themselves is more useful than a platform that takes a cut and owns their customer relationship."));

// Q13
children.push(question("What is the real-world impact if this were adopted? Quantify it if you can."));
children.push(probe("They want you to think in outcomes, and to be careful not to invent numbers."));
children.push(answerLabel());
children.push(ans("The impact is operational efficiency and revenue capture for a small business. Concretely: bookings that would have been lost to a missed call or a forgotten WhatsApp message are captured and paid for up front; the operator gets a revenue report and CSV export instead of a notebook, which makes tax and planning easier; and promo codes give them a lever to drive demand in quiet periods. I would not put a precise percentage on it without a pilot, but the mechanism of impact is clear — reduce booking friction and leakage, and give the owner visibility they did not have. That is the number I would want to measure in a trial rather than guess at now."));

// ============================================================
// SECTION 3 — FUTURE & LIMITATIONS
// ============================================================
children.push(new Paragraph({ children: [new PageBreak()] }));
children.push(new Paragraph({ heading: HeadingLevel.HEADING_1, children: [new TextRun({ text: "Part 3 — Future Work & Limitations", bold: true, size: 30, color: NAVY })] }));
children.push(ans("This is where the panel rewards self-awareness. A candidate who can critique their own system convincingly almost always scores higher than one who defends it as flawless."));

// Q14
children.push(question("What are the three most important limitations of your system as it stands today?"));
children.push(probe("The honesty test. Naming real limitations first is a strength, not a weakness."));
children.push(answerLabel());
children.push(ans("First, server-side trust: the payment amount and promo discount are currently influenced by the client, and that has to move fully server-side before this handles real money — it is my top priority. Second, data protection: refund bank details are stored without encryption, which needs fixing for PDPA compliance and basic hygiene. Third, test coverage and consistency: our automated tests are thin and there is naming duplication in the models, so the codebase is not yet at the reliability bar I would want for production. None of these are architectural dead-ends — they are well-understood fixes — but I would rather name them plainly than claim the system is production-ready when it is a strong prototype."));

// Q15
children.push(question("If this had to serve a thousand operators and their customers nationwide, what breaks first, and how would you scale it?"));
children.push(probe("Tests whether you understand scalability beyond “add a bigger server.”"));
children.push(answerLabel());
children.push(ans("The first pressure points are the database and the single-server XAMPP deployment model. I would start by moving off shared XAMPP hosting to a managed environment, putting the app behind a load balancer with multiple stateless PHP instances, and moving sessions out of the filesystem into Redis so any instance can serve any request. On the data side I would add indexes on the high-traffic columns — order status, created_date for reporting, the foreign keys — and introduce read replicas so heavy admin reporting queries do not contend with live booking writes. The revenue report aggregates over the order table on every load; at scale I would precompute daily summaries rather than recomputing on the fly. None of this requires a rewrite — CI4 scales horizontally fine when sessions and state are externalised — it is an infrastructure and indexing exercise."));
children.push(callout("Cite this", [
  { bullet: true, text: "Reporting already aggregates today/yesterday/week/month revenue from the order table — the obvious candidate for caching/summary tables at scale." },
  { bullet: true, text: "Soft deletes (is_deleted) mean queries must always filter; indexing those flags matters as data grows." },
], LIGHTBLUE));

// Q16
children.push(question("If you had three more months, what would you build next and why?"));
children.push(probe("Checks prioritisation — do you fix the foundation or chase shiny features?"));
children.push(answerLabel());
children.push(ans("I would spend the first month on correctness and safety, not features: move pricing and discounts fully server-side, encrypt the refund data, and write the risk-first test suite around payments and access control. With that foundation solid, month two would add the operational features a real operator would ask for first — delivery status tracking with customer notifications, and a driver/assignment view. Month three I would invest in the data they cannot get today: a better analytics dashboard and a customer-facing order-tracking link. The order is deliberate — I would not build tracking on top of a payment flow that can be tampered with."));

// Q17
children.push(question("What was the hardest technical problem you personally solved, and how?"));
children.push(probe("Tests depth of your individual contribution as the backend lead."));
children.push(answerLabel());
children.push(ans("The hardest piece for me was making the Stripe payment flow trustworthy. The naive version — let the browser tell the server the payment succeeded — is easy and wrong. Getting it right meant understanding the PaymentIntent lifecycle: creating the intent server-side, letting Stripe collect the card so we never handle card data, then confirming the result by re-retrieving the PaymentIntent from Stripe in store() rather than trusting the client, and adding a signature-verified webhook as the authoritative confirmation that fires even if the user closes the tab. Reasoning through the failure cases — tab closed mid-payment, duplicate callbacks, the difference between amount and amount_received — was where most of the real learning was, and it is why I can also tell you precisely where the flow still needs hardening."));

// Q18
children.push(question("If a juror says: “Honestly, this is just a CRUD app with a payment button. What makes it engineering?” — how do you respond?"));
children.push(probe("A deliberately provocative question. They want to see you stay composed and reframe, not get defensive."));
children.push(answerLabel());
children.push(ans("That is a fair challenge, and I would not get defensive about it. A lot of valuable software is CRUD at its core — the engineering is in the parts that are not CRUD. Here that is the payment integrity work: a third-party payment lifecycle, idempotent handling across an optimistic client path and an authoritative signed webhook, role-based access control, soft deletes with an activity audit trail, and localisation. The honest version is that the booking and admin screens are CRUD and that is fine — they should be simple. The engineering judgement shows up in how we handled money, trust boundaries, and the limitations I have been upfront about. I would rather be the candidate who can tell you exactly which 20% was hard than one who claims all of it was."));

// Q19 — humane closer
children.push(question("What would you do differently if you started this project again tomorrow?"));
children.push(probe("A humane, reflective question. They are looking for growth and self-knowledge, not a list of regrets."));
children.push(answerLabel());
children.push(ans("Two things. First, I would agree conventions and a thin test harness on day one as a team — the model-naming duplication and the thin coverage both trace back to four people starting in parallel before we aligned, and an afternoon of agreement up front would have saved that. Second, I would design the payment and pricing as server-authoritative from the very first commit instead of retrofitting trust later; it is much cheaper to build the trust boundary in than to add it afterwards. Beyond the code, I would get it in front of a real operator earlier, because the most useful feedback always came from imagining the actual person using it, and I would rather have had a real one."));

// ---- Closing ----
children.push(new Paragraph({ children: [new PageBreak()] }));
children.push(new Paragraph({ heading: HeadingLevel.HEADING_1, children: [new TextRun({ text: "Final preparation checklist", bold: true, size: 30, color: NAVY })] }));
children.push(callout("The night before", [
  { bullet: true, head: "Run it. ", text: "Have the system running locally so you can demo the booking-to-payment flow and the admin dashboard live if asked." },
  { bullet: true, head: "Know three numbers. ", text: "Lines in the Admin controller (~1,700), the models you own, the Stripe events you handle. Specificity reads as mastery." },
  { bullet: true, head: "Rehearse the hard one. ", text: "Be able to explain the client-trusted-amount weakness and its fix in under a minute, calmly." },
  { bullet: true, head: "Prepare one honest weakness per area. ", text: "Payments, data protection, testing. Volunteering them disarms the panel." },
  { bullet: true, head: "Bridge to the user. ", text: "End technical answers with the Sarawak operator who benefits. That is the project's soul." },
], GREEN));
children.push(spacer());
children.push(new Paragraph({ spacing: { before: 120 }, alignment: AlignmentType.CENTER,
  border: { top: { style: BorderStyle.SINGLE, size: 4, color: ACCENT, space: 6 } },
  children: [new TextRun({ text: "You built a real system that handles real money for real people. Defend it with that confidence — and the honesty to know exactly where it can be better.", italics: true, size: 20, color: "595959" })] }));

// ============================================================
// DOCUMENT
// ============================================================
const doc = new Document({
  creator: "EASE Sarawak FYP",
  title: "EASE Sarawak — FYP Defense Preparation",
  styles: {
    default: { document: { run: { font: "Calibri", size: 22 } } },
    paragraphStyles: [
      { id: "Heading1", name: "Heading 1", basedOn: "Normal", next: "Normal", quickFormat: true,
        run: { size: 30, bold: true, font: "Calibri", color: NAVY },
        paragraph: { spacing: { before: 240, after: 160 }, outlineLevel: 0,
          border: { bottom: { style: BorderStyle.SINGLE, size: 6, color: ACCENT, space: 4 } } } },
      { id: "Heading2", name: "Heading 2", basedOn: "Normal", next: "Normal", quickFormat: true,
        run: { size: 24, bold: true, font: "Calibri", color: NAVY },
        paragraph: { spacing: { before: 240, after: 120 }, outlineLevel: 1 } },
    ],
  },
  numbering: {
    config: [
      { reference: "callout-bullets",
        levels: [{ level: 0, format: LevelFormat.BULLET, text: "•", alignment: AlignmentType.LEFT,
          style: { paragraph: { indent: { left: 360, hanging: 220 } } } }] },
    ],
  },
  sections: [{
    properties: { page: { size: { width: 12240, height: 15840 },
      margin: { top: 1440, right: 1440, bottom: 1440, left: 1440 } } },
    footers: { default: new Footer({ children: [new Paragraph({
      alignment: AlignmentType.CENTER,
      children: [
        new TextRun({ text: "EASE Sarawak — FYP Defense Prep   |   Page ", size: 16, color: "808080" }),
        new TextRun({ children: [PageNumber.CURRENT], size: 16, color: "808080" }),
      ] })] }) },
    children,
  }],
});

Packer.toBuffer(doc).then((buffer) => {
  fs.writeFileSync("/sessions/sharp-dazzling-johnson/mnt/outputs/EASE_Sarawak_FYP_Defense_Prep.docx", buffer);
  console.log("Document written.");
});
