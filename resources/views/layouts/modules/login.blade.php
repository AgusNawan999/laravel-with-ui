@extends('layouts.app.auth')

@section('title')
  {{ Str::title('Emasin | Login') }}
@endsection

@section('content')
  <login-form />
@endsection
