@extends('layouts.guru')

@section('title', 'Kalender Akademik')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Kalender')

@section('guru-content')
    @include('shared._kalender_body')
@endsection
