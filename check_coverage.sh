#!/bin/bash
# QA Automation: Script para verificar la cobertura de pruebas.
echo "Ejecutando tests con Pest PHP para calcular la cobertura..."

# Asumimos que Xdebug o PCOV están instalados en el contenedor
php artisan test --coverage --min=90

if [ $? -eq 0 ]; then
    echo "✅ Cobertura aprobada. (Mayor a 90%)"
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] [Agent: QA Engineer] Ejecución de tests exitosa. Cobertura >= 90%." >> storage/logs/agent_activity.log
else
    echo "❌ Fallo de calidad: La cobertura de tests es menor al 90%."
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] [Agent: QA Engineer] Fallo de tests: Cobertura menor al 90%." >> storage/logs/agent_activity.log
    exit 1
fi
