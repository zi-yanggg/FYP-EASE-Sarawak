# EASE Sarawak — Design System

A reference design system extracted from the **EASE Sarawak** codebase (a Computing Technology Final Year Project): a baggage storage and delivery booking platform for travellers in Kuching, Sarawak. The system covers a public marketing & booking website, a payment flow (Stripe), and a back-office admin portal.

> **Brand tagline:** _Travel Light. Travel Smart. Travel with EASE._
> **Product promise:** Kuching Hands-Free Travel.

---

## Sources

| Source | What's in it |
|---|---|
| Local codebase: `easesarawak/` | CodeIgniter 4 / PHP app. Marketing site under `app/Views/{home,about,booking,...}.php`. Admin portal under `app/Views/admin/`. CSS under `public/assets/css/`. Critical token file: `public/assets/css/admin/ease-design-system.css`. |
| Brand fonts | `public/assets/BebasKai.ttf`, `public/assets/Eurostar Regular.ttf`, plus Google Fonts (Oxanium, Public Sans). Copied into `fonts/`. |
| Imagery | `public/assets/images/` — luggage / delivery / Kuching travel photography + the EASE logo system. Copied selectively into `assets/`. |

The project's own `README.md` says the system is built with CodeIgniter 4, MySQL ≥ 5.7, PHP ≥ 8.1, and Stripe for payments. There's no public hosting URL; access is via `http://easesarawak.fyp/` after a local XAMPP setup.

---

## Products represented

1. **Public marketing & booking website** — the front-of-house. Home, About, Booking flow, Payment (Stripe), Booking Confirmation, In-town Delivery info page, Policy/T&C, plus a Forgot/Reset password flow (`ease-auth-shell.css`).
   - Visual personality: photography-led, bold black + gold, pill-shaped section eyebrows.
   - Type: **EurostarRegular** (display & body), **BebasKai** as a decorative fallback.
2. **Admin portal** — the back office. Order management, Booking calendar, User management, Revenue reports, Transaction history, Service management, Promo codes, Refund requests, Contact messages. Built on a customised "KaiAdmin" Bootstrap shell, themed by `ease-design-system.css` (variants **Direction A** = 220 px black sidebar, **Direction D** = 72 px black icon rail).
   - Visual personality: editorial / operations dashboard. Cream paper background, gold accents on black ink chrome.
   - Type: **Oxanium** (UI labels, buttons, tables), **EurostarRegular** (KPI numbers, page titles), **Public Sans** (body, auth pages).

Both surfaces share the same **gold (`#F2BE00`) + near-black + cream** core palette and the same **pill-with-dot** signature element — only the rest of the typographic and density treatment changes between them.

---

## CONTENT FUNDAMENTALS

### Voice & tone
EASE writes like a **friendly travel concierge** — warm and reassuring, lightly aspirational, occasionally a touch promotional, but never silly. The brand is built around relieving a single pain point (carrying luggage around Kuching), so copy is **outcome-led**: it talks about what the traveller _gets_ to do, not the operational mechanics.

> _"Every moment in Kuching is an opportunity for discovery. With EASE, you're free to seize each one. Our seamless luggage storage and delivery services let you explore without limits — no bags to hold you back, no burdens to slow you down."_

### Person & address
- Addresses the customer as **"you"** ("Let us lift that burden off your shoulders").
- The company refers to itself as **"we"** / **"our"** / **"EASE"** — never first-person singular.
- Admin-facing copy switches to imperative & task-led ("View Pending", "Today's Orders", "Click to advance status").

### Casing
- **Hero & section titles:** `ALL CAPS` (`KUCHING HANDS-FREE TRAVEL`, `OUR SERVICES`, `WHY CHOOSE EASE?`). Letterspaced ~1px.
- **Pill eyebrows:** `ALL CAPS` (`• OUR SERVICES •`).
- **Buttons:** `ALL CAPS` (`BOOK NOW`, `CONTACT NOW`, `SUBMIT FORM`, `SCHEDULE TODAY`).
- **Body & paragraphs:** sentence case.
- **Card / service names:** Title Case (`Basic`, `Standard`, `On-demand`, `Book Online`, `Get Confirmation`).
- **Admin sidebar labels:** Title Case (`Order Management`, `Booking Calendar`, `Transaction History`).
- **Admin section labels above the nav (`Overview`, `Orders`, `Users`, `Reports`, `Management`):** Title Case in source but visually rendered as `UPPERCASE` letterspaced micro-labels.

