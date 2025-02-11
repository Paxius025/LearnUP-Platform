<button id="bookmark-button-{{ $post->id }}"
    class="absolute top-3 right-3 p-2 transition-transform duration-300 ease-in-out transform"
    onclick="toggleBookmark({{ $post->id }})">
    <svg id="bookmark-icon-{{ $post->id }}" xmlns="http://www.w3.org/2000/svg"
        class="w-8 h-8 stroke-gray-800 hover:scale-110 transition"
        viewBox="0 0 24 24"
        fill="{{ $isBookmarked ? 'red' : 'white' }}">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v18l7-5 7 5V3z" />
    </svg>
</button>
