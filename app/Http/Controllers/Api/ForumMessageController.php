<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Forum\ForumMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForumMessageController extends Controller
{
    public function __construct(private ForumMessageService $service) {}

    public function conversations(Request $request): JsonResponse
    {
        return response()->json($this->service->getConversations($request->user()->id));
    }

    public function messages(Request $request, int $id): JsonResponse
    {
        return response()->json($this->service->getMessages($id, $request->user()->id));
    }

    public function createConversation(Request $request): JsonResponse
    {
        $request->validate(['user_id' => 'required|integer']);
        $result = $this->service->findOrCreateConversation($request->user()->id, $request->user_id);
        return isset($result['error']) ? response()->json($result, 422) : response()->json($result);
    }

    public function send(Request $request, int $id): JsonResponse
    {
        $request->validate(['content' => 'required|string|max:2000']);
        $result = $this->service->sendMessage($id, $request->user()->id, $request->content);
        return isset($result['error']) ? response()->json($result, 422) : response()->json($result, 201);
    }

    public function markRead(Request $request, int $id): JsonResponse
    {
        $this->service->markRead($id, $request->user()->id);
        return response()->json(['success' => true]);
    }

    public function block(Request $request, int $userId): JsonResponse
    {
        $this->service->block($request->user()->id, $userId);
        return response()->json(['message' => 'Đã chặn']);
    }

    public function unblock(Request $request, int $userId): JsonResponse
    {
        $this->service->unblock($request->user()->id, $userId);
        return response()->json(['message' => 'Đã bỏ chặn']);
    }
}