### Sentence construction
- Short, declarative, often two-sentence pairs in marketing copy. First sentence sets the scene; second delivers the promise.
- Generous use of em-dashes for emphasis ("no bags to hold you back—no burdens to slow you down").
- Loves prepositional pairs and triplets in headers: `Travel Light. Travel Smart. Travel with EASE.`
- Pricing is always "Starts from RM__" — never an exact price up-front.

### Word choice / signature phrases
- _hassle-free, hands-free, seamless, peace of mind, smooth journey, hidden gems, hidden gems of Kuching, on-time, prompt, reliable, convenient, complimentary, top-notch security, secure storage_
- Avoids: jargon, abbreviations, technical detail, fee disclosure, anything alarming.
- "Kuching" appears in nearly every paragraph — geo/SEO-anchored.

### Emoji & punctuation
- **No emoji.** The marketing voice is restrained; emoji would clash with the photography-led editorial feel.
- Section eyebrows use small **gold dots (•)** flanking the label as a visual rhythm — they are graphic elements, not emoji.
- Exclamation marks used sparingly, only on contact CTAs (`CONTACT US TODAY!`).

### Microcopy examples
| Context | Copy |
|---|---|
| Hero headline | KUCHING HANDS-FREE TRAVEL |
| Hero sub | Discover the best of Kuching – We ensure you a smooth and hassle-free journey with our easy-to-use Kuching Luggage Storage and Delivery service. |
| Primary CTA | BOOK NOW |
| Service eyebrow | • OUR SERVICES • |
| Service section title | TRAVEL LIGHT WITH EASE |
| Step heading | 01 / Book Online — Reserve the luggage services you need in Kuching with just a few clicks. |
| Promise card | "Travel with peace of mind, knowing your luggage is protected by our top-notch security measures." |
| Closing | AT EASE, WE PROMISE YOU A WONDERFUL AND MEMORABLE JOURNEY IN KUCHING. |
| Admin breadcrumb | EASE Admin · **Dashboard** |
| Admin greeting | Good morning, **Admin** |
| Admin status pill | PENDING / IN PROGRESS / COMPLETED |
| Admin refund declaration | "I declare that the information I have provided in this form is accurate and complete to the best of my knowledge." |

### Internationalisation
The site supports **English, Chinese (Simplified), and Malay** via a runtime DOM-walker that swaps text nodes from a JSON map. Language is picked from a floating bottom-left flag button. Design rule: any tile/label needs to accommodate ~1.5× width for Malay translations.

---

## VISUAL FOUNDATIONS

### Colour
The palette is intentionally narrow:

- **Gold `#F2BE00`** — the singular brand colour. Used for primary buttons, eyebrow dots, hover accents, focus rings, footer rule, pill backgrounds, KPI icon tiles, sidebar active indicators. Sister tones: `#FFD54A` soft, `#D4A700` dark, `#FFF4CC` pale, `#FBF4D8` tint, `#ECE2B4` line.
- **Ink `#0A0A0A`** (with `#000` / `#131313` / `#1E1E1E` / `#2A2A2A` siblings) — used for primary type, navigation rails, sidebars, hero overlays, secondary button states. The brand "darkens to black on hover" — i.e. gold → black is the canonical primary-button hover.
- **Paper `#FFFDF5`** — admin's near-white cream background. Marketing site uses a colder `#F8F9FA` for its alt panels and `#F5F5F5` for the booking page background.
- **Foreground ramp:** `#111827 / #333 / #555 / #6B7280 / #9CA3AF` — five steps of grey.
- **Semantics:** pending = gold, in-progress = brown `#5B532C`, completed = green `#1E8E3E`, danger/refund = `#C04545`, urgent = `#FF8B5A`, info = `#1A6CB0`.

There are essentially **no gradient backgrounds in brand surfaces** — the only gradients used are dark-overlay scrims over photography (`linear-gradient(rgba(0,0,0,.7), rgba(0,0,0,.5))` over hero images) and a left-to-right black-to-transparent scrim on dashboard hero cards.

