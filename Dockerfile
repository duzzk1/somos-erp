# Dockerfile

# 1. Base da Imagem
# Usa uma imagem PHP-FPM baseada em Debian, que é mais compatível
FROM php:8.2-fpm

# 2. Instalação de dependências do sistema
# Usa 'apt-get' em vez de 'apk'
RUN apt-get update && apt-get install -y \
    git \
    supervisor \
    cron \
    curl \
    unzip \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    g++ \
    make \
    nodejs \
    npm \
    libmariadb-dev-compat \
    mariadb-client \
    netcat-openbsd \
    && rm -rf /var/lib/apt/lists/*

# 3. Instalação de extensões do PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 4. Instalação do Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Define o diretório de trabalho
WORKDIR /var/www/html

# 6. Cópia dos arquivos da aplicação
COPY . .

# 7. Instalação de dependências do projeto
RUN composer install --optimize-autoloader --no-dev
RUN rm -rf node_modules package-lock.json
RUN npm install --legacy-peer-deps
RUN npm run dev

# 8. Permissões de arquivo
# Garante que o diretório seja de propriedade do usuário www-data
RUN chown -R www-data:www-data /var/www/html

# 9. Copia a configuração do Supervisor
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# 10. Cópia do crontab do contêiner
COPY ./docker/crontab /etc/crontabs/root
RUN chmod 0644 /etc/crontabs/root

# 11. Cópia e permissões do script de entrada
COPY ./docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 12. Limpeza do cache
RUN rm -rf /root/.npm
RUN rm -rf /tmp/*

# 13. Configuração do ponto de entrada e comando
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Exposição da porta
EXPOSE 9000