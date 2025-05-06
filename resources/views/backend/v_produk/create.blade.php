@extends('backend.v_layouts.app') 
@section('content') 
<!-- Content Awal --> 

<div class="container-fluid"> 
    <div class="row"> 
        <div class="col-12"> 
            <div class="card"> 
                <form class="form-horizontal" action="{{ route('backend.produk.store') }}" method="post" enctype="multipart/form-data"> 
                    @csrf 

                    <div class="card-body"> 
                        <h4 class="card-title">{{ $judul }}</h4> 
                        <div class="row"> 

                            <!-- Kolom Foto -->
                            <div class="col-md-4"> 
                                <div class="form-group"> 
                                    <label>Foto</label> 
                                    <img class="foto-preview" style="display: block; max-width: 100%; margin-bottom: 10px;"> 
                                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" onchange="previewFoto()"> 
                                    @error('foto') 
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div> 
                                    @enderror 
                                </div> 
                            </div> 

                            <!-- Kolom Detail Produk -->
                            <div class="col-md-8"> 
                                <div class="form-group"> 
                                    <label>Kategori</label> 
                                    <select class="form-control @error('kategori_id') is-invalid @enderror" name="kategori_id"> 
                                        <option value="" selected>-- Pilih Kategori --</option> 
                                        @foreach ($kategori as $k) 
                                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option> 
                                        @endforeach 
                                    </select> 
                                    @error('kategori_id') 
                                    <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span> 
                                    @enderror 
                                </div> 

                                <div class="form-group"> 
                                    <label>Nama Produk</label> 
                                    <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" class="form-control @error('nama_produk') is-invalid @enderror" placeholder="Masukkan Nama Produk"> 
                                    @error('nama_produk') 
                                    <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span> 
                                    @enderror 
                                </div> 

                                <div class="form-group"> 
                                    <label>Detail</label> 
                                    <!-- <textarea name="detail" class="form-control @error('detail') is-invalid @enderror" placeholder=" Detail Produk">{{ old('detail') }}</textarea>  -->
                                    <textarea name="detail" class="form-control @error('detail') is-invalid @enderror" placeholder="Detail Produk" id="ckeditor">{{ old('detail') }}</textarea>
                                    @error('detail') 
                                    <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span> 
                                    @enderror 
                                </div> 

                                <div class="form-group"> 
                                    <label>Harga</label> 
                                    <input type="text" name="harga" value="{{ old('harga') }}" class="form-control @error('harga') is-invalid @enderror" placeholder="Masukkan Harga Produk" onkeypress="return hanyaAngka(event)"> 
                                    @error('harga') 
                                    <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span> 
                                    @enderror 
                                </div> 

                                <div class="form-group"> 
                                    <label>Berat</label> 
                                    <input type="text" name="berat" value="{{ old('berat') }}" class="form-control @error('berat') is-invalid @enderror" placeholder="Masukkan Berat Produk" onkeypress="return hanyaAngka(event)"> 
                                    @error('berat') 
                                    <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span> 
                                    @enderror 
                                </div> 

                                <div class="form-group"> 
                                    <label>Stok</label> 
                                    <input type="text" name="stok" value="{{ old('stok') }}" class="form-control @error('stok') is-invalid @enderror" placeholder="Masukkan Stok Produk" onkeypress="return hanyaAngka(event)"> 
                                    @error('stok') 
                                    <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span> 
                                    @enderror 
                                </div> 
                            </div> 
                        </div> 
                    </div> 

                    <!-- Tombol Aksi -->
                    <div class="border-top"> 
                        <div class="card-body"> 
                            <button type="submit" class="btn btn-primary">Simpan</button> 
                            <a href="{{ route('backend.produk.index') }}" class="btn btn-secondary">Kembali</a> 
                        </div> 
                    </div> 
                </form> 
            </div> 
        </div> 
    </div> 
</div> 

<!-- Content Akhir --> 
@endsection 
