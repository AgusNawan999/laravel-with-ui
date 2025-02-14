<?php
  use Symfony\Component\HttpKernel\Exception\HttpException;
  $title = 'Not Found';
  $code = '404';
  $message = 'Not Found';
  if(json_decode($exception->getMessage()) != null){
    $data = json_decode($exception->getMessage());
    $title = $data->title;
    $code = $data->code;
    $message = $data->message;
  }

?>

@extends('errors::minimal')

@section('title', __($title))
@section('code', $code)
@section('message', __($message))
