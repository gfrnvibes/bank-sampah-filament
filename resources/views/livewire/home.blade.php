<div>
    <!-- THEME (warna & tipografi konsisten) -->
    <style>
        :root{
            --brand-primary:#2F9E44;   /* hijau utama */
            --brand-primary-600:#2A8A3D;
            --brand-deep:#1B4332;      /* hijau hutan */
            --brand-accent:#FFD166;    /* kuning hangat */
            --brand-soft:#F3F8F4;      /* bg lembut */
            --brand-muted:#6C757D;     /* text sekunder */
        }
        .bg-brand-soft{ background-color: var(--brand-soft); }
        .text-brand{ color: var(--brand-deep) !important; }
        .btn-brand{ background: var(--brand-primary); color:#fff; border:none; }
        .btn-brand:hover{ background: var(--brand-primary-600); color:#fff; }
        .btn-accent{ background: var(--brand-accent); color:#111; border:none; }
        .btn-accent:hover{ filter: brightness(.95); color:#111; }
        .badge-desa{ background: var(--brand-accent); color:#111; font-weight:700; }
        .section-py{ padding-top: 4.5rem; padding-bottom: 4.5rem; }
        @media (min-width:992px){ .section-py{ padding-top: 6rem; padding-bottom: 6rem; } }

        .section-title{ letter-spacing:.2px; }
        .section-subtitle{ color: var(--brand-muted); }

        .feature-icon{
            width:64px; height:64px; display:flex; align-items:center; justify-content:center;
        }
        .step-number{
            width:44px; height:44px; font-weight:700;
        }

        /* Hero */
        .hero{
            background: radial-gradient(1200px 600px at -10% -20%, rgba(47,158,68,.15), transparent),
                        radial-gradient(1200px 600px at 110% -10%, rgba(255,209,102,.18), transparent),
                        linear-gradient(180deg, #fff, var(--brand-soft));
        }
        .hero-card{
            background:#fff; border:1px solid #EDF3EE;
        }

        /* Cards */
        .card-clean{ border:1px solid #E7EEE9; }

        /* Stats */
        .stats{
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-deep));
        }
    </style>

    <!-- TOP BAR IDENTITAS DESA -->
    {{-- <div class="py-2 bg-brand-soft border-bottom">
        <div class="container d-flex flex-wrap align-items-center gap-2">
            <span class="badge badge-desa rounded-pill px-3 py-2">Resmi Desa Tanggulun</span>
            <span class="text-brand fw-semibold">Kec. Kadungora, Kab. Garut</span>
        </div>
    </div> --}}

    <!-- Hero Section -->
    <section class="section-py hero">
        <div class="container">
            <div class="row align-items-center">
                <!-- Kiri - Hero Content -->
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <div class="p-4 p-lg-5 rounded-4 shadow-sm hero-card">
                        <h1 class="display-5 fw-bold mb-3 text-brand">Bank Sampah Digital Desa Tanggulun</h1>
                        <p class="lead mb-4 section-subtitle">
                            Ubah sampah jadi berkah. Sistem terintegrasi, transparan, dan ramah warga Tanggulun.
                        </p>
                        @guest
                            <a href="" class="btn btn-accent btn-lg px-4 py-2 fw-bold rounded-pill">
                                Masuk Sekarang
                            </a>
                        @endguest
                        @auth
                            <a href="" class="btn btn-brand btn-lg px-4 py-2 fw-bold rounded-pill">
                                Setor Sampah
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Kanan - Info Saldo -->
                {{-- <div class="col-lg-4">
                    @auth
                        <div class="card card-clean border-0 shadow-sm mb-3 rounded-4">
                            <div class="card-body d-flex justify-content-between align-items-center p-4">
                                <div>
                                    <span class="text-secondary">Saldo</span>
                                    <h4 class="fw-bold mb-0">Rp{{ number_format($userBalance, 0, ',', '.') }}</h4>
                                </div>
                                <div>
                                    <button class="btn btn-brand fw-bold" data-bs-toggle="modal"
                                        data-bs-target="#tarikModal" @if ($userBalance <= 0) disabled @endif>
                                        Tarik Saldo
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card card-clean border-0 shadow-sm rounded-4">
                            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-secondary">Sampah Terkumpul</span>
                                    <h5 class="fw-bold mb-0">
                                        {{ number_format(auth()->user()->wasteDeposits->where('status', 'completed')->sum('total_weight'), 1) }} Kg
                                    </h5>
                                </div>
                                <div>
                                    <a href="{{ route('setor-sampah') }}" class="btn btn-outline-success fw-bold">Setor</a>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div> --}}
            </div>
        </div>
    </section>

    @if (session()->has('success'))
        <div class="container py-4">
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        </div>
    @endif

    {{-- Modal Tarik Saldo --}}
    {{-- <div class="modal fade" id="tarikModal" tabindex="-1" aria-labelledby="tarikModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tarikModalLabel">Tarik Saldo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form wire:submit.prevent="withdrawBalance">
                    <div class="modal-body">
                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                            </div>
                        @endif

                        <div class="mb-2">
                            <small class="text-muted">Saldo tersedia: Rp{{ number_format($userBalance, 0, ',', '.') }}</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nominal" class="form-label text-secondary">Nominal</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input id="nominal" type="number"
                                       class="form-control @error('withdrawalAmount') is-invalid @enderror"
                                       wire:model="withdrawalAmount" placeholder="Masukkan nominal">
                                @error('withdrawalAmount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-brand" @if ($userBalance <= 0) disabled @endif wire:loading.attr="disabled">
                            <span wire:loading.remove>Tarik Saldo Sekarang</span>
                            <span wire:loading>Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    <!-- Tentang Desa / How It Works -->
    <section class="section-py">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1603575448878-868a20723f5d?ixlib=rb-1.2.1&auto=format&fit=crop&w=900&q=75"
                         alt="Bank Sampah Desa Tanggulun, Kadungora, Garut"
                         class="img-fluid rounded-4 shadow">
                </div>
                <div class="col-lg-6">
                    <h2 class="fw-bold display-6 section-title text-brand mb-3">Cara Kerja di Tanggulun</h2>
                    <p class="section-subtitle mb-4">Simple, transparan, dan ngikutin alur bank sampah desa.</p>

                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <div class="step-number bg-success text-white rounded-circle d-flex align-items-center justify-content-center">1</div>
                        </div>
                        <div>
                            <h3 class="h5 mb-1">Pilah Sampah</h3>
                            <p class="text-muted mb-0">Pisahkan organik, anorganik, & B3 sebelum disetor.</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <div class="step-number bg-success text-white rounded-circle d-flex align-items-center justify-content-center">2</div>
                        </div>
                        <div>
                            <h3 class="h5 mb-1">Setor / Jemput</h3>
                            <p class="text-muted mb-0">Datang ke titik kumpul desa atau gunakan layanan jemput.</p>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="me-3">
                            <div class="step-number bg-success text-white rounded-circle d-flex align-items-center justify-content-center">3</div>
                        </div>
                        <div>
                            <h3 class="h5 mb-1">Saldo & Manfaat</h3>
                            <p class="text-muted mb-0">Poin dikonversi ke saldo. Bisa dicairkan atau ditukar program desa.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section-py bg-brand-soft">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold display-6 section-title text-brand">Kenapa Pilih Bank Sampah Tanggulun?</h2>
                <p class="section-subtitle">Inovasi lokal dengan standar modern.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 card-clean">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-success bg-gradient text-white rounded-circle mx-auto mb-3">
                                <i class="bi bi-recycle fs-3"></i>
                            </div>
                            <h3 class="h5 mb-2">Daur Ulang Modern</h3>
                            <p class="text-muted mb-0">Pengolahan terintegrasi & tracking realtime setorannya.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 card-clean">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-warning bg-gradient text-dark rounded-circle mx-auto mb-3">
                                <i class="bi bi-cash-coin fs-3"></i>
                            </div>
                            <h3 class="h5 mb-2">Nilai Ekonomi</h3>
                            <p class="text-muted mb-0">Poin jadi rupiah—langsung ke saldo akun warga.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 card-clean">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-info bg-gradient text-white rounded-circle mx-auto mb-3">
                                <i class="bi bi-people-fill fs-3"></i>
                            </div>
                            <h3 class="h5 mb-2">Komunitas</h3>
                            <p class="text-muted mb-0">Gerak bareng PKK, Karang Taruna, & RT/RW.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="section-py stats text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="display-6 fw-bold">500+</div>
                    <p class="mb-0">Anggota</p>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="display-6 fw-bold">10+</div>
                    <p class="mb-0">Ton Sampah</p>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="display-6 fw-bold">100+</div>
                    <p class="mb-0">Jenis Sampah</p>
                </div>
                <div class="col-6 col-md-3">
                    <div class="display-6 fw-bold">24/7</div>
                    <p class="mb-0">Layanan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Alamat & Jam Layanan -->
    <section class="section-py">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-lg-7">
                    <h2 class="fw-bold display-6 text-brand mb-3">Alamat & Layanan Desa</h2>
                    <p class="text-muted mb-4">Balai Desa Tanggulun, Kec. Kadungora, Kab. Garut</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 border rounded-4 card-clean h-100">
                                <h5 class="mb-1">Jadwal Setor</h5>
                                <p class="mb-0 text-muted">Senin–Sabtu, 08.00–15.00 WIB</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded-4 card-clean h-100">
                                <h5 class="mb-1">Layanan Jemput</h5>
                                <p class="mb-0 text-muted">Reservasi H-1 via portal / petugas desa</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="p-4 border rounded-4 card-clean">
                        <h5 class="mb-3">Kontak Cepat</h5>
                        <ul class="list-unstyled mb-0 text-muted">
                            <li class="mb-2"><i class="bi bi-telephone me-2"></i> Kantor Desa: (0262) ———</li>
                            <li class="mb-2"><i class="bi bi-whatsapp me-2"></i> Admin Bank Sampah: 08xx-xxxx-xxxx</li>
                            <li class=""><i class="bi bi-envelope me-2"></i> Email: banksampah@tanggulun.desa.id</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="section-py bg-brand-soft">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold display-6 text-brand">Apa Kata Warga?</h2>
                <p class="section-subtitle">Cerita singkat dari warga Tanggulun.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 card-clean">
                        <div class="card-body p-4">
                            <div class="d-flex mb-3">
                                <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Siti Aminah"
                                     class="rounded-circle me-3" width="50" height="50">
                                <div>
                                    <h5 class="mb-0">Siti Aminah</h5>
                                    <small class="text-muted">Warga Tanggulun, sejak 2022</small>
                                </div>
                            </div>
                            <p class="mb-0">"Sekarang setor sampah makin gampang, saldo juga transparan."</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 card-clean">
                        <div class="card-body p-4">
                            <div class="d-flex mb-3">
                                <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Budi Santoso"
                                     class="rounded-circle me-3" width="50" height="50">
                                <div>
                                    <h5 class="mb-0">Budi Santoso</h5>
                                    <small class="text-muted">Pelapak, sejak 2021</small>
                                </div>
                            </div>
                            <p class="mb-0">"Harganya fair, pencairan saldo cepat, mantap buat warga."</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 card-clean">
                        <div class="card-body p-4">
                            <div class="d-flex mb-3">
                                <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Dewi Lestari"
                                     class="rounded-circle me-3" width="50" height="50">
                                <div>
                                    <h5 class="mb-0">Dewi Lestari</h5>
                                    <small class="text-muted">Aktivis Lingkungan</small>
                                </div>
                            </div>
                            <p class="mb-0">"Lebih bersih, lebih rapi. Edukasinya juga jalan."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-py">
        <div class="container text-center">
            <h2 class="fw-bold display-6 text-brand mb-3">Waktunya Gabung, Warga Tanggulun!</h2>
            <p class="lead section-subtitle mb-5">Mulai pilah sekarang, rasakan manfaatnya.</p>
            {{-- <div class="d-flex justify-content-center gap-3">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-brand btn-lg px-4 py-2 fw-bold rounded-pill">Daftar</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-success btn-lg px-4 py-2 fw-bold rounded-pill">Masuk</a>
                @endguest
                @auth
                    <a href="{{ route('setor-sampah') }}" class="btn btn-brand btn-lg px-4 py-2 fw-bold rounded-pill">Setor Sampah</a>
                @endauth
            </div> --}}
        </div>
    </section>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-modal', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('tarikModal'));
                if (modal) modal.hide();
            });
        });
    </script>
</div>
