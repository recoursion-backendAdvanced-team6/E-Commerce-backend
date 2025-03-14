<p>sideber</p>
<ul>
    @foreach ($menuItems as $item)
        <li> <a href="{{ $item['url'] }}" > {{ $item['label']  }} </a></li>
    @endforeach
</ul>