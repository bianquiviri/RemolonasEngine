#!/bin/bash
# Remolonas Engine - Instalador Automático SRE
# Este script levanta toda la infraestructura usando Docker de forma aislada.

set -e

echo "🚀 Iniciando instalación de Remolonas Engine..."

# 1. Verificar dependencias
if ! command -v docker &> /dev/null; then
    echo "❌ Error: Docker no está instalado. Por favor, instala Docker Desktop o similar."
    exit 1
fi

# 2. Configurar variables de entorno
if [ ! -f .env ]; then
    echo "📄 Creando archivo .env a partir de .env.example..."
    cp .env.example .env
else
    echo "✅ Archivo .env ya existe."
fi

# 3. Construir y levantar contenedores
echo "🐳 Levantando contenedores Docker (PostgreSQL, Redis, PHP-FPM, Nginx)..."
docker-compose up -d --build

# 4. Esperar a que la base de datos esté lista
echo "⏳ Esperando a que la base de datos esté lista..."
sleep 5

# 5. Configurar aplicación (ejecutado dentro del contenedor de la app)
echo "⚙️  Configurando Laravel..."
docker-compose exec app composer install --no-interaction
docker-compose exec app php artisan key:generate --force
docker-compose exec app php artisan migrate --force

# 6. Permisos de carpetas (por si acaso)
echo "🔐 Ajustando permisos de storage..."
docker-compose exec app chmod -R 775 storage bootstrap/cache

# 7. Limpiar caché de DNS de Nginx (Solución al 502 Bad Gateway)
echo "🔄 Reiniciando Nginx para forzar resolución de IP interna..."
docker-compose restart nginx

# 8. Ejecutar health check / QA
echo "✅ Instalación completada con éxito. Ejecutando scripts de calidad..."
if [ -f "check-agents.sh" ]; then
    ./check-agents.sh
fi

echo "==============================================="
echo "🟢 REMOLONAS ENGINE ESTÁ OPERATIVO"
echo "==============================================="
echo "👉 API y Dashboard: http://localhost:8000"
echo "👉 Base de Datos: localhost:5432"
echo "👉 Redis: localhost:6379"
echo "==============================================="
