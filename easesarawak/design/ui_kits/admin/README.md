# Admin UI Kit

Pixel-faithful recreation of the EASE Sarawak operations / admin portal — based on `easesarawak/app/Views/admin/*.php` and the production token file at `easesarawak/public/assets/css/admin/ease-design-system.css` (the "Direction A" 220 px black sidebar variant).

## Files
| File | What's in it |
|---|---|
| `index.html` | Live admin app — Login → Dashboard → Orders (table) → Calendar → Profile, with a clickable sidebar and an order-detail drawer that opens from any queue row or table row. |
| `admin.css` | Self-contained stylesheet: sidebar, topbar, page head, KPI grid, section header, card shell, queue rows, status pills, service pills, filters, tables, drawer, login card, toast, profile. Uses the same tokens documented in [`colors_and_type.css`](../../colors_and_type.css). |
| `components.jsx` | `Sidebar`, `Topbar`, `PageHead`, `KpiGrid`, `KpiCard`, `SectionHead`, `Card`, `CardHead`, `StatusPill`, `SvcPill`, `Av`, `QueueRow`, `FiltersBar`, `Drawer`, `Toast`. |
| `screens.jsx` | `LoginScreen`, `DashboardScreen` (+ `ActivityFeed`), `OrdersScreen`, `CalendarScreen`, `ProfileScreen`, plus the `SEED_ORDERS` fixture used across screens. |

## Screens recreated
- **Login** — derived from `easesarawak/app/Views/admin/login.php`. Dark hero band with logo, body with email/password + remember-me + forgot-password link. The hero strip uses the gold border-bottom that production admin pages share.
- **Dashboard** — operations bridge view: greeting page-head with live pulse, 4-card KPI grid (revenue / orders / pending / storage), live queue card with tab chips (All / Delivery / Storage / Urgent), 3-column activity feed.
- **Order Management** — sticky topbar, filters bar (search + status + service), striped table with gold ink header, order ID in mono, customer with deterministic avatar, route with gold arrow, ETA in Eurostar, status pill at the end. Clicking a row opens the drawer.
- **Booking Calendar** — week strip (Mon–Sun) + time-grid (09–14h) with rendered booking blocks (delivery / storage / urgent). Block colour follows the same service-pill palette used everywhere else.
- **Order detail drawer** — slide-in from the right. Key-value top, two "Send from / Deliver to" routes on gold tint, four-step timeline. Bottom action bar pushes "Mark completed" forward.
- **Profile** — large dark-bordered avatar with super-admin / active badges; account details key-value grid; weekly activity card.
- **Placeholder** — every other sidebar entry (Users, Revenue, Transaction History, Service Management, Promo Code, Contact, Refund Request) routes to a single-card placeholder pointing back at the source PHP view, so the system stays navigable without overpromising mocks.

## Click-through
1. The kit opens on **Login**. Submit drops you on the Dashboard and toasts "Welcome back, **Benjamin**."
2. From the Dashboard, **click any queue row** → order drawer slides in.
3. Inside the drawer, **Mark completed** → toast confirms and the drawer closes.
4. **Sidebar** routes between Dashboard, Orders, Calendar, Profile. Other entries open the placeholder card.
5. Filters on the Orders page narrow the table live (search by ID or customer, filter by status / service).

## Token parity with production
- Gold `#F2BE00`, ink `#0A0A0A`, paper `#FFFDF5`, gold-line `#ECE2B4`, gold-tint `#FBF4D8` — same values as `ease-design-system.css`.
- Status / service pills use the exact same colour pairs.
- Page-head titles use **EurostarRegular** at the same clamp range as the production header pill (`prof-page-title`, `dsh-greeting-title`).
- KPI numbers + EUR-style numerals are EurostarRegular at `2.2rem`, matching `.rpt-kpi-value`.
- All other UI chrome is **Oxanium**.

## What's intentionally simplified
- KPI cards don't auto-cycle (production cycles between 3–4 slides per card).
- No real CSV export, no real Stripe webhook hookup, no real DataTables paging — the table is a single-page list with the production look.
- The order timeline is a placeholder; the production app reads timestamps from the orders + payments tables.
- Calendar is a static week snapshot; production has full month/week/day views via FullCalendar.
- Dark mode toggle (which the production sidebar has) is omitted — every screen here is in the default light theme.
