<?php

namespace Webkul\Admin\DataGrids\Forum;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ForumReportDataGrid extends DataGrid
{
    protected $primaryColumn = 'id';

    const STATUS_PENDING = 'pending';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_DISMISSED = 'dismissed';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('forum_reports')
            ->leftJoin('customers', 'forum_reports.reporter_id', '=', 'customers.id')
            ->leftJoin('admins', 'forum_reports.reviewed_by', '=', 'admins.id')
            ->select(
                'forum_reports.id',
                'forum_reports.reportable_type',
                'forum_reports.reportable_id',
                'forum_reports.reason',
                'forum_reports.description',
                'forum_reports.status',
                'forum_reports.admin_notes',
                'forum_reports.reviewed_at',
                'forum_reports.created_at',
                DB::raw("CONCAT(customers.first_name, ' ', customers.last_name) as reporter_name"),
                DB::raw("CONCAT(admins.name) as reviewer_name")
            );

        $this->addFilter('id', 'forum_reports.id');
        $this->addFilter('status', 'forum_reports.status');
        $this->addFilter('reason', 'forum_reports.reason');
        $this->addFilter('created_at', 'forum_reports.created_at');
        $this->addFilter('reporter_name', DB::raw("CONCAT(customers.first_name, ' ', customers.last_name)"));

        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => 'ID',
            'type'       => 'integer',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'reporter_name',
            'label'      => 'Người báo cáo',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'reportable_type',
            'label'      => 'Loại',
            'type'       => 'string',
            'sortable'   => true,
            'closure'    => function ($row) {
                if (str_contains($row->reportable_type, 'ForumPost')) {
                    return '<span class="label-info">Bài viết</span>';
                } elseif (str_contains($row->reportable_type, 'ForumComment')) {
                    return '<span class="label-info">Bình luận</span>';
                }
                return $row->reportable_type;
            },
        ]);

        $this->addColumn([
            'index'      => 'reason',
            'label'      => 'Lý do',
            'type'       => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                ['label' => 'Spam', 'value' => 'spam'],
                ['label' => 'Không phù hợp', 'value' => 'inappropriate'],
                ['label' => 'Quấy rối', 'value' => 'harassment'],
                ['label' => 'Bản quyền', 'value' => 'copyright'],
                ['label' => 'Khác', 'value' => 'other'],
            ],
            'sortable'   => true,
            'closure'    => function ($row) {
                $reasons = [
                    'spam' => 'Spam',
                    'inappropriate' => 'Không phù hợp',
                    'harassment' => 'Quấy rối',
                    'copyright' => 'Bản quyền',
                    'other' => 'Khác',
                ];
                return $reasons[$row->reason] ?? $row->reason;
            },
        ]);

        $this->addColumn([
            'index'      => 'description',
            'label'      => 'Mô tả',
            'type'       => 'string',
            'sortable'   => false,
            'closure'    => function ($row) {
                return $row->description ? \Illuminate\Support\Str::limit($row->description, 60) : '-';
            },
        ]);

        $this->addColumn([
            'index'              => 'status',
            'label'              => 'Trạng thái',
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                ['label' => 'Chờ xử lý', 'value' => self::STATUS_PENDING],
                ['label' => 'Đã xem xét', 'value' => self::STATUS_REVIEWED],
                ['label' => 'Đã giải quyết', 'value' => self::STATUS_RESOLVED],
                ['label' => 'Đã từ chối', 'value' => self::STATUS_DISMISSED],
            ],
            'sortable'   => true,
            'closure'    => function ($row) {
                switch ($row->status) {
                    case self::STATUS_PENDING:
                        return '<p class="label-pending">Chờ xử lý</p>';
                    case self::STATUS_REVIEWED:
                        return '<p class="label-info">Đã xem xét</p>';
                    case self::STATUS_RESOLVED:
                        return '<p class="label-active">Đã giải quyết</p>';
                    case self::STATUS_DISMISSED:
                        return '<p class="label-canceled">Đã từ chối</p>';
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'reviewer_name',
            'label'      => 'Người xử lý',
            'type'       => 'string',
            'sortable'   => true,
            'closure'    => function ($row) {
                return $row->reviewer_name ?: '-';
            },
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => 'Ngày báo cáo',
            'type'            => 'datetime',
            'filterable'      => true,
            'filterable_type' => 'datetime_range',
            'sortable'        => true,
        ]);

        $this->addColumn([
            'index'      => 'reviewed_at',
            'label'      => 'Ngày xử lý',
            'type'       => 'datetime',
            'sortable'   => true,
            'closure'    => function ($row) {
                return $row->reviewed_at ? date('d/m/Y H:i', strtotime($row->reviewed_at)) : '-';
            },
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'index'  => 'view',
            'icon'   => 'icon-eye',
            'title'  => 'Xem chi tiết',
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.forum.reports.show', $row->id);
            },
        ]);

        $this->addAction([
            'index'  => 'edit',
            'icon'   => 'icon-edit',
            'title'  => 'Xử lý',
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.forum.reports.edit', $row->id);
            },
        ]);

        $this->addAction([
            'index'  => 'delete',
            'icon'   => 'icon-delete',
            'title'  => 'Xóa',
            'method' => 'DELETE',
            'url'    => function ($row) {
                return route('admin.forum.reports.delete', $row->id);
            },
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'index'   => 'update_status',
            'title'   => 'Cập nhật trạng thái',
            'method'  => 'POST',
            'url'     => route('admin.forum.reports.mass_update'),
            'options' => [
                ['label' => 'Chờ xử lý', 'value' => self::STATUS_PENDING],
                ['label' => 'Đã xem xét', 'value' => self::STATUS_REVIEWED],
                ['label' => 'Đã giải quyết', 'value' => self::STATUS_RESOLVED],
                ['label' => 'Đã từ chối', 'value' => self::STATUS_DISMISSED],
            ],
        ]);

        $this->addMassAction([
            'index'  => 'delete',
            'title'  => 'Xóa',
            'method' => 'POST',
            'url'    => route('admin.forum.reports.mass_delete'),
        ]);
    }
}
