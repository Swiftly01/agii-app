<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', config('app.name', 'Platform'))</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    
    <!-- Generic Meta Tags -->
    <meta name="author" content="">
    <meta name="keywords" content="@yield('keywords', '')">
    <meta name="description" content="@yield('description', '')">
    
    <!-- Neutral Favicon (no branding) -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Generic CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    
    <style>
        /* Generic Styles - No Branding */
        :root {
            --primary-color: #4a6bdf;
            --secondary-color: #6c757d;
            --text-color: #333333;
            --background-color: #ffffff;
            --border-color: #dee2e6;
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .border-primary {
            border-color: var(--primary-color) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #3a5bd0;
            border-color: #3a5bd0;
        }
        
        /* Remove all previous branding styles */
        body {
            font-family: 'Open Sans', sans-serif;
            color: var(--text-color);
        }
    </style>
    
    @stack('styles')
</head>

<body>

    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <defs>
            <symbol id="search" viewBox="0 0 24 24">
                <path fill="currentColor" d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z"/>
            </symbol>
            <symbol id="user" viewBox="0 0 24 24">
                <path fill="currentColor" d="M15.71 12.71a6 6 0 1 0-7.42 0a10 10 0 0 0-6.22 8.18a1 1 0 0 0 2 .22a8 8 0 0 1 15.9 0a1 1 0 0 0 1 .89h.11a1 1 0 0 0 .88-1.1a10 10 0 0 0-6.25-8.19ZM12 12a4 4 0 1 1 4-4a4 4 0 0 1-4 4Z"/>
            </symbol>
            <symbol id="cart" viewBox="0 0 24 24">
                <path fill="currentColor" d="M8.5 19a1.5 1.5 0 1 0 1.5 1.5A1.5 1.5 0 0 0 8.5 19ZM19 16H7a1 1 0 0 1 0-2h8.491a3.013 3.013 0 0 0 2.885-2.176l1.585-5.55A1 1 0 0 0 19 5H6.74a3.007 3.007 0 0 0-2.82-2H3a1 1 0 0 0 0 2h.921a1.005 1.005 0 0 1 .962.725l.155.545v.005l1.641 5.742A3 3 0 0 0 7 18h12a1 1 0 0 0 0-2Zm-1.326-9l-1.22 4.274a1.005 1.005 0 0 1-.963.726H8.754l-.255-.892L7.326 7ZM16.5 19a1.5 1.5 0 1 0 1.5 1.5a1.5 1.5 0 0 0-1.5-1.5Z"/>
            </symbol>
            <symbol id="heart" viewBox="0 0 24 24">
                <path fill="currentColor" d="M20.16 4.61A6.27 6.27 0 0 0 12 4a6.27 6.27 0 0 0-8.16 9.48l7.45 7.45a1 1 0 0 0 1.42 0l7.45-7.45a6.27 6.27 0 0 0 0-8.87Zm-1.41 7.46L12 18.81l-6.75-6.74a4.28 4.28 0 0 1 3-7.3a4.25 4.25 0 0 1 3 1.25a1 1 0 0 0 1.42 0a4.27 4.27 0 0 1 6 6.05Z"/>
            </symbol>
        </defs>
    </svg>

    <div class="preloader-wrapper">
        <div class="preloader"></div>
    </div>

    <!-- Minimal Header with NO navigation -->
    <header class="border-bottom">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center py-3">
                <!-- Simple Logo/Home Link -->
                <div class="logo">
                    <a href="#" class="text-decoration-none">
                        <span class="fs-4 fw-bold text-dark">Application</span>
                    </a>
                </div>
                
                <!-- Minimal Action Buttons -->
                <div class="d-flex align-items-center gap-3 d-none">
                    <!-- Simple Search Button -->
                    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="offcanvas" 
                            data-bs-target="#offcanvasSearch" aria-controls="offcanvasSearch">
                        <svg width="18" height="18" viewBox="0 0 24 24">
                            <use xlink:href="#search"></use>
                        </svg>
                    </button>
                    
                    <!-- User Actions -->
                    @if(Auth::check())
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <svg width="18" height="18" viewBox="0 0 24 24">
                                    <use xlink:href="#user"></use>
                                </svg>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/profile">Profile</a></li>
                                <li><a class="dropdown-item" href="/logout">Logout</a></li>
                            </ul>
                        </div>
                    @else
                        <a href="/login" class="btn btn-outline-primary btn-sm">Login</a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Search Panel (Hidden by default) -->
    <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasSearch" aria-labelledby="searchLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="searchLabel">Search</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="/search" method="GET" class="d-flex">
                <input type="text" name="q" class="form-control" placeholder="Search..." autofocus>
                <button type="submit" class="btn btn-primary ms-2">Search</button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Minimal Generic Footer -->
    <footer class="bg-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-2">
                        <a href="/" class="text-decoration-none">
                            <span class="fw-bold text-primary">Application</span>
                        </a>
                    </p>
                    <p class="text-muted small">
                        Employment Application
                    </p>
                </div>
                
                <div class="col-md-6 text-md-end">
                    <div class="mb-2">
                        <a href="/terms" class="text-muted small text-decoration-none me-3">Terms</a>
                        <a href="/privacy" class="text-muted small text-decoration-none">Privacy</a>
                    </div>
                    <p class="text-muted small mb-0">
                        &copy; {{ date('Y') }} All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    @stack('scripts')
</body>

</html>