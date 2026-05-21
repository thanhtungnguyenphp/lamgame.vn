<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationMail;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
class CustomerAuthController extends Controller
{

    protected $customerRepository;
    protected $customerGroupRepository;

    public function __construct(CustomerRepository $customerRepository, CustomerGroupRepository $customerGroupRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->customerGroupRepository = $customerGroupRepository;
        $this->middleware('guest:customer')->except('logout', 'profile', 'updateProfile');
    }

    /**
     * Test method
     */
    public function test()
    {
        return 'Controller working!';
    }
    
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Check if customer exists and is verified
        $customer = Customer::where('email', $request->email)->first();
        
        if ($customer && !$customer->is_verified) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản chưa được xác thực. Vui lòng kiểm tra email.'
                ], 422);
            }

            return back()->withErrors([
                'email' => 'Tài khoản chưa được xác thực. Vui lòng kiểm tra email.',
            ])->withInput();
        }

        // Attempt to log the user in
        if (Auth::guard('customer')->attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);
    }

    /**
     * Send the response after the user was authenticated.
     */
    protected function sendLoginResponse(Request $request)
    {
        $redirectUrl = route('shop.customers.account.profile.index');
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công!',
                'redirect_url' => $redirectUrl
            ]);
        }

        return redirect()->intended($redirectUrl)
                        ->with('success', 'Chào mừng bạn quay trở lại!');
    }

    /**
     * Get the failed login response instance.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không chính xác.'
            ], 422);
        }

        throw ValidationException::withMessages([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ]);
    }

    /**
     * Show the registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $this->validateRegistration($request);

        // Check if email already exists
        if (Customer::where('email', $request->email)->exists()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email này đã được đăng ký.'
                ], 422);
            }

            return back()->withErrors(['email' => 'Email này đã được đăng ký.'])->withInput();
        }

        // Create the customer (not verified yet)
        $customer = $this->createCustomer($request->all());

        // Generate verification URL (simple, no signature)
        $verificationUrl = route('auth.verify', [
            'id' => $customer->id,
            'hash' => sha1($customer->email),
            'expires' => now()->addHours(24)->timestamp
        ]);

        // Send verification email
        try {
            Mail::to($customer->email)->send(new VerificationMail($customer, $verificationUrl));
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.',
            ]);
        }

        return redirect(route('auth.login'))
                        ->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.');
    }

    /**
     * Validate the user registration request.
     */
    protected function validateRegistration(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ], [
            'first_name.required' => 'Họ là bắt buộc.',
            'last_name.required' => 'Tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được đăng ký.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password_confirmation.required' => 'Xác nhận mật khẩu là bắt buộc.',
        ]);
    }

    /**
     * Create a new customer instance.
     */
    protected function createCustomer(array $data)
    {
        // Get default customer group ("general" group)
        $defaultGroup = $this->customerGroupRepository->findOneWhere(['code' => 'general']);
        
        return Customer::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'channel_id' => core()->getCurrentChannel()->id,
            'customer_group_id' => $defaultGroup ? $defaultGroup->id : 2,
            'is_verified' => 0, // Not verified yet
            'status' => 0, // Inactive until verified
        ]);
    }

    /**
     * Verify email address
     */
    public function verifyEmail(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        // Check if hash matches
        if (sha1($customer->email) !== $request->hash) {
            return redirect(route('auth.login'))
                ->withErrors(['email' => 'Link xác thực không hợp lệ.']);
        }

        // Check expiration (24 hours)
        if ($request->has('expires') && now()->timestamp > $request->expires) {
            return redirect(route('auth.login'))
                ->withErrors(['email' => 'Link xác thực đã hết hạn.']);
        }

        // Check if already verified
        if ($customer->is_verified) {
            return redirect(route('auth.login'))
                ->with('info', 'Tài khoản đã được xác thực trước đó.');
        }

        // Activate account
        $customer->update([
            'is_verified' => 1,
            'status' => 1,
        ]);

        // Send welcome email
        try {
            Mail::to($customer->email)->send(new WelcomeMail($customer));
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        // Auto login
        Auth::guard('customer')->login($customer);

        return redirect(url('/'))
            ->with('success', 'Xác thực thành công! Chào mừng bạn đến với LAMGAME.');
    }

    /**
     * Log the user out
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã đăng xuất thành công.',
                'redirect_url' => url('/')
            ]);
        }

        return redirect(url('/'))
                        ->with('success', 'Đã đăng xuất thành công.');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.'
        ]);

        // We will send the password reset link to this user
        $response = Password::broker('customers')->sendResetLink(
            $request->only('email')
        );

        if ($response === Password::RESET_LINK_SENT) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Chúng tôi đã gửi link khôi phục mật khẩu đến email của bạn.'
                ]);
            }

            return back()->with('success', 'Chúng tôi đã gửi link khôi phục mật khẩu đến email của bạn.');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy người dùng với email này.'
            ], 422);
        }

        return back()->withErrors(['email' => 'Không tìm thấy người dùng với email này.']);
    }

    /**
     * Show password reset form
     */
    public function showPasswordResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $response = Password::broker('customers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($response === Password::PASSWORD_RESET) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mật khẩu đã được khôi phục thành công.',
                    'redirect_url' => route('auth.login')
                ]);
            }

            return redirect(route('auth.login'))->with('success', 'Mật khẩu đã được khôi phục thành công.');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Link khôi phục mật khẩu không hợp lệ hoặc đã hết hạn.'
            ], 422);
        }

        return back()->withErrors(['email' => 'Link khôi phục mật khẩu không hợp lệ hoặc đã hết hạn.']);
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        return view('auth.profile', [
            'customer' => Auth::guard('customer')->user()
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'first_name.required' => 'Họ là bắt buộc.',
            'last_name.required' => 'Tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng.',
            'avatar.image' => 'Avatar phải là ảnh.',
            'avatar.max' => 'Avatar tối đa 2MB.',
        ]);

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('customers/avatars', 'public');
            $data['image'] = $path;
        }

        $customer->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin thành công.'
            ]);
        }

        return back()->with('success', 'Cập nhật thông tin thành công.');
    }
}