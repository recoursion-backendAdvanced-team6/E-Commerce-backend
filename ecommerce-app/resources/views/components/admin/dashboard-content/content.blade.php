@props (['page', 'data']);

<p>admin/dashboard-content.cotent</p>
@if ($page == '')
    <p> home </p>

@elseif($page == 'product/create')
    <x-admin.dashboard-content.product-create :data="data"/>

@elseif($page == 'products')
    <x-admin.dashboard-content.products :data="data"/>

@elseif($page == 'orders')
    <x-admin.dashboard-content.orders :data="data"/>
