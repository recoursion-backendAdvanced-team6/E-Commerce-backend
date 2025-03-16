<x-layouts.admin.dashboard>

    <aside>
    <x-sidebar :page="$page"/>
    </side>
    <x-admin.dashboard-content.content :page="$page" :data="$data"/>
</x-layouts.admin.dashboard>