### Type
| Family | Role | Where it's used |
|---|---|---|
| **EurostarRegular** (TTF, custom) | Display | Marketing site hero, all marketing body, admin KPI numbers, admin page titles, admin avatar initials, logo wordmark. |
| **BebasKai** (TTF, custom) | Decorative caps | Legacy/fallback secondary; present in font-family stacks but rarely active in production. |
| **Oxanium** (Google) | Admin UI chrome | Sidebar labels, buttons, table headers, breadcrumbs, status pills, form labels, pagers, eyebrows. |
| **Public Sans** (Google) | Body | Forgot-password / reset-password (`ease-auth-shell`) and admin paragraph text. |
| **Montserrat** (Google) | Secondary | Fallback inside `var(--font-admin)`. |
| **JetBrains Mono / system mono** | Code | Order IDs, codes, command palette hint chips. |

Letter-spacing tightens with size: hero titles use `-0.01em` to `-0.02em`; eyebrows and small caps open up to `1.2px – 1.6px`. Headings are weight 600–700; eyebrows are weight 800.

### Backgrounds, imagery & textures
- **Photography is the hero.** Travellers, suitcases, luggage carts, hands holding parcels, planes being loaded. The library is **warm-leaning daylight**, faintly desaturated, with a touch of golden hour — no cool blues, no studio black backgrounds. People are shown from waist-down or with their faces partially out of frame; the **luggage is the protagonist**.
- **Full-bleed hero sections** with a `rgba(0,0,0,0.5)` overlay so white type reads. Hero on home animates between two cross-fading photos using a 20s loop with a slow zoom (`scale(1) → scale(1.1)`).
- **Repeating service pattern:** `service-v1-pattern.jpg` — a soft beige paper texture used behind the "Our Services" section.
- **Step-card pattern:** `bg-003-6.png` — a dark gradient panel used as the step-card background, giving each step a moody, branded surface for the gold number to sit on.
- **Hand-drawn illustrations:** none. Iconography is photographic-isolated PNGs (see ICONOGRAPHY).
- **Noise texture** (`noise-2.png`) sits in the asset library but is not actively applied across the site.

### Layout rules
- Marketing site: fixed top navbar (~85 px logo, 0.5 rem padding, `box-shadow: 0 2px 5px rgba(0,0,0,.1)`). Body content is pushed down with a `padding-top` to clear it. Hero is `height: 100vh`. Sections alternate cream/`#F8F9FA` and full-bleed photo with overlay.
- Hero content is centre-aligned (max 1000 px). Subsequent sections use centred eyebrows and two-column splits for content + image.
- Admin: 72 px or 220 px sidebar **sticky** to viewport (`position: sticky; top: 0; height: 100vh`), with a **3 px gold right border**. Main panel scrolls inside; page header is full-width, KPI cards are a 4-column grid (no gap between them — they share interior borders), tables are full-bleed inside their card.
- Language switcher is a **fixed bottom-left** floating button. Lives across every public page.

### Borders, dividers & rules
- **Card borders** are 1 px `#ECE2B4` (gold-line — a desaturated cream-gold), rarely full gold.
- Section dividers in the footer use a **2 px gold** rule (`border-top: 2px solid #f2be00`) — short of full-bleed, capped at ~850 px wide and centred.
- Admin tables use `rgba(242,190,0,.1)` for row separators (almost invisible — operations density).
- KPI grid has internal column-separator borders only, no outer top/bottom rules.

### Corner radii
- Marketing buttons: `5–6 px` (`var(--r-sm)`).
- Marketing pills + CTAs: `50 px` / `999 px` (`var(--r-pill)`).
- Marketing service cards: `10 px`.
- Admin pill buttons: `999 px`.
- Admin cards: **`18 px`** (signature large radius), occasionally `12–14 px` for smaller chips/tiles.
- Admin tiny chips, badges, eyebrow letters: `4 px`.
- Inputs: `6–8 px`.

### Cards
- **Marketing service cards:** white background, `10 px` radius, `box-shadow: 0 4px 12px rgba(0,0,0,.1)`, flex column with a gold icon tile (70 × 70 px, 10 px radius) at the top, then title, gold price, description, gold pill button.
- **Marketing step cards:** dark texture background (`bg-003-6.png`), 250 px wide, `box-shadow: 0 4px 12px rgba(0,0,0,.2)`, big gold number on the right, white step title and grey description left-aligned.
- **Admin cards:** `#fff` background, `1 px solid #ECE2B4` border, **`18 px` radius**, no shadow by default. Card headers are **black with a 2 px gold bottom border** and gold text inside.

