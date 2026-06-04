@extends('layouts.app')

@section('title', ($tenant->site_title ?? $tenant->name) . ' — ' . config('xenoraa.platform_name', 'Xenoraa'))
@section('meta_description', $tenant->bio ?? 'Professional profile on Xenoraa')

@section('content')
{{-- This renders the existing portfolio home view but scoped to the tenant --}}
@php
    // Override app name with tenant's site title
    config(['app.name' => $tenant->site_title ?? $tenant->name]);
@endphp

{{-- Reuse the existing portfolio home view --}}
@include('portfolio.home')
@endsection
