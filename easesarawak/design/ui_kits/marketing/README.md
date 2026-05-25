# Marketing UI Kit

Pixel-faithful recreation of the EASE Sarawak public marketing & booking site.

## Files
| File | What's in it |
|---|---|
| `index.html` | Live single-page app — Home → About → Booking → Payment → Confirmation, with a top-right demo bar to jump between screens. |
| `marketing.css` | All marketing-site CSS — hero, intro split, services grid, step cards, why-quadrants, contact, CTA, navbar, footer, booking form, payment, confirmation. |
| `components.jsx` | Reusable small components: `PillEyebrow`, `BtnPrimary`, `BtnPill`, `MkNav`, `MkFooter`, `LangSwitcher`, `ServiceCard`, `StepCard`, `WhyQuadrant`, `ContactItem`, `SvcToggleBtn`, `Field`. |
| `screens.jsx` | Full-page screens: `HomeScreen`, `AboutScreen`, `BookingScreen`, `PaymentScreen`, `ConfirmationScreen`, plus the embedded `ConnectForm`. |

## Screens recreated
- **Home** — based on `easesarawak/app/Views/home.php`. Hero with cross-fading background photos, intro split with media, services grid, how-it-works steps on dark texture cards, why-choose 2×2 quadrants on a dark photo, contact section with tabbed message/refund form, closing CTA.
- **About** — derived from `easesarawak/app/Views/about.php`. Title band, offer split, impact grid on dark photo, closing CTA.
- **Booking** — derived from `easesarawak/app/Views/booking.php`. Service toggle (3 services), full form, live total estimate.
- **Payment** — Stripe-style mock based on `easesarawak/app/Views/payment.php`. Order summary on the right.
- **Confirmation** — order ID, delivery details, total — based on `easesarawak/app/Views/booking_confirmation.php`.

## Components reused across pages
- The **fixed top navbar** + **black footer with gold rule** are global (sit outside the screen switch).
- The **bottom-left language switcher** mirrors the production `lang-toggle` (EN / 中文 / BM).
- **Pill eyebrows** with two gold dots are the signature element across every section header.

## Click-through
The demo bar in the top-right routes between screens. The Home page CTAs also navigate to Booking; Booking's Continue goes to Payment; Payment's Pay button goes to Confirmation; the confirmation page returns home. Booking data persists across the flow.

## What's intentionally simplified
- No real Stripe integration — the card inputs are visual only.
- No real validation, no real i18n — the language switcher changes its own label only.
- No real "scroll-to" smoothness on home — the demo nav uses instant scroll to keep state predictable.
- Booking total uses a simplified `base + (bags-1)*8` formula; the production app calculates from a server-side service-management table.
