<?php

namespace App\DataGrids\Admin;

use App\Models\SourceGameSeller;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class SellerDataGrid extends DataGrid
{
    protected $index = 'id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('source_game_sellers as s')
            ->leftJoin('customers as c', 's.customer_id', '=', 'c.id')
            ->select(
                's.id',
                's.shop_name',
                's.shop_slug',
                's.contact_email',
                's.status',
                's.total_products',
                's.total_sales',
                's.total_revenue',
                's.created_at',
                DB::raw("CONCAT(c.first_name, ' ', c.last_name) as customer_name")
            );

        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => 'ID',
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'shop_name',
            'label'      => 'Shop Name',
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'customer_name',
            'label'      => 'Customer',
            'type'       => 'string',
            'searchable' => true,
            'filterable' => false,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'contact_email',
            'label'      => 'Email',
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => 'Status',
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                $colors = [
                    'pending'   => 'warning',
                    'active'    => 'success',
                    'suspended' => 'danger',
                    'banned'    => 'danger',
                ];
                return '<span class="label-' . ($colors[$row->status] ?? 'info') . '">' . ucfirst($row->status) . '</span>';
            },
        ]);

        $this->addColumn([
            'index'      => 'total_products',
            'label'      => 'Products',
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'total_sales',
            'label'      => 'Sales',
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => 'Created At',
            'type'       => 'datetime',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'icon'   => 'icon-view',
            'title'  => 'View',
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.sellers.show', $row->id);
            },
        ]);
    }
}
