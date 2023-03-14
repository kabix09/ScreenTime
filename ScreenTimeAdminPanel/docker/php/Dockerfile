ARG PHP_VERSION
FROM php:${PHP_VERSION}-fpm-buster

ARG TIMEZONE
ARG PROJECT_NAME

RUN apt-get update && apt-get install -y \
    gnupg2 \
    openssl \
    git \
    unzip
    # php8.1-cli - Error: Unable to locate package php8-cli


RUN apt-get update \
    && apt-get install -y curl apt-transport-https multiarch-support \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/10/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get --no-install-recommends install -y zlib1g-dev g++ git libicu-dev libzip-dev zip libsodium-dev libcurl4-openssl-dev unixodbc-dev msodbcsql17 libxslt1-dev librabbitmq-dev libssh-dev \
    && docker-php-ext-install intl opcache pdo pdo_mysql curl sodium bcmath sockets xsl \
    && pecl install apcu sqlsrv pdo_sqlsrv amqp \
    && docker-php-ext-enable apcu sqlsrv pdo_sqlsrv amqp \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip


# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
&& printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
&& "date"


# Type docker-php-ext-install to see available extensions
WORKDIR /var/www/${PROJECT_NAME}


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
&& composer --version


# Install Symfony
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Copy and run the Docker entrypoint script
COPY entrypoint.sh /usr/bin/
RUN chmod +x /usr/bin/entrypoint.sh
ENTRYPOINT ["/usr/bin/entrypoint.sh"]