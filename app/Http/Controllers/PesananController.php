<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    // Menampilkan form untuk filter dan cetak laporan pesanan
    public function formPesanan()
    {
        return view('backend.v_pesanan.form', [
            'judul' => 'Form Cetak Laporan Pesanan Produk'
        ]);
    }

    // Memproses dan menampilkan halaman cetak laporan pesanan
    public function cetakPesanan(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $data = Order::with(['customer.user', 'orderItems.produk'])
            ->whereBetween('created_at', [$request->tanggal_awal, $request->tanggal_akhir])
            ->get();

        return view('backend.v_pesanan.cetak', [
            'judul' => 'Laporan Pesanan Produk',
            'tanggalAwal' => $request->tanggal_awal,
            'tanggalAkhir' => $request->tanggal_akhir,
            'data' => $data
        ]);
    }
}
