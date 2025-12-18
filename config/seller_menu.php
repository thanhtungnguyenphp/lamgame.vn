<?php

return [
    [
        'key'        => 'sellers',
        'name'       => 'Sellers',
        'route'      => 'admin.sellers.index',
        'sort'       => 7,
        'icon'       => 'icon-users',
    ],
    [
        'key'        => 'sellers.list',
        'name'       => 'Quản lý Sellers',
        'route'      => 'admin.sellers.index',
        'sort'       => 1,
        'icon'       => '',
    ],
    [
        'key'        => 'sellers.pending',
        'name'       => 'Chờ duyệt',
        'route'      => 'admin.sellers.pending',
        'sort'       => 2,
        'icon'       => '',
    ],
    [
        'key'        => 'sellers.products',
        'name'       => 'Sản phẩm Sellers',
        'route'      => 'admin.products.sellers',
        'sort'       => 3,
        'icon'       => '',
    ],
    [
        'key'        => 'sellers.products-pending',
        'name'       => 'Sản phẩm chờ duyệt',
        'route'      => 'admin.products.pending',
        'sort'       => 4,
        'icon'       => '',
    ],
];
