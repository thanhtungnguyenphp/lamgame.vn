<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;

class AttributeManageController extends Controller
{
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected AttributeFamilyRepository $familyRepository
    ) {}

    // === Attributes ===
    public function attributeList(Request $request): JsonResponse
    {
        $query = \Webkul\Attribute\Models\Attribute::query();

        if ($search = $request->input('search')) {
            $query->where('code', 'like', "%{$search}%")
                  ->orWhereHas('translations', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        $attrs = $query->orderBy('position')->paginate($request->integer('per_page', 50));

        return response()->json([
            'status' => 'success',
            'data' => $attrs->map(fn ($a) => [
                'id' => $a->id, 'code' => $a->code, 'admin_name' => $a->admin_name,
                'type' => $a->type, 'position' => $a->position,
                'is_required' => (bool) $a->is_required, 'is_filterable' => (bool) $a->is_filterable,
                'is_visible_on_front' => (bool) $a->is_visible_on_front,
            ]),
            'meta' => ['current_page' => $attrs->currentPage(), 'last_page' => $attrs->lastPage(), 'total' => $attrs->total()],
        ]);
    }

    public function attributeDetail(int $id): JsonResponse
    {
        $attr = $this->attributeRepository->with(['options', 'translations'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $attr]);
    }

    public function attributeStore(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:255|unique:attributes,code',
            'admin_name' => 'required|string|max:255',
            'type' => 'required|in:text,textarea,boolean,select,multiselect,datetime,date,image,file,checkbox,price',
            'is_required' => 'sometimes|boolean',
            'is_unique' => 'sometimes|boolean',
            'is_filterable' => 'sometimes|boolean',
            'is_visible_on_front' => 'sometimes|boolean',
            'position' => 'sometimes|integer',
        ]);

        $attr = $this->attributeRepository->create($data);
        return response()->json(['status' => 'success', 'message' => 'Attribute created', 'data' => ['id' => $attr->id]], 201);
    }

    public function attributeUpdate(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'admin_name' => 'sometimes|string|max:255',
            'is_required' => 'sometimes|boolean',
            'is_filterable' => 'sometimes|boolean',
            'is_visible_on_front' => 'sometimes|boolean',
            'position' => 'sometimes|integer',
        ]);

        $this->attributeRepository->update($data, $id);
        return response()->json(['status' => 'success', 'message' => 'Attribute updated']);
    }

    public function attributeDestroy(int $id): JsonResponse
    {
        $this->attributeRepository->delete($id);
        return response()->json(['status' => 'success', 'message' => 'Attribute deleted']);
    }

    // === Attribute Families ===
    public function familyList(Request $request): JsonResponse
    {
        $families = $this->familyRepository->orderBy('name')->get()->map(fn ($f) => [
            'id' => $f->id, 'code' => $f->code, 'name' => $f->name,
            'attribute_groups_count' => $f->attribute_groups?->count() ?? 0,
        ]);

        return response()->json(['status' => 'success', 'data' => $families]);
    }

    public function familyDetail(int $id): JsonResponse
    {
        $family = $this->familyRepository->with(['attribute_groups.custom_attributes'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $family]);
    }

    public function familyStore(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:255|unique:attribute_families,code',
            'name' => 'required|string|max:255',
        ]);

        $family = $this->familyRepository->create($data);
        return response()->json(['status' => 'success', 'message' => 'Family created', 'data' => ['id' => $family->id]], 201);
    }

    public function familyUpdate(Request $request, int $id): JsonResponse
    {
        $data = $request->validate(['name' => 'sometimes|string|max:255']);
        $this->familyRepository->update($data, $id);
        return response()->json(['status' => 'success', 'message' => 'Family updated']);
    }

    public function familyDestroy(int $id): JsonResponse
    {
        $this->familyRepository->delete($id);
        return response()->json(['status' => 'success', 'message' => 'Family deleted']);
    }
}
