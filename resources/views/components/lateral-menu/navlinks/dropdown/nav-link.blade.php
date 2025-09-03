<div x-data="{ open: false }" class="w-full">
    <button @click="open = !open" class="flex p-2 text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:bg-gray-700 transition duration-150 ease-in-out" :class="{'text-black': open}">
        <span>{{ $title }}</span>
    </button>
    <ul x-show="open" x-transition class="pl-4 -mt-4 space-y-1 text-sm text-gray-500">
        {{ $slot }}
    </ul>
</div>