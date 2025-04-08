<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? env('APP_NAME') }}</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <!-- ヘッダー -->
    @include('layouts.front.nav')

    <!-- メインコンテンツ -->
    <main class="grow container mx-auto px-4 py-8">
        {{ $slot }}
    </main>

    <!-- フッター -->
    @include('layouts.front.footer')

    @isset($js)
        <script>
            {{ $js }}
        </script>
    @endisset

    @vite('resources/js/app.js')
    @stack('scripts')
</body>

</html>
