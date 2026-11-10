@extends('layouts.siswa')

@section('title', 'Pusat Notifikasi')

@section('breadcrumb-parent', 'Portal Siswa')
@section('breadcrumb-current', 'Notifikasi')

@section('siswa-content')
    @include('notifications._index_body')
@endsection
