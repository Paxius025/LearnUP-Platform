@extends('layouts.app')
@include('components.navbar')

@foreach ($posts as $post)
    @include('components.post_card', ['post' => $post])
@endforeach

