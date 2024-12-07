#!/bin/bash
set -e

echo "Deployment started ..."

# Colocar a aplicação em modo manutenção
(php artisan down) || true

# Verificar se o .env existe, caso contrário, copiar o .env.example para .env
if [ ! -f .env ]; then
  echo ".env não encontrado. Copiando .env.example para .env"
  cp .env.example .env
else
  echo ".env encontrado. Nenhuma ação necessária."
fi

# Puxar as últimas atualizações do repositório
git pull origin main

# Instalar as dependências com Composer
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Instala o passport
php artisan install:passport

# Limpar caches e compilar os arquivos necessários
php artisan clear-compiled

php artisan optimize

# Rodar as migrações do banco de dados
php artisan migrate:fresh

# Colocar a aplicação em modo online novamente
php artisan up

echo "Deployment finished!"
