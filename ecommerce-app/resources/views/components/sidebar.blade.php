<ul >
    @foreach ($menuItems as $item)
        @if($item['label'] === 'ログアウト')
            <li>
                <form method="POST" action="{{ $item['url'] }}">
                    @csrf
                    <button type="submit" class="w-full text-left  text-red-600  rounded-md transition">
                        {{ $item['label'] }}
                    </button>
                </form>
            </li>
        @else
        <li class="m-1"> <a href="{{ $item['url'] }}" > {{ $item['label']  }} </a></li>
        @endif
    @endforeach

</ul>