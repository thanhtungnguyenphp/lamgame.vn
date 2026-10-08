@extends('shop::seller.layouts.master')

@section('page_title', __('lamgame.seller.register_title'))

@section('content')
@include('shop::seller.register-form')
@endsection
