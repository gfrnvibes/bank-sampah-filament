<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="shortcut icon" href="{{ asset('shopee.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <title>{{ $title ?? 'Page Title' }}</title>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>

        <!-- THEME (warna & tipografi konsisten) -->
        <style>
            :root {
                --brand-primary: #2F9E44;
                /* hijau utama */
                --brand-primary-600: #2A8A3D;
                --brand-deep: #1B4332;
                /* hijau hutan */
                --brand-accent: #FFD166;
                /* kuning hangat */
                --brand-soft: #F3F8F4;
                /* bg lembut */
                --brand-muted: #6C757D;
                /* text sekunder */
            }
        
            .bg-brand-soft {
                background-color: var(--brand-soft);
            }
        
            .text-brand {
                color: var(--brand-deep) !important;
            }
        
            .btn-brand {
                background: var(--brand-primary);
                color: #fff;
                border: none;
            }
        
            .btn-brand:hover {
                background: var(--brand-primary-600);
                color: #fff;
            }
        
            .btn-accent {
                background: var(--brand-accent);
                color: #111;
                border: none;
            }
        
            .btn-accent:hover {
                filter: brightness(.95);
                color: #111;
            }
        
            .badge-desa {
                background: var(--brand-accent);
                color: #111;
                font-weight: 700;
            }
        
            .section-py {
                padding-top: 4.5rem;
                padding-bottom: 4.5rem;
            }
        
            @media (min-width:992px) {
                .section-py {
                    padding-top: 6rem;
                    padding-bottom: 6rem;
                }
            }
        
            .section-title {
                letter-spacing: .2px;
            }
        
            .section-subtitle {
                color: var(--brand-muted);
            }
        
            .feature-icon {
                width: 64px;
                height: 64px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        
            .step-number {
                width: 44px;
                height: 44px;
                font-weight: 700;
            }
        
            /* Hero */
            .hero {
                background: radial-gradient(1200px 600px at -10% -20%, rgba(47, 158, 68, .15), transparent),
                    radial-gradient(1200px 600px at 110% -10%, rgba(255, 209, 102, .18), transparent),
                    linear-gradient(180deg, #fff, var(--brand-soft));
            }
        
            .hero-card {
                background: #fff;
                border: 1px solid #EDF3EE;
            }
        
            /* Cards */
            .card-clean {
                border: 1px solid #E7EEE9;
            }
        
            /* Stats */
            .stats {
                background: linear-gradient(135deg, var(--brand-primary), var(--brand-deep));
            }
        </style>
    

    @livewireStyles
</head>

<body>
    @include('components.navbar')

    <div class="min-vh-100">
        {{ $slot }}
    </div>

    {{-- Footer --}}
    <footer class="py-4 bg-success text-white text-center mt-5">
        <p class="mb-0">© 2025 Bank Sampah Desa Tanggulun • Jaga bumi bareng-bareng ♻️</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
        </script>
    @livewireScripts
    
</body>

</html>