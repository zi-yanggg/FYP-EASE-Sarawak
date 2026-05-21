/**
 * k6 Load Test — Payment Endpoints
 *
 * Tests the Stripe payment intent endpoint under load.
 * Uses Stripe test mode — set STRIPE_SECRET_KEY to a test key before running.
 *
 * Run:  k6 run --vus 10 --duration 30s tests/load/payment_endpoints.js
 */

import http from 'k6/http';
import { sleep, check, group } from 'k6';
import { Rate, Trend } from 'k6/metrics';

const intentErrorRate  = new Rate('intent_error_rate');
const intentDuration   = new Trend('intent_duration');

export const options = {
  stages: [
    { duration: '5s',  target: 5  },
    { duration: '20s', target: 10 },
    { duration: '5s',  target: 0  },
  ],
  thresholds: {
    intent_error_rate: ['rate<0.01'],  // <1% errors on payment endpoint
    intent_duration:   ['p(95)<3000'], // Stripe calls can be slow
  },
};

const BASE_URL = __ENV.BASE_URL || 'http://localhost/New/FYP-EASE-Sarawak/easesarawak/public';

export default function () {

  group('Payment intent validation', () => {
    // Zero amount must always be rejected fast (no Stripe call)
    const zeroRes = http.post(
      `${BASE_URL}/card-payment/intent`,
      JSON.stringify({ amount: 0, currency: 'myr' }),
      { headers: { 'Content-Type': 'application/json' } }
    );
    check(zeroRes, {
      'zero amount rejected with 422': (r) => r.status === 422,
    }) || intentErrorRate.add(1);

    // Negative amount must also be rejected
    const negRes = http.post(
      `${BASE_URL}/card-payment/intent`,
      JSON.stringify({ amount: -1, currency: 'myr' }),
      { headers: { 'Content-Type': 'application/json' } }
    );
    check(negRes, {
      'negative amount rejected with 422': (r) => r.status === 422,
    }) || intentErrorRate.add(1);

    sleep(1);
  });

  group('Webhook signature enforcement', () => {
    const webhookRes = http.post(
      `${BASE_URL}/webhook`,
      '{"type":"payment_intent.succeeded"}',
      {
        headers: {
          'Content-Type':    'application/json',
          'Stripe-Signature': 'invalid_signature',
        },
        redirects: 0,
      }
    );
    check(webhookRes, {
      'bad webhook signature rejected with 400': (r) => r.status === 400,
    });

    sleep(0.5);
  });
}
