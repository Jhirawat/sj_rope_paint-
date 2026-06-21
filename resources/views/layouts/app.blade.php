@php
    use App\Models\SiteSetting;

    $locale = app()->getLocale();
    $isEn = $locale === 'en';

    $siteColor = SiteSetting::get('site_theme_color', '#082B5B');
    $siteAccent = SiteSetting::get('site_accent_color', '#F4B400');
    $siteSecondary = SiteSetting::get('site_secondary_color', '#1E6BFF');

    $logoPath = SiteSetting::get($isEn ? 'logo_nav_en_path' : 'logo_nav_th_path') ?: SiteSetting::get('logo_path');
    $footerLogoPath = SiteSetting::get('footer_logo_path');
    $faviconPath = SiteSetting::get('favicon_path');
    $heroImagePath = SiteSetting::get('hero_image_path');
    $heroImageUrl = $heroImagePath
        ? asset($heroImagePath)
        : 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?q=80&w=1600&auto=format&fit=crop';

    $phones = json_decode(SiteSetting::get('contact_phones', '[]'), true);
    if (! is_array($phones) || count($phones) === 0) {
        $phones = [[
            'label' => $isEn ? 'Main phone' : 'เบอร์หลัก',
            'label_en' => 'Main phone',
            'number' => SiteSetting::get('phone', '081-353-7779'),
        ]];
    }

    $socials = json_decode(SiteSetting::get('social_links', '[]'), true);
    if (! is_array($socials) || count($socials) === 0) {
        $socials = [
            ['name' => 'Facebook', 'url' => SiteSetting::get('facebook_url', '#')],
            ['name' => 'LINE', 'url' => SiteSetting::get('line_url', '#')],
            ['name' => 'TikTok', 'url' => SiteSetting::get('tiktok_url', '#')],
            ['name' => 'YouTube', 'url' => SiteSetting::get('youtube_url', '#')],
        ];
    }

    $mainPhone = $phones[0]['number'] ?? '081-353-7779';
    $company = $isEn
        ? SiteSetting::get('company_name_en', 'SJ Rope Access Painting')
        : SiteSetting::get('company_name', 'SJ ทาสีโรยตัว');
    $email = SiteSetting::get('email', 'contact@sjrope.test');
    $lineId = SiteSetting::get('line_id', '@sjrope');
    $lineUrl = SiteSetting::get('line_url', '');
    $lineHref = str_starts_with($lineUrl, 'http') ? $lineUrl : 'https://line.me/R/ti/p/'.$lineId;

    $socialIcon = function ($name) {
        $n = strtolower($name ?? '');

        if (str_contains($n, 'line')) {
            return 'fa-brands fa-line';
        }

        if (str_contains($n, 'facebook') || str_contains($n, 'messenger')) {
            return 'fa-brands fa-facebook-f';
        }

        if (str_contains($n, 'tiktok')) {
            return 'fa-brands fa-tiktok';
        }

        if (str_contains($n, 'youtube')) {
            return 'fa-brands fa-youtube';
        }

        if (str_contains($n, 'map')) {
            return 'fa-solid fa-map-location-dot';
        }

        return 'fa-solid fa-link';
    };
