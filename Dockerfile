# Используем официальный образ PHP
FROM php:7.4-apache

# Копируем файлы приложения в контейнер
COPY . /var/www/html/

# Устанавливаем зависимости
RUN apt-get update && apt-get install -y \
    sqlite3 \
    && docker-php-ext-install pdo pdo_sqlite

# Указываем рабочую директорию
WORKDIR /var/www/html/

# Запускаем Apache сервер
CMD ["apache2-foreground"]
