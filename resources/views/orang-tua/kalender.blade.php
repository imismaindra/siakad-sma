@extends('layouts.orang-tua')

@section('title', 'Kalender Akademik')

@section('breadcrumb-parent', 'Informasi Sekolah')
@section('breadcrumb-current', 'Kalender')

@section('orang-tua-content')
    @include('shared._kalender_body')
@endsection
