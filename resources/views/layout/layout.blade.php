<!DOCTYPE html>
<html lang="en">

<head>
    <title>Agii @yield('title')</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="author" content="Agii">
    <meta name="keywords" content="Agii Buy and Sell Anything in Nigeria">
    <meta name="description" content="Agii | Buy and Sell Anything in Nigeria">

    <link rel="apple-touch-icon" sizes="192x192" href="/images/icons/launchericon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#90c74b">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        .logo {
            width: 200px;
            height: 100px;
            object-fit: contain;
        }

        .bg-whit {
            background-color: transparent;
            border-radius: 30%;
            border: 1px solid #fff;
            color: #fff;
        }

        .black {
            color: #000;
        }

        /* PWA Banner */
        #pwa-install-banner,
        #pwa-ios-tip {
            display: none;
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 99999;
            width: 90%;
            max-width: 380px;
            background: #1a1a2e;
            color: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.35);
            font-family: sans-serif;
            animation: pwaSlideUp 0.3s ease;
        }

        #pwa-install-banner {
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
        }

        #pwa-ios-tip {
            padding: 18px 20px;
            text-align: center;
        }

        @keyframes pwaSlideUp {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }
    </style>

    {{-- ============================================================
    PWA: capture beforeinstallprompt as early as possible
    MUST be in

    <head> — before any other scripts load
        ============================================================ --}}
        <script>
            window.__pwaPrompt = null;
        window.__pwaPromptReady = false;
        window.__pwaShowBanner = null; // set by DOMContentLoaded below

        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            window.__pwaPrompt = e;
            window.__pwaPromptReady = true;
            console.log('[PWA] beforeinstallprompt captured');

            // If DOMContentLoaded already ran, show banner now
            if (typeof window.__pwaShowBanner === 'function') {
                window.__pwaShowBanner();
            }
        });

        // Register service worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(function(reg) { console.log('[SW] Registered:', reg.scope); })
                    .catch(function(err) { console.error('[SW] Failed:', err); });
            });
        }
        </script>

        @stack('styles')
    </head>

