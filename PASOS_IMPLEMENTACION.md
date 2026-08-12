# 📋 Pasos de Implementación - RepuestoFijo MVP

## Fase 1: Configuración Inicial y Dependencias

### Paso 1.1: Instalar Laravel Livewire
```bash
composer require livewire/livewire
php artisan livewire:install
```

### Paso 1.2: Instalar Bootstrap 5
```bash
npm install bootstrap@5 @popperjs/core
# Configurar en resources/js/app.js y resources/css/app.css
```

### Paso 1.3: Instalar Google OAuth (Socialite)
```bash
composer require laravel/socialite
```

### Paso 1.4: Configurar variables de entorno
Editar `.env`:
- Agregar credenciales de Google OAuth
- Configurar MySQL/PostgreSQL (cambiar de SQLite)
- Agregar credenciales de Green API
- Agregar API keys de PlacaAPI/JSON.pe y SUNAT

---

## Fase 2: Base de Datos y Modelos

### Paso 2.1: Crear migraciones

**Migración: users (modificar la existente)**
```bash
php artisan make:migration add_google_fields_to_users_table
```
Campos adicionales: `google_id`, `ruc_dni`, `role` (enum: mechanic, provider, admin), `business_name`, `ciiu_code`

**Migración: vehicles**
```bash
php artisan make:migration create_vehicles_table
```
Campos: `plate` (unique), `vin`, `engine_code`, `brand`, `model`, `year`

**Migración: categories**
```bash
php artisan make:migration create_categories_table
```
Campos: `id`, `name`, `parent_id` (nullable), `icon` (nullable), `slug`

**Migración: providers**
```bash
php artisan make:migration create_providers_table
```
Campos: `id`, `user_id` (FK), `whatsapp_number`, `specialty`, `leads_count` (para freemium), `is_active`

**Migración: oem_products**
```bash
php artisan make:migration create_oem_products_table
```
Campos: `id`, `oem_code` (unique), `category_id` (FK), `description`, `common_brands` (JSON)

**Migración: repair_orders**
```bash
php artisan make:migration create_repair_orders_table
```
Campos: `id`, `user_id` (FK), `vehicle_plate` (FK), `status` (enum: draft, pending, confirmed, completed), `total_price`, `commission`, `created_at`, `updated_at`

**Migración: repair_items**
```bash
php artisan make:migration create_repair_items_table
```
Campos: `id`, `repair_order_id` (FK), `oem_product_id` (FK), `provider_id` (FK, nullable), `price` (nullable), `status` (enum: pending, confirmed, rejected), `green_api_message_id`, `retry_count`, `last_retry_at`

### Paso 2.2: Ejecutar migraciones
```bash
php artisan migrate
```

### Paso 2.3: Crear modelos Eloquent
```bash
php artisan make:model Vehicle
php artisan make:model Category
php artisan make:model Provider
php artisan make:model OemProduct
php artisan make:model RepairOrder
php artisan make:model RepairItem
```

### Paso 2.4: Definir relaciones en modelos
- User → RepairOrders (hasMany)
- Vehicle → RepairOrders (hasMany)
- Category → OemProducts (hasMany), Categories (hasMany - parent)
- RepairOrder → RepairItems (hasMany), User (belongsTo), Vehicle (belongsTo)
- RepairItem → RepairOrder (belongsTo), OemProduct (belongsTo), Provider (belongsTo)

---

## Fase 3: Autenticación con Google

