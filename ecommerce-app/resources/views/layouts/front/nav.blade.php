<nav class="bg-brand-50 shadow">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        <!-- ロゴ部分 -->
        <a href="{{ route('front.home') }}" class="hover:text-brand-200 transition-colors">
            <div class="flex items-center space-x-2">
                <!-- ロゴ画像を差し替えてください -->
                <img src="/images/team6-logo.png" alt="Team6" class="h-8 rounded-2xl">
                <span class="text-xl font-bold text-brand-900">Team6</span>
            </div>
        </a>

        <!-- ナビメニュー -->
        <ul class="flex items-center space-x-6 text-brand-900">
            <li>
                <a href="{{ route('front.products') }}" class="hover:text-brand-200 transition-colors">
                    一覧!!
                </a>
            </li>
            <li>
                <a href="{{ route('front.products') }}" class="hover:text-brand-200 transition-colors">
                    検索
                </a>
            </li>
            <li>
                <a href="{{ route('cart.index') }}" class="hover:text-brand-200 transition-colors">
                    カート
                </a>
            </li>
            @if (Route::has('login'))
                @auth
                    <li>
                        <a href="{{ route('mypage') }}" class="hover:text-brand-200 transition-colors">
                            マイページ
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-danger">ログアウト</button>
                        </form>
                    </li>
                @else
                    <li>
                        <a href="{{ route('login') }}" class="hover:text-brand-200 transition-colors">
                            ログイン
                        </a>
                    </li>
                    @if (Route::has('register'))
                        <li>
                            <a href="{{ route('register') }}" class="hover:text-brand-200 transition-colors">
                                会員登録
                            </a>
                        </li>
                    @endif
                @endauth
            @endif
        </ul>
    </div>
</nav>
