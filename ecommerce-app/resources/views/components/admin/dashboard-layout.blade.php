<x-front-layout>

    <div class="grid grid-cols-12 max-w-7xl">
        <div class="w-auto mr-2 col-span-2  xl:col-span-1 ">
            <aside>
            <x-sidebar/>
            </aside>
        </div>
        <div class="border-s-2 pl-5 h-auto  col-span-10 xl:col-span-11">
            {{ $slot  }}
        </div>
    </div>

</x-front-layout>
