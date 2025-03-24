<ul >
    @foreach ($menuItems as $item)
        <li class="m-1"> <a href="{{ $item['url'] }}" > {{ $item['label']  }} </a></li>
    @endforeach
</ul>