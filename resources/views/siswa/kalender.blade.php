@extends('layouts.siswa')

@section('title', 'Kalender Akademik')

@section('breadcrumb-parent', 'Portal Siswa')
@section('breadcrumb-current', 'Kalender')

@section('siswa-content')
    @include('shared._kalender_body')
@endsection
