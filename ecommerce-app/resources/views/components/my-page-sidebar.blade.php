<aside class="w-full md:w-64 bg-gray-100 border-r border-gray-200">
    <div class="p-4 flex flex-row md:flex-col items-center md:items-start space-x-4 md:space-x-0">
        <a href="{{ route('mypage') }}">
            <h2 class="text-lg font-bold md:mb-4">マイページ</h2>
        </a>
    </div>

    <nav class="px-4 pb-4 md:pb-0 md:pt-0 flex flex-row md:flex-col space-x-4 md:space-x-0 md:space-y-2">
        {{-- お気に入り一覧 --}}
        <a href="{{ route('favorites') }}"
           class="block py-2 text-gray-700 hover:bg-gray-200 rounded px-2 text-sm md:text-base">
            お気に入り一覧
        </a>
        {{-- 購入履歴 --}}
        <a href="{{ route('orders') }}"
           class="block py-2 text-gray-700 hover:bg-gray-200 rounded px-2 text-sm md:text-base">
            購入履歴一覧
        </a>
        {{-- プロフィール --}}
        <a href="{{ route('profile.edit') }}"
           class="block py-2 text-gray-700 hover:bg-gray-200 rounded px-2 text-sm md:text-base">
            プロフィール
        </a>
        {{-- ログアウト --}}
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit"
                    class="block w-full text-left py-2 text-gray-700 hover:bg-gray-200 rounded px-2 text-sm md:text-base">
                ログアウト
            </button>
        </form>
    </nav>
</aside>
