@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">รายละเอียดโพสต์</h2>

        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-xl font-semibold">{{ $post->title }}</h3>
            <p class="text-gray-600 text-sm">โดย {{ $post->user->name }} | สร้างเมื่อ {{ $post->created_at->format('d/m/Y') }}</p>
            <hr class="my-4">

            <div class="prose max-w-full">
                {!! $post->content !!}
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.manage.posts') }}" class="text-blue-500 underline">🔙 กลับไปหน้าจัดการโพสต์</a>
            </div>
        </div>
    </div>
@endsection
