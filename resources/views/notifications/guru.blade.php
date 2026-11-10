@extends('layouts.guru')

@section('title', 'Pusat Notifikasi')

@section('breadcrumb-parent', 'Portal Guru')
@section('breadcrumb-current', 'Notifikasi')

@section('guru-content')
    @include('notifications._index_body')
@endsection
