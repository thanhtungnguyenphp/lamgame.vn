<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Forum\ForumFollowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForumFollowController extends Controller
{
    public function __construct(private ForumFollowService $service) {}

    public function follow(Request $request): JsonResponse
    {
        $request->validate(['type' => 'required|in:user,category,tag', 'id' => 'required|string']);
        $result = $this->service->follow($request->user()->id, $request->type, $request->id);
        return isset($result['error']) ? response()->json($result, 422) : response()->json($result);
    }

    public function unfollow(Request $request): JsonResponse
    {
        $request->validate(['type' => 'required|in:user,category,tag', 'id' => 'required|string']);
        $this->service->unfollow($request->user()->id, $request->type, $request->id);
        return response()->json(['success' => true]);
    }

    public function feed(Request $request): JsonResponse
    {
        return response()->json($this->service->feed($request->user()->id));
    }

    public function followers(Request $request, string $type, string $id): JsonResponse
    {
        return response()->json(['count' => $this->service->followers($type, $id)]);
    }

    public function following(Request $request): JsonResponse
    {
        return response()->json($this->service->following($request->user()->id));
    }
}
