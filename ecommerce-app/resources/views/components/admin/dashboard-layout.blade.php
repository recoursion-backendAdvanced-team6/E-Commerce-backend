<x-front-layout>

    <div class="grid grid-cols-12 grid-rows-1  max-w-7xl">
        <div class="w-auto mr-2 col-span-2  xl:col-span-1 ">
            <aside>
            <x-sidebar/>
            </aside>
        </div>
        <div class="border-s-2 pl-5 h-auto w-full col-span-9">
            {{ $slot  }}
        </div>
    </div>

</x-front-layout>
