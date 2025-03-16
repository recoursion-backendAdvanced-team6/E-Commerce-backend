@props (['page', 'data'])

@switch($page)
    @case('dashboard')
    <p> {{$data['test'] ? $data['test'] : 'データがありません' }} </p>
        @break

    @case('product/create')
        <x-admin.dashboard-content.product-create :data="$data"/>
        @break

    @case('products')
        <x-admin.dashboard-content.products :data="$data"/>
        @break

    @case('orders')
        <x-admin.dashboard-content.orders :data="$data"/>
        @break

    @default
    <p>ページが見つかりません</p>
@endswitch