<body>

    {{-- ============================================================
    PWA BANNERS — placed right after

    <body> open so they
        are in the DOM immediately when JS runs
        ============================================================ --}}

        {{-- Android / Desktop Chrome install banner --}}
        <div id="pwa-install-banner" role="dialog" aria-label="Install Agii app">
            <img src="/images/icons/launchericon-72x72.png" width="44" height="44"
                style="border-radius:10px; flex-shrink:0;" alt="Agii">
            <div style="flex:1; min-width:0;">
                <div style="font-weight:700; font-size:15px; line-height:1.2;">Install Agii</div>
                <div id="pwa-banner-subtitle" style="font-size:12px; opacity:0.7; margin-top:3px;">
                    Add to home screen for quick access
                </div>
            </div>
            <div style="display:flex; flex-direction:column; gap:6px; flex-shrink:0;">
                <button id="pwa-install-btn" style="background:#90c74b; color:#fff; border:none; padding:8px 16px;
                       border-radius:8px; cursor:pointer; font-weight:700; font-size:13px;
                       white-space:nowrap;">
                    Install
                </button>
                <button id="pwa-dismiss-btn" style="background:transparent; color:#aaa; border:none;
                       cursor:pointer; font-size:12px; padding:0;">
                    Not now
                </button>
            </div>
        </div>

        {{-- iOS Safari tip --}}
        <div id="pwa-ios-tip" role="dialog" aria-label="How to install Agii on iOS">
            <div style="font-weight:700; font-size:15px; margin-bottom:10px;">Install Agii</div>
            <div style="font-size:13px; opacity:0.85; line-height:1.7;">
                Tap <strong>Share</strong> <span style="font-size:17px;">⎙</span>
                then tap <strong>"Add to Home Screen"</strong> <span style="font-size:17px;">＋</span>
            </div>
            <button id="pwa-ios-dismiss" style="margin-top:14px; background:transparent; color:#aaa;
                   border:1px solid rgba(255,255,255,0.2); border-radius:6px;
                   cursor:pointer; font-size:12px; padding:6px 16px;">
                Dismiss
            </button>
        </div>

        {{-- ============================================================
        SVG SPRITE
        ============================================================ --}}
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <defs>
                <symbol id="link" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M12 19a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0-4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm-5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm7-12h-1V2a1 1 0 0 0-2 0v1H8V2a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3Zm1 17a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-9h16Zm0-11H4V6a1 1 0 0 1 1-1h1v1a1 1 0 0 0 2 0V5h8v1a1 1 0 0 0 2 0V5h1a1 1 0 0 1 1 1ZM7 15a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0 4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Z" />
                </symbol>
                <symbol id="arrow-right" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M17.92 11.62a1 1 0 0 0-.21-.33l-5-5a1 1 0 0 0-1.42 1.42l3.3 3.29H7a1 1 0 0 0 0 2h7.59l-3.3 3.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76Z" />
                </symbol>
                <symbol id="category" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M19 5.5h-6.28l-.32-1a3 3 0 0 0-2.84-2H5a3 3 0 0 0-3 3v13a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-10a3 3 0 0 0-3-3Zm1 13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-13a1 1 0 0 1 1-1h4.56a1 1 0 0 1 .95.68l.54 1.64a1 1 0 0 0 .95.68h7a1 1 0 0 1 1 1Z" />
                </symbol>
                <symbol id="calendar" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M19 4h-2V3a1 1 0 0 0-2 0v1H9V3a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3Zm1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7h16Zm0-9H4V7a1 1 0 0 1 1-1h2v1a1 1 0 0 0 2 0V6h6v1a1 1 0 0 0 2 0V6h2a1 1 0 0 1 1 1Z" />
                </symbol>
                <symbol id="heart" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M20.16 4.61A6.27 6.27 0 0 0 12 4a6.27 6.27 0 0 0-8.16 9.48l7.45 7.45a1 1 0 0 0 1.42 0l7.45-7.45a6.27 6.27 0 0 0 0-8.87Zm-1.41 7.46L12 18.81l-6.75-6.74a4.28 4.28 0 0 1 3-7.3a4.25 4.25 0 0 1 3 1.25a1 1 0 0 0 1.42 0a4.27 4.27 0 0 1 6 6.05Z" />
                </symbol>
                <symbol id="plus" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M19 11h-6V5a1 1 0 0 0-2 0v6H5a1 1 0 0 0 0 2h6v6a1 1 0 0 0 2 0v-6h6a1 1 0 0 0 0-2Z" />
                </symbol>
                <symbol id="minus" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19 11H5a1 1 0 0 0 0 2h14a1 1 0 0 0 0-2Z" />
                </symbol>
                <symbol id="cart" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M8.5 19a1.5 1.5 0 1 0 1.5 1.5A1.5 1.5 0 0 0 8.5 19ZM19 16H7a1 1 0 0 1 0-2h8.491a3.013 3.013 0 0 0 2.885-2.176l1.585-5.55A1 1 0 0 0 19 5H6.74a3.007 3.007 0 0 0-2.82-2H3a1 1 0 0 0 0 2h.921a1.005 1.005 0 0 1 .962.725l.155.545v.005l1.641 5.742A3 3 0 0 0 7 18h12a1 1 0 0 0 0-2Zm-1.326-9l-1.22 4.274a1.005 1.005 0 0 1-.963.726H8.754l-.255-.892L7.326 7ZM16.5 19a1.5 1.5 0 1 0 1.5 1.5a1.5 1.5 0 0 0-1.5-1.5Z" />
                </symbol>
                <symbol id="check" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M18.71 7.21a1 1 0 0 0-1.42 0l-7.45 7.46l-3.13-3.14A1 1 0 1 0 5.29 13l3.84 3.84a1 1 0 0 0 1.42 0l8.16-8.16a1 1 0 0 0 0-1.47Z" />
                </symbol>
                <symbol id="trash" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M10 18a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1ZM20 6h-4V5a3 3 0 0 0-3-3h-2a3 3 0 0 0-3 3v1H4a1 1 0 0 0 0 2h1v11a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8h1a1 1 0 0 0 0-2ZM10 5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1h-4Zm7 14a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V8h10Zm-3-1a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1Z" />
                </symbol>
                <symbol id="star-outline" viewBox="0 0 15 15">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        d="M7.5 9.804L5.337 11l.413-2.533L4 6.674l2.418-.37L7.5 4l1.082 2.304l2.418.37l-1.75 1.793L9.663 11L7.5 9.804Z" />
                </symbol>
                <symbol id="star-solid" viewBox="0 0 15 15">
                    <path fill="currentColor"
                        d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z" />
                </symbol>
                <symbol id="search" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z" />
                </symbol>
                <symbol id="user" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M15.71 12.71a6 6 0 1 0-7.42 0a10 10 0 0 0-6.22 8.18a1 1 0 0 0 2 .22a8 8 0 0 1 15.9 0a1 1 0 0 0 1 .89h.11a1 1 0 0 0 .88-1.1a10 10 0 0 0-6.25-8.19ZM12 12a4 4 0 1 1 4-4a4 4 0 0 1-4 4Z" />
                </symbol>
                <symbol id="close" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M18.71 7.21a1 1 0 0 0-1.42 0L12 12.5L6.71 7.21a1 1 0 0 0-1.42 1.42L10.59 14l-5.3 5.29a1 1 0 0 0 1.42 1.42L12 15.41l5.29 5.3a1 1 0 0 0 1.42-1.42L13.41 14l5.3-5.37a1 1 0 0 0 0-1.42Z" />
                </symbol>
            </defs>
        </svg>

        <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasSearch"
            aria-labelledby="Search">
            <div class="offcanvas-header justify-content-center">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="order-md-last">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary">Search</span>
                    </h4>
                    <form role="search" action="/" method="get" class="d-flex mt-3 gap-0">
                        <input class="form-control rounded-start rounded-0 bg-light" type="text"
                            placeholder="What are you looking for?" aria-label="What are you looking for?">
                        <button class="btn btn-dark rounded-end rounded-0" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </div>

        <header>
            <div class="container-fluid">
                <div class="row py-3 border-bottom">

                    <div class="col-sm-4 col-lg-3 text-center text-sm-start order-1 order-lg-1">
                        <div class="main-logo">
                            <a href="/">
                                <img src="{{ asset('images/agiilogo3.png') }}" width="100" alt="logo"
                                    class="img-fluid logo">
                            </a>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5 order-3 order-lg-2 my-2 my-lg-2">
                        <div class="search-bar row bg-light p-2 my-2 rounded-4 align-items-center">
                            <form id="search-form" class="row w-100 g-2" action="{{ route('products.index') }}"
                                method="GET">
                                <div class="col-md-3 d-none d-md-block">
                                    @php $categories = $categories ?? collect(); @endphp
                                    <select name="category" class="form-select border-0 bg-transparent">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category')==$cat->id ? 'selected' :
                                            '' }}>
                                            {{ $cat->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-10 col-md-5">
                                    <input type="text" name="search" class="form-control border-0 bg-transparent"
                                        placeholder="Search for more than 20,000 products"
                                        value="{{ request('search') }}" />
                                </div>
                                <div class="col-md-3 d-none d-md-block">
                                    @php
                                    $navbarStates =
                                    \App\Models\Location::select('state')->distinct()->orderBy('state')->get();
                                    @endphp
                                    <select name="location" class="form-select border-0 bg-transparent"
                                        onchange="this.form.submit()">
                                        <option value="">All States</option>
                                        @foreach($navbarStates as $navbarState)
                                        <option value="{{ $navbarState->state }}" {{
                                            request('location')===$navbarState->state ? 'selected' : '' }}>
                                            {{ $navbarState->state }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 col-md-1">
                                    <button type="submit" class="btn btn-transparent p-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div
                        class="col-sm-8 col-lg-4 d-flex justify-content-end gap-5 align-items-center mt-4 mt-sm-0 justify-content-center justify-content-sm-end order-2 order-lg-3">
                        <ul class="d-flex justify-content-end list-unstyled m-0">
                            <li>
                                <a href="/store-around-me" class="bg-light p-2 mx-1">Store Around Me</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="#" class="bg-light p-2 mx-1 dropdown-toggle" id="userDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg width="24" height="24" viewBox="0 0 24 24">
                                        <use xlink:href="#user"></use>
                                    </svg>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li class="px-3 py-2">
                                        <div class="fw-bold">{{ Auth::user()->name ?? 'User' }}</div>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    @if (!Auth::user())
                                    <li><a class="dropdown-item" href="/login">Login</a></li>
                                    <li><a class="dropdown-item" href="/register">Register</a></li>
                                    @else
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">My Profile</a></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ auth()->user() ? '/logout' : '/login' }}">
                                            {{ auth()->user() ? 'Logout' : 'Login' }}
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <div class="container-fluid">
                <div class="row py-3">
                    <div class="d-flex justify-content-center justify-content-sm-between align-items-center">
                        <nav class="main-menu d-flex navbar navbar-expand-lg">
                            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
                                <div class="offcanvas-header justify-content-center">
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                        aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body">
                                    <ul
                                        class="navbar-nav justify-content-end menu-list list-unstyled d-flex gap-md-3 mb-0">
                                        <li class="nav-item active">
                                            <a href="{{ url('products') }}" class="nav-link">Product</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ url('services') }}" class="nav-link">Service</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#kids" class="nav-link">Ride</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        @yield('content')

        <footer class="py-5" style="background: linear-gradient(135deg, #90c74b 0%, #90c74b 100%);" id="footer1">
            <div class="container">
                <div class="row">

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="footer-menu">
                            <img src="{{ asset('images/agiilogo3.png') }}" width="105" alt="logo" class="mb-3">
                            <div class="social-links mt-4">
                                <ul class="d-flex list-unstyled gap-2">
                                    <li>
                                        <a href="https://www.facebook.com/agiinigeria" target="_blank"
                                            class="rounded-full bg-whit p-2" title="Facebook">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M15.12 5.32H17V2.14A26.11 26.11 0 0 0 14.26 2c-2.72 0-4.58 1.66-4.58 4.7v2.62H6.61v3.56h3.07V22h3.68v-9.12h3.06l.46-3.56h-3.52V7.05c0-1.05.28-1.73 1.76-1.73Z" />
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://x.com/agiinigeria" target="_blank"
                                            class="rounded-full bg-whit p-2" title="Twitter">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M22.991 3.95a1 1 0 0 0-1.51-.86a7.48 7.48 0 0 1-1.874.794a5.152 5.152 0 0 0-3.374-1.242a5.232 5.232 0 0 0-5.223 5.063a11.032 11.032 0 0 1-6.814-3.924a1.012 1.012 0 0 0-.857-.365a.999.999 0 0 0-.785.5a5.276 5.276 0 0 0-.242 4.769l-.002.001a1.041 1.041 0 0 0-.496.89a3.042 3.042 0 0 0 .027.439a5.185 5.185 0 0 0 1.568 3.312a.998.998 0 0 0-.066.77a5.204 5.204 0 0 0 2.362 2.922a7.465 7.465 0 0 1-3.59.448A1 1 0 0 0 1.45 19.3a12.942 12.942 0 0 0 7.01 2.061a12.788 12.788 0 0 0 12.465-9.363a12.822 12.822 0 0 0 .535-3.646l-.001-.2a5.77 5.77 0 0 0 1.532-4.202Z" />
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://youtube.com/@agiinigeria" target="_blank"
                                            class="rounded-full bg-whit p-2" title="YouTube">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M23 9.71a8.5 8.5 0 0 0-.91-4.13a2.92 2.92 0 0 0-1.72-1A78.36 78.36 0 0 0 12 4.27a78.45 78.45 0 0 0-8.34.3a2.87 2.87 0 0 0-1.46.74c-.9.83-1 2.25-1.1 3.45a48.29 48.29 0 0 0 0 6.48a9.55 9.55 0 0 0 .3 2a3.14 3.14 0 0 0 .71 1.36a2.86 2.86 0 0 0 1.49.78a45.18 45.18 0 0 0 6.5.33c3.5.05 6.57 0 10.2-.28a2.88 2.88 0 0 0 1.53-.78a2.49 2.49 0 0 0 .61-1a10.58 10.58 0 0 0 .52-3.4c.04-.56.04-3.94.04-4.54ZM9.74 14.85V8.66l5.92 3.11c-1.66.92-3.85 1.96-5.92 3.08Z" />
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.instagram.com/agiinigeria" target="_blank"
                                            class="rounded-full bg-whit p-2" title="Instagram">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M17.34 5.46a1.2 1.2 0 1 0 1.2 1.2a1.2 1.2 0 0 0-1.2-1.2Zm4.6 2.42a7.59 7.59 0 0 0-.46-2.43a4.94 4.94 0 0 0-1.16-1.77a4.7 4.7 0 0 0-1.77-1.15a7.3 7.3 0 0 0-2.43-.47C15.06 2 14.72 2 12 2s-3.06 0-4.12.06a7.3 7.3 0 0 0-2.43.47a4.78 4.78 0 0 0-1.77 1.15a4.7 4.7 0 0 0-1.15 1.77a7.3 7.3 0 0 0-.47 2.43C2 8.94 2 9.28 2 12s0 3.06.06 4.12a7.3 7.3 0 0 0 .47 2.43a4.7 4.7 0 0 0 1.15 1.77a4.78 4.78 0 0 0 1.77 1.15a7.3 7.3 0 0 0 2.43.47C8.94 22 9.28 22 12 22s3.06 0 4.12-.06a7.3 7.3 0 0 0 2.43-.47a4.7 4.7 0 0 0 1.77-1.15a4.85 4.85 0 0 0 1.16-1.77a7.59 7.59 0 0 0 .46-2.43c0-1.06.06-1.4.06-4.12s0-3.06-.06-4.12ZM20.14 16a5.61 5.61 0 0 1-.34 1.86a3.06 3.06 0 0 1-.75 1.15a3.19 3.19 0 0 1-1.15.75a5.61 5.61 0 0 1-1.86.34c-1 .05-1.37.06-4 .06s-3 0-4-.06a5.73 5.73 0 0 1-1.94-.3a3.27 3.27 0 0 1-1.1-.75a3 3 0 0 1-.74-1.15a5.54 5.54 0 0 1-.4-1.9c0-1-.06-1.37-.06-4s0-3 .06-4a5.54 5.54 0 0 1 .35-1.9A3 3 0 0 1 5 5a3.14 3.14 0 0 1 1.1-.8A5.73 5.73 0 0 1 8 3.86c1 0 1.37-.06 4-.06s3 0 4 .06a5.61 5.61 0 0 1 1.86.34a3.06 3.06 0 0 1 1.19.8a3.06 3.06 0 0 1 .75 1.1a5.61 5.61 0 0 1 .34 1.9c.05 1 .06 1.37.06 4s-.01 3-.06 4ZM12 6.87A5.13 5.13 0 1 0 17.14 12A5.12 5.12 0 0 0 12 6.87Zm0 8.46A3.33 3.33 0 1 1 15.33 12A3.33 3.33 0 0 1 12 15.33Z" />
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.tiktok.com/@agiinigeria" target="_blank"
                                            class="rounded-full bg-whit p-2" title="TikTok">
                                            <svg fill="#fff" viewBox="0 0 32 32" width="16" height="16"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M16.656 1.029c1.637-0.025 3.262-0.012 4.886-0.025 0.054 2.031 0.878 3.859 2.189 5.213l-0.002-0.002c1.411 1.271 3.247 2.095 5.271 2.235l0.028 0.002v5.036c-1.912-0.048-3.71-0.489-5.331-1.247l0.082 0.034c-0.784-0.377-1.447-0.764-2.077-1.196l0.052 0.034c-0.012 3.649 0.012 7.298-0.025 10.934-0.103 1.853-0.719 3.543-1.707 4.954l0.020-0.031c-1.652 2.366-4.328 3.919-7.371 4.011l-0.014 0c-0.123 0.006-0.268 0.009-0.414 0.009-1.73 0-3.347-0.482-4.725-1.319l0.040 0.023c-2.508-1.509-4.238-4.091-4.558-7.094l-0.004-0.041c-0.025-0.625-0.037-1.25-0.012-1.862 0.49-4.779 4.494-8.476 9.361-8.476 0.547 0 1.083 0.047 1.604 0.136l-0.056-0.008c0.025 1.849-0.050 3.699-0.050 5.548-0.423-0.153-0.911-0.242-1.42-0.242-1.868 0-3.457 1.194-4.045 2.861l-0.009 0.030c-0.133 0.427-0.21 0.918-0.21 1.426 0 0.206 0.013 0.41 0.037 0.61l-0.002-0.024c0.332 2.046 2.086 3.59 4.201 3.59 0.061 0 0.121-0.001 0.181-0.004l-0.009 0c1.463-0.044 2.733-0.831 3.451-1.994l0.010-0.018c0.267-0.372 0.45-0.822 0.511-1.311l0.001-0.014c0.125-2.237 0.075-4.461 0.087-6.698 0.012-5.036-0.012-10.060 0.025-15.083z">
                                                </path>
                                            </svg>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="footer-menu">
                            <h5 class="widget-title text-black">Useful Links</h5>
                            <ul class="menu-list list-unstyled">
                                <li class="menu-item"><a href="/about" class="nav-link text-white">About Agii NG</a>
                                </li>
                                <li class="menu-item"><a href="/faq" class="nav-link text-white">FAQ</a></li>
                                <li class="menu-item"><a href="{{ route('register.affiliate') }}"
                                        class="nav-link text-white">Join Our Team</a></li>
                                {{-- <li class="menu-item"><a href="https://agii.ng/become-login"
                                        class="nav-link text-white">Log in Affiliate Account</a></li> --}}
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="footer-menu">
                            <h5 class="widget-title text-black">Customer Service</h5>
                            <ul class="menu-list list-unstyled">
                                <li class="menu-item"><a href="/contact" class="nav-link text-white">Contact us</a></li>
                                <li class="menu-item"><a href="/terms-and-condition" class="nav-link text-white">Terms
                                        and conditions</a></li>
                                <li class="menu-item"><a href="/policy" class="nav-link text-white">Privacy Policy</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="footer-menu">
                            <h5 class="widget-title text-black">My Account</h5>
                            <ul class="menu-list list-unstyled">
                                <li class="menu-item"><a href="/login" class="nav-link text-white">Sign In</a></li>
                            </ul>
                        </div>
                    </div>

                </div>

                <div class="row mt-5 pt-4 border-top border-white-20">
                    <div class="col-md-6">
                        <p class="text-white mb-0">Copyright &copy; 2025 Agii NG. All Rights Reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="d-flex gap-4 justify-content-md-end justify-content-start">
                            <a href="/terms-and-condition" class="text-white text-decoration-none">Terms of Service</a>
                            <a href="/policy" class="text-white text-decoration-none">Privacy Policy</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
        </script>
        <script src="{{ asset('js/plugins.js') }}"></script>
        <script src="{{ asset('js/script.js') }}"></script>

        {{-- ============================================================
        PWA BANNER LOGIC
        Runs after all scripts + DOM is ready.
        Banner HTML is already in the DOM (top of

        <body>).
            ============================================================ --}}
            <script>
                (function() {
        // Already dismissed or installed — do nothing
        if (localStorage.getItem('pwa-dismissed')) return;

        var banner     = document.getElementById('pwa-install-banner');
        var installBtn = document.getElementById('pwa-install-btn');
        var dismissBtn = document.getElementById('pwa-dismiss-btn');
        var iosTip     = document.getElementById('pwa-ios-tip');
        var iosDismiss = document.getElementById('pwa-ios-dismiss');

        var ua           = navigator.userAgent;
        var isIOS        = /iphone|ipad|ipod/i.test(ua);
        var isSafari     = /safari/i.test(ua) && !/chrome|crios|fxios/i.test(ua);
        var isStandalone = window.navigator.standalone === true
                        || window.matchMedia('(display-mode: standalone)').matches;

        // Already running as PWA — bail out
        if (isStandalone) return;

        function dismiss() {
            banner.style.display = 'none';
            iosTip.style.display = 'none';
            localStorage.setItem('pwa-dismissed', '1');
        }

        // ── iOS Safari ───────────────────────────────────────────
        if (isIOS && isSafari) {
            setTimeout(function() {
                iosTip.style.display = 'block';
            }, 3000);
            iosDismiss.addEventListener('click', dismiss);
            return;
        }

        // ── Android / Desktop Chrome ─────────────────────────────

        // Define the show function and expose it globally so the
        // <head> listener can call it if the event fires first
        window.__pwaShowBanner = function() {
            setTimeout(function() {
                banner.style.display = 'flex';
                console.log('[PWA] Banner shown');
            }, 3000);
        };

        // beforeinstallprompt may have already fired before this script ran
        if (window.__pwaPromptReady) {
            window.__pwaShowBanner();
        }
        // Otherwise the <head> listener will call __pwaShowBanner() when it fires

        // Fallback: Chrome suppressed the event ("Open in app" button visible instead)
        // Show a soft nudge after 7s if banner still hasn't appeared
        setTimeout(function() {
            if (!window.__pwaPromptReady && banner.style.display !== 'flex') {
                document.getElementById('pwa-banner-subtitle').textContent =
                    'Tap "Open in app" in your address bar';
                installBtn.textContent = 'Got it';
                installBtn.addEventListener('click', dismiss, { once: true });
                banner.style.display = 'flex';
                console.log('[PWA] Fallback nudge shown');
            }
        }, 7000);

        // ── Install button ───────────────────────────────────────
        installBtn.addEventListener('click', async function() {
            var prompt = window.__pwaPrompt;

            if (!prompt) {
                dismiss();
                return;
            }

            banner.style.display = 'none';
            window.__pwaPrompt = null;
            window.__pwaPromptReady = false;

            try {
                await prompt.prompt();
                var result = await prompt.userChoice;
                console.log('[PWA] User choice:', result.outcome);
                if (result.outcome === 'accepted') {
                    localStorage.setItem('pwa-dismissed', '1');
                }
            } catch (err) {
                console.warn('[PWA] Prompt error:', err);
            }
        });

        dismissBtn.addEventListener('click', dismiss);

        window.addEventListener('appinstalled', function() {
            console.log('[PWA] App installed successfully');
            dismiss();
            window.__pwaPrompt = null;
            window.__pwaPromptReady = false;
        });
    })();
            </script>

            @stack('scripts')
        </body>

</html>