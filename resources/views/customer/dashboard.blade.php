@extends('layout.layout')
@section('content')
    <div class="container">
        <h1>Welcome, {{ Auth::user()->first_name }} ({{ Auth::user()->user_type }})</h1>
    </div>
@endsection
