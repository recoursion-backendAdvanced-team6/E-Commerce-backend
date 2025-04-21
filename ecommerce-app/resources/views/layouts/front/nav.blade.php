<nav class="bg-brand-50 shadow" x-data="{ open: false }">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        <!-- ロゴ部分 -->
        <a href="{{ route('front.home') }}" class="hover:text-brand-200 transition-colors">
            <div class="flex items-center space-x-2">
                <img src="/images/team6-logo.png" alt="Team6" class="h-8 rounded-2xl">
                <span class="text-xl font-bold text-brand-900">Team6</span>
            </div>
        </a>

        <!-- ハンバーガーメニュー（モバイル用） -->
        <button @click="open = !open" class="md:hidden text-brand-900 focus:outline-none">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <!-- メニュー（PC用） -->
        <ul class="hidden md:flex items-center space-x-6 text-brand-900">
            <li><a href="{{ route('front.products') }}" class="hover:text-brand-200 transition-colors">一覧</a></li>
            <li><a href="{{ route('cart.index') }}" class="hover:text-brand-200 transition-colors">カート</a></li>
            @if (Route::has('login'))
                @auth
                    <li><a href="{{ route('mypage') }}" class="hover:text-brand-200 transition-colors">マイページ</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="hover:text-brand-200 transition-colors">ログアウト</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="hover:text-brand-200 transition-colors">ログイン</a></li>
                    @if (Route::has('register'))
                        <li><a href="{{ route('register') }}" class="hover:text-brand-200 transition-colors">会員登録</a></li>
                    @endif
                @endauth
            @endif
        </ul>
    </div>

    <!-- モバイルメニュー -->
    <div x-show="open" class="md:hidden px-4 pb-4">
        <ul class="flex flex-col items-end space-y-2 text-brand-900 text-right">
            <li><a href="{{ route('front.products') }}" class="block hover:text-brand-200">一覧</a></li>
            <li><a href="{{ route('cart.index') }}" class="block hover:text-brand-200">カート</a></li>
            @if (Route::has('login'))
                @auth
                    <li><a href="{{ route('mypage') }}" class="block hover:text-brand-200">マイページ</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block hover:text-brand-200">ログアウト</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="block hover:text-brand-200">ログイン</a></li>
                    @if (Route::has('register'))
                        <li><a href="{{ route('register') }}" class="block hover:text-brand-200">会員登録</a></li>
                    @endif
                @endauth
            @endif
        </ul>
    </div>
</nav>
