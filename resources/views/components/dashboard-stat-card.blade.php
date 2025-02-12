@php
    $colors = [
        'green' => 'bg-green-500 border-green-600 text-white',
        'yellow' => 'bg-yellow-400 border-yellow-500 text-white',
        'blue' => 'bg-blue-500 border-blue-600 text-white',
        'red' => 'bg-red-500 border-red-600 text-white',
    ];
@endphp

<div class="p-6 {{ $colors[$color] }} shadow-xl rounded-lg text-center">
    <h3 class="text-lg font-bold flex justify-center items-center">
        {{ $icon }} {{ $title }}
    </h3>
    <p class="text-2xl font-bold mt-2">{{ $count }}</p>
</div>
