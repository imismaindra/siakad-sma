@extends('layouts.orang-tua')

@section('title', 'Pusat Notifikasi')

@section('breadcrumb-parent', 'Portal Wali Murid')
@section('breadcrumb-current', 'Notifikasi')

@section('orang-tua-content')
    @include('notifications._index_body')
@endsection
