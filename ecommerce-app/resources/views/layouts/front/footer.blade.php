<footer class="bg-brand-50 px-6 py-10 text-sm text-brand-999 rounded-3xl m-4">
    <div class="container mx-auto">
        <!-- フッターメイン部分: グリッドで複数カラム -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <!-- カテゴリー -->
            <div>
                <h3 class="font-bold mb-3">カテゴリー</h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('front.product.category', ['category' => 1]) }}" class="hover:text-brand-200">
                            Web 開発
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.product.category', ['category' => 2]) }}" class="hover:text-brand-200">
                            モバイル開発
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.product.category', ['category' => 3]) }}" class="hover:text-brand-200">
                            データサイエンス
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.product.category', ['category' => 4]) }}" class="hover:text-brand-200">
                            機械学習 / AI
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.product.category', ['category' => 5]) }}" class="hover:text-brand-200">
                            プログラミング言語
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.product.category', ['category' => 6]) }}" class="hover:text-brand-200">
                            ソフトウェア工学
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.product.category', ['category' => 7]) }}" class="hover:text-brand-200">
                            DevOps & インフラ
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.product.category', ['category' => 8]) }}" class="hover:text-brand-200">
                            セキュリティ
                        </a>
                    </li>
                </ul>
            </div>

            <!-- ログイン/会員登録 -->
            <div>
                @if (Route::has('login'))
                    @auth
                        <h3 class="font-bold mb-3">会員機能</h3>
                        <ul class="space-y-1">
                            <li><a href="#" class="hover:text-brand-200">お気に入り</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="hover:text-brand-200">ログアウト</button>
                                </form>
                            </li>
                        </ul>
                    @else
                        <h3 class="font-bold mb-3">会員機能</h3>
                        <ul class="space-y-1">
                            <li><a href="{{ route('login') }}" class="hover:text-brand-200">ログイン</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-brand-200">会員登録</a></li>
                        </ul>
                    @endauth
                @endif
            </div>
            <!-- カートを見る -->
            <div>
                <h3 class="font-bold mb-3">カートを見る</h3>
                <ul class="space-y-1">
                    <li><a href="{{ route('cart.index')}}" class="hover:text-brand-200">カート</a></li>
                </ul>
            </div>

            <!-- お問い合わせ -->
            <div>
                <h3 class="font-bold mb-3">ヘルプ</h3>
                <ul class="space-y-1">
                    <li><a href="#" class="hover:text-brand-200">お問合せ</a></li>
                </ul>
            </div>
        </div>

        <!-- 下部: ロゴ・その他リンク・コピーライト -->
        <div class="mt-10 flex flex-col md:flex-row items-center md:justify-between">
            <!-- ロゴと会社情報 -->
            <div class="flex items-center space-x-4 mb-4 md:mb-0">
                <!-- ロゴ画像を差し替えてください -->
                <img src="/images/team6-logo.png" alt="Team6" class="h-8">
                <div>
                    <p class="font-bold">Team6</p>
                </div>
            </div>

            <!-- 下部リンク -->
            <div class="flex items-center space-x-6 mb-4 md:mb-0 text-lg">
                <a href="#" class="hover:text-brand-200">🔝</a>
            </div>

            <!-- コピーライト -->
            <div class="text-xs text-brand-900">
                &copy; {{ date('Y') }} Team6 All Rights Reserved.
            </div>
        </div>
    </div>
</footer>