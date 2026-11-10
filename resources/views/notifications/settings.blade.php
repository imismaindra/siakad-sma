@extends('layouts.' . $roleLayout)

@section('title', 'Pengaturan Notifikasi')

@section('breadcrumb-parent', 'Kotak Masuk')
@section('breadcrumb-current', 'Pengaturan Notifikasi')

@section('admin-content')
    @include('notifications._settings_body')
@endsection

@section('guru-content')
    @include('notifications._settings_body')
@endsection

@section('siswa-content')
    @include('notifications._settings_body')
@endsection

@section('orang-tua-content')
    @include('notifications._settings_body')
@endsection
