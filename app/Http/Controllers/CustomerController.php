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

    // New method for backend customer detail page with order graph
    public function detail($id)
    {
        $customer = Customer::findOrFail($id);
        return view('backend.v_customer.detail', [
            'judul' => 'Detail Customer',
            'subJudul' => 'Detail dan Grafik Pesanan Customer',
            'customer' => $customer,
        ]);
    }

    // New method to provide JSON order data for specific customer
    public function grafikData($id)
    {
        $startDate = now()->subDays(30)->startOfDay();
        $endDate = now()->endOfDay();

        $orders = $customerOrders = \App\Models\Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('customer_id', $id)
            ->whereIn('status', ['Paid', 'Kirim', 'Selesai'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        $data = [];
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $found = $orders->firstWhere('date', $dateStr);
            $data[] = [
                'date' => $dateStr,
                'total' => $found ? $found->total : 0,
            ];
        }

        return response()->json($data);
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

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('backend.v_customer.edit', [
            'judul' => 'Edit Customer',
            'edit' => $customer,
        ]);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $rules = [
            'nama' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $customer->user_id,
            'hp' => 'required|min:10|max:13',
            'alamat' => 'required',
            'pos' => 'required',
            'foto' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ];

        $messages = [
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar maksimal adalah 1024 KB.'
        ];

        $validatedData = $request->validate($rules, $messages);

        // Update user data
        $user = $customer->user;
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->hp = $request->hp;

        if ($request->file('foto')) {
            // Hapus gambar lama jika ada
            if ($user->foto) {
                $oldImagePath = public_path('storage/img-customer/') . $user->foto;
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
            \App\Helpers\ImageHelper::uploadAndResize($file, $directory, $originalFileName, 385, 400);

            $user->foto = $originalFileName;
        }

        $user->save();

        // Update customer data
        $customer->alamat = $request->alamat;
        $customer->pos = $request->pos;
        $customer->save();

        return redirect()->route('backend.customer.index')->with('success', 'Data customer berhasil diperbarui.');
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