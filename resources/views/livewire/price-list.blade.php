<div>
    <!-- HEADER -->
    <header class="py-5 text-center bg-brand-soft mb-4">
        <div class="container">
            <h1 class="fw-bold">Daftar Harga Sampah</h1>
            <p class="text-muted">Harga per kilogram — update yang paling fresh ✨</p>
        </div>
    </header>

    <!-- CONTENT -->
    <section class="container pb-5">

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
    </section>

    <script>
        // BOOSTRAP-ONLY HOVER (tanpa CSS eksternal)
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.classList.add('shadow-lg');
                card.style.transform = 'translateY(-6px)';
            });
            card.addEventListener('mouseleave', () => {
                card.classList.remove('shadow-lg');
                card.style.transform = 'translateY(0)';
            });
        });
    </script>

</div>
