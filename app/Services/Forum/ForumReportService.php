<?php

namespace App\Services\Forum;

use App\Models\ForumReport;
use Illuminate\Database\Eloquent\Model;

class ForumReportService
{
    public function create(Model $reportable, int $reporterId, array $data): ForumReport
    {
        return ForumReport::create([
            'reporter_id'     => $reporterId,
            'reportable_type' => get_class($reportable),
            'reportable_id'   => $reportable->id,
            'reason'          => $data['reason'],
            'description'     => $data['description'] ?? null,
            'status'          => 'pending',
            'ip_address'      => request()->ip(),
        ]);
    }

    public function hasDuplicate(Model $reportable, int $reporterId): bool
    {
        return ForumReport::where('reporter_id', $reporterId)
            ->where('reportable_type', get_class($reportable))
            ->where('reportable_id', $reportable->id)
            ->exists();
    }

    public function resolveReportable(string $type, int $id): ?Model
    {
        return match ($type) {
            'post'    => \App\Models\ForumPost::find($id),
            'comment' => \App\Models\ForumComment::find($id),
            default   => null,
        };
    }

    public function updateStatus(ForumReport $report, string $status, ?string $notes, int $adminId): void
    {
        $report->update([
            'status'      => $status,
            'admin_notes' => $notes,
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
        ]);
    }

    public function massUpdateStatus(array $ids, string $status, int $adminId): int
    {
        $count = 0;
        foreach ($ids as $id) {
            $report = ForumReport::find($id);
            if ($report) {
                $report->update([
                    'status'      => $status,
                    'reviewed_by' => $adminId,
                    'reviewed_at' => now(),
                ]);
                $count++;
            }
        }
        return $count;
    }

    public function massDelete(array $ids): int
    {
        return ForumReport::destroy($ids);
    }
}
