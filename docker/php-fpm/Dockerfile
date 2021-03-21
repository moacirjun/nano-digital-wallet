FROM php:7.3-fpm-alpine

RUN apk add --no-cache \
		acl \
		file \
		gettext \
		git \
		mariadb-client \
	;

ARG USER_ID=7777
ARG GROUP_ID=7777

ARG APCU_VERSION=5.1.17
RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		coreutils \
		freetype-dev \
		icu-dev \
		libjpeg-turbo-dev \
		libpng-dev \
		libtool \
		libwebp-dev \
		libzip-dev \
		mariadb-dev \
		zlib-dev \
	; \
	\
	docker-php-ext-configure gd --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include --with-webp-dir=/usr/include --with-freetype-dir=/usr/include/; \
	docker-php-ext-configure zip --with-libzip; \
	docker-php-ext-install -j$(nproc) \
		exif \
		gd \
		intl \
		pdo_mysql \
		zip \
	; \
	pecl install \
		apcu-${APCU_VERSION} \
	; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .sylius-phpexts-rundeps $runDeps; \
	\
	apk del .build-deps; \
	\
	\
	apk add --no-cache $PHPIZE_DEPS \
	&& pecl install xdebug; \
	mkdir -p /var/logs && touch /var/logs/xdebug.log && chown -R www-data:www-data /var/logs;

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY conf/php.ini /usr/local/etc/php/php.ini
COPY conf/php-cli.ini /usr/local/etc/php/php-cli.ini

WORKDIR /var/www/html/app

# Craete php user
RUN addgroup -S --gid $GROUP_ID php
RUN adduser --home /home/php --uid $USER_ID -G php --shell /bin/sh -D php

# CMD composer install;  php-fpm
CMD php-fpm
EXPOSE 9000
