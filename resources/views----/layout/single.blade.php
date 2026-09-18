<!DOCTYPE html>
<html lang="en">

<head>
    <title>iPhone 13 Pro Max - Agii Marketplace</title>
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
        .product-gallery-slider {
            height: 500px;
            border-radius: 16px;
            overflow: hidden;
        }

        .product-thumbnail-slider {
            margin-top: 20px;
            height: 100px;
        }

        .product-thumbnail-slider .swiper-slide {
            opacity: 0.6;
            transition: opacity 0.3s ease;
            cursor: pointer;
            border-radius: 12px;
            overflow: hidden;
        }

        .product-thumbnail-slider .swiper-slide-thumb-active {
            opacity: 1;
            border: 2px solid #90c74b;
        }

        .product-info-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
        }

        .seller-info {
            background: var(--light-primary-color);
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }

        .action-buttons .btn {
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .product-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            margin: 20px 0;
        }

        .product-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
        }

        .review-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--border-color);
        }

        .rating-stars {
            color: #90c74b;
        }

        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 30px;
        }

        .breadcrumb-item a {
            color: #90c74b;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #6c757d;
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

    <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasCart"
        aria-labelledby="My Cart">
        <div class="offcanvas-header justify-content-center">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="order-md-last">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-primary">Your cart</span>
                    <span class="badge bg-primary rounded-pill">3</span>
                </h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-sm">
                        <div>
                            <h6 class="my-0">Growers cider</h6>
                            <small class="text-body-secondary">Brief description</small>
                        </div>
                        <span class="text-body-secondary">$12</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-sm">
                        <div>
                            <h6 class="my-0">Fresh grapes</h6>
                            <small class="text-body-secondary">Brief description</small>
                        </div>
                        <span class="text-body-secondary">$8</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-sm">
                        <div>
                            <h6 class="my-0">Heinz tomato ketchup</h6>
                            <small class="text-body-secondary">Brief description</small>
                        </div>
                        <span class="text-body-secondary">$5</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total (USD)</span>
                        <strong>$20</strong>
                    </li>
                </ul>

                <button class="w-100 btn btn-primary btn-lg" type="submit">Continue to checkout</button>
            </div>
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
                        <li class="d-lg-none">
                            <a href="#" class="rounded-circle bg-light p-2 mx-1" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                                <svg width="24" height="24" viewBox="0 0 24 24">
                                    <use xlink:href="#cart"></use>
                                </svg>
                            </a>
                        </li>
                        <li class="d-lg-none">
                            <a href="#" class="rounded-circle bg-light p-2 mx-1" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasSearch" aria-controls="offcanvasSearch">
                                <svg width="24" height="24" viewBox="0 0 24 24">
                                    <use xlink:href="#search"></use>
                                </svg>
                            </a>
                        </li>
                    </ul>

                    <div class="cart text-end d-none d-lg-block dropdown">
                        <button class="border-0 bg-transparent d-flex flex-column gap-2 lh-1" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                            <span class="fs-6 text-muted dropdown-toggle">Your Cart</span>
                            <span class="cart-total fs-5 fw-bold">$1290.00</span>
                        </button>
                    </div>
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

                                <select class="filter-categories border-0 mb-0 me-5">
                                    <option>Shop by Departments</option>
                                    <option>Groceries</option>
                                    <option>Drinks</option>
                                    <option>Chocolates</option>
                                </select>

                                <ul
                                    class="navbar-nav justify-content-end menu-list list-unstyled d-flex gap-md-3 mb-0">
                                    <li class="nav-item active">
                                        <a href="#women" class="nav-link">Women</a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a href="#men" class="nav-link">Men</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#kids" class="nav-link">Kids</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#accessories" class="nav-link">Accessories</a>
                                    </li>
                                    <li class="nav-item dropdown">
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
                                    </li>
                                    <li class="nav-item">
                                        <a href="#brand" class="nav-link">Brand</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#sale" class="nav-link">Sale</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#blog" class="nav-link">Blog</a>
                                    </li>
                                </ul>

                            </div>

                        </div>
                </div>
            </div>
        </div>
    </header>


    <!-- Breadcrumb -->
    <section class="py-4">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/electronics') }}">Electronics</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/phones') }}">Phones</a></li>
                    <li class="breadcrumb-item active">iPhone 13 Pro Max</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Product Details Section -->
    <section class="py-5">
        <div class="container-fluid">
            <div class="row">
                <!-- Product Images -->
                <div class="col-lg-6">
                    <div class="product-gallery-slider swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=600&h=500&fit=crop"
                                    alt="iPhone 13 Pro Max Front" class="w-100 h-100 object-fit-cover rounded-4">
                            </div>
                            <div class="swiper-slide">
                                <img src="https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=600&h=500&fit=crop"
                                    alt="iPhone 13 Pro Max Back" class="w-100 h-100 object-fit-cover rounded-4">
                            </div>
                            <div class="swiper-slide">
                                <img src="https://images.unsplash.com/photo-1632661674599-31b7a0239676?w=600&h=500&fit=crop"
                                    alt="iPhone 13 Pro Max Side" class="w-100 h-100 object-fit-cover rounded-4">
                            </div>
                            <div class="swiper-slide">
                                <img src="https://images.unsplash.com/photo-1573148195900-7845dcb9b91f?w=600&h=500&fit=crop"
                                    alt="iPhone 13 Pro Max Accessories"
                                    class="w-100 h-100 object-fit-cover rounded-4">
                            </div>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>

                    <!-- Thumbnail Slider -->
                    <div class="product-thumbnail-slider swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=150&h=100&fit=crop"
                                    alt="Thumbnail 1" class="w-100 h-100 object-fit-cover">
                            </div>
                            <div class="swiper-slide">
                                <img src="https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=150&h=100&fit=crop"
                                    alt="Thumbnail 2" class="w-100 h-100 object-fit-cover">
                            </div>
                            <div class="swiper-slide">
                                <img src="https://images.unsplash.com/photo-1632661674599-31b7a0239676?w=150&h=100&fit=crop"
                                    alt="Thumbnail 3" class="w-100 h-100 object-fit-cover">
                            </div>
                            <div class="swiper-slide">
                                <img src="https://images.unsplash.com/photo-1573148195900-7845dcb9b91f?w=150&h=100&fit=crop"
                                    alt="Thumbnail 4" class="w-100 h-100 object-fit-cover">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    <div class="product-info-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-success fs-6">Available</span>
                            <button class="btn btn-outline-primary btn-bookmark">
                                <svg width="20" height="20" class="me-2">
                                    <use xlink:href="#heart"></use>
                                </svg>
                                Save for Later
                            </button>
                        </div>

                        <h1 class="display-5 fw-bold mb-3">iPhone 13 Pro Max</h1>

                        <div class="product-meta">
                            <div class="product-meta-item">
                                <svg width="18" height="18" class="text-primary">
                                    <use xlink:href="#star-solid"></use>
                                </svg>
                                <span class="fw-semibold">4.8 (128 reviews)</span>
                            </div>
                            <div class="product-meta-item">
                                <iconify-icon icon="mdi:eye-outline" width="18"></iconify-icon>
                                <span>2.5k views</span>
                            </div>
                            <div class="product-meta-item">
                                <iconify-icon icon="mdi:map-marker" width="18"></iconify-icon>
                                <span>Lekki, Lagos</span>
                            </div>
                        </div>

                        <div class="price-section mb-4">
                            <h2 class="text-primary fw-bold">₦450,000</h2>
                            <del class="text-muted fs-5">₦520,000</del>
                            <span class="badge bg-danger ms-2">Save ₦70,000</span>
                        </div>

                        <!-- Product Specifications -->
                        <div class="specifications mb-4">
                            <h5 class="fw-semibold mb-3">Specifications</h5>
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Storage:</small>
                                    <div class="fw-semibold">256GB</div>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Color:</small>
                                    <div class="fw-semibold">Sierra Blue</div>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Condition:</small>
                                    <div class="fw-semibold">Excellent</div>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Warranty:</small>
                                    <div class="fw-semibold">3 Months</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons mb-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <a href="https://wa.me/2349012345678?text=Hi, I'm interested in the iPhone 13 Pro Max you listed on Agii"
                                        class="btn btn-success w-100" target="_blank">
                                        <iconify-icon icon="logos:whatsapp-icon" width="20"
                                            class="me-2"></iconify-icon>
                                        Chat on WhatsApp
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="tel:+2349012345678" class="btn btn-primary w-100">
                                        <iconify-icon icon="mdi:phone" width="20" class="me-2"></iconify-icon>
                                        Call Vendor
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Seller Info -->
                        <div class="seller-info">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=50&h=50&fit=crop&crop=face"
                                    alt="Seller" class="rounded-circle me-3" width="50" height="50">
                                <div>
                                    <h6 class="mb-1 fw-semibold">James Adekunle</h6>
                                    <div class="rating-stars small">
                                        <svg width="16" height="16">
                                            <use xlink:href="#star-solid"></use>
                                        </svg>
                                        <span class="text-muted ms-1">4.9 • 89 reviews</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="fw-bold">89%</div>
                                    <small class="text-muted">Response Rate</small>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold">1hr</div>
                                    <small class="text-muted">Avg. Response</small>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold">2yrs</div>
                                    <small class="text-muted">On Agii</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Description & Details -->
    <section class="py-5 bg-light">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Product Description -->
                    <div class="product-info-card mb-4">
                        <h3 class="section-title">Product Description</h3>
                        <div class="product-description">
                            <p>Brand new iPhone 13 Pro Max in perfect condition. Purchased 3 months ago and well
                                maintained. Comes with original box and all accessories.</p>

                            <h5 class="mt-4 mb-3">Key Features:</h5>
                            <ul>
                                <li>6.7-inch Super Retina XDR display with ProMotion</li>
                                <li>A15 Bionic chip with 6-core CPU</li>
                                <li>Pro camera system with 12MP Wide, Ultra Wide, and Telephoto</li>
                                <li>Cinematic mode with shallow depth of field</li>
                                <li>5G capable for superfast downloads</li>
                                <li>Face ID for secure authentication</li>
                                <li>All-day battery life</li>
                            </ul>

                            <h5 class="mt-4 mb-3">What's Included:</h5>
                            <ul>
                                <li>iPhone 13 Pro Max 256GB</li>
                                <li>Original USB-C to Lightning Cable</li>
                                <li>Documentation and SIM ejector tool</li>
                                <li>Original box</li>
                                <li>Screen protector (already applied)</li>
                                <li>Clear protective case</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    <div class="product-info-card">
                        <h3 class="section-title">Customer Reviews (128)</h3>

                        <!-- Review Summary -->
                        <div class="row mb-4">
                            <div class="col-md-4 text-center">
                                <div class="display-4 fw-bold text-primary">4.8</div>
                                <div class="rating-stars mb-2">
                                    <svg width="20" height="20">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <svg width="20" height="20">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <svg width="20" height="20">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <svg width="20" height="20">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                    <svg width="20" height="20">
                                        <use xlink:href="#star-solid"></use>
                                    </svg>
                                </div>
                                <small class="text-muted">Based on 128 reviews</small>
                            </div>
                            <div class="col-md-8">
                                <!-- Rating distribution would go here -->
                            </div>
                        </div>

                        <!-- Individual Reviews -->
                        <div class="review-card">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=40&h=40&fit=crop&crop=face"
                                        alt="User" class="rounded-circle me-3" width="40" height="40">
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Sarah Johnson</h6>
                                        <div class="rating-stars small">
                                            <svg width="16" height="16">
                                                <use xlink:href="#star-solid"></use>
                                            </svg>
                                            <svg width="16" height="16">
                                                <use xlink:href="#star-solid"></use>
                                            </svg>
                                            <svg width="16" height="16">
                                                <use xlink:href="#star-solid"></use>
                                            </svg>
                                            <svg width="16" height="16">
                                                <use xlink:href="#star-solid"></use>
                                            </svg>
                                            <svg width="16" height="16">
                                                <use xlink:href="#star-solid"></use>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">2 days ago</small>
                            </div>
                            <p class="mb-0">Excellent seller! The phone is exactly as described. Fast delivery and
                                great communication. Highly recommended!</p>
                        </div>

                        <div class="review-card">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&h=40&fit=crop&crop=face"
                                        alt="User" class="rounded-circle me-3" width="40" height="40">
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Mike Adebayo</h6>
                                        <div class="rating-stars small">
                                            <svg width="16" height="16">
                                                <use xlink:href="#star-solid"></use>
                                            </svg>
                                            <svg width="16" height="16">
                                                <use xlink:href="#star-solid"></use>
                                            </svg>
                                            <svg width="16" height="16">
                                                <use xlink:href="#star-solid"></use>
                                            </svg>
                                            <svg width="16" height="16">
                                                <use xlink:href="#star-solid"></use>
                                            </svg>
                                            <svg width="16" height="16">
                                                <use xlink:href="#star-outline"></use>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">1 week ago</small>
                            </div>
                            <p class="mb-0">Good product and honest seller. Phone works perfectly. Delivery was a bit
                                delayed but seller communicated well throughout.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Seller Location & Info -->
                    <div class="product-info-card mb-4">
                        <h3 class="section-title">Seller Information</h3>
                        <div class="mb-3">
                            <h6 class="fw-semibold">Location</h6>
                            <div class="d-flex align-items-center text-muted">
                                <iconify-icon icon="mdi:map-marker" class="me-2"></iconify-icon>
                                <span>Lekki Phase 1, Lagos, Nigeria</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-semibold">Member Since</h6>
                            <p class="text-muted mb-0">March 2022</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-semibold">Response Time</h6>
                            <p class="text-muted mb-0">Usually within 1 hour</p>
                        </div>
                        <div>
                            <h6 class="fw-semibold">Items for Sale</h6>
                            <p class="text-muted mb-0">24 active listings</p>
                        </div>
                    </div>

                    <!-- Safety Tips -->
                    <div class="product-info-card">
                        <h3 class="section-title">Safety Tips</h3>
                        <div class="alert alert-warning">
                            <small>
                                <strong>Stay Safe:</strong>
                                <ul class="mt-2 mb-0 ps-3">
                                    <li>Meet in a public place</li>
                                    <li>Inspect the item before paying</li>
                                    <li>Never pay in advance</li>
                                    <li>Avoid sharing personal information</li>
                                    <li>Trust your instincts</li>
                                </ul>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Similar Products -->
    <section class="py-5">
        <div class="container-fluid">
            <h2 class="section-title mb-4">You May Also Like</h2>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                <!-- Similar Product 1 -->
                <div class="col">
                    <div class="product-item">
                        <span class="badge bg-success position-absolute m-3">New</span>
                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                <use xlink:href="#heart"></use>
                            </svg></a>
                        <figure>
                            <a href="product-detail.html" title="Samsung Galaxy S22 Ultra">
                                <img src="https://images.unsplash.com/photo-1651055007347-316b759b60e9?w=400&h=300&fit=crop"
                                    class="tab-image" alt="Samsung Galaxy S22 Ultra">
                            </a>
                        </figure>
                        <h3>Samsung Galaxy S22 Ultra</h3>
                        <span class="qty">512GB • Like New</span>
                        <div class="rating-block">
                            <svg width="16" height="16" class="text-primary">
                                <use xlink:href="#star-solid"></use>
                            </svg>
                            <span class="rating-text">4.7</span>
                        </div>
                        <span class="price">₦380,000</span>
                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <span class="location text-muted small">
                                <iconify-icon icon="mdi:map-marker"></iconify-icon> Ikeja
                            </span>
                            <a href="product-detail.html" class="btn btn-primary btn-sm">View Product</a>
                        </div>
                    </div>
                </div>

                <!-- Similar Product 2 -->
                <div class="col">
                    <div class="product-item">
                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                <use xlink:href="#heart"></use>
                            </svg></a>
                        <figure>
                            <a href="product-detail.html" title="Google Pixel 7 Pro">
                                <img src="https://images.unsplash.com/photo-1662948991585-0a37ec2d6c1a?w=400&h=300&fit=crop"
                                    class="tab-image" alt="Google Pixel 7 Pro">
                            </a>
                        </figure>
                        <h3>Google Pixel 7 Pro</h3>
                        <span class="qty">256GB • Excellent</span>
                        <div class="rating-block">
                            <svg width="16" height="16" class="text-primary">
                                <use xlink:href="#star-solid"></use>
                            </svg>
                            <span class="rating-text">4.6</span>
                        </div>
                        <span class="price">₦320,000</span>
                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <span class="location text-muted small">
                                <iconify-icon icon="mdi:map-marker"></iconify-icon> Victoria Island
                            </span>
                            <a href="product-detail.html" class="btn btn-primary btn-sm">View Product</a>
                        </div>
                    </div>
                </div>

                <!-- Add more similar products as needed -->
            </div>
        </div>
    </section>

    <!-- Footer (Same as home page) -->
    <footer class="py-5" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
        <!-- Your footer code from home page here -->
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.7/dist/iconify-icon.min.js"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <script>
        // Initialize Swiper
        var galleryThumbs = new Swiper('.product-thumbnail-slider', {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });

        var galleryTop = new Swiper('.product-gallery-slider', {
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            thumbs: {
                swiper: galleryThumbs
            }
        });

        // Bookmark functionality
        document.querySelector('.btn-bookmark').addEventListener('click', function() {
            this.classList.toggle('btn-primary');
            this.classList.toggle('btn-outline-primary');

            if (this.classList.contains('btn-primary')) {
                this.innerHTML =
                    '<svg width="20" height="20" class="me-2"><use xlink:href="#heart"></use></svg> Saved';
            } else {
                this.innerHTML =
                    '<svg width="20" height="20" class="me-2"><use xlink:href="#heart"></use></svg> Save for Later';
            }
        });
    </script>
</body>

</html>
