<?php

namespace Webkul\Admin\DataGrids\Forum;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ForumCommentDataGrid extends DataGrid
{
    protected $primaryColumn = 'id';

    const STATUS_PUBLISHED = 'published';
    const STATUS_PENDING = 'pending';
    const STATUS_REJECTED = 'rejected';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('forum_comments')
            ->leftJoin('forum_posts', 'forum_comments.post_id', '=', 'forum_posts.id')
            ->select(
                'forum_comments.id',
                'forum_comments.content',
                'forum_comments.author_name',
                'forum_comments.author_email',
                'forum_comments.status',
                'forum_comments.likes_count',
                'forum_comments.dislikes_count',
                'forum_comments.replies_count',
                'forum_comments.created_at',
                'forum_posts.title as post_title',
                'forum_posts.slug as post_slug'
            );

        $this->addFilter('id', 'forum_comments.id');
        $this->addFilter('content', 'forum_comments.content');
        $this->addFilter('status', 'forum_comments.status');
        $this->addFilter('author_name', 'forum_comments.author_name');
        $this->addFilter('created_at', 'forum_comments.created_at');
        $this->addFilter('post_title', 'forum_posts.title');

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
            'index'      => 'content',
            'label'      => 'Nội dung',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
            'closure'    => function ($row) {
                return \Illuminate\Support\Str::limit(strip_tags($row->content), 80);
            },
        ]);

        $this->addColumn([
            'index'      => 'author_name',
            'label'      => 'Tác giả',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'post_title',
            'label'      => 'Bài viết',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                return '<a href="' . route('forum.posts.show', $row->post_slug) . '" target="_blank" class="text-blue-600 hover:underline">' 
                    . \Illuminate\Support\Str::limit($row->post_title, 50) . 
                    '</a>';
            },
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
            'index'      => 'likes_count',
            'label'      => 'Thích',
            'type'       => 'integer',
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'replies_count',
            'label'      => 'Trả lời',
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
            'title'  => 'Xem bài viết',
            'method' => 'GET',
            'url'    => function ($row) {
                return route('forum.posts.show', $row->post_slug);
            },
        ]);

        $this->addAction([
            'index'  => 'edit',
            'icon'   => 'icon-edit',
            'title'  => 'Chỉnh sửa',
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.forum.comments.edit', $row->id);
            },
        ]);

        $this->addAction([
            'index'  => 'delete',
            'icon'   => 'icon-delete',
            'title'  => 'Xóa',
            'method' => 'DELETE',
            'url'    => function ($row) {
                return route('admin.forum.comments.delete', $row->id);
            },
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'index'   => 'update_status',
            'title'   => 'Cập nhật trạng thái',
            'method'  => 'POST',
            'url'     => route('admin.forum.comments.mass_update'),
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
            'url'    => route('admin.forum.comments.mass_delete'),
        ]);
    }
}
