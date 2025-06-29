<style>
    table {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ccc;
        font-size: 13px;
    }

    table tr td,
    table tr th {
        padding: 6px;
        border: 1px solid #ccc;
    }

    th {
        background-color: #f2f2f2;
        text-align: center;
    }
</style>

<table>
    <tr>
        <td align="left">
            <strong>Perihal:</strong> {{ $judul }} <br>
            <strong>Periode:</strong> {{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }} s/d
            {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}
        </td>
    </tr>
</table>

<br>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Pelanggan</th>
            <th>Email</th>
            <th>Alamat</th>
            <th>Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total Harga</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach ($data as $order)
        @foreach ($order->orderItems as $item)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $order->customer->user->nama }}</td>
            <td>{{ $order->customer->user->email }}</td>
            <td>{!! $order->alamat !!}</td>
            <td>{{ $item->produk->nama_produk }}</td>
            <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
            <td>{{ $item->quantity }}</td>
            <td>Rp. {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
</table>

<script>
    window.onload = function() {
        window.print();
    }
</script>