<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
/** REGISTER USER + OTP */
    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>'user',
        ]);

        // Hapus OTP lama
        DB::table('login_otps')->where('email',$user->email)->delete();

        // Generate OTP baru
        $otp = rand(100000,999999);
        DB::table('login_otps')->insert([
            'email'=>$user->email,
            'otp'=>$otp,
            'expires_at'=>now()->addMinutes(10),
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        try {
            Mail::raw("Kode OTP verifikasi email kamu adalah: $otp. Berlaku 10 menit.", function($message) use ($user){
                $message->to($user->email)->subject('OTP Verifikasi Email');
            });
        } catch (\Exception $e) {
            $user->delete();
            return response()->json(['message'=>'Gagal mengirim OTP. Coba lagi.'], 500);
        }

        return response()->json([
            'message'=>'Registrasi berhasil! OTP dikirim ke email.',
            'user'=>[
                'id'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email,
            ]
        ]);
    }

    /** VERIFY EMAIL OTP */
    public function verifyEmailOtp(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'otp'=>'required|digits:6'
        ]);

        $record = DB::table('login_otps')
            ->where('email',$request->email)
            ->where('otp',$request->otp)
            ->where('expires_at','>=',now())
            ->first();

        if(!$record) return response()->json(['message'=>'OTP salah atau kadaluarsa'],400);

        $user = User::where('email',$request->email)->first();
        if(!$user) return response()->json(['message'=>'User tidak ditemukan'],404);

        $user->email_verified_at = now();
        $user->save();

        DB::table('login_otps')->where('id',$record->id)->delete();

        return response()->json(['message'=>'Email berhasil diverifikasi. Kamu bisa login sekarang.']);
    }

    /** REQUEST OTP LOGIN */
    public function loginOtpRequest(Request $request)
    {
        $request->validate(['email'=>'required|email']);
        $user = User::where('email',$request->email)->first();
        if(!$user) return response()->json(['message'=>'Email tidak terdaftar'],404);

        // Hapus OTP lama
        DB::table('login_otps')->where('email',$user->email)->delete();

        $otp = rand(100000,999999);
        DB::table('login_otps')->insert([
            'email'=>$user->email,
            'otp'=>$otp,
            'expires_at'=>now()->addMinutes(10),
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        Mail::raw("Kode OTP login kamu adalah: $otp. Berlaku 10 menit.", function($message) use ($user){
            $message->to($user->email)->subject('OTP Login');
        });

        return response()->json(['message'=>'OTP login dikirim ke email']);
    }

    /** VERIFY LOGIN OTP */
    public function loginOtpVerify(Request $request)
    {
        $request->validate(['email'=>'required|email','otp'=>'required|digits:6']);

        $record = DB::table('login_otps')
            ->where('email',$request->email)
            ->where('otp',$request->otp)
            ->where('expires_at','>=',now())
            ->first();

        if(!$record) return response()->json(['message'=>'OTP salah atau kadaluarsa'],400);

        $user = User::where('email',$request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        DB::table('login_otps')->where('id',$record->id)->delete();

        return response()->json(['message'=>'Login berhasil','token'=>$token,'user'=>$user]);
    }
    /** LOGIN PAKAI PASSWORD */
public function loginPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Email atau password salah'], 401);
    }

    if (is_null($user->email_verified_at)) {
        return response()->json(['message' => 'Email belum diverifikasi. Silahkan cek email dan masukkan OTP.'], 403);
    }

    // Buat token baru
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login berhasil',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ],
        'token' => $token,
    ]);
}
    /** LOGOUT */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message'=>'Logout berhasil']);
    }

    /** FORGOT PASSWORD */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email'=>'required|email']);
        $user = User::where('email',$request->email)->first();
        if(!$user) return response()->json(['message'=>'Email tidak terdaftar'],404);

        $otp = rand(100000,999999);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email'=>$request->email],
            ['token'=>$otp,'created_at'=>Carbon::now()]
        );

        Mail::raw("OTP reset password: $otp. Berlaku 10 menit.", function($message) use ($request){
            $message->to($request->email)->subject('OTP Reset Password');
        });

        return response()->json(['message'=>'OTP telah dikirim ke email']);
    }

    /** VERIFY OTP RESET PASSWORD */
    public function verifyOtp(Request $request)
    {
        $request->validate(['email'=>'required|email','otp'=>'required|digits:6']);
        $record = DB::table('password_reset_tokens')
            ->where('email',$request->email)
            ->where('token',$request->otp)
            ->first();

        if(!$record || Carbon::now()->diffInMinutes($record->created_at)>10)
            return response()->json(['message'=>'OTP tidak valid atau kadaluarsa'],400);

        return response()->json(['message'=>'OTP valid']);
    }

    /** RESET PASSWORD */
    public function resetPassword(Request $request)
    {
        $request->validate(['email'=>'required|email','password'=>'required|min:6|confirmed']);
        $user = User::where('email',$request->email)->first();
        if(!$user) return response()->json(['message'=>'Email tidak terdaftar'],404);

        $user->password = Hash::make($request->password);
        $user->save();
        DB::table('password_reset_tokens')->where('email',$request->email)->delete();

        return response()->json(['message'=>'Password berhasil direset']);
    }

    /** UPDATE NAMA USER */
    public function updateName(Request $request)
    {
        $request->validate(['name'=>'required|string|max:255']);
        $user = $request->user();
        $user->update(['name'=>$request->name]);
        return response()->json(['message'=>'Nama berhasil diperbarui','user'=>$user]);
    }

    /** TASKS UNTUK USER BIASA */
    public function getTasks()
    {
        return response()->json(['tasks'=>[
            ['id'=>1,'title'=>'Belajar Vue','status'=>'selesai'],
            ['id'=>2,'title'=>'Kerjain projek Laravel','status'=>'proses']
        ]]);
    }

    /** ADMIN: GET ALL USERS */
    public function getAllUsers()
    {
        return response()->json(['users'=>User::select('id','name','email','role','email_verified_at','created_at')->get()]);
    }

    /** ADMIN: DELETE USER */
    public function deleteUser($id)
    {
        $user = User::find($id);
        if(!$user) return response()->json(['message'=>'User tidak ditemukan'],404);
        $user->delete();
        return response()->json(['message'=>'User berhasil dihapus']);
    }

    /** ADMIN: UPDATE ROLE USER */
    public function updateUserRole(Request $request,$id)
    {
        $request->validate(['role'=>'required|in:admin,user']);
        $user=User::find($id);
        if(!$user) return response()->json(['message'=>'User tidak ditemukan'],404);
        $user->role=$request->role;
        $user->save();
        return response()->json(['message'=>'Role berhasil diperbarui','user'=>$user]);
    }
}