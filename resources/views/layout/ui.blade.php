<!DOCTYPE html>
<html lang="en">

<head>
    <title>FoodMart - Free eCommerce Grocery Store HTML Website Template</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <!-- Fixed CSS Links -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">

    <style>
        /* Add this to your CSS file */
        .product-item {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-item figure {
            height: 200px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-item figure img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rating-block {
            display: flex;
            align-items: center;
            gap: 4px;
            margin: 8px 0;
        }

        .rating-text {
            font-size: 14px;
            font-weight: 600;
            color: #222222;
        }

        .product-item h3 {
            min-height: 48px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-item .qty {
            min-height: 20px;
        }

        .product-item .price {
            margin-bottom: 12px;
        }
    </style>
</head>

<body>

    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <defs>
            <symbol xmlns="http://www.w3.org/2000/svg" id="link" viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M12 19a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0-4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm-5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm7-12h-1V2a1 1 0 0 0-2 0v1H8V2a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3Zm1 17a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-9h16Zm0-11H4V6a1 1 0 0 1 1-1h1v1a1 1 0 0 0 2 0V5h8v1a1 1 0 0 0 2 0V5h1a1 1 0 0 1 1 1ZM7 15a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0 4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Z" />
            </symbol>
            <symbol xmlns="http://www.w3.org/2000/svg" id="arrow-right" viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M17.92 11.62a1 1 0 0 0-.21-.33l-5-5a1 1 0 0 0-1.42 1.42l3.3 3.29H7a1 1 0 0 0 0 2h7.59l-3.3 3.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76Z" />
            </symbol>
            <symbol xmlns="http://www.w3.org/2000/svg" id="category" viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M19 5.5h-6.28l-.32-1a3 3 0 0 0-2.84-2H5a3 3 0 0 0-3 3v13a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-10a3 3 0 0 0-3-3Zm1 13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-13a1 1 0 0 1 1-1h4.56a1 1 0 0 1 .95.68l.54 1.64a1 1 0 0 0 .95.68h7a1 1 0 0 1 1 1Z" />
            </symbol>
            <symbol xmlns="http://www.w3.org/2000/svg" id="calendar" viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M19 4h-2V3a1 1 0 0 0-2 0v1H9V3a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3Zm1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7h16Zm0-9H4V7a1 1 0 0 1 1-1h2v1a1 1 0 0 0 2 0V6h6v1a1 1 0 0 0 2 0V6h2a1 1 0 0 1 1 1Z" />
            </symbol>
            <symbol xmlns="http://www.w3.org/2000/svg" id="heart" viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M20.16 4.61A6.27 6.27 0 0 0 12 4a6.27 6.27 0 0 0-8.16 9.48l7.45 7.45a1 1 0 0 0 1.42 0l7.45-7.45a6.27 6.27 0 0 0 0-8.87Zm-1.41 7.46L12 18.81l-6.75-6.74a4.28 4.28 0 0 1 3-7.3a4.25 4.25 0 0 1 3 1.25a1 1 0 0 0 1.42 0a4.27 4.27 0 0 1 6 6.05Z" />
            </symbol>
            <symbol xmlns="http://www.w3.org/2000/svg" id="plus" viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M19 11h-6V5a1 1 0 0 0-2 0v6H5a1 1 0 0 0 0 2h6v6a1 1 0 0 0 2 0v-6h6a1 1 0 0 0 0-2Z" />
            </symbol>
            <symbol xmlns="http://www.w3.org/2000/svg" id="minus" viewBox="0 0 24 24">
                <path fill="currentColor" d="M19 11H5a1 1 0 0 0 0 2h14a1 1 0 0 0 0-2Z" />
            </symbol>
            <symbol xmlns="http://www.w3.org/2000/svg" id="cart" viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M8.5 19a1.5 1.5 0 1 0 1.5 1.5A1.5 1.5 0 0 0 8.5 19ZM19 16H7a1 1 0 0 1 0-2h8.491a3.013 3.013 0 0 0 2.885-2.176l1.585-5.55A1 1 0 0 0 19 5H6.74a3.007 3.007 0 0 0-2.82-2H3a1 1 0 0 0 0 2h.921a1.005 1.005 0 0 1 .962.725l.155.545v.005l1.641 5.742A3 3 0 0 0 7 18h12a1 1 0 0 0 0-2Zm-1.326-9l-1.22 4.274a1.005 1.005 0 0 1-.963.726H8.754l-.255-.892L7.326 7ZM16.5 19a1.5 1.5 0 1 0 1.5 1.5a1.5 1.5 0 0 0-1.5-1.5Z" />
            </symbol>
            <symbol xmlns="http://www.w3.org/2000/svg" id="check" viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M18.71 7.21a1 1 0 0 0-1.42 0l-7.45 7.46l-3.13-3.14A1 1 0 1 0 5.29 13l3.84 3.84a1 1 0 0 0 1.42 0l8.16-8.16a1 1 0 0 0 0-1.47Z" />
            </symbol>
            <symbol xmlns="http://www.w3.org/2000/svg" id="trash" viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M10 18a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1ZM20 6h-4V5a3 3 0 0 0-3-3h-2a3 3 0 0 0-3 3v1H4a1 1 0 0 0 0 2h1v11a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8h1a1 1 0 0 0 0-2ZM10 5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1h-4Zm7 14a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V8h10Zm-3-1a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1Z" />
            </symbol>
            <symbol xmlns="http://www.w3.org/2000/svg" id="star-outline" viewBox="0 0 15 15">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    d="M7.5 9.804L5.337 11l.413-2.533L4 6.674l2.418-.37L7.5 4l1.082 2.304l2.418.37l-1.75 1.793L9.663 11L7.5 9.804Z" />
            </symbol>
            <symbol xmlns="http://www.w3.org/2000/svg" id="star-solid" viewBox="0 0 15 15">
                <path fill="currentColor"
                    d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z" />
            </symbol>
            <symbol xmlns="http://www.w3.org/2000/svg" id="search" viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z" />
            </symbol>
            <symbol xmlns="http://www.w3.org/2000/svg" id="user" viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M15.71 12.71a6 6 0 1 0-7.42 0a10 10 0 0 0-6.22 8.18a1 1 0 0 0 2 .22a8 8 0 0 1 15.9 0a1 1 0 0 0 1 .89h.11a1 1 0 0 0 .88-1.1a10 10 0 0 0-6.25-8.19ZM12 12a4 4 0 1 1 4-4a4 4 0 0 1-4 4Z" />
            </symbol>
            <symbol xmlns="http://www.w3.org/2000/svg" id="close" viewBox="0 0 15 15">
                <path fill="currentColor"
                    d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z" />
            </symbol>
        </defs>
    </svg>

    <div class="preloader-wrapper">
        <div class="preloader">
        </div>
    </div>



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
                <form role="search" action="index.html" method="get" class="d-flex mt-3 gap-0">
                    <input class="form-control rounded-start rounded-0 bg-light" type="email"
                        placeholder="What are you looking for?" aria-label="What are you looking for?">
                    <button class="btn btn-dark rounded-end rounded-0" type="submit">Search</button>
                </form>
            </div>
        </div>
    </div>

    <header>
        <div class="container-fluid">
            <div class="row py-3 border-bottom">

                <div class="col-sm-4 col-lg-3 text-center text-sm-start">
                    <div class="main-logo">
                        <a href="index.html">
                            <img src="{{ asset('images/Agiilogo2.png') }}" width="100" alt="logo"
                                class="img-fluid">
                        </a>
                    </div>
                </div>

                <div class="col-sm-6 offset-sm-2 offset-md-0 col-lg-5 d-none d-lg-block">
                    <div class="search-bar row bg-light p-2 my-2 rounded-4">
                        <div class="col-md-4 d-none d-md-block">
                            <select class="form-select border-0 bg-transparent">
                                <option>All Categories</option>
                                <option>Groceries</option>
                                <option>Drinks</option>
                                <option>Chocolates</option>
                            </select>
                        </div>
                        <div class="col-11 col-md-7">
                            <form id="search-form" class="text-center" action="index.html" method="post">
                                <input type="text" class="form-control border-0 bg-transparent"
                                    placeholder="Search for more than 20,000 products" />
                            </form>
                        </div>
                        <div class="col-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="col-sm-8 col-lg-4 d-flex justify-content-end gap-5 align-items-center mt-4 mt-sm-0 justify-content-center justify-content-sm-end">
                    <div class="support-box text-end d-none d-xl-block">
                        <span class="fs-6 text-muted">For Support?</span>
                        <h5 class="mb-0">+980-34984089</h5>
                    </div>

                    <ul class="d-flex justify-content-end list-unstyled m-0">
                        <li>
                            <a href="#" class="rounded-circle bg-light p-2 mx-1">
                                <svg width="24" height="24" viewBox="0 0 24 24">
                                    <use xlink:href="#user"></use>
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="rounded-circle bg-light p-2 mx-1">
                                <svg width="24" height="24" viewBox="0 0 24 24">
                                    <use xlink:href="#heart"></use>
                                </svg>
                            </a>
                        </li>

                    </ul>


                </div>

            </div>
        </div>
        <div class="container-fluid">
            <div class="row py-3">
                <div class="d-flex  justify-content-center justify-content-sm-between align-items-center">
                    <nav class="main-menu d-flex navbar navbar-expand-lg">

                        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                            aria-labelledby="offcanvasNavbarLabel">

                            <div class="offcanvas-header justify-content-center">
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>

                            <div class="offcanvas-body">

                                <ul
                                    class="navbar-nav justify-content-end menu-list list-unstyled d-flex gap-md-3 mb-0">
                                    <li class="nav-item active">
                                        <a href="#Product" class="nav-link">Product</a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a href="#men" class="nav-link">Service</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#kids" class="nav-link">Ride</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#accessories" class="nav-link">Accessories</a>
                                    </li>
                                    {{-- <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" role="button" id="pages"
                                            data-bs-toggle="dropdown" aria-expanded="false">Pages</a>
                                        <ul class="dropdown-menu" aria-labelledby="pages">
                                            <li><a href="index.html" class="dropdown-item">About Us </a></li>
                                            <li><a href="index.html" class="dropdown-item">Shop </a></li>
                                            <li><a href="index.html" class="dropdown-item">Single Product </a></li>
                                            <li><a href="index.html" class="dropdown-item">Cart </a></li>
                                            <li><a href="index.html" class="dropdown-item">Checkout </a></li>
                                            <li><a href="index.html" class="dropdown-item">Blog </a></li>
                                            <li><a href="index.html" class="dropdown-item">Single Post </a></li>
                                            <li><a href="index.html" class="dropdown-item">Styles </a></li>
                                            <li><a href="index.html" class="dropdown-item">Contact </a></li>
                                            <li><a href="index.html" class="dropdown-item">Thank You </a></li>
                                            <li><a href="index.html" class="dropdown-item">My Account </a></li>
                                            <li><a href="index.html" class="dropdown-item">404 Error </a></li>
                                        </ul>
                                    </li> --}}

                                </ul>

                            </div>

                        </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Banner Section -->
    <section class="banner-section py-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Buy and Sell Anything in Nigeria</h1>
                    <p class="lead mb-4">Discover the best deals on thousands of products. From electronics to fashion,
                        find everything you need in one place.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-primary btn-lg">Start Selling</a>
                        {{-- <a href="#" class="btn btn-outline-primary btn-lg">Sell Items</a> --}}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="swiper-slide">
                        <div class="banner-content text-center">
                            <img src="https://agii.ng/frontend/assets/images/demos/demo-14/slider/slide-1.png"
                                alt="Banner" class="img-fluid rounded-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="py-5 overflow-hidden">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <div class="section-header d-flex flex-wrap justify-content-between mb-5">
                        <h2 class="section-title">Category</h2>

                        <div class="d-flex align-items-center">
                            <a href="#" class="btn-link text-decoration-none">View All Categories →</a>
                            <div class="swiper-buttons">
                                <button
                                    class="swiper-prev category-carousel-prev btn btn-yellow swiper-button-disabled"
                                    disabled="" tabindex="-1" aria-label="Previous slide"
                                    aria-controls="swiper-wrapper-cee5e329f35af381" aria-disabled="true">❮</button>
                                <button class="swiper-next category-carousel-next btn btn-yellow" tabindex="0"
                                    aria-label="Next slide" aria-controls="swiper-wrapper-cee5e329f35af381"
                                    aria-disabled="false">❯</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <div class="category-carousel swiper swiper-initialized swiper-horizontal">
                        <div class="swiper-wrapper" id="swiper-wrapper-cee5e329f35af381" aria-live="polite">
                            <a href="index.html" class="nav-link category-item swiper-slide swiper-slide-active"
                                style="width: 336.75px; margin-right: 30px;" role="group" aria-label="1 / 12">
                                <img src="images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                                <h3 class="category-title">Fruits &amp; Veges</h3>
                            </a>
                            <a href="index.html" class="nav-link category-item swiper-slide swiper-slide-next"
                                style="width: 336.75px; margin-right: 30px;" role="group" aria-label="2 / 12">
                                <img src="images/icon-bread-baguette.png" alt="Category Thumbnail">
                                <h3 class="category-title">Breads &amp; Sweets</h3>
                            </a>
                            <a href="index.html" class="nav-link category-item swiper-slide"
                                style="width: 336.75px; margin-right: 30px;" role="group" aria-label="3 / 12">
                                <img src="images/icon-soft-drinks-bottle.png" alt="Category Thumbnail">
                                <h3 class="category-title">Fruits &amp; Veges</h3>
                            </a>
                            <a href="index.html" class="nav-link category-item swiper-slide"
                                style="width: 336.75px; margin-right: 30px;" role="group" aria-label="4 / 12">
                                <img src="images/icon-wine-glass-bottle.png" alt="Category Thumbnail">
                                <h3 class="category-title">Fruits &amp; Veges</h3>
                            </a>
                            <a href="index.html" class="nav-link category-item swiper-slide"
                                style="width: 336.75px; margin-right: 30px;" role="group" aria-label="5 / 12">
                                <img src="images/icon-animal-products-drumsticks.png" alt="Category Thumbnail">
                                <h3 class="category-title">Fruits &amp; Veges</h3>
                            </a>
                            <a href="index.html" class="nav-link category-item swiper-slide"
                                style="width: 336.75px; margin-right: 30px;" role="group" aria-label="6 / 12">
                                <img src="images/icon-bread-herb-flour.png" alt="Category Thumbnail">
                                <h3 class="category-title">Fruits &amp; Veges</h3>
                            </a>
                            <a href="index.html" class="nav-link category-item swiper-slide"
                                style="width: 336.75px; margin-right: 30px;" role="group" aria-label="7 / 12">
                                <img src="images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                                <h3 class="category-title">Fruits &amp; Veges</h3>
                            </a>
                            <a href="index.html" class="nav-link category-item swiper-slide"
                                style="width: 336.75px; margin-right: 30px;" role="group" aria-label="8 / 12">
                                <img src="images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                                <h3 class="category-title">Fruits &amp; Veges</h3>
                            </a>
                            <a href="index.html" class="nav-link category-item swiper-slide"
                                style="width: 336.75px; margin-right: 30px;" role="group" aria-label="9 / 12">
                                <img src="images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                                <h3 class="category-title">Fruits &amp; Veges</h3>
                            </a>
                            <a href="index.html" class="nav-link category-item swiper-slide"
                                style="width: 336.75px; margin-right: 30px;" role="group" aria-label="10 / 12">
                                <img src="images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                                <h3 class="category-title">Fruits &amp; Veges</h3>
                            </a>
                            <a href="index.html" class="nav-link category-item swiper-slide"
                                style="width: 336.75px; margin-right: 30px;" role="group" aria-label="11 / 12">
                                <img src="images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                                <h3 class="category-title">Fruits &amp; Veges</h3>
                            </a>
                            <a href="index.html" class="nav-link category-item swiper-slide"
                                style="width: 336.75px; margin-right: 30px;" role="group" aria-label="12 / 12">
                                <img src="images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                                <h3 class="category-title">Fruits &amp; Veges</h3>
                            </a>

                        </div>
                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                    </div>

                </div>
            </div>
        </div>
    </section>


    <div class="col-md-12">
        <div class="bootstrap-tabs product-tabs">
            <div class="tabs-header d-flex justify-content-between border-bottom my-5">
                <h3>Trending Products</h3>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a href="#" class="nav-link text-uppercase fs-6 active" id="nav-all-tab"
                            data-bs-toggle="tab" data-bs-target="#nav-all" aria-selected="true"
                            role="tab">All</a>
                        <a href="#" class="nav-link text-uppercase fs-6" id="nav-electronics-tab"
                            data-bs-toggle="tab" data-bs-target="#nav-electronics" aria-selected="false"
                            tabindex="-1" role="tab">Electronics</a>
                        <a href="#" class="nav-link text-uppercase fs-6" id="nav-vehicles-tab"
                            data-bs-toggle="tab" data-bs-target="#nav-vehicles" aria-selected="false" tabindex="-1"
                            role="tab">Vehicles</a>
                        <a href="#" class="nav-link text-uppercase fs-6" id="nav-properties-tab"
                            data-bs-toggle="tab" data-bs-target="#nav-properties" aria-selected="false"
                            tabindex="-1" role="tab">Properties</a>
                    </div>
                </nav>
            </div>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
                    <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                        <!-- Product 1 -->
                        <div class="col">
                            <div class="product-item">
                                <span class="badge bg-success position-absolute m-3">New</span>
                                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                        <use xlink:href="#heart"></use>
                                    </svg></a>
                                <figure>
                                    <a href="product-detail.html" title="iPhone 13 Pro Max">
                                        <img src="https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=400&h=300&fit=crop"
                                            class="tab-image" alt="iPhone 13 Pro Max">
                                    </a>
                                </figure>
                                <h3>iPhone 13 Pro Max</h3>
                                <span class="qty">256GB • Excellent Condition</span>
                                <div class="rating-block">
                                    <svg width="16" height="16" class="text-primary">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <span class="rating-text">4.8</span>
                                </div>
                                <span class="price">₦450,000</span>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <span class="location text-muted small">
                                        <iconify-icon icon="mdi:map-marker"></iconify-icon> Lagos
                                    </span>
                                    <a href="product-detail.html" class="btn btn-primary btn-sm">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 2 -->
                        <div class="col">
                            <div class="product-item">
                                <span class="badge bg-danger position-absolute m-3">Hot Deal</span>
                                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                        <use xlink:href="#heart"></use>
                                    </svg></a>
                                <figure>
                                    <a href="product-detail.html" title="Toyota Camry 2018">
                                        <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=400&h=300&fit=crop"
                                            class="tab-image" alt="Toyota Camry 2018">
                                    </a>
                                </figure>
                                <h3>Toyota Camry 2018</h3>
                                <span class="qty">Automatic • 45,000km</span>
                                <div class="rating-block">
                                    <svg width="16" height="16" class="text-primary">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <span class="rating-text">4.9</span>
                                </div>
                                <span class="price">₦8,500,000</span>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <span class="location text-muted small">
                                        <iconify-icon icon="mdi:map-marker"></iconify-icon> Abuja
                                    </span>
                                    <a href="product-detail.html" class="btn btn-primary btn-sm">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 3 -->
                        <div class="col">
                            <div class="product-item">
                                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                        <use xlink:href="#heart"></use>
                                    </svg></a>
                                <figure>
                                    <a href="product-detail.html" title="3-Bedroom Apartment">
                                        <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=400&h=300&fit=crop"
                                            class="tab-image" alt="3-Bedroom Apartment">
                                    </a>
                                </figure>
                                <h3>3-Bedroom Apartment</h3>
                                <span class="qty">Lekki Phase 1 • Fully Furnished</span>
                                <div class="rating-block">
                                    <svg width="16" height="16" class="text-primary">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <span class="rating-text">4.7</span>
                                </div>
                                <span class="price">₦25,000,000</span>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <span class="location text-muted small">
                                        <iconify-icon icon="mdi:map-marker"></iconify-icon> Lekki
                                    </span>
                                    <a href="product-detail.html" class="btn btn-primary btn-sm">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 4 -->
                        <div class="col">
                            <div class="product-item">
                                <span class="badge bg-success position-absolute m-3">New</span>
                                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                        <use xlink:href="#heart"></use>
                                    </svg></a>
                                <figure>
                                    <a href="product-detail.html" title="MacBook Pro M1">
                                        <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&h=300&fit=crop"
                                            class="tab-image" alt="MacBook Pro M1">
                                    </a>
                                </figure>
                                <h3>MacBook Pro M1</h3>
                                <span class="qty">2022 Model • 16GB RAM</span>
                                <div class="rating-block">
                                    <svg width="16" height="16" class="text-primary">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <span class="rating-text">4.9</span>
                                </div>
                                <span class="price">₦750,000</span>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <span class="location text-muted small">
                                        <iconify-icon icon="mdi:map-marker"></iconify-icon> Ikeja
                                    </span>
                                    <a href="product-detail.html" class="btn btn-primary btn-sm">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 5 -->
                        <div class="col">
                            <div class="product-item">
                                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                        <use xlink:href="#heart"></use>
                                    </svg></a>
                                <figure>
                                    <a href="product-detail.html" title="Samsung Galaxy S21">
                                        <img src="https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=400&h=300&fit=crop"
                                            class="tab-image" alt="Samsung Galaxy S21">
                                    </a>
                                </figure>
                                <h3>Samsung Galaxy S21</h3>
                                <span class="qty">128GB • Like New</span>
                                <div class="rating-block">
                                    <svg width="16" height="16" class="text-primary">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <span class="rating-text">4.6</span>
                                </div>
                                <span class="price">₦280,000</span>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <span class="location text-muted small">
                                        <iconify-icon icon="mdi:map-marker"></iconify-icon> Port Harcourt
                                    </span>
                                    <a href="product-detail.html" class="btn btn-primary btn-sm">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 6 -->
                        <div class="col">
                            <div class="product-item">
                                <span class="badge bg-warning position-absolute m-3">Negotiable</span>
                                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                        <use xlink:href="#heart"></use>
                                    </svg></a>
                                <figure>
                                    <a href="product-detail.html" title="Honda Civic 2015">
                                        <img src="https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=400&h=300&fit=crop"
                                            class="tab-image" alt="Honda Civic 2015">
                                    </a>
                                </figure>
                                <h3>Honda Civic 2015</h3>
                                <span class="qty">Manual • 75,000km</span>
                                <div class="rating-block">
                                    <svg width="16" height="16" class="text-primary">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <span class="rating-text">4.5</span>
                                </div>
                                <span class="price">₦4,200,000</span>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <span class="location text-muted small">
                                        <iconify-icon icon="mdi:map-marker"></iconify-icon> Ibadan
                                    </span>
                                    <a href="product-detail.html" class="btn btn-primary btn-sm">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 7 -->
                        <div class="col">
                            <div class="product-item">
                                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                        <use xlink:href="#heart"></use>
                                    </svg></a>
                                <figure>
                                    <a href="product-detail.html" title="Office Space">
                                        <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=400&h=300&fit=crop"
                                            class="tab-image" alt="Office Space">
                                    </a>
                                </figure>
                                <h3>Office Space</h3>
                                <span class="qty">Victoria Island • 200sqm</span>
                                <div class="rating-block">
                                    <svg width="16" height="16" class="text-primary">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <span class="rating-text">4.8</span>
                                </div>
                                <span class="price">₦500,000/yr</span>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <span class="location text-muted small">
                                        <iconify-icon icon="mdi:map-marker"></iconify-icon> VI
                                    </span>
                                    <a href="product-detail.html" class="btn btn-primary btn-sm">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 8 -->
                        <div class="col">
                            <div class="product-item">
                                <span class="badge bg-success position-absolute m-3">New</span>
                                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                        <use xlink:href="#heart"></use>
                                    </svg></a>
                                <figure>
                                    <a href="product-detail.html" title="PlayStation 5">
                                        <img src="https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=400&h=300&fit=crop"
                                            class="tab-image" alt="PlayStation 5">
                                    </a>
                                </figure>
                                <h3>PlayStation 5</h3>
                                <span class="qty">Disc Version • 2 Controllers</span>
                                <div class="rating-block">
                                    <svg width="16" height="16" class="text-primary">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <span class="rating-text">4.9</span>
                                </div>
                                <span class="price">₦380,000</span>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <span class="location text-muted small">
                                        <iconify-icon icon="mdi:map-marker"></iconify-icon> Surulere
                                    </span>
                                    <a href="product-detail.html" class="btn btn-primary btn-sm">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / product-grid -->
                </div>

                <!-- Electronics Tab -->
                <div class="tab-pane fade" id="nav-electronics" role="tabpanel"
                    aria-labelledby="nav-electronics-tab">
                    <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                        <!-- Electronics Product 1 -->
                        <div class="col">
                            <div class="product-item">
                                <span class="badge bg-success position-absolute m-3">New</span>
                                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                        <use xlink:href="#heart"></use>
                                    </svg></a>
                                <figure>
                                    <a href="product-detail.html" title="Smart TV 55inch">
                                        <img src="https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=400&h=300&fit=crop"
                                            class="tab-image" alt="Smart TV 55inch">
                                    </a>
                                </figure>
                                <h3>Smart TV 55inch</h3>
                                <span class="qty">Samsung • 4K UHD</span>
                                <div class="rating-block">
                                    <svg width="16" height="16" class="text-primary">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <span class="rating-text">4.7</span>
                                </div>
                                <span class="price">₦320,000</span>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <span class="location text-muted small">
                                        <iconify-icon icon="mdi:map-marker"></iconify-icon> Lagos
                                    </span>
                                    <a href="product-detail.html" class="btn btn-primary btn-sm">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Electronics Product 2 -->
                        <div class="col">
                            <div class="product-item">
                                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                        <use xlink:href="#heart"></use>
                                    </svg></a>
                                <figure>
                                    <a href="product-detail.html" title="Wireless Headphones">
                                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=300&fit=crop"
                                            class="tab-image" alt="Wireless Headphones">
                                    </a>
                                </figure>
                                <h3>Wireless Headphones</h3>
                                <span class="qty">Sony • Noise Cancelling</span>
                                <div class="rating-block">
                                    <svg width="16" height="16" class="text-primary">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <span class="rating-text">4.6</span>
                                </div>
                                <span class="price">₦85,000</span>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <span class="location text-muted small">
                                        <iconify-icon icon="mdi:map-marker"></iconify-icon> Abuja
                                    </span>
                                    <a href="product-detail.html" class="btn btn-primary btn-sm">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicles Tab -->
                <div class="tab-pane fade" id="nav-vehicles" role="tabpanel" aria-labelledby="nav-vehicles-tab">
                    <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                        <!-- Vehicle Product 1 -->
                        <div class="col">
                            <div class="product-item">
                                <span class="badge bg-danger position-absolute m-3">Hot Deal</span>
                                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                        <use xlink:href="#heart"></use>
                                    </svg></a>
                                <figure>
                                    <a href="product-detail.html" title="Mercedes Benz 2019">
                                        <img src="https://images.unsplash.com/photo-1555215695-3004980ad54e?w=400&h=300&fit=crop"
                                            class="tab-image" alt="Mercedes Benz 2019">
                                    </a>
                                </figure>
                                <h3>Mercedes Benz 2019</h3>
                                <span class="qty">C-Class • 30,000km</span>
                                <div class="rating-block">
                                    <svg width="16" height="16" class="text-primary">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <span class="rating-text">4.9</span>
                                </div>
                                <span class="price">₦15,000,000</span>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <span class="location text-muted small">
                                        <iconify-icon icon="mdi:map-marker"></iconify-icon> Abuja
                                    </span>
                                    <a href="product-detail.html" class="btn btn-primary btn-sm">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Properties Tab -->
                <div class="tab-pane fade" id="nav-properties" role="tabpanel" aria-labelledby="nav-properties-tab">
                    <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                        <!-- Property Product 1 -->
                        <div class="col">
                            <div class="product-item">
                                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                        <use xlink:href="#heart"></use>
                                    </svg></a>
                                <figure>
                                    <a href="product-detail.html" title="Duplex for Sale">
                                        <img src="https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=400&h=300&fit=crop"
                                            class="tab-image" alt="Duplex for Sale">
                                    </a>
                                </figure>
                                <h3>Duplex for Sale</h3>
                                <span class="qty">5 Bedrooms • GRA</span>
                                <div class="rating-block">
                                    <svg width="16" height="16" class="text-primary">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <span class="rating-text">4.8</span>
                                </div>
                                <span class="price">₦45,000,000</span>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <span class="location text-muted small">
                                        <iconify-icon icon="mdi:map-marker"></iconify-icon> Benin
                                    </span>
                                    <a href="product-detail.html" class="btn btn-primary btn-sm">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>









    <style>
        :root {
            --dark: #333;
            --primary: #9ece61;
            --primary-dark: #74a534;
        }



        .timer {
            font-size: 1.2rem;
            margin: 1rem 0;
            color: var(--primary);
            font-weight: bold;
        }

        /* Popup Styles */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            backdrop-filter: blur(5px);
        }

        .popup-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .popup {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
            transform: scale(0.8);
            transition: transform 0.4s;
            overflow: hidden;
            color: var(--dark);
        }

        .popup-overlay.active .popup {
            transform: scale(1);
        }

        .popup-header {
            padding: 20px 25px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--primary);
            color: white;
        }

        .popup-title {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            font-size: 1.5rem;
            color: white;
            cursor: pointer;
            transition: background 0.2s;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .popup-body {
            padding: 25px;
        }

        .popup-icon {
            font-size: 4rem;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .popup-message {
            font-size: 2rem;
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
            text-transform: uppercase;
        }

        .popup-footer {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        .cookie-option {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
            justify-content: center;
        }

        .cookie-option input {
            width: 18px;
            height: 18px;
        }

        .popup-title {
            color: white;
        }
    </style>


    <!-- Popup -->
    <div class="popup-overlay" id="blankPopup">
        <div class="popup">
            <div class="popup-header">
                <h2 class="popup-title">Welcome to Agii.ng</h2>
                <button class="close-btn">&times;</button>
            </div>
            <div class="popup-body">
                <div class="popup-icon">
                    <i class="far fa-window-maximize"></i>
                </div>
                <h1 class="popup-message text-center">
                    Register and get 10% off your first order
                </h1>

                <button class="btn btn-primary w-100 mb-3">
                    Register Now
                </button>

                <div class="cookie-option">
                    <input type="checkbox" id="dontShowAgain">
                    <label for="dontShowAgain">Don't show this popup again for 30 minutes</label>
                </div>

                <div class="popup-footer d-none">
                    <button class="btn" id="popupCloseBtn">
                        <i class="fas fa-check"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get DOM elements
            const blankPopup = document.getElementById('blankPopup');

            const closeButtons = document.querySelectorAll('.close-btn');
            const popupCloseBtn = document.getElementById('popupCloseBtn');
            const dontShowAgainCheckbox = document.getElementById('dontShowAgain');




            // Cookie name
            const popupCookieName = 'popupShown';

            // Function to set a cookie
            function setCookie(name, value, minutes) {
                const date = new Date();
                date.setTime(date.getTime() + (minutes * 60 * 1000));
                const expires = "expires=" + date.toUTCString();
                document.cookie = name + "=" + value + ";" + expires + ";path=/";
            }

            // Function to get a cookie
            function getCookie(name) {
                const cookieName = name + "=";
                const decodedCookie = decodeURIComponent(document.cookie);
                const cookieArray = decodedCookie.split(';');

                for (let i = 0; i < cookieArray.length; i++) {
                    let cookie = cookieArray[i];
                    while (cookie.charAt(0) === ' ') {
                        cookie = cookie.substring(1);
                    }
                    if (cookie.indexOf(cookieName) === 0) {
                        return cookie.substring(cookieName.length, cookie.length);
                    }
                }
                return "";
            }

            // Function to delete a cookie
            function deleteCookie(name) {
                document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            }

            // Function to open a popup
            function openPopup() {
                blankPopup.classList.add('active');
                document.body.style.overflow = 'hidden';

            }

            // Function to close a popup
            function closePopup() {
                blankPopup.classList.remove('active');
                document.body.style.overflow = '';

                // If "don't show again" is checked, set a cookie
                if (dontShowAgainCheckbox.checked) {
                    setCookie(popupCookieName, 'true', 30); // 30 minutes
                }
                // Reset checkbox
                dontShowAgainCheckbox.checked = false;
            }

            // Check if popup should be shown
            function checkPopup() {
                const popupShown = getCookie(popupCookieName);

                if (popupShown === "") {
                    // No cookie found, show popup after 10 seconds
                    let countdown = 10;

                    const countdownInterval = setInterval(() => {
                        countdown--;
                        if (countdown <= 0) {
                            clearInterval(countdownInterval);
                            openPopup();

                        }
                    }, 1000);

                }
            }


            closeButtons.forEach(button => {
                button.addEventListener('click', closePopup);
            });

            popupCloseBtn.addEventListener('click', closePopup);

            // Close popup when clicking outside the content
            blankPopup.addEventListener('click', function(e) {
                if (e.target === this) {
                    closePopup();
                }
            });

            // Close popup with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && blankPopup.classList.contains('active')) {
                    closePopup();
                }
            });

            // Initialize popup check
            checkPopup();
        });
    </script>









    <footer class="py-5" style="background: linear-gradient(135deg, #90c74b 0%, #90c74b 100%);">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-menu">
                        <img src="{{ asset('images/Agiilogo2.png') }}" width="100" alt="logo" class="mb-3">
                        <p class="text-white-50 mb-4">Your trusted marketplace for buying and selling anything in
                            Nigeria. Quality products, great deals.</p>
                        <div class="social-links mt-4">
                            <ul class="d-flex list-unstyled gap-2">
                                <li>
                                    <a href="#" class="btn bg-white p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M15.12 5.32H17V2.14A26.11 26.11 0 0 0 14.26 2c-2.72 0-4.58 1.66-4.58 4.7v2.62H6.61v3.56h3.07V22h3.68v-9.12h3.06l.46-3.56h-3.52V7.05c0-1.05.28-1.73 1.76-1.73Z" />
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="btn bg-white p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M22.991 3.95a1 1 0 0 0-1.51-.86a7.48 7.48 0 0 1-1.874.794a5.152 5.152 0 0 0-3.374-1.242a5.232 5.232 0 0 0-5.223 5.063a11.032 11.032 0 0 1-6.814-3.924a1.012 1.012 0 0 0-.857-.365a.999.999 0 0 0-.785.5a5.276 5.276 0 0 0-.242 4.769l-.002.001a1.041 1.041 0 0 0-.496.89a3.042 3.042 0 0 0 .027.439a5.185 5.185 0 0 0 1.568 3.312a.998.998 0 0 0-.066.77a5.204 5.204 0 0 0 2.362 2.922a7.465 7.465 0 0 1-3.59.448A1 1 0 0 0 1.45 19.3a12.942 12.942 0 0 0 7.01 2.061a12.788 12.788 0 0 0 12.465-9.363a12.822 12.822 0 0 0 .535-3.646l-.001-.2a5.77 5.77 0 0 0 1.532-4.202Zm-3.306 3.212a.995.995 0 0 0-.234.702c.01.165.009.331.009.488a10.824 10.824 0 0 1-.454 3.08a10.685 10.685 0 0 1-10.546 7.93a10.938 10.938 0 0 1-2.55-.301a9.48 9.48 0 0 0 2.942-1.564a1 1 0 0 0-.602-1.786a3.208 3.208 0 0 1-2.214-.935q.224-.042.445-.105a1 1 0 0 0-.08-1.943a3.198 3.198 0 0 1-2.25-1.726a5.3 5.3 0 0 0 .545.046a1.02 1.02 0 0 0 .984-.696a1 1 0 0 0-.4-1.137a3.196 3.196 0 0 1-1.425-2.673c0-.066.002-.133.006-.198a13.014 13.014 0 0 0 8.21 3.48a1.02 1.02 0 0 0 .817-.36a1 1 0 0 0 .206-.867a3.157 3.157 0 0 1-.087-.729a3.23 3.23 0 0 1 3.226-3.226a3.184 3.184 0 0 1 2.345 1.02a.993.993 0 0 0 .921.298a9.27 9.27 0 0 0 1.212-.322a6.681 6.681 0 0 1-1.026 1.524Z" />
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="btn bg-white p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M23 9.71a8.5 8.5 0 0 0-.91-4.13a2.92 2.92 0 0 0-1.72-1A78.36 78.36 0 0 0 12 4.27a78.45 78.45 0 0 0-8.34.3a2.87 2.87 0 0 0-1.46.74c-.9.83-1 2.25-1.1 3.45a48.29 48.29 0 0 0 0 6.48a9.55 9.55 0 0 0 .3 2a3.14 3.14 0 0 0 .71 1.36a2.86 2.86 0 0 0 1.49.78a45.18 45.18 0 0 0 6.5.33c3.5.05 6.57 0 10.2-.28a2.88 2.88 0 0 0 1.53-.78a2.49 2.49 0 0 0 .61-1a10.58 10.58 0 0 0 .52-3.4c.04-.56.04-3.94.04-4.54ZM9.74 14.85V8.66l5.92 3.11c-1.66.92-3.85 1.96-5.92 3.08Z" />
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="btn bg-white p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M17.34 5.46a1.2 1.2 0 1 0 1.2 1.2a1.2 1.2 0 0 0-1.2-1.2Zm4.6 2.42a7.59 7.59 0 0 0-.46-2.43a4.94 4.94 0 0 0-1.16-1.77a4.7 4.7 0 0 0-1.77-1.15a7.3 7.3 0 0 0-2.43-.47C15.06 2 14.72 2 12 2s-3.06 0-4.12.06a7.3 7.3 0 0 0-2.43.47a4.78 4.78 0 0 0-1.77 1.15a4.7 4.7 0 0 0-1.15 1.77a7.3 7.3 0 0 0-.47 2.43C2 8.94 2 9.28 2 12s0 3.06.06 4.12a7.3 7.3 0 0 0 .47 2.43a4.7 4.7 0 0 0 1.15 1.77a4.78 4.78 0 0 0 1.77 1.15a7.3 7.3 0 0 0 2.43.47C8.94 22 9.28 22 12 22s3.06 0 4.12-.06a7.3 7.3 0 0 0 2.43-.47a4.7 4.7 0 0 0 1.77-1.15a4.85 4.85 0 0 0 1.16-1.77a7.59 7.59 0 0 0 .46-2.43c0-1.06.06-1.4.06-4.12s0-3.06-.06-4.12ZM20.14 16a5.61 5.61 0 0 1-.34 1.86a3.06 3.06 0 0 1-.75 1.15a3.19 3.19 0 0 1-1.15.75a5.61 5.61 0 0 1-1.86.34c-1 .05-1.37.06-4 .06s-3 0-4-.06a5.73 5.73 0 0 1-1.94-.3a3.27 3.27 0 0 1-1.1-.75a3 3 0 0 1-.74-1.15a5.54 5.54 0 0 1-.4-1.9c0-1-.06-1.37-.06-4s0-3 .06-4a5.54 5.54 0 0 1 .35-1.9A3 3 0 0 1 5 5a3.14 3.14 0 0 1 1.1-.8A5.73 5.73 0 0 1 8 3.86c1 0 1.37-.06 4-.06s3 0 4 .06a5.61 5.61 0 0 1 1.86.34a3.06 3.06 0 0 1 1.19.8a3.06 3.06 0 0 1 .75 1.1a5.61 5.61 0 0 1 .34 1.9c.05 1 .06 1.37.06 4s-.01 3-.06 4ZM12 6.87A5.13 5.13 0 1 0 17.14 12A5.12 5.12 0 0 0 12 6.87Zm0 8.46A3.33 3.33 0 1 1 15.33 12A3.33 3.33 0 0 1 12 15.33Z" />
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="btn bg-white p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M1.04 17.52q.1-.16.32-.02a21.308 21.308 0 0 0 10.88 2.9a21.524 21.524 0 0 0 7.74-1.46q.1-.04.29-.12t.27-.12a.356.356 0 0 1 .47.12q.17.24-.11.44q-.36.26-.92.6a14.99 14.99 0 0 1-3.84 1.58A16.175 16.175 0 0 1 12 22a16.017 16.017 0 0 1-5.9-1.09a16.246 16.246 0 0 1-4.98-3.07a.273.273 0 0 1-.12-.2a.215.215 0 0 1 .04-.12Zm6.02-5.7a4.036 4.036 0 0 1 .68-2.36A4.197 4.197 0 0 1 9.6 7.98a10.063 10.063 0 0 1 2.66-.66q.54-.06 1.76-.16v-.34a3.562 3.562 0 0 0-.28-1.72a1.5 1.5 0 0 0-1.32-.6h-.16a2.189 2.189 0 0 0-1.14.42a1.64 1.64 0 0 0-.62 1a.508.508 0 0 1-.4.46L7.8 6.1q-.34-.08-.34-.36a.587.587 0 0 1 .02-.14a3.834 3.834 0 0 1 1.67-2.64A6.268 6.268 0 0 1 12.26 2h.5a5.054 5.054 0 0 1 3.56 1.18a3.81 3.81 0 0 1 .37.43a3.875 3.875 0 0 1 .27.41a2.098 2.098 0 0 1 .18.52q.08.34.12.47a2.856 2.856 0 0 1 .06.56q.02.43.02.51v4.84a2.868 2.868 0 0 0 .15.95a2.475 2.475 0 0 0 .29.62q.14.19.46.61a.599.599 0 0 1 .12.32a.346.346 0 0 1-.16.28q-1.66 1.44-1.8 1.56a.557.557 0 0 1-.58.04q-.28-.24-.49-.46t-.3-.32a4.466 4.466 0 0 1-.29-.39q-.2-.29-.28-.39a4.91 4.91 0 0 1-2.2 1.52a6.038 6.038 0 0 1-1.68.2a3.505 3.505 0 0 1-2.53-.95a3.553 3.553 0 0 1-.99-2.69Zm3.44-.4a1.895 1.895 0 0 0 .39 1.25a1.294 1.294 0 0 0 1.05.47a1.022 1.022 0 0 0 .17-.02a1.022 1.022 0 0 1 .15-.02a2.033 2.033 0 0 0 1.3-1.08a3.13 3.13 0 0 0 .33-.83a3.8 3.8 0 0 0 .12-.73q.01-.28.01-.92v-.5a7.287 7.287 0 0 0-1.76.16a2.144 2.144 0 0 0-1.76 2.22Zm8.4 6.44a.626.626 0 0 1 .12-.16a3.14 3.14 0 0 1 .96-.46a6.52 6.52 0 0 1 1.48-.22a1.195 1.195 0 0 1 .38.02q.9.08 1.08.3a.655.655 0 0 1 .08.36v.14a4.56 4.56 0 0 1-.38 1.65a3.84 3.84 0 0 1-1.06 1.53a.302.302 0 0 1-.18.08a.177.177 0 0 1-.08-.02q-.12-.06-.06-.22a7.632 7.632 0 0 0 .74-2.42a.513.513 0 0 0-.08-.32q-.2-.24-1.12-.24q-.34 0-.8.04q-.5.06-.92.12a.232.232 0 0 1-.16-.04a.065.065 0 0 1-.02-.08a.153.153 0 0 1 .02-.06Z" />
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6">
                    <div class="footer-menu">
                        <h5 class="widget-title text-white">Quick Links</h5>
                        <ul class="menu-list list-unstyled">
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">About us</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Conditions</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Our Journals</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Careers</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Affiliate
                                    Programme</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Press</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6">
                    <div class="footer-menu">
                        <h5 class="widget-title text-white">Customer Service</h5>
                        <ul class="menu-list list-unstyled">
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">FAQ</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Contact</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Privacy Policy</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Returns &
                                    Refunds</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Cookie
                                    Guidelines</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Delivery
                                    Information</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6">
                    <div class="footer-menu">
                        <h5 class="widget-title text-white">Categories</h5>
                        <ul class="menu-list list-unstyled">
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Electronics</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Vehicles</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Properties</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Fashion</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Home & Garden</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="nav-link text-white-50 hover-text-white">Services</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-menu">
                        <h5 class="widget-title text-white">Newsletter</h5>
                        <p class="text-white-50">Subscribe to our newsletter to get updates about our latest products
                            and exclusive offers.</p>
                        <form class="d-flex mt-4 gap-0" role="newsletter">
                            <input class="form-control rounded-start rounded-0 border-0" type="email"
                                placeholder="Your email address" aria-label="Email Address"
                                style="background: rgba(255,255,255,0.1); color: white;">
                            <button class="btn btn-light rounded-end rounded-0 text-dark fw-bold" type="submit">
                                Subscribe
                            </button>
                        </form>
                        <div class="mt-3">
                            <small class="text-white-50">By subscribing, you agree to our Privacy Policy</small>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Section -->
            <div class="row mt-5 pt-4 border-top border-white-20">
                <div class="col-md-6">
                    <p class="text-white-50 mb-0">&copy; 2024 Agii Marketplace. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex gap-4 justify-content-md-end justify-content-start">
                        <a href="#" class="text-white-50 hover-text-white text-decoration-none">Terms of
                            Service</a>
                        <a href="#" class="text-white-50 hover-text-white text-decoration-none">Privacy
                            Policy</a>
                        <a href="#" class="text-white-50 hover-text-white text-decoration-none">Cookie
                            Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <style>
        .footer-menu .nav-link {
            transition: all 0.3s ease;
            padding: 4px 0;
        }

        .footer-menu .nav-link:hover {
            color: #fff !important;
            padding-left: 8px;
        }

        .hover-text-white:hover {
            color: #fff !important;
        }

        .btn-outline-light {
            border-color: rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: #fff;
            transform: translateY(-2px);
        }

        .border-white-20 {
            border-color: rgba(255, 255, 255, 0.2) !important;
        }

        .widget-title {
            position: relative;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .widget-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: #fff;
            border-radius: 2px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }
    </style>

    <script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
