<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SourceGameSeller;
use App\Mail\SellerApproved;
use App\Mail\SellerRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminSellerController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return app(\App\DataGrids\Admin\SellerDataGrid::class)->toJson();
        }

        return view('admin.sellers.index');
    }

    public function pending()
    {
        $sellers = SourceGameSeller::with('customer')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.sellers.pending', compact('sellers'));
    }

    public function show($id)
    {
        $seller = SourceGameSeller::with('customer')->findOrFail($id);
        
        return view('admin.sellers.show', compact('seller'));
    }

    public function approve(Request $request, $id)
    {
        $seller = SourceGameSeller::findOrFail($id);

        if ($seller->status !== 'pending') {
            return back()->with('error', 'Seller này đã được xử lý rồi.');
        }

        $seller->update([
            'status' => 'active',
            'verified' => true,
            'verified_at' => now(),
        ]);

        // Send approval email
        try {
            Mail::to($seller->contact_email)->send(new SellerApproved($seller));
        } catch (\Exception $e) {
            \Log::error('Failed to send approval email: ' . $e->getMessage());
        }

        return back()->with('success', 'Đã duyệt seller thành công!');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $seller = SourceGameSeller::findOrFail($id);

        if ($seller->status !== 'pending') {
            return back()->with('error', 'Seller này đã được xử lý rồi.');
        }

        $seller->update([
            'status' => 'rejected',
        ]);

        // Store rejection reason in notes
        $seller->customer->update([
            'notes' => 'Seller rejected: ' . $request->reason,
        ]);

        // Send rejection email
        try {
            Mail::to($seller->contact_email)->send(new SellerRejected($seller, $request->reason));
        } catch (\Exception $e) {
            \Log::error('Failed to send rejection email: ' . $e->getMessage());
        }

        return back()->with('success', 'Đã từ chối seller.');
    }

    public function suspend($id)
    {
        $seller = SourceGameSeller::findOrFail($id);
        
        $seller->update(['status' => 'suspended']);

        return back()->with('success', 'Đã tạm ngưng seller.');
    }

    public function activate($id)
    {
        $seller = SourceGameSeller::findOrFail($id);
        
        $seller->update(['status' => 'active']);

        return back()->with('success', 'Đã kích hoạt lại seller.');
    }
}
