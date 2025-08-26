#!/bin/bash

# Espera o contêiner do banco de dados iniciar
until nc -z -v -w30 db 3306
do
  echo "Aguardando o serviço de banco de dados..."
  sleep 1
done

# Executa as migrações
echo "Executando as migrações..."
php artisan migrate --force

# Executa o comando principal do contêiner (o php-fpm)
exec "$@"