FROM arm64v8/php:8.2-fpm-alpine

# defalt file path
ENV INPUT_FILE_PATH=inputFile

WORKDIR /var/www

RUN apk update && apk add --no-cache \
    bash \
    curl \
    git

ENTRYPOINT ["php-fpm"]
