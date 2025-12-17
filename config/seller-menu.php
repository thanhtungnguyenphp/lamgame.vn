<?php

return [
    [
        'key'   => 'sellers',
        'name'  => 'Sellers',
        'route' => 'admin.sellers.index',
        'sort'  => 5,
        'icon'  => 'icon-users',
    ],
    [
        'key'   => 'sellers.all',
        'name'  => 'Tất cả Sellers',
        'route' => 'admin.sellers.index',
        'sort'  => 1,
        'icon'  => '',
    ],
    [
        'key'   => 'sellers.pending',
        'name'  => 'Chờ duyệt',
        'route' => 'admin.sellers.pending',
        'sort'  => 2,
        'icon'  => '',
    ],
];
