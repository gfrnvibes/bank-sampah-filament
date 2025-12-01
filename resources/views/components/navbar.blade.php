<nav class="navbar navbar-expand-lg sticky-top bg-success" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            <img src="{{ asset('images/logo putih.png') }}" alt="logo" width="140">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto gap-3 mb-2 mb-lg-0">
                <x-nav-link :active="request()->routeIs('/')" href="/">Home</x-nav-link>
                {{-- <x-nav-link :active="request()->routeIs('price-list')" href="{{ route('price-list') }}">Daftar Harga</x-nav-link> --}}
                <x-nav-link :active="request()->routeIs('panduan')" href="{{ route('panduan') }}">Panduan</x-nav-link>
                <x-nav-link :active="request()->routeIs('/nasabah')" href="/nasabah">Dashboard</x-nav-link>
                {{-- <x-nav-link :active="request()->routeIs()" href="">Riwayat</x-nav-link> --}}
            </ul>
            <div class="d-flex gap-2">
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Keluar</button>
                    </form>
                @endauth


                @guest
                    <a href="{{ route('filament.nasabah.auth.login') }}" class="btn btn-light fw-bold">Masuk</a>
                    <a href="{{ route('filament.nasabah.auth.register') }}" class="btn btn-outline-light">Daftar</a>
                @endguest
            </div>
        </div>
    </div>
</nav>
