#!/bin/bash
# Script para verificar el estado del entorno de desarrollo
echo "--- Monitoreo de Agentes Remolonas ---"
docker-compose ps | grep "Up"
echo "Estado de Redis: $(docker-compose exec redis redis-cli ping)"
echo "Último registro de agentes:"
tail -n 5 storage/logs/agent_activity.log
