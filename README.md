# 🌿 Remolonas Engine: The Digital Greenhouse

Remolonas Engine es el núcleo operativo de suscripciones D2C para fruta y verdura orgánica, diseñado con estándares SRE y una estética editorial premium denominada **"Greenhouse Glow"**.

## 🎨 Sistema de Diseño: Greenhouse Glow
El sistema utiliza una estética **Dark Editorial** con las siguientes características:
- **Modo Oscuro Profundo:** Fondo `#0a0a0a`.
- **Glassmorphism:** Paneles translúcidos con `backdrop-filter: blur(40px)`.
- **Tipografía:** *Newsreader* (Serif) para editorial y *Manrope* (Sans) para funcionalidad.
- **Micro-animaciones:** Ken Burns effect en fondos y transiciones orgánicas.

---

## 🔐 Acceso y Credenciales de Prueba

Para probar las diferentes capas del sistema (Cliente, Operador, Supervisor), utiliza las siguientes credenciales tras ejecutar el seeder.

### 👤 Administrador (Supervisor)
- **Email:** `admin@remolonas.com`
- **Password:** `password`
- **Capacidades:** Vista global, métricas SRE, gestión de tiendas.

### 📦 Operario (Picking)
- **Email:** `operator@remolonas.com`
- **Password:** `password`
- **Capacidades:** Interfaz de picking de alta fidelidad, escaneo de productos.

### 🛒 Cliente
- **Email:** `customer@remolonas.com`
- **Password:** `password`
- **Capacidades:** Suscripción, selección de planes, historial.

---

## 🚀 Instalación y Setup

### 1. Requisitos
- Docker & Docker Compose
- PHP 8.4+

### 2. Inicialización de Datos
Si los usuarios no aparecen en tu base de datos o quieres resetear el entorno:

```bash
# Ejecutar seeder de operaciones (Recomendado)
php artisan db:seed --class=RemolonasOpsSeeder

# O reset completo (Cuidado: borra todo)
php artisan migrate:fresh --seed
```

### 3. Ejecución en Docker
```bash
docker-compose up -d
```

---

## 🛠 Tech Stack
- **Backend:** Laravel 11 (PHP 8.4)
- **Frontend:** Blade, Tailwind CSS, Vanilla JS
- **Infraestructura:** Docker, Nginx, PostgreSQL, Redis
- **Seguridad:** Spatie Permissions (RBAC), Sanctum (API Auth)

---
*Desarrollado con ❤️ para la huerta orgánica.*
