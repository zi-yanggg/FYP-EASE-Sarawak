<?php

namespace App\Services;

use App\Models\PromoCodeModel;

class PromoService
{
    public function __construct(private ?PromoCodeModel $promoModel = null)
    {
        $this->promoModel = $this->promoModel ?? new PromoCodeModel();
    }

    /**
     * @return array{valid: bool, message: string, discount?: float|int, discount_type?: string}
     */
    public function validate(string $promoCode): array
    {
        $promoCode = trim($promoCode);

        if ($promoCode === '') {
            return ['valid' => false, 'message' => 'Please enter a promo code'];
        }

        $promoData = $this->promoModel
            ->where('code', $promoCode)
            ->where('is_deleted', 0)
            ->first();

        if (! $promoData) {
            return ['valid' => false, 'message' => 'Invalid promo code'];
        }

        $now = date('Y-m-d H:i:s');

        if ($promoData['validation_date'] > $now) {
            return ['valid' => false, 'message' => 'This promo code is not yet active'];
        }

        if ($promoData['expired_date'] < $now) {
            return ['valid' => false, 'message' => 'This promo code has expired'];
        }

        $discountType = $promoData['discount_type'];
        $discount     = $discountType === 'amount'
            ? (float) $promoData['discount_amount']
            : (int) $promoData['discount_percentage'];

        return [
            'valid'         => true,
            'message'       => 'Promo code applied successfully!',
            'discount'      => $discount,
            'discount_type' => $discountType,
        ];
    }

    /**
     * @param array{discount: float|int, discount_type: string} $promo
     */
    public function calculateDiscount(float $subtotal, array $promo): float
    {
        if ($subtotal <= 0) {
            return 0.0;
        }

        if (($promo['discount_type'] ?? '') === 'amount') {
            return min($subtotal, (float) ($promo['discount'] ?? 0));
        }

        return $subtotal * ((float) ($promo['discount'] ?? 0) / 100);
    }

    /**
     * @return array<string, mixed>
     */
    public function toApiResponse(array $result): array
    {
        if (! $result['valid']) {
            return [
                'success' => true,
                'valid'   => false,
                'message' => $result['message'],
            ];
        }

        return [
            'success'       => true,
            'valid'         => true,
            'discount'      => $result['discount'],
            'discount_type' => $result['discount_type'],
            'message'       => $result['message'],
        ];
    }
}
