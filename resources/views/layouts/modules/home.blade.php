@extends('layouts.app.app-home')

@section('title')
  {{ Str::title('Beranda') }}
@endsection

@section('content')
  <main-app key="home-app"></main-app>
@endsection
