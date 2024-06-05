FROM arm64v8/php:8.2-fpm-alpine

WORKDIR /var/www

RUN apk update && apk add --no-cache \
    bash \
    curl \
    git
