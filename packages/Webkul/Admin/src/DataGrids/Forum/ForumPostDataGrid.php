<?php

namespace Webkul\Admin\DataGrids\Forum;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ForumPostDataGrid extends DataGrid
{
    protected $primaryColumn = 'id';

    const STATUS_PUBLISHED = 'published';
    const STATUS_PENDING = 'pending';
    const STATUS_REJECTED = 'rejected';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('forum_posts')
            ->leftJoin('forum_categories', 'forum_posts.category_id', '=', 'forum_categories.id')
            ->select(
                'forum_posts.id',
                'forum_posts.title',
                'forum_posts.slug',
                'forum_posts.author_name',
                'forum_posts.author_email',
                'forum_posts.type',
                'forum_posts.status',
                'forum_posts.is_featured',
                'forum_posts.is_sticky',
                'forum_posts.views_count',
                'forum_posts.comments_count',
                'forum_posts.likes_count',
                'forum_posts.created_at',
                'forum_categories.name as category_name'
            );

        $this->addFilter('id', 'forum_posts.id');
        $this->addFilter('title', 'forum_posts.title');
        $this->addFilter('status', 'forum_posts.status');
        $this->addFilter('author_name', 'forum_posts.author_name');
        $this->addFilter('created_at', 'forum_posts.created_at');
        $this->addFilter('category_name', 'forum_categories.name');

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
            'index'      => 'title',
            'label'      => 'Tiêu đề',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'author_name',
            'label'      => 'Tác giả',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'category_name',
            'label'      => 'Danh mục',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'type',
            'label'      => 'Loại',
            'type'       => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                ['label' => 'Thảo luận', 'value' => 'discussion'],
                ['label' => 'Câu hỏi', 'value' => 'question'],
                ['label' => 'Chia sẻ', 'value' => 'share'],
            ],
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'status',
            'label'              => 'Trạng thái',
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                ['label' => 'Đã xuất bản', 'value' => self::STATUS_PUBLISHED],
                ['label' => 'Chờ duyệt', 'value' => self::STATUS_PENDING],
                ['label' => 'Từ chối', 'value' => self::STATUS_REJECTED],
            ],
            'sortable'   => true,
            'closure'    => function ($row) {
                switch ($row->status) {
                    case self::STATUS_PUBLISHED:
                        return '<p class="label-active">Đã xuất bản</p>';
                    case self::STATUS_PENDING:
                        return '<p class="label-pending">Chờ duyệt</p>';
                    case self::STATUS_REJECTED:
                        return '<p class="label-canceled">Từ chối</p>';
                }
            },
        ]);

        $this->addColumn([
            'index'    => 'is_featured',
            'label'    => 'Nổi bật',
            'type'     => 'boolean',
            'sortable' => true,
            'closure'  => function ($row) {
                return $row->is_featured ? '<span class="icon-check text-green-500"></span>' : '';
            },
        ]);

        $this->addColumn([
            'index'    => 'is_sticky',
            'label'    => 'Ghim',
            'type'     => 'boolean',
            'sortable' => true,
            'closure'  => function ($row) {
                return $row->is_sticky ? '<span class="icon-pin text-blue-500"></span>' : '';
            },
        ]);

        $this->addColumn([
            'index'      => 'views_count',
            'label'      => 'Lượt xem',
            'type'       => 'integer',
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'comments_count',
            'label'      => 'Bình luận',
            'type'       => 'integer',
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'likes_count',
            'label'      => 'Thích',
            'type'       => 'integer',
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => 'Ngày tạo',
            'type'            => 'datetime',
            'filterable'      => true,
            'filterable_type' => 'datetime_range',
            'sortable'        => true,
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
                return route('forum.posts.show', $row->slug);
            },
        ]);

        $this->addAction([
            'index'  => 'edit',
            'icon'   => 'icon-edit',
            'title'  => 'Chỉnh sửa',
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.forum.posts.edit', $row->id);
            },
        ]);

        $this->addAction([
            'index'  => 'delete',
            'icon'   => 'icon-delete',
            'title'  => 'Xóa',
            'method' => 'DELETE',
            'url'    => function ($row) {
                return route('admin.forum.posts.delete', $row->id);
            },
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'index'   => 'update_status',
            'title'   => 'Cập nhật trạng thái',
            'method'  => 'POST',
            'url'     => route('admin.forum.posts.mass_update'),
            'options' => [
                ['label' => 'Đã xuất bản', 'value' => self::STATUS_PUBLISHED],
                ['label' => 'Chờ duyệt', 'value' => self::STATUS_PENDING],
                ['label' => 'Từ chối', 'value' => self::STATUS_REJECTED],
            ],
        ]);

        $this->addMassAction([
            'index'  => 'delete',
            'title'  => 'Xóa',
            'method' => 'POST',
            'url'    => route('admin.forum.posts.mass_delete'),
        ]);
    }
}
