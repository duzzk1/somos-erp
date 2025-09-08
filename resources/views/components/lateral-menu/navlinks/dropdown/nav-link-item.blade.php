<a href="{{ $href }}"
   class="w-full p-2 block hover:text-blue-500 text-white transition duration-150 ease-in-out {{ $attributes->get('class') }}"
   {{ $attributes }}>
    {{ $slot }}
</a>