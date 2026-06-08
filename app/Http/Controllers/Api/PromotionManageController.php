<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\CartRule\Repositories\CartRuleRepository;
use Webkul\CatalogRule\Repositories\CatalogRuleRepository;

class PromotionManageController extends Controller
{
    public function __construct(
        protected CartRuleRepository $cartRuleRepository,
        protected CatalogRuleRepository $catalogRuleRepository
    ) {}

    // === Catalog Rules ===
    public function catalogRuleList(Request $request): JsonResponse
    {
        $rules = $this->catalogRuleRepository->orderBy('created_at', 'desc')->paginate($request->integer('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $rules->map(fn ($r) => [
                'id' => $r->id, 'name' => $r->name, 'status' => (bool) $r->status,
                'starts_from' => $r->starts_from, 'ends_till' => $r->ends_till,
                'action_type' => $r->action_type, 'discount_amount' => (float) $r->discount_amount,
            ]),
            'meta' => ['current_page' => $rules->currentPage(), 'last_page' => $rules->lastPage(), 'total' => $rules->total()],
        ]);
    }

    public function catalogRuleDetail(int $id): JsonResponse
    {
        $rule = $this->catalogRuleRepository->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $rule]);
    }

    public function catalogRuleStore(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'sometimes|boolean',
            'channels' => 'sometimes|array',
            'customer_groups' => 'sometimes|array',
            'starts_from' => 'sometimes|date',
            'ends_till' => 'sometimes|date',
            'condition_type' => 'sometimes|in:1,2',
            'conditions' => 'sometimes|array',
            'action_type' => 'required|string',
            'discount_amount' => 'required|numeric|min:0',
            'sort_order' => 'sometimes|integer',
        ]);

        $rule = $this->catalogRuleRepository->create($data);
        return response()->json(['status' => 'success', 'message' => 'Catalog rule created', 'data' => ['id' => $rule->id]], 201);
    }

    public function catalogRuleUpdate(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'status' => 'sometimes|boolean',
            'starts_from' => 'sometimes|date|nullable',
            'ends_till' => 'sometimes|date|nullable',
            'action_type' => 'sometimes|string',
            'discount_amount' => 'sometimes|numeric|min:0',
        ]);

        $this->catalogRuleRepository->update($data, $id);
        return response()->json(['status' => 'success', 'message' => 'Catalog rule updated']);
    }

    public function catalogRuleDestroy(int $id): JsonResponse
    {
        $this->catalogRuleRepository->delete($id);
        return response()->json(['status' => 'success', 'message' => 'Catalog rule deleted']);
    }

    // === Cart Rules ===
    public function cartRuleList(Request $request): JsonResponse
    {
        $rules = $this->cartRuleRepository->orderBy('created_at', 'desc')->paginate($request->integer('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $rules->map(fn ($r) => [
                'id' => $r->id, 'name' => $r->name, 'status' => (bool) $r->status,
                'coupon_type' => $r->coupon_type, 'use_auto_generation' => (bool) $r->use_auto_generation,
                'usage_per_customer' => $r->usage_per_customer, 'times_used' => $r->times_used,
                'starts_from' => $r->starts_from, 'ends_till' => $r->ends_till,
                'action_type' => $r->action_type, 'discount_amount' => (float) $r->discount_amount,
            ]),
            'meta' => ['current_page' => $rules->currentPage(), 'last_page' => $rules->lastPage(), 'total' => $rules->total()],
        ]);
    }

    public function cartRuleDetail(int $id): JsonResponse
    {
        $rule = $this->cartRuleRepository->with(['cart_rule_coupon'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $rule]);
    }

    public function cartRuleStore(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'sometimes|boolean',
            'coupon_type' => 'sometimes|in:0,1',
            'use_auto_generation' => 'sometimes|boolean',
            'usage_per_customer' => 'sometimes|integer',
            'starts_from' => 'sometimes|date',
            'ends_till' => 'sometimes|date',
            'action_type' => 'required|string',
            'discount_amount' => 'required|numeric|min:0',
            'discount_quantity' => 'sometimes|integer',
            'apply_to_shipping' => 'sometimes|boolean',
            'free_shipping' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer',
        ]);

        $rule = $this->cartRuleRepository->create($data);
        return response()->json(['status' => 'success', 'message' => 'Cart rule created', 'data' => ['id' => $rule->id]], 201);
    }

    public function cartRuleUpdate(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'status' => 'sometimes|boolean',
            'starts_from' => 'sometimes|date|nullable',
            'ends_till' => 'sometimes|date|nullable',
            'action_type' => 'sometimes|string',
            'discount_amount' => 'sometimes|numeric|min:0',
        ]);

        $this->cartRuleRepository->update($data, $id);
        return response()->json(['status' => 'success', 'message' => 'Cart rule updated']);
    }

    public function cartRuleDestroy(int $id): JsonResponse
    {
        $this->cartRuleRepository->delete($id);
        return response()->json(['status' => 'success', 'message' => 'Cart rule deleted']);
    }
}
