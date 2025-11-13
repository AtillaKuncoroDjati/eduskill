<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        return view('user.profile.index', ['user' => auth()->user()]);
    }

    public function update_avatar(Request $request, User $user)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->avatar && $user->avatar !== 'default-avatar.png') {
            $oldAvatarPath = public_path('uploads/avatar/' . $user->avatar);
            if (file_exists($oldAvatarPath)) {
                unlink($oldAvatarPath);
            }
        }

        $extension = strtolower($request->avatar->extension());
        $filename = $user->username . '_' . time() . '.' . $extension;

        $request->avatar->move(public_path('uploads/avatar'), $filename);

        $user->avatar = $filename;
        $user->save();

        session()->flash('success_message', 'Perubahan berhasil! Foto profil Anda sekarang telah diperbarui sesuai dengan pilihan terbaru.');

        return redirect()->route('user.profile', $user);
    }

    public function update_profile(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:50|unique:users,username,' . $user->id,
            'phone' => 'nullable|string|max:15',
            'email' => 'required|string|email|max:100|unique:users,email,' . $user->id
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->name = $request->name;
        $user->username = $request->username ?: null;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->save();

        session()->flash('success_message', 'Perubahan berhasil! Profil Anda sekarang telah diperbarui sesuai dengan pilihan terbaru.');

        return redirect()->route('user.profile', $user);
    }

    public function update_password(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal harus 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!password_verify($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Perubahan gagal! Kata sandi saat ini tidak sesuai.'
            ], 422);
        }

        if ($request->current_password === $request->new_password) {
            return response()->json([
                'message' => 'Perubahan gagal! Kata sandi baru tidak boleh sama dengan kata sandi saat ini.'
            ], 422);
        }

        if ($request->new_password !== $request->new_password_confirmation) {
            return response()->json([
                'message' => 'Perubahan gagal! Konfirmasi kata sandi baru tidak sesuai.'
            ], 422);
        }

        if (strlen($request->new_password) < 8) {
            return response()->json([
                'message' => 'Perubahan gagal! Kata sandi baru harus memiliki minimal 8 karakter.'
            ], 422);
        }

        $user->password = bcrypt($request->new_password);
        $user->password_changed_at = now();
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Perubahan berhasil! Kata sandi Anda sekarang telah diperbarui.',
            'redirect' => route('user.profile', $user)
        ]);
    }

    public function update_otp_setting(Request $request, User $user)
    {
        $request->validate([
            'is_otp' => 'required|boolean',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Perubahan gagal! Anda harus mengatur nomor telepon terlebih dahulu sebelum mengaktifkan OTP.',
                'redirect' => route('user.profile', $user)
            ]);
        }

        $user->is_otp = $request->is_otp;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Perubahan berhasil! Pengaturan OTP Anda sekarang telah diperbarui.',
            'redirect' => route('user.profile', $user)
        ]);
    }

    public function update_email_setting(Request $request, User $user)
    {
        $request->validate([
            'is_email_notification_enabled' => 'required|boolean',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->is_email_notification_enabled = $request->is_email_notification_enabled;
        $user->save();

        $user->refresh();

        if (!$user->is_email_notification_enabled && !$user->is_whatsapp_notification_enabled) {
            $user->is_email_notification_enabled = true;
            $user->save();

            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menonaktifkan keduanya! Minimal salah satu notifikasi (Email atau WhatsApp) harus aktif.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Perubahan berhasil! Pengaturan notifikasi email Anda sekarang telah diperbarui.',
            'redirect' => route('user.profile', $user)
        ]);
    }


    public function update_whatsapp_setting(Request $request, User $user)
    {
        $request->validate([
            'is_whatsapp_notification_enabled' => 'required|boolean',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Perubahan gagal! Anda harus mengatur nomor telepon terlebih dahulu sebelum mengaktifkan notifikasi WhatsApp.',
                'redirect' => route('user.profile', $user)
            ]);
        }

        $user->is_whatsapp_notification_enabled = $request->is_whatsapp_notification_enabled;
        $user->save();

        $user->refresh();

        if (!$user->is_email_notification_enabled && !$user->is_whatsapp_notification_enabled) {
            $user->is_whatsapp_notification_enabled = true;
            $user->save();

            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menonaktifkan keduanya! Minimal salah satu notifikasi (Email atau WhatsApp) harus aktif.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Perubahan berhasil! Pengaturan notifikasi WhatsApp Anda sekarang telah diperbarui.',
            'redirect' => route('user.profile', $user)
        ]);
    }
}
