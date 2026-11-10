@extends('layouts.' . $roleLayout)

@section('title', 'Pusat Notifikasi')

@section('breadcrumb-parent', 'Kotak Masuk')
@section('breadcrumb-current', 'Pusat Notifikasi')

@section('admin-content')
    @include('notifications._index_body')
@endsection

@section('guru-content')
    @include('notifications._index_body')
@endsection

@section('siswa-content')
    @include('notifications._index_body')
@endsection

@section('orang-tua-content')
    @include('notifications._index_body')
@endsection