### Shadows
- Marketing: `0 2px 5px rgba(0,0,0,.1)` (navbar), `0 4px 12px rgba(0,0,0,.1)` (cards), `0 15px 30px rgba(0,0,0,.4)` (button hover lift).
- Admin: cards mostly **un-shadowed**, relying on the cream paper background and gold-line border for elevation. The order drawer uses `-16px 0 40px rgba(0,0,0,.18)`. Toast uses `0 12px 40px rgba(0,0,0,.32)`.
- Focus rings always use **gold @ 15% opacity, 3 px** (`box-shadow: 0 0 0 3px rgba(242,190,0,.15)`).
- Pulse animation (`dirApulse`): gold ring expanding from 0 to 8 px over 1.6 s — used for "live" indicators on the dashboard.

### Hover & press
- **Primary action hover:** background `gold → black`, text stays white. This is the dominant pattern across both surfaces.
- **Marketing CTAs (pill):** also lift `translateY(-5px)` and grow shadow on hover.
- **Admin small pill buttons:** `translateY(-1px)` + soft shadow on hover.
- **Marketing icon-circle (contact phone / email):** outline fills gold and icon inverts to black on hover.
- **Admin nav rail items:** text + icon shift from `rgba(247,242,220,.55)` → `#fff` + gold icon; active state pins a 3 px gold tab on the left edge.
- **Press / active:** subtle `translateY(1px)` on clickable status pills. No pronounced scale-down anywhere.
- Cards lift `translateY(-5px)` on hover (marketing service/step cards).

### Transparency & blur
- Used very sparingly. Mobile "why choose" quadrants on small screens get `rgba(255,255,255,0.08)` with `backdrop-filter: blur(8px)` + a 5 px gold left border. The hamburger overlay drops a `rgba(0,0,0,0.5)` scrim. No frosted-glass nav, no glassy modals on desktop.

### Imagery treatment (colour vibe)
- Photography skews **warm, golden-hour, slightly faded**. The dominant temperature is amber, hands & faces are softly lit. No moody blue/cyan stock; no extreme monochrome. The brand colour (gold) ties into the imagery without needing colour-correction.
- Overlays on photography are always **flat black at 50–75 % opacity**, never coloured.

### Motion
- Hero photos: 20 s `zoom + crossfade` loop (5 % fade-in, 45 % zoom, 50 % fade-out, scale 1 → 1.15).
- Cards: `transform .3s ease` for hover lifts.
- Buttons: `background .3s ease`, `transform .18s` for admin pills.
- Drawers (admin order detail): `transform .28s cubic-bezier(.2,.7,.2,1)` from `translateX(102%)` to `0`.
- Toast: `.25s` slide-up + fade.
- Command palette: `.2s` scale + opacity from `0.98` to `1`.
- No bounces, no spring physics, no scroll-jacking, no parallax. The motion language is **calm and short** (everything < 0.35 s) — appropriate for a service-oriented site.

### Fixed elements
- **Top navbar** (marketing, fixed, z-1000, white, full-width).
- **Bottom-left language switcher** (fixed, z-9999).
- **Admin sidebar** (sticky inside the flex shell, z-200) — not technically fixed, but visually pinned thanks to `position: sticky` + full viewport height.

---

## ICONOGRAPHY

EASE's icon system is **deliberately mixed** — a side-effect of the site being both a polished marketing surface and a Bootstrap-derived admin portal. Designers reusing this brand should keep the families separated by context.

