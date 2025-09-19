# Usar imagem oficial do PHP com Apache
FROM php:8.2-apache

# Copiar arquivos para dentro do container
COPY . /var/www/html/

# Ativar extensões necessárias
RUN docker-php-ext-install mysqli pdo pdo_mysql
