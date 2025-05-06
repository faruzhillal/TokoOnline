<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RajaOngkirController; // Tambahkan ini
use Illuminate\Support\Facades\Http;


// Route untuk halaman utama
Route::get('/', function () {
    return redirect()->route('beranda');
});

// Route untuk Beranda
Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])
    ->name('backend.beranda')
    ->middleware('auth');

// Route untuk Login
Route::get('backend/login', [LoginController::class, 'loginBackend'])
    ->name('backend.login');

Route::post('backend/login', [LoginController::class, 'authenticateBackend'])
    ->name('backend.login');

Route::post('backend/logout', [LoginController::class, 'logoutBackend'])
    ->name('backend.logout');

// Route untuk User
Route::resource('backend/user', UserController::class, ['as' => 'backend'])
    ->middleware('auth');

// Route untuk laporan user
Route::get('backend/laporan/formuser', [UserController::class, 'formUser'])
    ->name('backend.laporan.formuser')
    ->middleware('auth');

Route::post('backend/laporan/cetakuser', [UserController::class, 'cetakUser'])
    ->name('backend.laporan.cetakuser')
    ->middleware('auth');

// Route untuk Kategori
Route::resource('backend/kategori', KategoriController::class, ['as' => 'backend'])
    ->middleware('auth');

// Route untuk Produk
Route::resource('backend/produk', ProdukController::class, ['as' => 'backend'])
    ->middleware('auth');

// Route untuk menambahkan foto
Route::post('foto-produk/store', [ProdukController::class, 'storeFoto'])
    ->name('backend.foto_produk.store')
    ->middleware('auth');

// Route untuk menghapus foto
Route::delete('foto-produk/{id}', [ProdukController::class, 'destroyFoto'])
    ->name('backend.foto_produk.destroy')
    ->middleware('auth');

// Route untuk laporan produk
Route::get('backend/laporan/formproduk', [ProdukController::class, 'formProduk'])
    ->name('backend.laporan.formproduk')
    ->middleware('auth');

Route::post('backend/laporan/cetakproduk', [ProdukController::class, 'cetakProduk'])
    ->name('backend.laporan.cetakproduk')
    ->middleware('auth');

// Frontend
Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');

Route::get('/produk/detail/{id}', [ProdukController::class, 'detail'])->name('produk.detail');

Route::get('/produk/kategori/{id}', [
    ProdukController::class,
    'produkKategori'
])->name('produk.kategori');

Route::get('/produk/all', [ProdukController::class, 'produkAll'])->name('produk.all');

//API Google
Route::get('/auth/redirect', [CustomerController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/google/callback', [CustomerController::class, 'callback'])->name('auth.callback');

// Logout
Route::post('/logout', [CustomerController::class, 'logout'])->name('logout');
Route::post('/customer/logout', [CustomerController::class, 'logout'])->name('customer.logout');

// Route untuk Customer
Route::resource('backend/customer', CustomerController::class, ['as' => 'backend'])->middleware('auth');

// Group route untuk customer
Route::middleware('is.customer')->group(function () {
    // Route untuk menampilkan halaman akun customer
    Route::get('/customer/akun/{id}', [CustomerController::class, 'akun'])
        ->name('customer.akun');

    // Route untuk mengupdate data akun customer
    Route::put('/customer/updateakun/{id}', [CustomerController::class, 'updateAkun'])
        ->name('customer.updateakun');
});

// Group route untuk customer
Route::middleware('is.customer')->group(function () {
    // Route untuk menampilkan halaman akun customer
    Route::get('/customer/akun/{id}', [CustomerController::class, 'akun'])
        ->name('customer.akun');

    // Route untuk mengupdate data akun customer
    Route::put('/customer/updateakun/{id}', [CustomerController::class, 'updateAkun'])
        ->name('customer.updateakun');

    // Route untuk menambahkan produk ke keranjang
    Route::post('add-to-cart/{id}', [OrderController::class, 'addToCart'])
        ->name('order.addToCart');

    // Route untuk melihat keranjang
    Route::get('cart', [OrderController::class, 'viewCart'])
        ->name('order.cart');
});

Route::middleware(['auth', 'is.customer'])->group(function () {
    Route::put('update-cart/{id}', [OrderController::class, 'updateCart'])->name('order.updateCart');
    Route::delete('remove-from-cart/{id}', [OrderController::class, 'removeFromCart'])->name('order.removeFromCart');
    Route::post('checkout', [OrderController::class, 'checkout'])->name('order.checkout');
});

Route::get('/cek-ongkir', function () {
    return view('ongkir');
});

Route::get('/provinces', [RajaOngkirController::class, 'getProvinces']);
Route::get('/cities', [RajaOngkirController::class, 'getCities']);
Route::post('/cost', [RajaOngkirController::class, 'getCost']);

Route::get('/list-ongkir', function () {
    $response = Http::withHeaders([
        'key' => 'hag5CnSV414a08d4ca5ab2725eolKyh0'
    ])->get('https://api.rajaongkir.com/starter/province'); //ganti 'province' atau 'city'
    dd($response->json());
});
