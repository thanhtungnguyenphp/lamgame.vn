<?php

namespace App\Services;

use App\Models\HireRequest;
use App\Mail\NewHireRequestMail;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;

class HireRequestService
{
    public function create(array $data): HireRequest
    {
        $request = HireRequest::create($data);

        // Notify admin
        $adminEmail = config('mail.admin_email', 'admin@lamgame.vn');
        Mail::to($adminEmail)->queue(new NewHireRequestMail($request));

        return $request;
    }

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = HireRequest::query();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function updateStatus(int $id, string $status, ?string $notes = null): HireRequest
    {
        $request = HireRequest::findOrFail($id);
        $request->update(array_filter([
            'status'      => $status,
            'admin_notes' => $notes,
        ], fn ($v) => $v !== null));

        return $request;
    }
}
