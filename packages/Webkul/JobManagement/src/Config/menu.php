<?php

return [
    [
        'key'        => 'jobs',
        'name'       => 'job_management::app.admin.layouts.jobs',
        'route'      => 'admin.jobs.index',
        'sort'       => 6,
        'icon'       => 'icon-briefcase',
    ], [
        'key'        => 'jobs.manage',
        'name'       => 'job_management::app.admin.layouts.manage-jobs',
        'route'      => 'admin.jobs.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'jobs.companies',
        'name'       => 'job_management::app.admin.layouts.companies',
        'route'      => 'admin.companies.index',
        'sort'       => 2,
        'icon'       => '',
    ]
];
