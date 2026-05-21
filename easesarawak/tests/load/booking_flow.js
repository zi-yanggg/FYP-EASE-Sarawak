/**
 * k6 Load Test — Booking Flow
 *
 * Simulates real users browsing, filling in booking details, and checking promo codes.
 *
 * Run locally:   k6 run tests/load/booking_flow.js
 * Smoke test:    k6 run --vus 5 --duration 15s tests/load/booking_flow.js
 * Load test:     k6 run --vus 50 --duration 60s tests/load/booking_flow.js
 * Stress test:   k6 run --vus 200 --duration 120s tests/load/booking_flow.js
 */

import http from 'k6/http';
import { sleep, check, group } from 'k6';
import { Trend, Rate, Counter } from 'k6/metrics';

// ── Custom metrics ────────────────────────────────────────────────────────────
const homePageDuration    = new Trend('home_page_duration');
const bookingPageDuration = new Trend('booking_page_duration');
const promoCheckDuration  = new Trend('promo_check_duration');
const errorRate           = new Rate('error_rate');
const promoRequests       = new Counter('promo_requests');

// ── Thresholds — test FAILS if these are breached ─────────────────────────────
export const options = {
  stages: [
    { duration: '10s', target: 10 },   // ramp up
    { duration: '30s', target: 50 },   // sustained load
    { duration: '10s', target: 0 },    // ramp down
  ],
  thresholds: {
    http_req_failed:        ['rate<0.05'],   // <5% errors
    http_req_duration:      ['p(95)<2000'],  // 95% of requests under 2s
    home_page_duration:     ['p(95)<1500'],
    booking_page_duration:  ['p(95)<1500'],
    promo_check_duration:   ['p(95)<500'],
    error_rate:             ['rate<0.05'],
  },
};

const BASE_URL = __ENV.BASE_URL || 'http://localhost/New/FYP-EASE-Sarawak/easesarawak/public';

// ── Virtual User scenario ─────────────────────────────────────────────────────
export default function () {

  group('Browse public pages', () => {
    const homeRes = http.get(`${BASE_URL}/`);
    homePageDuration.add(homeRes.timings.duration);
    check(homeRes, {
      'home page status 200': (r) => r.status === 200,
      'home page has content': (r) => r.body.includes('EASE') || r.body.length > 500,
    }) || errorRate.add(1);

    sleep(1);

    const bookingRes = http.get(`${BASE_URL}/booking`);
    bookingPageDuration.add(bookingRes.timings.duration);
    check(bookingRes, {
      'booking page status 200': (r) => r.status === 200,
    }) || errorRate.add(1);

    sleep(1);

    http.get(`${BASE_URL}/bookingdetail`);
    sleep(0.5);
    http.get(`${BASE_URL}/bookingcustomerdetail`);
    sleep(0.5);
  });

  group('Check promo code', () => {
    promoRequests.add(1);

    const promoRes = http.post(
      `${BASE_URL}/checkPromoCode`,
      JSON.stringify({ promo_code: 'TESTCODE' }),
      { headers: { 'Content-Type': 'application/json' } }
    );

    promoCheckDuration.add(promoRes.timings.duration);
    check(promoRes, {
      'promo check returns JSON': (r) => r.headers['Content-Type']?.includes('application/json'),
      'promo check status 200': (r) => r.status === 200,
    }) || errorRate.add(1);

    sleep(0.5);
  });

  group('Unauthenticated admin block', () => {
    const adminRes = http.get(`${BASE_URL}/admin`, { redirects: 0 });
    check(adminRes, {
      'admin redirects unauthenticated': (r) => r.status === 302 || r.status === 301,
    }) || errorRate.add(1);
  });

  sleep(Math.random() * 2 + 1); // 1–3s think time between iterations
}
