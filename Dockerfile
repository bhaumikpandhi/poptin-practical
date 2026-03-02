FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    default-mysql-client \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (layer caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs

# Copy package files and install node deps
COPY package.json package-lock.json* ./
RUN npm ci

# Copy application source
COPY . .

# Run composer autoload & scripts
RUN composer dump-autoload --optimize && \
    rm -f bootstrap/cache/packages.php bootstrap/cache/services.php && \
    php artisan package:discover --ansi || true

# Build args for Vite (passed at build time so VITE_* vars are baked into JS)
ARG VITE_REVERB_APP_KEY
ARG VITE_REVERB_HOST
ARG VITE_REVERB_PORT=443
ARG VITE_REVERB_SCHEME=https
ARG VITE_APP_NAME

# Make them available as env vars during npm run build
ENV VITE_REVERB_APP_KEY=$VITE_REVERB_APP_KEY
ENV VITE_REVERB_HOST=$VITE_REVERB_HOST
ENV VITE_REVERB_PORT=$VITE_REVERB_PORT
ENV VITE_REVERB_SCHEME=$VITE_REVERB_SCHEME
ENV VITE_APP_NAME=$VITE_APP_NAME

# Build frontend assets (VITE_* vars are now available)
RUN npm run build

# Copy Docker config files
COPY .docker/nginx.conf /etc/nginx/nginx.conf
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY .docker/start.sh /usr/local/bin/start.sh

# Permissions
RUN chmod +x /usr/local/bin/start.sh \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# PHP config
RUN echo "opcache.enable=1\nopcache.memory_consumption=128\nopcache.max_accelerated_files=10000\nrealpath_cache_size=4096K\nrealpath_cache_ttl=600" \
    > /usr/local/etc/php/conf.d/opcache.ini

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]