<?php

// Legacy Job Dashboard Redirects
// These routes redirect old job-dashboard URLs to new admin URLs

Route::redirect('/admin/job-dashboard', '/admin/jobs', 301);
Route::redirect('/admin/job-dashboard/jobs', '/admin/jobs', 301);
Route::redirect('/admin/job-dashboard/create', '/admin/jobs/create', 301);

Route::get('/admin/job-dashboard/edit/{id}', function($id) {
    return redirect()->route('admin.jobs.edit', $id, 301);
});

Route::get('/job-dashboard-test', function() {
    return redirect()->route('admin.jobs.index', 301);
});
