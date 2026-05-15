<?php

namespace App\Services;

use App\Models\LicenseKey;
use App\Models\ProductLicense;

class LicenseService
{
    public function getProductLicenses(int $productId): array
    {
        return ProductLicense::where('product_id', $productId)
            ->where('is_active', true)
            ->with('licenseType')
            ->get()
            ->map(fn ($pl) => [
                'id' => $pl->id,
                'type' => $pl->licenseType->name,
                'slug' => $pl->licenseType->slug,
                'price' => $pl->price,
                'max_projects' => $pl->licenseType->max_projects,
                'allows_resale' => $pl->licenseType->allows_resale,
                'description' => $pl->licenseType->description,
            ])->toArray();
    }

    public function verify(string $key): array
    {
        $license = LicenseKey::where('key', $key)->with('licenseType')->first();
        if (!$license) return ['valid' => false, 'error' => 'License key không tồn tại'];
        if ($license->expires_at && $license->expires_at->isPast()) return ['valid' => false, 'error' => 'License đã hết hạn'];

        return [
            'valid' => true,
            'product_id' => $license->product_id,
            'type' => $license->licenseType->name,
            'activated_at' => $license->activated_at,
            'expires_at' => $license->expires_at,
        ];
    }

    public function getMyLicenses(int $customerId)
    {
        return LicenseKey::where('customer_id', $customerId)
            ->with('licenseType')
            ->orderByDesc('created_at')
            ->paginate(20);
    }

    public function transfer(int $licenseId, int $currentOwnerId, int $newOwnerId): array
    {
        $license = LicenseKey::where('id', $licenseId)->where('customer_id', $currentOwnerId)->first();
        if (!$license) return ['error' => 'License không tồn tại hoặc không thuộc về bạn'];

        $license->update(['customer_id' => $newOwnerId, 'transferred_to' => $newOwnerId]);
        return ['success' => true, 'message' => 'Đã chuyển license'];
    }

    public function generateAfterPurchase(int $productId, int $licenseTypeId, int $customerId, int $orderId): LicenseKey
    {
        return LicenseKey::generate($productId, $licenseTypeId, $customerId, $orderId);
    }
}
