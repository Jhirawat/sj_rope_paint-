@extends('layouts.app')
@section('content')<section class="section"><div class="container"><article class="card card-sj p-5"><h1>{{ $article->title() }}</h1><p class="text-muted">{{ $article->published_at?->format('d/m/Y') }}</p>{!! nl2br(e(app()->getLocale()==='en' && $article->content_en ? $article->content_en : $article->content_th)) !!}</article></div></section>@endsection