@endphp
<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $company)</title>
    <meta name="description" content="{{ $isEn ? SiteSetting::get('meta_description_en', 'High-rise painting, rope access repair, waterproofing and facade maintenance services.') : SiteSetting::get('meta_description_th', 'บริการทาสีอาคารสูง โรยตัวซ่อมรอยร้าว กันซึม ล้างกระจก และซ่อมบำรุงภายนอกอาคาร') }}">
    <meta property="og:title" content="{{ $company }}">
    <meta property="og:description" content="{{ $isEn ? SiteSetting::get('meta_description_en', 'High-rise painting and rope access maintenance.') : SiteSetting::get('meta_description_th', 'บริการทาสีอาคารสูงและซ่อมบำรุงด้วยทีมงานมืออาชีพ') }}">
    @if(SiteSetting::get('og_image_path'))
        <meta property="og:image" content="{{ asset(SiteSetting::get('og_image_path')) }}">
    @endif
    <meta name="theme-color" content="#ffffff">
    @if($faviconPath)
        <link rel="icon" href="{{ asset('images/sj-v9/sj-favicon.png') }}?v=101">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sj-navy: {{ $siteColor }};
            --sj-blue: {{ $siteSecondary }};
            --sj-safety: {{ $siteAccent }};
            --sj-orange: #fb8500;
            --sj-light: #F8FAFC;
            --sj-muted: #617084;
        }
        body { font-family: Tahoma, Arial, sans-serif; background:#fff; color:#162233; }
        .navbar { background:#fff; box-shadow:0 7px 24px rgba(8,43,91,.10); min-height:92px; }
        .navbar .container { align-items:center; }
        .navbar .nav-link { color:var(--sj-navy); font-weight:700; opacity:.86; }
        .navbar .nav-link:hover, .navbar .nav-link:focus { color:var(--sj-blue); opacity:1; }
        .navbar-toggler { border-color:rgba(8,43,91,.25); }
        .navbar-toggler-icon { filter:invert(16%) sepia(71%) saturate(1640%) hue-rotate(194deg) brightness(82%) contrast(98%); }
        .btn-primary { background:var(--sj-blue); border-color:var(--sj-blue); }
        .btn-primary:hover { filter:brightness(.94); }
        .btn-sj { background:var(--sj-safety); border:0; color:#111; font-weight:700; display:inline-flex; align-items:center; justify-content:center; gap:8px; line-height:1.25; padding:.76rem 1.05rem; min-height:44px; }
        .btn-sj:hover { background:var(--sj-orange); color:#111; }
        .btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; line-height:1.25; min-height:40px; }
        .hero { background:linear-gradient(120deg, rgba(255,255,255,.94), rgba(238,246,255,.78)), url('{{ $heroImageUrl }}'); background-size:cover; background-position:center; color:var(--sj-navy); padding:0; }
        .section { padding:75px 0; }
        .card-sj { border:0; border-radius:22px; box-shadow:0 18px 45px rgba(7,27,53,.10); overflow:hidden; }
        .soft-card { background:#fff; border:1px solid #e9eef5; border-radius:22px; box-shadow:0 14px 36px rgba(7,27,53,.07); }
        .icon-box { width:58px; height:58px; border-radius:18px; background:#fff4cf; color:#fb8500; display:grid; place-items:center; font-size:24px; }
        .badge-safety { background:#fff4cf; color:#8a5100; }
        .footer { background:linear-gradient(90deg,var(--sj-navy),var(--sj-blue)); color:#dce7f2; }
        .footer a { color:#fff; text-decoration:none; }
        .footer .muted { color:#afbfd0; }
        .social-icons { display:flex; gap:10px; flex-wrap:wrap; }
        .social-icons a { width:42px; height:42px; border-radius:50%; display:grid; place-items:center; background:rgba(255,255,255,.12); color:#fff; transition:.2s; }
        .social-icons a:hover { background:var(--sj-safety); color:#111; transform:translateY(-2px); }
        .project-ph { height:230px; background:linear-gradient(135deg,#dfe8f3,#a6b9cc); border-radius:18px; display:grid; place-items:center; color:#082B5B; font-weight:700; }
        .project-cover { height:230px; width:100%; object-fit:cover; }
        .service-cover { height:220px; width:100%; object-fit:cover; }
        .sticky-cta { position:fixed; right:18px; bottom:18px; z-index:20; }
        .lang { color:rgba(8,43,91,.45); white-space:nowrap; }
        .lang a { color:var(--sj-navy); text-decoration:none; padding:4px 2px; font-weight:800; letter-spacing:.02em; }
        .lang a:hover, .lang a.active { color:var(--sj-safety); }
        .lang .sep { color:rgba(8,43,91,.35); padding:0 7px; }
        .form-control, .form-select { border-radius:12px; padding:12px; }
        .brand-logo { height:74px; max-width:430px; width:auto; object-fit:contain; background:transparent; border-radius:0; padding:0; display:block; image-rendering:auto; }
        .navbar-brand { max-width:46vw; overflow:visible; }
        @media(max-width:768px){ .brand-logo{height:56px;max-width:250px}.navbar{min-height:74px}.navbar-brand{max-width:72vw} }
        .footer-logo { max-width:430px; width:100%; border-radius:12px; box-shadow:0 12px 36px rgba(0,0,0,.18); margin-bottom:18px; }
        .text-balance { text-wrap:balance; }
        .step-num { width:42px; height:42px; border-radius:50%; background:var(--sj-safety); display:grid; place-items:center; font-weight:800; color:#111; }
        .feature-icon { font-size:28px; color:#fb8500; }
        .before-after { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
        .ba-img { height:260px; object-fit:cover; width:100%; border-radius:18px; }
        .gallery-thumb { height:160px; object-fit:cover; width:100%; border-radius:14px; }
        .admin-help { font-size:.9rem; color:#6c757d; }
        .toast-container { z-index:3000; }
        .preview-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(110px,1fr)); gap:10px; }
        .preview-item { position:relative; border:1px solid #e3e8ef; border-radius:14px; overflow:hidden; background:#fff; }
        .preview-item img { width:100%; height:105px; object-fit:cover; }
        .preview-item .cap { font-size:.75rem; padding:6px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .map-preview { height:260px; border:0; border-radius:18px; width:100%; background:#e8eef6; }
        .counter { display:flex; align-items:center; gap:8px; }
        .counter input { text-align:center; max-width:90px; }
        .counter .btn { width:44px; height:44px; border-radius:12px; }
        .quick-float { animation:pulseSJ 2.6s infinite; }
        @keyframes pulseSJ { 0%,100% { box-shadow:0 0 0 0 rgba(255,183,3,.45); } 50% { box-shadow:0 0 0 12px rgba(255,183,3,0); } }
        @media(max-width:768px) {
            .sticky-cta { left:10px; right:10px; bottom:10px; justify-content:space-between; }
            .sticky-cta .btn { flex:1; font-size:.86rem; padding:.65rem .4rem; }
            .section { padding:48px 0; }
            .hero { padding:82px 0; }
            .before-after { grid-template-columns:1fr; }
            .ba-img { height:210px; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
                @if($logoPath)
                    <img src="{{ asset('images/sj-v9/sj-navbar-logo.png') }}?v=101" class="brand-logo" alt="SJ Rope Painting">
                @else
                    <i class="fa-solid fa-person-falling-burst text-warning"></i>
                    <span>{{ $company }}</span>
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('services') }}">{{ __('services') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('projects') }}">{{ __('projects') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('articles') }}">{{ __('articles') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">{{ __('contact') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('quote.track.form') }}">{{ __('track') }}</a></li>
                    <li class="nav-item d-none d-lg-block"><a class="btn btn-primary ms-lg-3 rounded-pill px-3" href="tel:0813537779"><i class="fa-solid fa-phone"></i>{{ $isEn ? 'Call Now' : 'โทรเลย' }}</a></li>
                    <li class="nav-item">
                        <a class="btn btn-sj ms-lg-3" href="{{ route('quote.form') }}">
                            <i class="fa-solid fa-clipboard-list"></i>{{ __('request_quote') }}
                        </a>
                    </li>
                </ul>
                <div class="lang ms-lg-3 mt-3 mt-lg-0" aria-label="Language switcher">
                    <a class="{{ $locale === 'th' ? 'active' : '' }}" href="{{ route('language.switch', 'th') }}">TH</a><span class="sep">|</span><a class="{{ $locale === 'en' ? 'active' : '' }}" href="{{ route('language.switch', 'en') }}">EN</a>
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success">{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="container mt-3">
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        </div>
    @endif

    @yield('content')

    <div class="sticky-cta d-flex gap-2">
        <a class="btn btn-success rounded-pill shadow quick-float" href="tel:{{ preg_replace('/[^0-9+]/', '', $mainPhone) }}">
            <i class="fa-solid fa-phone"></i> {{ $isEn ? 'Call' : 'โทร' }}
        </a>
        <a class="btn btn-info text-white rounded-pill shadow" href="{{ $lineHref }}" target="_blank" rel="noopener">
            <i class="fa-brands fa-line"></i> LINE
        </a>
        <a class="btn btn-sj rounded-pill shadow" href="{{ route('quote.form') }}">
            <i class="fa-solid fa-file-signature"></i>{{ $isEn ? 'Quote' : 'ขอราคา' }}
        </a>
    </div>

    <footer class="footer py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    @if($footerLogoPath)
                        <img class="footer-logo" src="{{ asset('images/sj-v9/sj-footer-logo.png') }}?v=101" alt="SJ Footer Logo">
                    @else
                        <h4>{{ $company }}</h4>
                    @endif
                    <p class="muted">
                        {{ $isEn ? 'High-rise painting, rope access repair, waterproofing, window cleaning and facade maintenance. Primary service area: Hua Hin–Cha-am.' : 'รับทาสีอาคารสูง โรยตัวซ่อมรอยร้าว กันซึม ล้างกระจก และซ่อมบำรุงภายนอกอาคาร พื้นที่หลักหัวหิน-ชะอำ' }}
                    </p>
                    <div class="social-icons mt-3">
                        @foreach($socials as $soc)
                            @php($socialUrl = $soc['url'] ?? '#')
                            <a href="{{ str_starts_with($socialUrl, 'http') ? $socialUrl : '#' }}" target="_blank" rel="noopener" title="{{ $soc['name'] ?? 'Social' }}">
                                <i class="{{ $socialIcon($soc['name'] ?? '') }}"></i>
                            </a>
                        @endforeach
                        @if(SiteSetting::get('google_maps_link'))
                            <a href="{{ SiteSetting::get('google_maps_link') }}" target="_blank" rel="noopener" title="Google Maps">
                                <i class="fa-solid fa-map-location-dot"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3">
                    <h6>{{ $isEn ? 'Contact' : 'ติดต่อ' }}</h6>
                    @foreach($phones as $p)
                        @php($personName = $isEn ? ($p['person_en'] ?? $p['label_en'] ?? '') : ($p['person'] ?? $p['label'] ?? ''))
                        <div class="mb-1">
                            <i class="fa-solid fa-phone text-warning me-1"></i>
                            {{ $p['number'] ?? '' }} @if($personName)<span>{{ $personName }}</span>@endif
                        </div>
                    @endforeach
                    <div><i class="fa-brands fa-line text-warning me-1"></i>LINE: {{ $lineId }}</div>
                    <div><i class="fa-solid fa-envelope text-warning me-1"></i>Email: {{ $email }}</div>
                </div>
                <div class="col-lg-3">
                    <h6>{{ $isEn ? 'Quick Links' : 'เมนูลัด' }}</h6>
                    <div class="d-grid gap-1">
                        <a href="{{ route('services') }}">{{ __('services') }}</a>
                        <a href="{{ route('projects') }}">{{ __('projects') }}</a>
                        <a href="{{ route('quote.form') }}">{{ __('request_quote') }}</a>
                        <a href="{{ route('quote.track.form') }}">{{ __('track') }}</a>
                        <a href="{{ route('contact') }}">{{ __('contact') }}</a>
                    </div>
                </div>
                <div class="col-lg-2">
                    <h6>{{ $isEn ? 'Service Area' : 'พื้นที่บริการ' }}</h6>
                    <div class="muted">
                        @foreach($isEn ? ['Hua Hin–Cha-am (Primary Area)','Bangkok','Chonburi'] : ['หัวหิน-ชะอำ (พื้นที่หลัก)','กรุงเทพมหานคร','ชลบุรี'] as $area)
                            <div>{{ $area }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="sjToast" class="toast align-items-center text-bg-dark border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="sjToastText">...</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.SJ_LOCALE = '{{ $locale }}';
        window.sjToast = (msg) => {
            const el = document.getElementById('sjToast');
            const txt = document.getElementById('sjToastText');
            if (!el || !txt) {
                console.log(msg);
                return;
            }
            txt.textContent = msg;
            bootstrap.Toast.getOrCreateInstance(el, { delay: 3500 }).show();
        };
    </script>
    @yield('scripts')
</body>
</html>
