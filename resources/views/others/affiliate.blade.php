@extends('layout.layout')
@section('title', 'Become an Affiliate - Earn With Agii')

@section('content')

    <main class="main">
        <div class="bg-white">
            <div class="d-flex justify-content-center align-items-center" style="height: 10vh;">
                <h3 class="page-title text-black">Become an Affiliate</h3>
            </div>

            @include('others.partials.affiliate.hero')
        </div>

        @include('others.partials.affiliate.how-it-works', [
            'howItWorks' => config('affiliate.how_it_works'),
            'benefits' => config('affiliate.benefits'),
        ])

        @include('others.partials.affiliate.reasons', [
            'reasons' => config('affiliate.reasons'),
        ])

        @include('others.partials.affiliate.cta')
    </main>

@endsection
