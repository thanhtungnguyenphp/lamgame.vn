<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use Webkul\User\Models\Admin;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

class AuthController extends Controller
{
    /**
     * Login user and create token
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'device_name' => 'nullable|string'
            ]);

            $user = Admin::where('email', $request->email)->first();

            if (! $user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng không tồn tại.',
                    'errors' => [
                        'email' => ['Email không tồn tại trong hệ thống.']
                    ]
                ], 401);
            }

            if (! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đăng nhập không thành công.',
                    'errors' => [
                        'password' => ['Mật khẩu không chính xác.']
                    ]
                ], 401);
            }

            if (! $user->status) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tài khoản bị khóa.',
                    'errors' => [
                        'account' => ['Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin.']
                    ]
                ], 401);
            }

            $deviceName = $request->device_name ?? $request->userAgent() ?? 'Unknown Device';
            $token = $user->createToken($deviceName)->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Đăng nhập thành công.',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => new AdminResource($user),
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi đăng nhập.',
                'errors' => [
                    'system' => ['Vui lòng thử lại sau hoặc liên hệ admin.']
                ]
            ], 500);
        }
    }

    /**
     * Register a new user
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $user = Admin::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'status' => true, // Account is active by default
                'role_id' => 1, // Default role - adjust as needed
            ]);

            $deviceName = $validated['device_name'] ?? $request->userAgent() ?? 'Unknown Device';
            $token = $user->createToken($deviceName)->plainTextToken;

            // Log successful registration
            \Log::info('User registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Đăng ký tài khoản thành công.',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => new AdminResource($user),
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $request->email ?? 'unknown',
                'ip' => $request->ip()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi đăng ký tài khoản.',
                'errors' => [
                    'system' => ['Vui lòng thử lại sau hoặc liên hệ admin.']
                ]
            ], 500);
        }
    }

    /**
     * Get authenticated user details
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new AdminResource($request->user()),
        ]);
    }

    /**
     * Update user profile
     */
/**
     * Update user profile
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            $data = $request->safe()->only(['name', 'email']);

            // Xử lý upload image nếu có
            if ($request->hasFile('image')) {
                // Xóa image cũ nếu có
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }

                // Upload và resize image mới
                $image = $request->file('image');
                $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('admin', $filename, 'public');

                // Resize image
                $img = Image::make(storage_path('app/public/' . $path));
                $img->fit(300, 300, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save();

                $data['image'] = $path;
            }

            // Admin model doesn't have phone field - skip phone processing

            // Cập nhật thông tin user
            $user->update($data);

            // Reset email verification nếu email thay đổi
            if ($request->email !== $user->getOriginal('email')) {
                $user->email_verified_at = null;
                $user->save();
                
                // Gửi email xác thực mới nếu có chức năng verify
                // event(new VerificationEmailChanged($user));
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật hồ sơ thành công.',
                'data' => [
                    'user' => new AdminResource($user)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi cập nhật hồ sơ.',
                'errors' => [
                    'system' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided current password is incorrect.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Password changed successfully',
        ]);
    }

    /**
     * Send password reset link
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Password reset link sent successfully',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password reset successfully',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    /**
     * Logout user (Revoke the token)
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}