# EASE Sarawak — Architecture & Design Recommendations

**Project:** EASE Sarawak — Luggage Delivery & Storage Booking Platform
**Prepared for:** EASE Sarawak Development Team (4 Developers)
**Date:** 2026-04-22

---

## Table of Contents

1. [Project Context](#1-project-context)
2. [Data Collected from Customers](#2-data-collected-from-customers)
3. [Non-Functional Requirements Summary](#3-non-functional-requirements-summary)
4. [Part 1 — Architecture Recommendations (Without CI/CD)](#part-1--architecture-recommendations-without-cicd)
   - [Architecture A — Enhanced Monolith](#architecture-a--enhanced-monolith-lowest-complexity)
   - [Architecture B — Separated API + Frontend](#architecture-b--separated-api--frontend-recommended)
   - [Architecture C — Cloud-Native Containerized](#architecture-c--cloud-native-containerized-most-scalable)
   - [Comparison Summary (Part 1)](#comparison-summary-part-1)
   - [Cross-Cutting Concerns](#cross-cutting-concerns-all-architectures)
5. [Part 2 — Architecture Recommendations (With CI/CD)](#part-2--architecture-recommendations-with-cicd)
   - [Shared CI/CD Foundations](#shared-foundations-across-all-architectures)
   - [Architecture A — Enhanced Monolith with CI/CD](#architecture-a--enhanced-monolith-with-cicd)
   - [Architecture B — Separated API + Frontend with CI/CD](#architecture-b--separated-api--frontend-with-cicd-recommended)
   - [Architecture C — Cloud-Native Containers with CI/CD](#architecture-c--cloud-native-containers-with-cicd)
   - [Comparison Summary (Part 2)](#updated-comparison-summary-part-2)
6. [Final Recommendation](#final-recommendation)

---

## 1. Project Context

**EASE Sarawak** is a luggage delivery and storage booking platform for travelers in Sarawak, Malaysia — similar in booking flow to Grab, Uber, or LuggageHero.

**Services offered:**
- In-town luggage delivery (pickup from one location, deliver to another)
- Luggage storage (drop-off and pick-up at a storage location)

**Users:**
- **Public booking site** — travelers and tourists (hundreds of concurrent users, scalable)
- **Admin dashboard** — EASE Sarawak staff and workers (dozens of concurrent users)

**Deployment target:** Cloud-first, but must also be deployable on local servers.

**Geographic scope:** Currently Kuching, Sarawak — designed to expand to future city locations within Sarawak.

---

## 2. Data Collected from Customers

Based on the booking flow ([bookingcustomerdetail.php](easesarawak/app/Views/bookingcustomerdetail.php), [bookingdetail.php](easesarawak/app/Views/bookingdetail.php)):

| Data Type | Field | Sensitivity |
|-----------|-------|-------------|
| Personal | First Name, Last Name | Medium |
| Identity | NRIC / Passport / Driving License number | **High** |
| Contact | Email address | Medium |
| Contact | Phone number | Medium |
| Social | WhatsApp / WeChat / Line handle | Medium |
| Travel | Baggage photos, travel documents (file upload) | **High** |
| Booking | Service type, origin, destination, dates, times | Low |
| Booking | Luggage quantity, special luggage notes | Low |
| Payment | Card payments via Stripe (card data never touches server) | **High** |
| Pricing | Promo codes, discounts, insurance selection | Low |

**Compliance obligations:**
- **PDPA Malaysia** — Personal Data Protection Act (personal info, IDs, contacts)
- **PCI-DSS** — Payment Card Industry standard (Stripe.js handles card data server-side isolation)
- **Document retention** — uploaded travel documents must have defined retention and deletion policies

---

## 3. Non-Functional Requirements Summary

| Requirement | Specification |
|-------------|--------------|
| **Scalability** | Booking site: hundreds of concurrent users, auto-scale capable. Admin: dozens. |
| **Separation** | Booking site and admin site must be independently deployable |
| **Availability** | Globally accessible; locally focused on Sarawak with future city expansion |
| **Usability** | Easy for elderly users and low-tech-literacy users (Grab/Uber-level simplicity) |
| **Accessibility** | WCAG 2.1 AA compliance target |
| **Security** | PDPA, PCI-DSS, admin cybersecurity best practices (MFA, audit logs, rate limiting) |
| **Portability** | Cloud-deployable AND locally deployable on physical servers |
| **Team size** | 4 developers |

---

# Part 1 — Architecture Recommendations (Without CI/CD)

---

## Architecture A — Enhanced Monolith (Lowest Complexity)

### Overview

Stay server-rendered but upgrade to Laravel, split booking and admin as separate subdomains pointing to the same codebase, and add a CDN and caching layer.

### Architecture Diagram

```
[CloudFlare CDN]
       |
  [Nginx Reverse Proxy]
  /               \
[booking.ease.com] [admin.ease.com]
       |                |
  [Laravel App — single deployment]
       |
  [MySQL] + [Redis Cache/Sessions]
       |
  [S3/Object Storage — document uploads]
```

### How It Addresses Non-Functional Requirements

| NFR | Solution |
|-----|---------|
| **Scalability** | Vertical scaling (bigger VM), Redis for session offload, CDN absorbs static traffic |
| **Usability** | Laravel Blade + Bootstrap, no JS framework complexity, WCAG-friendly |
| **Security** | Laravel's built-in CSRF, bcrypt, middleware, environment-based secrets |
| **Separation** | Booking and Admin on separate subdomains, middleware guards admin routes |
| **Compliance (PDPA/PCI)** | Stripe.js keeps card data off server, documents encrypted in S3 |

### Pros

- Fastest to build — 4 devs can maintain it easily
- Easiest to deploy locally (still runs on LAMP/LEMP)
- No build pipeline complexity
- Laravel has excellent PDPA-ready tooling (data masking, auditing)

### Cons

- Scaling booking independently requires scaling the whole app
- Harder to add non-PHP services later (e.g. Python for AI)
- Single point of failure without clustering

### Estimated Monthly Cost

| Component | Service | Cost |
|-----------|---------|------|
| App server | 1x DO Droplet 4GB / AWS t3.medium | ~$24–$37/mo |
| Database | DO Managed MySQL / AWS RDS t3.micro | ~$15–$25/mo |
| Cache | Redis (same server or DO $10 plan) | ~$0–$15/mo |
| Storage | S3 / DO Spaces (~10GB) | ~$2–$5/mo |
| CDN | CloudFlare Free | $0 |
| **Total** | | **~$41–$82/mo** |

### Key Reading

- [Laravel Official Docs](https://laravel.com/docs/11.x)
- [Laravel Security Best Practices](https://laravel.com/docs/11.x/csrf)
- [PDPA Malaysia Overview](https://www.pdp.gov.my)
- [DigitalOcean — Deploy Laravel on Ubuntu](https://www.digitalocean.com/community/tutorials/how-to-deploy-a-laravel-application-with-nginx-on-ubuntu-22-04)

---

## Architecture B — Separated API + Frontend *(Recommended)*

### Overview

Split booking site and admin site into independent deployments. A single Laravel REST API serves both. This directly satisfies the requirement for the booking site to be separable from the admin site.

### Architecture Diagram

```
[CloudFlare CDN + WAF]
         |
   [Load Balancer]
   /             \
[Booking App]  [Admin App]
  (server-rendered   (server-rendered
   Laravel Blade      or Vue.js SPA)
   or Next.js)
         \             /
        [Laravel REST API]
               |
    [MySQL Primary] → [Read Replica]
               |
         [Redis]   [S3 Uploads]
               |
        [Queue Worker]
        (emails, notifications)
```

### How It Addresses Non-Functional Requirements

| NFR | Solution |
|-----|---------|
| **Scalability** | Booking app scales independently from admin; API scales horizontally |
| **Usability** | Booking site optimised for mobile and low tech literacy; admin is a separate concern |
| **Security** | API uses JWT/Sanctum tokens, rate limiting per endpoint, WAF on CloudFlare |
| **Separation** | Truly separate deployments — different servers, different code |
| **Future cities** | Add city config to API; booking site reads it dynamically |
| **Compliance** | API-level data access controls, audit log middleware, document encryption |

### What Each Component Does

| Component | Responsibility |
|-----------|---------------|
| **Laravel API** | All business logic, database, Stripe webhooks, file uploads, auth |
| **Booking App** | Lightweight, public-facing, optimised for mobile and low-literacy UX |
| **Admin App** | Internal tool, richer UI (DataTables, charts), stricter auth (MFA, IP whitelist) |
| **Queue Worker** | Booking confirmations, email receipts, document processing asynchronously |

### Pros

- Clean separation as required
- Booking site can be deployed on a CDN edge (very fast globally)
- Admin can be locked down to specific IPs
- API can serve a mobile app in the future
- Each layer scales independently

### Cons

- More moving parts — 3 separate deployments vs 1
- Requires disciplined API design (versioning, documentation)
- 4 devs must coordinate API contracts

### Estimated Monthly Cost

| Component | Service | Cost |
|-----------|---------|------|
| API server x2 (load balanced) | DO Droplet 2GB x2 | ~$24/mo |
| Booking app | DO App Platform / Vercel | ~$0–$12/mo |
| Admin app | DO Droplet 1GB (private network) | ~$6/mo |
| Database | DO Managed MySQL with 1 read replica | ~$30–$50/mo |
| Redis | DO Managed Redis | ~$15/mo |
| Storage | S3 / DO Spaces | ~$5/mo |
| Queue | Supervisor on API server | $0 |
| CDN/WAF | CloudFlare Pro | ~$20/mo |
| **Total** | | **~$100–$132/mo** |

### Key Reading

- [Laravel Sanctum (API Auth)](https://laravel.com/docs/11.x/sanctum)
- [Laravel Queues](https://laravel.com/docs/11.x/queues)
- [REST API Design Best Practices — Microsoft](https://learn.microsoft.com/en-us/azure/architecture/best-practices/api-design)
- [OWASP API Security Top 10](https://owasp.org/www-project-api-security/)
- [CloudFlare WAF Docs](https://developers.cloudflare.com/waf/)

---

## Architecture C — Cloud-Native Containerized (Most Scalable)

### Overview

Everything runs in Docker containers orchestrated with Kubernetes or AWS ECS. Each service auto-scales based on load. This matches Grab/Uber-level architecture.

### Architecture Diagram

```
[CloudFlare CDN + WAF]
         |
[AWS ALB / GCP Load Balancer]
    /          |         \
[Booking    [Admin     [API
 Container]  Container]  Containers x N]
                              |
              [RDS MySQL Multi-AZ] + [ElastiCache Redis]
                              |
              [S3 Encrypted] + [SQS Queue]
                              |
              [Worker Containers] (emails, notifications)
                              |
              [CloudWatch / Grafana] (monitoring)
```

### How It Addresses Non-Functional Requirements

| NFR | Solution |
|-----|---------|
| **Scalability** | Auto-scaling groups — booking spikes auto-add containers |
| **Availability** | Multi-AZ database, health checks, zero-downtime deploys |
| **Security** | VPC isolation, security groups, Secrets Manager, WAF, MFA-enforced admin |
| **Compliance** | AWS/GCP data residency (pin to Singapore region), CloudTrail audit logs |
| **Local deploy** | Docker Compose version runs identically on local servers |
| **Future cities** | Add city as config/env — no code changes needed |

### Pros

- True auto-scaling for traffic spikes
- Zero-downtime deployments
- Works identically locally (Docker Compose) and on cloud
- Full observability (logs, metrics, traces)
- Most future-proof — easy to add mobile API or AI services later

### Cons

- Highest operational complexity
- Needs someone comfortable with Docker and cloud operations
- Higher baseline cost even when idle
- Overkill for current scale (hundreds of users)
- CI/CD pipeline setup required

### Estimated Monthly Cost (AWS Singapore Region)

| Component | Service | Cost |
|-----------|---------|------|
| Container hosting | ECS Fargate (2 tasks, booking + API) | ~$30–$60/mo |
| Database | RDS MySQL t3.small Multi-AZ | ~$50–$80/mo |
| Cache | ElastiCache Redis t3.micro | ~$20/mo |
| Storage | S3 with encryption | ~$5/mo |
| Load Balancer | AWS ALB | ~$20/mo |
| Queue | SQS | ~$1/mo |
| CDN | CloudFront + CloudFlare Free | ~$5/mo |
| Monitoring | CloudWatch basic | ~$5–$10/mo |
| **Total** | | **~$136–$201/mo** |

### Key Reading

- [Docker Getting Started](https://docs.docker.com/get-started/)
- [AWS ECS Getting Started](https://docs.aws.amazon.com/ecs/latest/developerguide/getting-started.html)
- [12-Factor App](https://12factor.net/)
- [AWS Well-Architected Framework](https://aws.amazon.com/architecture/well-architected/)
- [OWASP Top 10](https://owasp.org/www-top-10/)

---

## Comparison Summary (Part 1)

| | Architecture A | Architecture B | Architecture C |
|--|--|--|--|
| **Complexity** | Low | Medium | High |
| **Scalability** | Manual (vertical) | Semi-auto (horizontal) | Auto-scaling |
| **Separation** | Subdomain only | True separate deployments | True separate deployments |
| **Local deploy** | Easy (LAMP/LEMP) | Moderate | Docker Compose |
| **Monthly cost** | ~$41–$82 | ~$100–$132 | ~$136–$201 |
| **Best for** | FYP → early production | Production-ready | Enterprise-ready |

---

## Cross-Cutting Concerns (All Architectures)

### Security — PDPA + PCI-DSS

| Requirement | Implementation |
|-------------|---------------|
| Card data isolation | Stripe.js already handles this — card data never touches PHP server |
| NRIC/Passport encryption | Use Laravel `encrypt()` or AWS KMS for ID fields at rest |
| Document retention | Auto-delete uploaded travel documents after 90 days (PDPA compliance) |
| Admin access | MFA mandatory (Google Authenticator or Authy) |
| Rate limiting | Limit booking submissions to prevent abuse and credential stuffing |
| Audit logging | Log all admin actions with user ID, timestamp, and IP address |

### Usability — Low Tech Literacy and Elderly Users

| Requirement | Implementation |
|-------------|---------------|
| Touch targets | Minimum 44px buttons/inputs (Apple HIG standard) |
| Error messages | Plain English — no "Error 422 Unprocessable Entity" |
| Progress steps | Clear step indicators (like Grab booking flow) |
| Auto-fill | Pre-fill phone/email from user profile where possible |
| High contrast | WCAG AA contrast ratio (4.5:1 minimum) |
| Font size | Minimum 16px body text |

### Key Reading (Cross-Cutting)

- [WCAG 2.1 Quick Reference](https://www.w3.org/WAI/WCAG21/quickref/)
- [PDPA Malaysia Compliance](https://www.pdp.gov.my)
- [Stripe Security Best Practices](https://stripe.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-top-10/)

---

# Part 2 — Architecture Recommendations (With CI/CD)

---

## Shared Foundations Across All Architectures

### Branch Strategy (GitFlow Simplified)

```
feature/* ──► develop ──► staging ──► main (production)
                  ▲                        |
              hotfix/*  ───────────────────┘
```

| Branch | Purpose | Auto-deploys to |
|--------|---------|----------------|
| `feature/*` | Individual developer work | Nothing (CI tests only) |
| `develop` | Integration branch | Dev environment |
| `staging` | Pre-production validation | Staging environment |
| `main` | Production-ready code | Production (manual approval gate) |
| `hotfix/*` | Critical production fixes | Staging → Production fast-track |

### Universal CI Pipeline Stages

Every push on every branch runs the following stages:

```
[Push / PR] → [Lint] → [Unit Tests] → [Security Scan] → [Build] → [Deploy]
```

| Stage | Tool | What It Checks |
|-------|------|---------------|
| **Lint** | PHP CodeSniffer, ESLint | Code style, PSR-12 formatting |
| **Unit Tests** | PHPUnit | Business logic correctness |
| **Security Scan** | OWASP Dependency-Check, Trivy | Vulnerable packages, known CVEs |
| **SAST** | SonarCloud (free tier available) | SQL injection, XSS, insecure patterns |
| **Secrets Detection** | truffleHog / git-secrets | Accidentally committed API keys, passwords |
| **Build** | Composer install, npm run build | Clean build with no errors |
| **Deploy** | Architecture-specific (see below) | Automated or approval-gated |

---

## Architecture A — Enhanced Monolith with CI/CD

### Full Architecture Diagram

```
┌─────────────────── SOURCE CONTROL ──────────────────────┐
│                                                          │
│  Developer ──git push──► GitHub Repo                    │
│                               │                         │
│               ┌───────────────┼───────────────┐         │
│           feature/*        develop           main        │
│               │               │               │         │
│           [CI only]      [CI + deploy     [CI + manual  │
│                           to DEV]          approval     │
│                                            → PROD]      │
└──────────────────────────────────────────────────────────┘
                               │
                    ┌──────────▼──────────┐
                    │  GitHub Actions CI  │
                    │  - composer install │
                    │  - PHPUnit          │
                    │  - CodeSniffer      │
                    │  - OWASP dep-check  │
                    └──────────┬──────────┘
                               │
              ┌────────────────┼────────────────┐
              │                │                │
         [DEV VPS]      [STAGING VPS]     [PROD VPS]
              │                │                │
         SSH deploy       SSH deploy       SSH deploy
         + migrate        + migrate     (manual trigger)
         + cache clear    + cache clear  + blue/green swap
              │                │                │
        booking.dev      booking.staging  booking.ease.com
        admin.dev        admin.staging    admin.ease.com
                               │
                    ┌──────────▼──────────┐
                    │   PRODUCTION STACK  │
                    │                     │
                    │  [CloudFlare CDN]   │
                    │       │             │
                    │  [Nginx + PHP-FPM]  │
                    │  booking.ease.com   │
                    │  admin.ease.com     │
                    │       │             │
                    │  [Laravel App]      │
                    │       │             │
                    │  [MySQL]  [Redis]   │
                    │       │             │
                    │  [S3 — documents]   │
                    └─────────────────────┘
```

### GitHub Actions Pipeline

```yaml
# .github/workflows/deploy.yml
on:
  push:
    branches: [develop, staging, main]

jobs:
  ci:
    steps:
      - composer install
      - php artisan test          # PHPUnit
      - phpcs --standard=PSR12    # Code style
      - dependency-check          # OWASP scan

  deploy-dev:
    needs: ci
    if: branch == 'develop'
    steps:
      - SSH into DEV VPS
      - git pull
      - composer install --no-dev
      - php artisan migrate --force
      - php artisan config:cache
      - php artisan route:cache
      - reload php-fpm

  deploy-prod:
    needs: ci
    if: branch == 'main'
    environment: production       # requires manual approval in GitHub
    steps:
      - same as dev deploy
      - notify Slack/email on success/failure
```

### Deployment Strategy

| Environment | Trigger | Approval Required |
|-------------|---------|------------------|
| Dev | Auto on `develop` push | No |
| Staging | Auto on `staging` push | No |
| Production | Push to `main` | Yes — 1 developer must approve in GitHub |

### Rollback Strategy

- `git revert <commit>` + re-run pipeline
- Or SSH into server and `git checkout <previous-tag>`
- Database: keep migration rollback scripts (`php artisan migrate:rollback`)

### Zero Downtime

Nginx serves existing PHP-FPM workers while new code deploys, then gracefully reloads — no dropped requests.

### Secrets Management

```
GitHub Secrets (per environment):
  DEV_SSH_KEY, DEV_DB_PASSWORD, DEV_STRIPE_KEY
  STAGING_SSH_KEY, STAGING_DB_PASSWORD
  PROD_SSH_KEY, PROD_DB_PASSWORD, PROD_STRIPE_KEY
  PROD_ANTHROPIC_KEY, PROD_GOOGLE_MAPS_KEY

Rules:
  - Never commit .env to git
  - Never hardcode secrets in PHP or JS files
  - Never log secrets (mask in CI output)
```

### Updated Monthly Cost

| Component | Cost |
|-----------|------|
| Prod + Staging VPS (2x DO Droplet 2GB) | ~$36/mo |
| Dev VPS (smallest, DO Droplet 1GB) | ~$6/mo |
| Managed MySQL | ~$15/mo |
| Redis | ~$10/mo |
| S3 / DO Spaces | ~$5/mo |
| CloudFlare Free | $0 |
| GitHub Team (4 devs) | ~$16/mo |
| **Total** | **~$88–$108/mo** |

### Key Reading

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [Laravel Deployment Guide](https://laravel.com/docs/11.x/deployment)
- [GitHub Environments and Approval Gates](https://docs.github.com/en/actions/deployment/targeting-different-environments/using-environments-for-deployment)
- [OWASP Dependency-Check](https://owasp.org/www-project-dependency-check/)

---

## Architecture B — Separated API + Frontend with CI/CD *(Recommended)*

### Full Architecture Diagram

```
┌──────────────────── SOURCE CONTROL ─────────────────────────┐
│                                                              │
│   Developer ──git push──► GitHub (Monorepo or 3 repos)      │
│                                │                            │
│                   ┌────────────┼────────────┐               │
│                [API]      [Booking]       [Admin]            │
│                   │            │              │              │
│           GitHub Actions  GitHub Actions  GitHub Actions     │
│           (per service)   (per service)   (per service)      │
└──────────────────────────────────────────────────────────────┘
                   │            │              │
          ┌────────▼──┐  ┌──────▼──────┐ ┌───▼──────┐
          │ API CI/CD │  │ Booking     │ │ Admin    │
          │           │  │ CI/CD       │ │ CI/CD    │
          │ - PHPUnit │  │ - PHPUnit   │ │ - Tests  │
          │ - SAST    │  │ - SAST      │ │ - SAST   │
          │ - Trivy   │  │ - Lint      │ │ - Lint   │
          └────┬──────┘  └──────┬──────┘ └───┬──────┘
               │                │             │
    ┌──────────▼────────────────▼─────────────▼──────────┐
    │                  ENVIRONMENTS                        │
    │  develop ──► DEV    staging ──► STAGING              │
    │  main ──► PROD (manual approval gate)                │
    └──────────────────────────────────────────────────────┘
               │
    ┌──────────▼────────────────────────────────────────────┐
    │               PRODUCTION INFRASTRUCTURE                │
    │                                                        │
    │   [CloudFlare CDN + WAF + DDoS Protection]             │
    │                    │                                   │
    │         ┌──────────┴──────────┐                       │
    │         │                     │                       │
    │  [Booking Server]       [Admin Server]                 │
    │  booking.ease.com       admin.ease.com                 │
    │  Laravel Blade / Vue    Laravel Blade                  │
    │  Public-facing          IP-restricted                  │
    │         │                     │                       │
    │         └──────────┬──────────┘                       │
    │                    │                                   │
    │            [API Server x2]                             │
    │            Load balanced                               │
    │            api.ease.com                                │
    │            Laravel REST API                            │
    │            Rate limited per endpoint                   │
    │                    │                                   │
    │     ┌──────────────┼──────────────┐                   │
    │     │              │              │                   │
    │  [MySQL        [Redis         [S3 Encrypted           │
    │  Primary +     Sessions +     Document                │
    │  Read Replica] Cache +        Storage]                │
    │                Queue]                                  │
    │                    │                                   │
    │            [Queue Worker]                              │
    │            (emails, receipts,                          │
    │             document processing)                       │
    └────────────────────────────────────────────────────────┘
```

### CI/CD Pipeline Stages

```
┌──────────────────────────────────────────────────────────┐
│                   PIPELINE STAGES                        │
│                                                          │
│  [1. CI — runs on every push and PR]                     │
│      composer install / npm install                      │
│      → PHPUnit / Jest tests                              │
│      → PHP CodeSniffer / ESLint                          │
│      → SonarCloud SAST scan                              │
│      → OWASP Dependency-Check                            │
│      → Build assets (npm run build)                      │
│                                                          │
│  [2. CD to DEV — auto on develop branch]                 │
│      → SSH deploy + migrate + cache clear                │
│      → Smoke test (curl health check endpoint)           │
│                                                          │
│  [3. CD to STAGING — auto on staging branch]             │
│      → Deploy + migrate                                  │
│      → Run integration tests against staging DB          │
│      → Notify team via Slack or Discord                  │
│                                                          │
│  [4. CD to PRODUCTION — manual approval on main]         │
│      → GitHub Environment approval (1 approver required) │
│      → Blue/Green deploy (swap live server)              │
│      → Database migration (backwards-compatible only)    │
│      → Health check — auto-rollback if health check fails│
│      → Tag release in GitHub (e.g. v1.0.2)              │
│      → Notify team                                       │
└──────────────────────────────────────────────────────────┘
```

### Blue/Green Deployment (Zero Downtime)

```
BEFORE DEPLOY:
  CloudFlare → [Server A: v1.0.1 — LIVE]
               [Server B: v1.0.1 — IDLE]

DURING DEPLOY:
  Deploy v1.0.2 to Server B (idle, no traffic)
  Run database migrations (backwards-compatible)
  Run health check on Server B

ON SUCCESS:
  CloudFlare → [Server A: v1.0.1 — IDLE]
               [Server B: v1.0.2 — LIVE]

ON FAILURE (health check fails):
  Keep Server A as LIVE
  Discard Server B changes — no user impact
```

### Secrets Management

```
GitHub Secrets (scoped per environment):
  DEV_SSH_KEY, DEV_DB_PASSWORD, DEV_STRIPE_KEY
  STAGING_SSH_KEY, STAGING_DB_PASSWORD
  PROD_SSH_KEY, PROD_DB_PASSWORD, PROD_STRIPE_KEY
  PROD_ANTHROPIC_KEY, PROD_GOOGLE_MAPS_KEY

Never in:
  - .env files committed to git
  - Hardcoded in any PHP or JS file
  - Log files or error messages
  - Public GitHub Actions output (mask all secrets)
```

### Updated Monthly Cost

| Component | Service | Cost |
|-----------|---------|------|
| API servers x2 (load balanced) | DO Droplet 2GB x2 | ~$24/mo |
| Booking server | DO Droplet 2GB | ~$12/mo |
| Admin server | DO Droplet 1GB | ~$6/mo |
| Dev + Staging servers | DO Droplet 1GB x2 | ~$12/mo |
| Managed MySQL + read replica | DO Managed DB | ~$50/mo |
| Redis (managed) | DO Redis | ~$15/mo |
| S3 / DO Spaces (document storage) | ~10GB | ~$5/mo |
| CloudFlare Pro (WAF + DDoS) | | ~$20/mo |
| GitHub Team (4 devs) | CI/CD minutes + environments | ~$16/mo |
| SonarCloud SAST | Free for open source / ~$10 private | ~$0–$10/mo |
| **Total** | | **~$160–$180/mo** |

### Key Reading

- [GitHub Actions — Environments & Approvals](https://docs.github.com/en/actions/deployment/targeting-different-environments/using-environments-for-deployment)
- [Blue/Green Deployments — AWS Whitepaper](https://docs.aws.amazon.com/whitepapers/latest/overview-deployment-options/bluegreen-deployments.html)
- [Laravel Sanctum — API Auth](https://laravel.com/docs/11.x/sanctum)
- [SonarCloud SAST Setup](https://docs.sonarcloud.io/getting-started/github/)
- [Secrets in GitHub Actions](https://docs.github.com/en/actions/security-guides/encrypted-secrets)
- [OWASP API Security Top 10](https://owasp.org/www-project-api-security/)

---

## Architecture C — Cloud-Native Containers with CI/CD

### Full Architecture Diagram

```
┌──────────────────── SOURCE CONTROL ────────────────────────┐
│                                                            │
│  Developer ──git push──► GitHub                           │
│                              │                            │
│                   GitHub Actions Triggers                  │
└────────────────────────────────────────────────────────────┘
                              │
┌─────────────────── CI PIPELINE ────────────────────────────┐
│                                                            │
│  [Test Stage]           [Security Stage]   [Build Stage]  │
│  PHPUnit                Trivy image scan   Docker build    │
│  ESLint/CodeSniffer     OWASP dep-check    Multi-stage     │
│  SonarCloud SAST        Secrets detection  Dockerfile      │
│                         (truffleHog)                       │
│                                    │                       │
│                         [Push to ECR / GitHub Registry]    │
│                         booking:sha-abc123                 │
│                         api:sha-abc123                     │
│                         admin:sha-abc123                   │
└────────────────────────────────────────────────────────────┘
                              │
┌─────────────────── CD PIPELINE ────────────────────────────┐
│                                                            │
│  develop ──► [ECS DEV cluster — auto deploy]               │
│  staging ──► [ECS STAGING cluster — auto deploy]           │
│                        + integration tests                 │
│                        + performance test (k6)             │
│  main ──► [ECS PROD cluster — manual approval]             │
│                        + rolling deploy (1 task at a time) │
│                        + health check per task             │
│                        + auto-rollback on failure          │
│                        + GitHub Release tag created        │
└────────────────────────────────────────────────────────────┘
                              │
┌──────────────── PRODUCTION INFRASTRUCTURE ─────────────────┐
│                                                            │
│  [CloudFlare CDN + WAF + Rate Limiting]                    │
│                    │                                       │
│          [AWS ALB — Application Load Balancer]             │
│          /               |              \                  │
│  [Booking         [Admin             [API                  │
│   ECS Tasks x2]   ECS Task x1]       ECS Tasks x3]        │
│  Auto-scaling     Fixed (admin)      Auto-scaling          │
│  0–5 tasks        1 task             1–10 tasks            │
│          \               |              /                  │
│          [AWS VPC — Private Subnet]                        │
│                    │                                       │
│     ┌──────────────┼──────────────┐                       │
│     │              │              │                       │
│  [RDS MySQL    [ElastiCache    [S3 + KMS                  │
│  Multi-AZ      Redis           Encryption                  │
│  Auto-backup]  Cluster]        PDPA-compliant]            │
│                    │                                       │
│            [SQS Queue]                                     │
│            [ECS Worker Tasks]                              │
│            (email, notifications,                          │
│             document processing)                           │
│                    │                                       │
│  ┌─────────────────┼─────────────────┐                    │
│  │          OBSERVABILITY            │                    │
│  │  CloudWatch Logs + Metrics        │                    │
│  │  X-Ray Distributed Tracing        │                    │
│  │  Grafana Dashboard                │                    │
│  │  PagerDuty Alerts                 │                    │
│  └───────────────────────────────────┘                    │
└────────────────────────────────────────────────────────────┘
```

### Multi-Stage Dockerfile

```dockerfile
# Stage 1 — Builder (installs dependencies)
FROM php:8.3-cli AS builder
RUN composer install --no-dev --optimize-autoloader
RUN npm run build

# Stage 2 — Production image (minimal, small attack surface)
FROM php:8.3-fpm-alpine
COPY --from=builder /app .
# No composer, no npm, no dev tools in production image
```

### Rolling Deployment Strategy (ECS)

```
3 API tasks running v1.0.1
     ↓
Replace task 1 → v1.0.2, health check passes ✓
Replace task 2 → v1.0.2, health check passes ✓
Replace task 3 → v1.0.2, health check passes ✓
     ↓
All tasks now on v1.0.2 — zero downtime achieved

If any health check fails:
     → Stop rollout immediately
     → Revert all tasks back to v1.0.1 automatically
     → Notify team
```

### Local Development Parity (Docker Compose)

```yaml
# docker-compose.yml — identical stack to production, runs on any laptop
services:
  api:       # same Docker image as production
  booking:   # same Docker image as production
  admin:     # same Docker image as production
  mysql:     # MySQL 8 matching RDS version exactly
  redis:     # Redis matching ElastiCache version
  worker:    # Queue worker (same image as API)
  mailhog:   # Local email testing — no real emails sent in dev
```

This guarantees **what runs locally runs in production** — eliminates "works on my machine" bugs.

### Secrets Management (AWS Secrets Manager)

```
- DB credentials injected as environment variables at container start
- Stripe and Anthropic API keys stored in AWS Secrets Manager
- DB passwords rotated automatically every 30 days
- IAM roles — each container only accesses the secrets it needs
- GitHub Actions uses OIDC (no long-lived AWS keys stored anywhere)
```

### Updated Monthly Cost (AWS Singapore Region — ap-southeast-1)

| Component | Service | Cost |
|-----------|---------|------|
| ECS Fargate (Booking + Admin + API ~4 tasks) | 0.25 vCPU / 0.5GB each | ~$30–$50/mo |
| RDS MySQL Multi-AZ | t3.small | ~$60–$80/mo |
| ElastiCache Redis | t3.micro | ~$20/mo |
| ALB Load Balancer | Per hour + LCU | ~$20/mo |
| S3 + KMS encryption | ~10GB documents | ~$7/mo |
| SQS Queue | Near free at low volume | ~$1/mo |
| CloudWatch + X-Ray | Logs + traces | ~$10/mo |
| CloudFlare Pro | WAF + DDoS | ~$20/mo |
| GitHub Team + Actions | 4 devs + CI minutes | ~$16/mo |
| Dev + Staging ECS | Smaller tasks | ~$20/mo |
| **Total** | | **~$204–$244/mo** |

### Key Reading

- [AWS ECS with GitHub Actions](https://docs.github.com/en/actions/deployment/deploying-to-your-cloud-provider/deploying-to-amazon-elastic-container-service)
- [Docker Multi-Stage Builds](https://docs.docker.com/build/building/multi-stage/)
- [Trivy — Container Security Scanning](https://trivy.dev)
- [AWS Secrets Manager](https://docs.aws.amazon.com/secretsmanager/latest/userguide/intro.html)
- [GitHub OIDC with AWS (no long-lived keys)](https://docs.github.com/en/actions/deployment/security-hardening-your-deployments/configuring-openid-connect-in-amazon-web-services)
- [k6 — Load and Performance Testing](https://k6.io/docs/)
- [12-Factor App](https://12factor.net/)

---

## Updated Comparison Summary (Part 2)

| | **A — Enhanced Monolith** | **B — Separated API** *(Recommended)* | **C — Cloud-Native** |
|--|--|--|--|
| **Complexity** | Low | Medium | High |
| **CI/CD Tool** | GitHub Actions + SSH | GitHub Actions + SSH | GitHub Actions + ECS/Docker |
| **Deploy Strategy** | Direct SSH + PHP-FPM reload | Blue/Green server swap | ECS Rolling deploy |
| **Rollback** | git revert + redeploy | Swap back to idle server | ECS auto-rollback |
| **Secrets** | GitHub Secrets | GitHub Secrets | AWS Secrets Manager |
| **Environments** | Dev, Staging, Production | Dev, Staging, Production | Dev, Staging, Production |
| **Zero Downtime** | Near (PHP-FPM graceful reload) | Yes (Blue/Green) | Yes (Rolling) |
| **Local Dev Parity** | Close (XAMPP or Laravel Valet) | Close | Exact (Docker Compose) |
| **Security Scanning** | OWASP dep-check + CodeSniffer | + SonarCloud SAST | + Trivy image scan + truffleHog |
| **Monthly Cost** | ~$88–$108 | ~$160–$180 | ~$204–$244 |
| **Best for** | Fast MVP launch | Production-ready | Enterprise scale |

---

## Final Recommendation

**Architecture B (Separated API + Frontend with CI/CD)** is the recommended path for EASE Sarawak.

### Why

1. **Satisfies separation requirement** — booking site and admin site are truly independent deployments on separate servers, not just subdomain routing
2. **Right-sized for 4 developers** — achievable without DevOps specialist knowledge
3. **Production-ready from day one** — blue/green deploys, approval gates, zero downtime
4. **PDPA and PCI-DSS ready** — API-level access controls, encrypted document storage, Stripe.js isolation
5. **Clear upgrade path** — when traffic grows, migrate to Architecture C by containerising the existing Laravel services without rewriting application code
6. **Cost-effective** — ~$160–$180/mo is reasonable for a real-world deployed SaaS

### Recommended Upgrade Path

```
Current (FYP)          Phase 1 (Launch)       Phase 2 (Scale)
CodeIgniter 4    →     Architecture B     →    Architecture C
XAMPP local            Laravel API             Docker + ECS
No CI/CD               GitHub Actions          Full containerisation
                        Blue/Green deploy       Auto-scaling
                        PDPA-compliant          Multi-city support
```

### Immediate Next Steps for the Team

1. Set up GitHub repository with branch protection rules on `main` and `staging`
2. Create GitHub Environments (dev, staging, production) with approval requirement on production
3. Write the GitHub Actions CI workflow (PHPUnit + CodeSniffer + OWASP dep-check)
4. Migrate from CodeIgniter 4 to Laravel (significantly better ecosystem for this use case)
5. Design the REST API contract before splitting booking and admin apps
6. Set up CloudFlare with WAF rules and rate limiting before going live

---

*Document generated: 2026-04-22*
*Project: FYP-EASE-Sarawak*
*Team size: 4 developers*
