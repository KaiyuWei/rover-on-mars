FROM arm64v8/php:8.2-fpm-alpine

# defalt file path
ENV DEFAULT_INPUT_FILE_PATH=inputFile

WORKDIR /var/www

RUN apk update && apk add --no-cache \
    bash \
    curl \
    git \
    vim

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# create a non-root user and set permissions
RUN adduser --disabled-password --gecos "" myuser && chown -R myuser:myuser /var/www

# Copy all application files and set the appropriate permissions
COPY --chown=myuser:myuser . /var/www

# switch to non-root user
USER myuser

RUN composer install --no-dev --optimize-autoloader

# switch back to root user
USER root

ENTRYPOINT ["php-fpm"]
