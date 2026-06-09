<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordOtp;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $remember = $request->boolean('remember');

        if (Auth::attempt([$loginType => $request->login, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->isKitchen()) {
                return redirect()->intended('/kitchen/dashboard');
            } elseif ($user->isCourier()) {
                return redirect()->intended('/courier/dashboard');
            }
            
            return redirect()->intended('/customer/dashboard');
        }

        return back()->withErrors([
            'login' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'password.min' => 'Password minimal 8 karakter.',
            'password.mixed' => 'Password harus mengandung huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        CustomerProfile::create([
            'user_id' => $user->id,
        ]);

        Auth::login($user);

        return redirect('/customer/dashboard')->with('success', 'Pendaftaran berhasil!');
    }

    // ── Social Login ──

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['login' => 'Login Google gagal. Silakan coba lagi.']);
        }

        return $this->handleSocialUser($socialUser, 'google');
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $socialUser = Socialite::driver('facebook')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['login' => 'Login Facebook gagal. Silakan coba lagi.']);
        }

        return $this->handleSocialUser($socialUser, 'facebook');
    }

    protected function handleSocialUser($socialUser, string $provider)
    {
        $user = User::where('provider', $provider)
                    ->where('provider_id', $socialUser->getId())
                    ->first();

        if ($user) {
            Auth::login($user, true);
            return $this->redirectByRole($user);
        }

        $existingUser = User::where('email', $socialUser->getEmail())->first();

        if ($existingUser) {
            $existingUser->update([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'provider_token' => $socialUser->token,
            ]);

            Auth::login($existingUser, true);
            return $this->redirectByRole($existingUser);
        }

        $name = $socialUser->getName();
        $username = str($name)->snake()->limit(20, '')->toString() . '_' . substr($socialUser->getId(), 0, 6);
        $baseUsername = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $user = User::create([
            'name' => $name,
            'username' => $username,
            'email' => $socialUser->getEmail(),
            'password' => Hash::make(str()->random(32)),
            'role' => 'customer',
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'provider_token' => $socialUser->token,
        ]);

        CustomerProfile::create([
            'user_id' => $user->id,
        ]);

        Auth::login($user, true);

        return redirect()->route('customer.dashboard')->with('success', 'Login berhasil dengan ' . ucfirst($provider) . '!');
    }

    protected function redirectByRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->intended('/admin/dashboard');
        } elseif ($user->isKitchen()) {
            return redirect()->intended('/kitchen/dashboard');
        } elseif ($user->isCourier()) {
            return redirect()->intended('/courier/dashboard');
        }
        return redirect()->intended('/customer/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Email tidak terdaftar dalam sistem.'
        ]);

        $otp = rand(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($otp),
                'created_at' => Carbon::now()
            ]
        );

        try {
            Mail::to($request->email)->send(new ResetPasswordOtp($otp));
        } catch (\Exception $e) {
            // For testing: we can check the logs if SMTP is not configured.
            \Illuminate\Support\Facades\Log::info("OTP for {$request->email} is {$otp}");
        }

        return redirect()->route('password.verify_otp', ['email' => $request->email])
                         ->with('success', 'Kode OTP 6 digit telah dikirim ke email Anda.');
    }

    public function showVerifyOtpForm(Request $request)
    {
        if (!$request->has('email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-otp', ['email' => $request->email]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6',
        ]);

        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$resetRecord || !Hash::check($request->otp, $resetRecord->token)) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.'])->withInput();
        }

        $createdAt = Carbon::parse($resetRecord->created_at);
        if ($createdAt->diffInSeconds(Carbon::now()) > 60) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa (lebih dari 1 menit). Silakan kirim ulang kode.'])->withInput();
        }

        // OTP Valid. Forward to reset password form with the OTP as a token
        return redirect()->route('password.reset', [
            'email' => $request->email,
            'token' => $request->otp
        ]);
    }

    public function showResetPasswordForm(Request $request)
    {
        if (!$request->has('email') || !$request->has('token')) {
            return redirect()->route('password.request');
        }
        
        return view('auth.reset-password', [
            'email' => $request->email,
            'token' => $request->token
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'password.min' => 'Password minimal 8 karakter.',
            'password.mixed' => 'Password harus mengandung huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'Sesi reset password tidak valid atau Anda menginputkan OTP yang salah.']);
        }

        $createdAt = Carbon::parse($resetRecord->created_at);
        // We give them 15 minutes to actually type the new password after OTP is verified
        if ($createdAt->diffInMinutes(Carbon::now()) > 15) {
            return back()->withErrors(['email' => 'Sesi reset password sudah kadaluarsa. Silakan ulangi proses.']);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Delete the token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login dengan password baru Anda.');
    }
}
