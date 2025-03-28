FROM php:8.2-fpm

# パッケージ情報の更新と必要なライブラリのインストール
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# PHP 拡張のインストール（pdo, pdo_mysql, bcmath を含む）
RUN docker-php-ext-install pdo pdo_mysql bcmath

# Composer の最新バージョンをコピー
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html