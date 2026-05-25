---
name: ease-sarawak-design
description: Use this skill to generate well-branded interfaces and assets for EASE Sarawak — Kuching's hands-free luggage storage & delivery brand. Works for production code, throwaway prototypes, slide decks, and marketing collateral. Contains brand tokens, fonts (EurostarRegular, BebasKai, Oxanium, Public Sans), gold/ink/cream palette, photography library, and two UI kits (public marketing site + admin operations portal).
user-invocable: true
---

Read `README.md` in this skill first — it covers brand voice, casing rules, visual foundations, and iconography conventions in depth.

Then explore the other files you'll need:

- **`colors_and_type.css`** — drop-in stylesheet with every token (gold ramp, ink ramp, paper, fg ramp, semantic colours, type families, scale, radii, shadows, spacing, motion). Pre-built semantic styles for `h1-h6`, `.pill-title`, `.btn-*`, `.card-*`, `.status-pill`, `.svc-pill`.
- **`assets/`** — logos (wordmark + standalone keyhole mark), brand PNG iconography (luggage / suitcase / delivery / send-from / etc), travel photography (warm daylight), service pattern, step-card dark gradient, flags.
- **`fonts/`** — `EurostarRegular.ttf` + `BebasKai.ttf`. Oxanium and Public Sans load from Google Fonts.
- **`ui_kits/marketing/`** — full marketing site recreation. Reusable components (`PillEyebrow`, `ServiceCard`, `StepCard`, `MkNav`, `MkFooter`, `LangSwitcher`, …) + working Home / About / Booking / Payment / Confirmation flow.
- **`ui_kits/admin/`** — admin portal recreation. Reusable components (`Sidebar`, `Topbar`, `KpiCard`, `QueueRow`, `Drawer`, `StatusPill`, …) + working Login / Dashboard / Orders table / Calendar / Profile.
- **`preview/`** — 21 small specimen cards that document the system visually (palette, type, spacing, radii, shadows, all key components).

## How to use it

If you're creating **visual artifacts** (slides, mocks, throwaway prototypes, ads, social posts):
- Copy the assets you need out of `assets/` and `fonts/`.
- Link `colors_and_type.css` from your HTML.
- Use the brand "pill eyebrow + ALL-CAPS section title + sentence-case lede" structure — this is the spine of every EASE layout.
- Pair photography with a 50–75 % black overlay; never use coloured overlays.
- Primary CTA: gold pill / square that turns black on hover. Don't invent new accent colours.
- Body copy is warm-concierge, addresses the customer as "you", omits emoji.

If you're working on **production code** (back-end portal extension, new public page, new admin module):
- Mirror the structure in `ui_kits/`. Components are intentionally small and cosmetic — adapt them, don't fork them whole.
- Pull tokens from `colors_and_type.css` so the rest of the admin portal continues to match.
- For admin pages, follow the Direction A layout (220 px black sidebar, gold border, cream `paper` background, 18 px-radius cards, gold-line borders).
- For marketing pages, fixed top white navbar with the wordmark, BebasKai/Eurostar headings, dark-photo + overlay hero, pill eyebrows.

If the user invokes this skill **without specific guidance**, ask:
1. Are we making a deck, a static mock, a clickable prototype, or production code?
2. Which surface — marketing (public) or admin (operations)?
3. Audience and tone (English / Mandarin / BM)?
4. Do they have copy, photography, or a brief already, or do we make it?
Then act as an expert designer who outputs **HTML artifacts** (slides, prototypes, mocks) _or_ **production code** depending on the answer, leaning on the components and tokens in this skill.

## Don'ts
- Don't introduce blue-purple gradients, emoji cards, or rounded cards with coloured left-border accents — these are AI-slop and clash with EASE's editorial photography-led feel.
- Don't recolour the logo.
- Don't invent new icon families. Use the brand PNG tiles for marketing service slots and Font Awesome 6 solid for chrome — both are documented in the ICONOGRAPHY section of `README.md`.
- Don't pad pages with filler content. EASE pages are tight and outcome-led.
