<?php

namespace App\Http\Controllers;

use App\Services\LicenseService;
use Illuminate\Support\Facades\Auth;

class MyLicenseController extends Controller
{
    public function __construct(private LicenseService $service) {}

    /**
     * Trang "License của tôi" cho khách đã đăng nhập (session guard customer).
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        if (! $customer) {
            return redirect()->route('shop.customer.session.index');
        }

        $licenses = $this->service->getMyLicenses($customer->id);

        return view('lamgame.pages.my-licenses', compact('licenses'));
    }
}
