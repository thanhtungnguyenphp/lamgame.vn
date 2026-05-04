<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HireRequestService;
use Illuminate\Http\Request;

class HireRequestController extends Controller
{
    public function __construct(private HireRequestService $service) {}

    public function index(Request $request)
    {
        $hireRequests = $this->service->list(
            $request->only(['status', 'search']),
            20
        );

        return view('admin.hire-requests.index', compact('hireRequests'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,quoted,closed',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $this->service->updateStatus($id, $request->status, $request->admin_notes);

        return back()->with('success', 'Cập nhật thành công.');
    }
}
