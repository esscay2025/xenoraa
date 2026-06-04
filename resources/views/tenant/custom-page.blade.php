@extends('layouts.app')
@section('title', $page->meta_title ?: $page->title . ' — ' . $siteName)
@section('meta_description', $page->meta_desc ?: '')

@section('content')
<div style="max-width:900px;margin:4rem auto;padding:0 1.5rem;">
    <h1 style="font-size:2.25rem;font-weight:800;margin:0 0 0.5rem;color:var(--text-primary, #fff);">{{ $page->title }}</h1>
    <div style="width:60px;height:4px;background:{{ $accent }};border-radius:2px;margin-bottom:2rem;"></div>
    <div class="custom-page-content" style="color:var(--text-secondary, #a0a0a0);line-height:1.8;font-size:1rem;">
        {!! $page->content !!}
    </div>
</div>
<style>
.custom-page-content h1,.custom-page-content h2,.custom-page-content h3,.custom-page-content h4 { color:var(--text-primary,#fff); margin:1.5rem 0 0.75rem; }
.custom-page-content p { margin:0 0 1rem; }
.custom-page-content ul,.custom-page-content ol { padding-left:1.5rem; margin:0 0 1rem; }
.custom-page-content li { margin-bottom:0.35rem; }
.custom-page-content a { color:{{ $accent }}; text-decoration:underline; }
.custom-page-content blockquote { border-left:3px solid {{ $accent }};padding-left:1rem;margin:1rem 0;opacity:0.8; }
.custom-page-content code { background:rgba(255,255,255,0.08);padding:0.15rem 0.4rem;border-radius:4px;font-size:0.875rem; }
.custom-page-content pre { background:rgba(255,255,255,0.05);padding:1rem;border-radius:8px;overflow-x:auto;margin:1rem 0; }
.custom-page-content img { max-width:100%;border-radius:8px;margin:1rem 0; }
.custom-page-content table { width:100%;border-collapse:collapse;margin:1rem 0; }
.custom-page-content th,.custom-page-content td { padding:0.5rem 0.75rem;border:1px solid rgba(255,255,255,0.1);text-align:left; }
.custom-page-content th { background:rgba(255,255,255,0.05);font-weight:700; }
</style>
@endsection
