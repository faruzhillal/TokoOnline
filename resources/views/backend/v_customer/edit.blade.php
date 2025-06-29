@extends('backend.v_layouts.app') {{-- Pastikan ini layout untuk backend --}}
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                {{-- Form untuk mengupdate data customer (Admin) --}}
                {{-- Arahkan ke route 'customer.update' yang sudah kita definisikan untuk backend --}}
                <form action="{{ route('customer.update', $edit->id) }}" method="post" enctype="multipart/form-data">
                    @method('put') {{-- Menggunakan method PUT untuk update --}}
                    @csrf {{-- Token CSRF untuk keamanan --}}

                    <div class="card-body">
                        <h4 class="card-title">{{ $judul }}</h4> {{-- Judul dari controller --}}

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Foto</label>
                                    {{-- view image --}}
                                    {{-- Jika ada foto di customer, tampilkan. Jika tidak, tampilkan default. --}}
                                    {{-- Asumsi foto customer disimpan di 'storage/img-customer/' --}}
                                    @if ($edit->foto)
                                    <img src="{{ asset('storage/img-customer/' . $edit->foto) }}"
                                        class="foto-preview img-fluid" width="100%">
                                    @else
                                    <img src="{{ asset('storage/img-customer/img-default.jpg') }}"
                                        class="foto-preview img-fluid" width="100%">
                                    @endif
                                    <p></p>

                                    {{-- Input file untuk foto --}}
                                    <input type="file" name="foto"
                                        class="form-control @error('foto') is-invalid @enderror"
                                        onchange="previewFoto()" />
                                    @error('foto')
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8">
                                {{--
                                    Jika kamu sebelumnya punya field 'role' dan 'status' untuk User,
                                    mereka kemungkinan besar TIDAK RELEVAN untuk Customer di backend.
                                    Saya HAPUS bagian ini. Jika Customer kamu memang punya field ini,
                                    kamu bisa tambahkannya kembali sesuai kebutuhan.
                                --}}
                                {{-- Contoh: Hak Akses (jika Customer punya role sendiri)
                                <div class="form-group">
                                    <label>Hak Akses Customer</label>
                                    <select name="role_customer" class="form-control @error('role_customer') is-invalid @enderror">
                                        <option value="" {{ old('role_customer', $edit->role_customer ?? '') == '' ? 'selected' : '' }}>
                                - Pilih Hak Akses -</option>
                                <option value="customer_biasa"
                                    {{ old('role_customer', $edit->role_customer ?? '') == 'customer_biasa' ? 'selected' : '' }}>
                                    Customer Biasa</option>
                                <option value="reseller"
                                    {{ old('role_customer', $edit->role_customer ?? '') == 'reseller' ? 'selected' : '' }}>
                                    Reseller</option>
                                </select>
                                @error('role_customer')
                                <span class="invalid-feedback alert-danger" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            --}}


                            <div class="form-group">
                                <label>Nama</label>
                                {{-- Menggunakan $edit->nama --}}
                                <input type="text" name="nama" value="{{ old('nama', $edit->nama) }}"
                                    class="form-control @error('nama') is-invalid @enderror"
                                    placeholder="Masukkan Nama">
                                @error('nama')
                                <span class="invalid-feedback alert-danger" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                {{-- Menggunakan $edit->email --}}
                                <input type="email" name="email" value="{{ old('email', $edit->email) }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Masukkan Email">
                                @error('email')
                                <span class="invalid-feedback alert-danger" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>HP</label>
                                {{-- Menggunakan $edit->hp --}}
                                <input type="text" onkeypress="return hanyaAngka(event)" name="hp"
                                    value="{{ old('hp', $edit->hp) }}"
                                    class="form-control @error('hp') is-invalid @enderror"
                                    placeholder="Masukkan Nomor HP">
                                @error('hp')
                                <span class="invalid-feedback alert-danger" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Alamat</label><br>
                                {{-- Menggunakan $edit->alamat --}}
                                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                    placeholder="Masukkan Alamat Lengkap">{{ old('alamat', $edit->alamat) }}</textarea>
                                @error('alamat')
                                <span class="invalid-feedback alert-danger" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Kode Pos</label>
                                {{-- Menggunakan $edit->pos --}}
                                <input type="text" name="pos" value="{{ old('pos', $edit->pos)}}"
                                    class="form-control @error('pos') is-invalid @enderror"
                                    placeholder="Masukkan Kode Pos">
                                @error('pos')
                                <span class="invalid-feedback alert-danger" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            {{-- Jika admin ingin mengubah password customer (opsional) --}}
                            {{--
                                <div class="form-group">
                                    <label>Password (Kosongkan jika tidak diubah)</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password Baru">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Konfirmasi Password Baru">
                    </div>
                    --}}
            </div>
        </div>
    </div>
    <div class="border-top">
        <div class="card-body">
            <button type="submit" class="btn btn-primary">Perbaharui</button>
            {{-- Kembali ke daftar customer di backend --}}
            <a href="{{ route('customer.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
    </form>
</div>
</div>
</div>
</div>
@endsection

@push('scripts') {{-- Jika layoutmu menggunakan @stack('scripts') --}}
<script>
    // Fungsi previewFoto() untuk gambar
    function previewFoto() {
        const foto = document.querySelector('input[name="foto"]');
        const fotoPreview = document.querySelector('.foto-preview');

        if (foto.files && foto.files[0]) {
            const oFReader = new FileReader();
            oFReader.readAsDataURL(foto.files[0]);

            oFReader.onload = function(oFREvent) {
                fotoPreview.src = oFREvent.target.result;
            }
        } else {
            // Jika tidak ada file yang dipilih, kembalikan ke default atau foto asli
            // Menggunakan $edit->foto karena ini adalah objek Customer langsung
            fotoPreview.src =
                "{{ $edit->foto ? asset('storage/img-customer/' . $edit->foto) : asset('storage/img-customer/img-default.jpg') }}";
        }
    }

    // Fungsi hanyaAngka() untuk input HP (pastikan ini ada di script global atau di sini)
    function hanyaAngka(event) {
        var charCode = (event.which) ? event.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
</script>
@endpush {{-- Akhiri @push --}}