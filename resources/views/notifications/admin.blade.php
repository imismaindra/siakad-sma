@extends('layouts.admin')

@section('title', 'Pusat Notifikasi')

@section('breadcrumb-parent', 'Akun & Sistem')
@section('breadcrumb-current', 'Notifikasi')

@section('admin-content')
    @include('notifications._index_body')
@endsection
