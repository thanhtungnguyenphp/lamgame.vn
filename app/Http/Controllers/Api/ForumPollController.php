<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Forum\ForumPollService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForumPollController extends Controller
{
    public function __construct(private ForumPollService $service) {}

    public function vote(Request $request, int $id): JsonResponse
    {
        $request->validate(['option_ids' => 'required|array|min:1', 'option_ids.*' => 'integer']);
        $result = $this->service->vote($id, $request->user()->id, $request->option_ids);
        return isset($result['error'])
            ? response()->json($result, 422)
            : response()->json($result);
    }

    public function retract(Request $request, int $id): JsonResponse
    {
        $result = $this->service->retractVote($id, $request->user()->id);
        return isset($result['error'])
            ? response()->json($result, 422)
            : response()->json($result);
    }

    public function results(int $id): JsonResponse
    {
        return response()->json($this->service->getResults($id));
    }
}
