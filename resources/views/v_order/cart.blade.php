@extends('v_layouts.app')

@section('content')
<div class="container">
    <h2>Keranjang Belanja</h2>

    @if(session()->has('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session()->has('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if($order && $order->orderItems->count() > 0)
    @php
    $totalHarga = 0;
    $totalBerat = 0;
    @endphp

    @foreach($order->orderItems as $item)
    @php
    $totalHarga += $item->harga * $item->quantity;
    $totalBerat += $item->produk->berat * $item->quantity;
    @endphp

    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-2">
                @if($item->produk->gambar)
                <img src="{{ asset('storage/' . $item->produk->gambar) }}" class="img-fluid rounded-start"
                    alt="{{ $item->produk->nama_produk }}">
                @else
                <img src="{{ asset('images/default-product.png') }}" class="img-fluid rounded-start"
                    alt="Default Product Image">
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $item->produk->nama_produk }}</h5>
                    <p class="card-text">
                        * Berat: {{ $item->produk->berat }} Gram<br>
                        * Stok: {{ $item->produk->stok }}
                    </p>
                    <p class="card-text"><small class="text-muted">Rp.
                            {{ number_format($item->harga, 0, ',', '.') }}</small></p>
                    <form action="{{ route('order.updateCart', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                            max="{{ $item->produk->stok }}">
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </form>
                </div>
            </div>
            <div class="col-md-2">
                <p class="card-text text-end"><strong>Rp.
                        {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}</strong></p>
                <form action="{{ route('order.removeFromCart', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <div class="row">
        <div class="col-md-6">
            <h4>Total Harga: Rp. {{ number_format($totalHarga, 0, ',', '.') }}</h4>
            <h4>Total Berat: {{ $totalBerat }} Gram</h4>
        </div>
        <div class="col-md-6 text-end">
            <form action="{{ route('order.checkout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Checkout</button>
            </form>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        Keranjang belanja kosong.
    </div>
    @endif
</div>
@endsection