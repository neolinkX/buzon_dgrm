# Usar una imagen base de PHP
FROM php:8.2-cli

# Instalar las dependencias necesarias, incluyendo mysqli
RUN apt-get update && apt-get install -y \
    gcc \
    && docker-php-ext-install mysqli \
    && apt-get clean

# Copiar el código fuente del proyecto
COPY . /app

# Establecer el directorio de trabajo
WORKDIR /app

# Puerto de exposición
EXPOSE 8080

# Comando para ejecutar la aplicación
CMD ["php", "-S", "0.0.0.0:8080", "-t", "/app"]