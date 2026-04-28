<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderManageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'increment_id'   => $this->increment_id,
            'status'         => $this->status,
            'is_guest'       => (bool) $this->is_guest,
            'customer'       => [
                'id'    => $this->customer_id,
                'name'  => trim($this->customer_first_name . ' ' . $this->customer_last_name),
                'email' => $this->customer_email,
            ],
            'items' => $this->whenLoaded('items', fn () =>
                $this->items->map(fn ($item) => [
                    'id'          => $item->id,
                    'product_id'  => $item->product_id,
                    'name'        => $item->name,
                    'sku'         => $item->sku,
                    'type'        => $item->type,
                    'qty_ordered' => (int) $item->qty_ordered,
                    'price'       => (float) $item->price,
                    'total'       => (float) $item->total,
                ])
            ),
            'payment' => $this->whenLoaded('payment', fn () => $this->payment ? [
                'method'       => $this->payment->method,
                'method_title' => $this->payment->method_title,
            ] : null),
            'billing_address' => $this->when($this->relationLoaded('addresses'), function () {
                $billing = $this->addresses->firstWhere('address_type', 'billing');
                return $billing ? [
                    'name'    => trim(($billing->first_name ?? '') . ' ' . ($billing->last_name ?? '')),
                    'email'   => $billing->email,
                    'phone'   => $billing->phone,
                    'city'    => $billing->city,
                    'country' => $billing->country,
                ] : null;
            }),
            'sub_total'       => (float) $this->sub_total,
            'discount_amount' => (float) $this->discount_amount,
            'tax_amount'      => (float) $this->tax_amount,
            'grand_total'     => (float) $this->grand_total,
            'currency'        => $this->order_currency_code,
            'coupon_code'     => $this->coupon_code,
            'comments' => $this->whenLoaded('comments', fn () =>
                $this->comments->map(fn ($c) => [
                    'id'                => $c->id,
                    'comment'           => $c->comment,
                    'customer_notified' => (bool) $c->customer_notified,
                    'created_at'        => $c->created_at?->toIso8601String(),
                ])
            ),
            'invoices' => $this->whenLoaded('invoices', fn () =>
                $this->invoices->map(fn ($inv) => [
                    'id'          => $inv->id,
                    'state'       => $inv->state,
                    'grand_total' => (float) $inv->grand_total,
                ])
            ),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
