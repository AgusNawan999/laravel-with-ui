@extends('layouts.app.app-login')

@section('title')
  {{ Str::title('Emasin | Login') }}
@endsection

@section('content')
  <login-form />
@endsection
