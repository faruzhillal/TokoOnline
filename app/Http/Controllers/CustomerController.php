<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ImageHelper;

class CustomerController extends Controller
{
    // Redirect ke Google

    public function index()
    {
        $customer = Customer::orderBy('id', 'desc')->get();
        return view('backend.v_customer.index', [
            'judul' => 'Customer',
            'sub' => 'Halaman Customer',
            'index' => $customer
        ]);
    }
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }
    // Callback dari Google
    public function callback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
            // Cek user berdasarkan email atau google_id di tabel customers
            $user = User::where('email', $socialUser->email)
                ->orWhereHas('customer', function ($query) use ($socialUser) {
                    $query->where('google_id', $socialUser->id);
                })
                ->first();

            if (!$user) {
                // Buat user baru di tabel users
                $user = User::create([
                    'nama' => $socialUser->name,
                    'email' => $socialUser->email,
                    'role' => '2',
                    'status' => 1,
                    'password' => 'admin',
                ]);

                // Buat data customer terkait
                $user->customer()->create([
                    'google_id' => $socialUser->id,
                    'google_token' => $socialUser->token,
                    'google_refresh_token' => $socialUser->refreshToken,
                ]);
            } else {
                // Update data customer jika sudah ada
                $user->customer()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'google_id' => $socialUser->id,
                        'google_token' => $socialUser->token,
                    ]
                );
            }

            $user->load('customer');

            Auth::login($user);
            return redirect()->intended('beranda');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Login gagal: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Logout pengguna
        $request->session()->invalidate(); // Hapus session
        $request->session()->regenerateToken(); // Regenerate token CSRF
        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }

    public function akun($id)
    {
        $loggedInCustomerId = Auth::user()->id;

        // Cek apakah ID yang diberikan sama dengan ID customer yang sedang login
        if ($id != $loggedInCustomerId) {
            // Redirect atau tampilkan pesan error
            return redirect()->route('customer.akun', ['id' => $loggedInCustomerId])
                ->with('msgError', 'Anda tidak berhak mengakses akun ini.');
        }

        $customer = Customer::where('user_id', $id)->firstOrFail();
        $userData = User::where('id', $customer->user_id)->firstOrFail();
        return view('v_customer.edit', [
            'judul' => 'Customer',
            'subJudul' => 'Akun Customer',
            'edit' => $customer,
            'userData' => $userData,
        ]);
    }

    public function updateAkun(Request $request, $id)
    {
        $customer = Customer::where('user_id', $id)->firstOrFail();

        $rules = [
            'nama' => 'required|max:255',
            'hp' => 'required|min:10|max:13',
            'foto' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ];

        $messages = [
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar maksimal adalah 1024 KB.'
        ];

        // Validasi email jika diubah
        if ($request->email != $customer->user->email) {
            $rules['email'] = 'required|max:255|email|unique:customer';
        }

        // Validasi alamat jika diubah
        if ($request->alamat != $customer->alamat) {
            $rules['alamat'] = 'required';
        }

        // Validasi pos jika diubah
        if ($request->pos != $customer->pos) {
            $rules['pos'] = 'required';
        }

        // Validasi data
        $validatedData = $request->validate($rules, $messages);

        // Menggunakan ImageHelper untuk mengelola gambar
        if ($request->file('foto')) {
            // Hapus gambar lama jika ada
            if ($customer->user->foto) {
                $oldImagePath = public_path('storage/img-customer/') . $customer->user->foto;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Proses upload gambar baru
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-customer/';

            // Simpan gambar dengan ukuran yang ditentukan
            ImageHelper::uploadAndResize($file, $directory, $originalFileName, 385, 400); // null (jika tinggi otomatis)

            // Simpan nama file asli di database
            $validatedData['foto'] = $originalFileName;
        }

        // Update data user dan customer
        $customer->user->update($validatedData);
        $customer->update([
            'alamat' => $request->input('alamat'),
            'pos' => $request->input('pos'),
        ]);

        return redirect()->route('customer.akun', $id)->with('success', 'Data berhasil diperbarui');
    }
}
