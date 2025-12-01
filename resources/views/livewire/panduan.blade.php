<div>
    <!-- HERO -->
    <section class="py-5 text-center bg-brand-soft">
        <div class="container">
            <h1 class="fw-bold mb-3">Panduan Bank Sampah</h1>
            <p class="lead text-muted mb-4">Cara mudah mulai nabung sampah dan bantu bumi jadi lebih adem 🌱</p>
            <a href="#panduan" class="btn btn-success btn-lg">
                <i class="fa-solid fa-book"></i> Lihat Panduan
            </a>
        </div>
    </section>

    <!-- KONTEN -->
    <section id="panduan" class="py-5">
        <div class="container">

            <h2 class="fw-semibold text-center mb-4">Jenis Sampah yang Diterima</h2>

        <div class="row g-4">
            @forelse ($items as $item)
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0" style="transition: .3s;">

                        <div class="ratio ratio-4x3">

                            @if ($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="w-100 h-100 object-fit-cover"
                                    alt="{{ $item->name }}">
                            @else
                                <div class="d-flex justify-content-center align-items-center bg-danger text-white w-100 h-100"
                                    style="font-size: 1.3rem; font-weight: 600;">
                                    {{ $item->name }}
                                </div>
                            @endif

                        </div>


                        <div class="card-body text-center">
                            <h5 class="fw-bold">{{ $item->name }}</h5>
                            <p class="text-muted small">{{ $item->description }}</p>

                            <span class="badge bg-success fs-6">
                                Rp {{ number_format($item->price_per_kg, 0, ',', '.') }} / kg
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    Belum ada data jenis sampah.
                </div>
            @endforelse
        </div>

        </div>
    </section>

    <div class="py-5 container-fluid bg-brand-soft">
        <h2 class="fw-semibold text-center mb-4">Cara Menabung Sampah</h2>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <ol class="list-group list-group-flush ps-0 shadow-sm">
                    <li class="list-group-item d-flex align-items-start">
                        <i class="fa-solid fa-hand-sparkles text-success me-3 fs-4"></i>
                        <span>
                            <strong>Bersihkan Sampah</strong><br>
                            Bilas sampah plastik/kaleng biar gak bau dan lebih mudah diolah.
                        </span>
                    </li>
                    <li class="list-group-item d-flex align-items-start">
                        <i class="fa-solid fa-scissors text-primary me-3 fs-4"></i>
                        <span>
                            <strong>Pilah Berdasarkan Jenis</strong><br>
                            Pisahkan plastik, kertas, logam, dan lainnya biar proses lebih cepat.
                        </span>
                    </li>
                    <li class="list-group-item d-flex align-items-start">
                        <i class="fa-solid fa-scale-balanced text-warning me-3 fs-4"></i>
                        <span>
                            <strong>Datang ke Bank Sampah</strong><br>
                            Bawa sampahmu ke pos Bank Sampah terdekat untuk ditimbang.
                        </span>
                    </li>
                    <li class="list-group-item d-flex align-items-start">
                        <i class="fa-solid fa-piggy-bank text-danger me-3 fs-4"></i>
                        <span>
                            <strong>Dapetin Saldo</strong><br>
                            Hasil penimbangan langsung masuk ke saldo tabunganmu.
                        </span>
                    </li>
                </ol>
            </div>
        </div>
    </div>

    <section class="py-5 bg-white">
        <div class="container">

            <h2 class="fw-semibold text-center mb-4">Syarat & Ketentuan</h2>
            <p class="text-center text-muted mb-5">
                Biar semuanya jelas dan transparan, cek aturan mainnya di bawah ya ✨
            </p>

            <div class="row g-4">

                <!-- PENYETORAN SAMPAH -->
                <div class="col-md-6">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">
                                <i class="fa-solid fa-recycle text-success me-2"></i>
                                Penyetoran Sampah
                            </h5>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    Sampah harus dalam kondisi bersih, kering, dan sudah dipilah sesuai kategori.
                                </li>
                                <li class="list-group-item">
                                    Penyetoran dilakukan pada jam operasional Bank Sampah.
                                </li>
                                <li class="list-group-item">
                                    Petugas berhak menolak sampah yang kotor, basah, atau tidak layak daur ulang.
                                </li>
                                <li class="list-group-item">
                                    Harga per kilogram mengikuti daftar harga yang berlaku pada hari penyetoran.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- PENARIKAN SALDO -->
                <div class="col-md-6">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">
                                <i class="fa-solid fa-wallet text-primary me-2"></i>
                                Penarikan Saldo
                            </h5>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    Penarikan saldo hanya dapat dilakukan oleh pemilik akun atau wali sah.
                                </li>
                                <li class="list-group-item">
                                    Minimal saldo penarikan mengikuti aturan yang ditetapkan Bank Sampah.
                                </li>
                                <li class="list-group-item">
                                    Penarikan dilakukan pada jam kerja dan dicatat dalam buku tabungan.
                                </li>
                                <li class="list-group-item">
                                    Saldo tidak dapat diuangkan jika masih terdapat transaksi yang belum diverifikasi.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

</div>
