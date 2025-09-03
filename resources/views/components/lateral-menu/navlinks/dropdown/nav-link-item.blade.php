<a href="{{ $href }}"
   class="w-full p-2 block hover:text-blue-500 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition duration-150 ease-in-out {{ $attributes->get('class') }}"
   {{ $attributes }}>
    {{ $slot }}
</a>