### What's actually used in production
| Family | Where | How loaded |
|---|---|---|
| **Bootstrap Icons** (v1.11.3) | Marketing site contact section (phone, envelope, geo-alt), language switcher caret, admin sidebar toggle, dark-mode toggle. | CDN: `https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css` |
| **Font Awesome 6.5.0 / 7.0.1** (free) | Admin everywhere — sidebar nav (`fa-home, fa-layer-group, fa-calendar-alt, fa-th-list, fa-user-plus, fa-pen-square, fa-file-invoice, fa-table, fa-tag, fa-envelope, fa-file-invoice-dollar`), KPI tiles (`fa-coins, fa-piggy-bank, fa-shopping-bag, fa-hourglass-half, fa-truck, fa-warehouse`), table actions, social icons in marketing footer (`fa-instagram, fa-tiktok, fa-facebook-f`). | CDN: `https://cdnjs.cloudflare.com/ajax/libs/font-awesome/{6.5.0,7.0.1}/css/all.min.css` |
| **Brand PNG iconography** | Marketing service cards (`case-1.png, baggage.png, suitcase.png`), booking flow (`luggage.png, luggage-quantity.png, distance.png, send-from.png, deliver-to.png`), trust strip (`customer.png, satisfaction.png, delivered.png, delivery-man.png, user.png`). | Bundled in `public/assets/images/`; copied here to `assets/icon-*.png`. |
| **Flag icons** | Language switcher (`gb.png, cn.png, my.png`). | Bundled PNGs. Copied here to `assets/flag-*.png`. |

### Rules
- **Marketing service / feature spots** use the **brand PNG iconography** placed inside a 70×70 px gold-filled rounded tile. Do not mix Font Awesome glyphs into these slots — it breaks the editorial feel.
- **Functional UI** (admin chrome, table actions, contact methods, social) uses **Font Awesome 6 solid**. Stroke weight here is naturally heavy; don't pair with thin line icons.
- **Small affordances** (caret, search hint, dark-mode toggle, hamburger lines) use **Bootstrap Icons** for their subtler weight.
- **Logo glyph** (the "EASE keyhole" mark — `Ease_PNG_File-09.png`) is the only mark used as a favicon and on the admin login/auth screens. Never recolour it.
- **No SVG sprite, no custom icon font, no Lucide / Heroicons set ships with the codebase.** When extending the system, prefer **Font Awesome 6 solid** for parity with the admin portal.
- **No emoji** anywhere on either surface. The bullet "dots" used in pill eyebrows are **CSS-drawn circles**, not Unicode "•".
- Unicode characters are used very sparingly: `▾` for the navbar dropdown caret, `→` (not yet, but recommended) for "next" affordances. Avoid `→ ★ ✓` for status — those have dedicated FA glyphs and brand colours instead.

### Substitutions flagged for review
- **None.** All brand fonts (`EurostarRegular`, `BebasKai`) shipped with the codebase and are mirrored into `fonts/` here. Oxanium and Public Sans load from Google Fonts in production already.

---

## File index

| File | What it is |
|---|---|
| `colors_and_type.css` | All design tokens (gold/ink/paper ramps, type scale, radii, shadows, spacing, motion) plus semantic element styles (h1–h6, buttons, pills, status, cards, forms). Drop-in stylesheet — load before any component CSS. |
| `assets/logo-*.png` | EASE wordmark + isolated keyhole mark + favicon. |
| `assets/icon-*.png` | Brand PNG iconography (luggage, suitcase, delivery man, etc). |
| `assets/photo-*.{webp,jpg,png}` | Travel/luggage photography library. Hero, banner, about, why-choose, valet, traveler, parcels. |
| `assets/flag-*.png` | Language-switcher flags (UK, China, Malaysia). |
| `assets/pattern-*.{jpg,png}` | Service section paper texture + step-card dark gradient pattern. |
| `assets/texture-noise.png` | Optional noise overlay. |
| `fonts/EurostarRegular.ttf`, `fonts/BebasKai.ttf` | Local brand fonts (Oxanium + Public Sans load from Google Fonts). |
| `preview/*.html` | Small cards used to populate the Design System tab — one concept per card (palette, type, pills, buttons, cards, etc). |
| `ui_kits/marketing/` | UI kit for the public website — components + interactive screens. |
| `ui_kits/admin/` | UI kit for the back-office admin portal — components + interactive screens. |
| `SKILL.md` | Cross-compatible Agent-Skills entry point — read this when invoking the system inside another tool. |

---

## Index

1. **Tokens & semantics** → [`colors_and_type.css`](colors_and_type.css)
2. **Marketing kit** → [`ui_kits/marketing/`](ui_kits/marketing/)
3. **Admin kit** → [`ui_kits/admin/`](ui_kits/admin/)
4. **Design System preview cards** → [`preview/`](preview/)
5. **Brand assets** → [`assets/`](assets/) · [`fonts/`](fonts/)
6. **Agent Skill entry** → [`SKILL.md`](SKILL.md)
