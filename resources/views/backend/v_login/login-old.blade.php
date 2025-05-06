<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online</title>
</head>

<body>
    <h3>{{ $judul }}</h3>

    <!-- Error Handling -->
    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>{{ session('error') }}</strong>
    </div>
    @endif
    <!-- Error End -->

    <form action="{{ route('backend.login') }}" method="post">
        @csrf
        <label for="email">User</label><br>
        <input type="text" name="email" id="email" value="{{ old('email') }}"
            class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Email">
        @error('email')
        <span class="invalid-feedback alert-danger" role="alert">
            {{ $message }}
        </span>
        @enderror
        <br>

        <label for="password">Password</label><br>
        <input type="password" name="password" id="password" value="{{ old('password') }}"
            class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan Password">
        @error('password')
        <span class="invalid-feedback alert-danger" role="alert">
            {{ $message }}
        </span>
        @enderror
        <br>

        <button type="submit">Login</button>
    </form>
</body>

</html>