### Paso 3.1: Configurar Google OAuth
1. Crear proyecto en Google Cloud Console
2. Obtener Client ID y Secret
3. Configurar en `config/services.php`:
```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

### Paso 3.2: Crear controlador de autenticación
```bash
php artisan make:controller Auth/GoogleAuthController
```

### Paso 3.3: Implementar rutas de autenticación
En `routes/web.php`:
- GET `/auth/google` → Redirige a Google
- GET `/auth/google/callback` → Procesa respuesta

### Paso 3.4: Crear middleware para validar DNI/RUC
```bash
php artisan make:middleware RequireBusinessInfo
```
Solo requerir DNI/RUC cuando se active ZettaBot (no en login inicial)

---

## Fase 4: Sistema de Búsqueda

### Paso 4.1: Crear servicio de detección de formato
```bash
php artisan make:class Services/OmniboxDetector
```
Lógica para detectar si input es Placa (formato peruano) o Código OEM

### Paso 4.2: Crear servicio de consulta de Placa
```bash
php artisan make:class Services/VehicleApiService
```
- Consultar API externa (PlacaAPI/JSON.pe)
- Guardar datos en tabla `vehicles`
- Cachear resultados

### Paso 4.3: Crear controlador de búsqueda
```bash
php artisan make:controller SearchController
```
Endpoints:
- POST `/api/search` → Buscar por Placa u OEM
- GET `/api/vehicle/{plate}` → Obtener datos de vehículo

---

## Fase 5: Categorías y Productos

### Paso 5.1: Crear seeder de categorías
```bash
php artisan make:seeder CategorySeeder
```
Categorías principales:
- Motor (Pistones, Válvulas, Distribución)
- Suspensión (Amortiguadores, Resortes, Trapecios)
- Frenos (Pastillas, Discos, Bombas)
- Eléctrico (Alternadores, Arrancadores, Bujías)
- Transmisión (Kit de Embrague, Palieres)
- Refrigeración (Radiadores, Bombas de agua)
- Mantenimiento (Filtros de Aceite, Aire, Cabina, Combustible)

### Paso 5.2: Crear componente Livewire de categorías
```bash
php artisan make:livewire CategoryGrid
```
Parrilla visual con Bootstrap 5

### Paso 5.3: Crear sistema de productos OEM
- Formulario para agregar productos OEM
- Búsqueda por código OEM
- Asociación con categorías

---

## Fase 6: Validación SUNAT

### Paso 6.1: Crear servicio de validación SUNAT
```bash
php artisan make:class Services/SunatService
```
- Validar DNI/RUC
- Obtener CIIU
- Detectar si es importadora mayorista

### Paso 6.2: Implementar lógica de bloqueo
Si CIIU corresponde a importadora → Bloquear rol "Mecánico" y redirigir a contacto comercial

---

## Fase 7: Órdenes de Reparación

### Paso 7.1: Crear componente Livewire de Orden de Reparación
```bash
php artisan make:livewire RepairOrderBuilder
```
- Agregar ítems a la orden
- Ver estado en tiempo real
- Confirmar orden

### Paso 7.2: Crear controlador de órdenes
```bash
php artisan make:controller RepairOrderController
```
- Crear orden
- Agregar ítem
- Confirmar orden (dispara ZettaBot)

---

## Fase 8: Integración Green API (ZettaBot)

### Paso 8.1: Crear servicio de Green API
```bash
php artisan make:class Services/GreenApiService
```
Métodos:
- `sendWhatsAppMessage($providerId, $oemCode)`
- `handleWebhook($data)`

### Paso 8.2: Crear Job para reintentos
```bash
php artisan make:job RetryProviderMessage
```
Lógica de reintentos cada 3 minutos (máximo 3 veces)

### Paso 8.3: Configurar cola de trabajos
En `.env`:
```
QUEUE_CONNECTION=database
```
```bash
php artisan queue:table
php artisan migrate
```

### Paso 8.4: Crear endpoint webhook
En `routes/web.php`:
```php
POST /webhook/green-api → GreenApiWebhookController@handle
```

### Paso 8.5: Implementar procesamiento de respuestas
- Parsear respuesta del proveedor (Sí/No + Precio)
- Actualizar `repair_items` con precio y estado
- Emitir evento Livewire para actualizar vista

---

## Fase 9: Frontend con Livewire

### Paso 9.1: Crear layout principal
- Navbar con Bootstrap 5
- Integrar Livewire scripts
- Estilos responsive

### Paso 9.2: Crear vistas principales
- `/` → Página de inicio con buscador Omnibox
- `/search` → Resultados de búsqueda
- `/vehicle/{plate}` → Detalles de vehículo + Parrilla de categorías
- `/repair-order` → Constructor de orden de reparación
- `/repair-order/{id}` → Tracking en tiempo real

### Paso 9.3: Implementar actualización en tiempo real
Usar Livewire polling o eventos para actualizar estado de ítems sin recargar página

---

## Fase 10: Sistema de Pagos

### Paso 10.1: Crear modelo de transacciones
```bash
php artisan make:migration create_transactions_table
```
Campos: `id`, `repair_order_id`, `user_id`, `amount`, `type` (commission, delivery), `status`, `payment_method`

### Paso 10.2: Implementar lógica de cobro
Al confirmar disponibilidad → Cobrar S/ 10.00 de comisión

---

## Fase 11: Panel de Administración

### Paso 11.1: Crear middleware de admin
```bash
php artisan make:middleware IsAdmin
```

### Paso 11.2: Crear controladores de admin
```bash
php artisan make:controller Admin/ProviderController
php artisan make:controller Admin/DashboardController
```

### Paso 11.3: Implementar registro manual de proveedores
- Formulario para crear proveedores
- Asignar WhatsApp number
- Configurar leads freemium (10 gratis/mes)

---

## Fase 12: Testing y Optimización

### Paso 12.1: Crear tests básicos
```bash
php artisan make:test VehicleSearchTest
php artisan make:test RepairOrderTest
php artisan make:test GreenApiIntegrationTest
```

### Paso 12.2: Configurar entorno de producción
- Variables de entorno
- Optimizar assets
- Configurar cache

---

## 📌 Orden Recomendado de Implementación

1. **Semana 1:** Fases 1-2 (Configuración + Base de datos)
2. **Semana 2:** Fases 3-4 (Autenticación + Búsqueda)
3. **Semana 3:** Fases 5-6 (Categorías + Validación SUNAT)
4. **Semana 4:** Fases 7-8 (Órdenes + Green API)
5. **Semana 5:** Fases 9-10 (Frontend + Pagos)
6. **Semana 6:** Fases 11-12 (Admin + Testing)

---

## 🔧 Comandos Útiles

```bash
# Crear componente Livewire
php artisan make:livewire NombreComponente

# Crear migración
php artisan make:migration nombre_migracion

# Crear modelo con migración
php artisan make:model NombreModelo -m

# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders
php artisan db:seed

# Procesar cola de trabajos
php artisan queue:work

# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```
