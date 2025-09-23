<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Webkul\Shop\Http\Controllers\Controller;
use Illuminate\Support\Str;

class CourseRegistrationController extends Controller
{
    /**
     * API endpoint để nhận đăng ký khóa học từ landing page
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerCourse(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255', 
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string|max:1000',
            'course_interest' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $data = $validator->validated();
            
            // Thông tin khóa học Virtual Product đã tạo
            $courseProductId = 18; // Product ID của khóa học
            $courseSku = 'COURSE-ESG-001';
            $coursePrice = 26600000; // 26,600,000 VND
            $channelId = 1; // Default channel
            
            // Tạo Order cho khóa học - use correct column names
            $orderData = [
                'customer_email' => $data['email'],
                'customer_first_name' => $data['first_name'],
                'customer_last_name' => $data['last_name'],
                'status' => 'pending',
                'grand_total' => $coursePrice,
                'base_grand_total' => $coursePrice,
                'sub_total' => $coursePrice,
                'base_sub_total' => $coursePrice,
                'base_currency_code' => 'VND',
                'channel_currency_code' => 'VND', 
                'order_currency_code' => 'VND',
                'channel_id' => $channelId,
                'channel_name' => 'Default',
                'channel_type' => 'Webkul\\\\Core\\\\Models\\\\Channel',
                'is_guest' => 1,
                'total_item_count' => 1,
                'total_qty_ordered' => 1,
                'increment_id' => $this->generateOrderIncrementId(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Note: 'notes' column doesn't exist in orders table
            // Message will be stored in order_addresses or as separate record if needed

            // Insert Order
            $orderId = DB::table('orders')->insertGetId($orderData);

            // Tạo Order Item cho khóa học - Set product fields to null to avoid morphTo issues
            $orderItemData = [
                'order_id' => $orderId,
                'product_id' => null, // Set to null to avoid Product class loading
                'product_type' => null, // Set to null to avoid morphTo relationship
                'sku' => $courseSku,
                'type' => 'virtual',
                'name' => 'Khóa học Kỹ thuật viên Chăm sóc Cổ – Vai – Gáy & Mắt',
                'qty_ordered' => 1,
                'qty_shipped' => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'price' => $coursePrice,
                'base_price' => $coursePrice,
                'total' => $coursePrice,
                'base_total' => $coursePrice,
                'weight' => 0,
                'total_weight' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('order_items')->insert($orderItemData);

            // Tạo payment record để avoid null payment error trong admin view
            $paymentData = [
                'order_id' => $orderId,
                'method' => 'cashondelivery',
                'method_title' => 'Cash On Delivery',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            DB::table('order_payment')->insert($paymentData);

            // Note: order_addresses table doesn't exist, skipping address creation
            // For course registration, billing address is not critical since it's virtual product

            // Tạo Customer (tùy chọn) để theo dõi học viên
            $existingCustomer = DB::table('customers')->where('email', $data['email'])->first();
            
            if (!$existingCustomer) {
                $customerData = [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'status' => 1,
                    'is_verified' => 0,
                    'customer_group_id' => 1,
                    'channel_id' => $channelId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $customerId = DB::table('customers')->insertGetId($customerData);
                
                // Cập nhật order với customer_id
                DB::table('orders')->where('id', $orderId)->update([
                    'customer_id' => $customerId,
                    'is_guest' => 0,
                ]);
            } else {
                // Cập nhật order với existing customer
                DB::table('orders')->where('id', $orderId)->update([
                    'customer_id' => $existingCustomer->id,
                    'is_guest' => 0,
                ]);
            }

            DB::commit();

            // Response thành công
            return response()->json([
                'success' => true,
                'message' => 'Đăng ký khóa học thành công!',
                'data' => [
                    'order_id' => $orderId,
                    'order_increment_id' => $orderData['increment_id'],
                    'course_name' => 'Khóa học Kỹ thuật viên Chăm sóc Cổ – Vai – Gáy & Mắt',
                    'price' => number_format($coursePrice, 0, ',', '.') . ' VND',
                    'customer_email' => $data['email'],
                    'status' => 'pending'
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Course registration error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng ký khóa học. Vui lòng thử lại.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Tạo increment ID cho order
     * 
     * @return string
     */
    private function generateOrderIncrementId()
    {
        $lastOrder = DB::table('orders')->orderBy('id', 'desc')->first();
        
        if ($lastOrder && !empty($lastOrder->increment_id)) {
            $lastIncrementId = intval($lastOrder->increment_id);
            return str_pad($lastIncrementId + 1, 9, '0', STR_PAD_LEFT);
        }
        
        return str_pad(1, 9, '0', STR_PAD_LEFT);
    }

    /**
     * API để lấy thông tin khóa học
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCourseInfo()
    {
        try {
            $courseProduct = DB::table('products')->where('id', 18)->first();
            
            if (!$courseProduct) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy khóa học'
                ], 404);
            }

            // Lấy thông tin attributes của khóa học
            $productAttributes = DB::table('product_attribute_values as pav')
                ->join('attributes as a', 'pav.attribute_id', '=', 'a.id')
                ->join('attribute_translations as at', 'a.id', '=', 'at.attribute_id')
                ->where('pav.product_id', 18)
                ->where('at.locale', 'vi')
                ->select('a.code', 'at.name', 'pav.text_value', 'pav.boolean_value', 'pav.date_value')
                ->get();

            $courseInfo = [
                'id' => $courseProduct->id,
                'sku' => $courseProduct->sku,
                'name' => 'Khóa học Kỹ thuật viên Chăm sóc Cổ – Vai – Gáy & Mắt',
                'price' => 26600000,
                'formatted_price' => '26.600.000 VND',
                'type' => $courseProduct->type,
                'status' => 'active', // Default status since column doesn't exist
                'attributes' => []
            ];

            // Format attributes
            foreach ($productAttributes as $attr) {
                $value = $attr->text_value ?? $attr->boolean_value ?? $attr->date_value;
                $courseInfo['attributes'][$attr->code] = [
                    'name' => $attr->name,
                    'value' => $value
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $courseInfo
            ]);

        } catch (\Exception $e) {
            \Log::error('Get course info error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thông tin khóa học'
            ], 500);
        }
    }

    /**
     * API để check status đăng ký bằng email
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkRegistrationStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $email = $request->input('email');
            
            // Tìm orders của email này cho khóa học
            $orders = DB::table('orders as o')
                ->join('order_items as oi', 'o.id', '=', 'oi.order_id')
                ->where('o.customer_email', $email)
                ->where('oi.sku', 'COURSE-ESG-001') // Filter by SKU instead of product_id
                ->select('o.id', 'o.increment_id', 'o.status', 'o.grand_total', 'o.created_at')
                ->orderBy('o.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'email' => $email,
                    'total_registrations' => $orders->count(),
                    'orders' => $orders->map(function($order) {
                        return [
                            'order_id' => $order->increment_id,
                            'status' => $order->status,
                            'total' => number_format($order->grand_total, 0, ',', '.') . ' VND',
                            'registered_at' => $order->created_at
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Check registration status error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi kiểm tra trạng thái đăng ký'
            ], 500);
        }
    }
}
