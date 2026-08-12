# Plan Maestro: RepuestoFijo (Fase 1 - MVP)

## 1. 🚀 Visión del Negocio

RepuestoFijo es un marketplace logístico B2B para el mercado automotriz peruano. Centraliza la oferta de importadores especializados mediante un sistema de **Búsqueda Inteligente (Placa/OEM)** y gestiona la disponibilidad en tiempo real mediante **ZettaBot (WhatsApp)**. El modelo garantiza el control de la transacción mediante **Delivery Forzado** al taller.

## 2. 👥 Protagonistas y Roles

- **Usuarios (Mecánicos/Talleres):** Registrados mediante Google Login + DNI/RUC. Validados por actividad económica. Pagan S/ 10.00 por gestión tras confirmar disponibilidad.
- **Proveedores (Importadores/Mayoristas):** Dueños del stock. Registrados manualmente por administración en Fase 1. Responden al Bot para confirmar stock/precio.
- **ZettaBot (Asistente Logístico):** Basado en Green API. Gestiona la persistencia (reintentos cada 3 min) y retorna webhooks a Laravel.
- **Sistema Central (Laravel):** Orquestador de datos y lógica de negocio.

## 3. 🛠️ Stack Tecnológico (Optimizado para el Arquitecto)

| Componente | Tecnología |
|------------|------------|
| Core | Laravel 11 (PHP 8.3) |
| Frontend Reactivo | Laravel Livewire (Para estados en tiempo real sin JS complejo) |
| Estilos | Bootstrap 5 |
| Base de Datos | MySQL / PostgreSQL |
| Chatbot Engine | Green API (Integración directa vía Webhooks) |
| APIs Externas | PlacaAPI / JSON.pe (Para datos de vehículos) y API SUNAT (Para DNI/RUC) |

## 4. 🔍 Lógica de Búsqueda y Registro

### A. Registro Progresivo

- **Acceso:** Login con Google (rápido).
- **Búsqueda:** El usuario puede buscar por Placa u OEM de forma abierta.
- **Validación:** Se solicita DNI/RUC solo al activar el ZettaBot.
- **Filtro de RUC:** El sistema valida el CIIU. Si es RUC de Importadora mayorista, se bloquea el rol de "Mecánico" y se redirige a contacto comercial.

### B. Buscador Omnibox Inteligente

- **Detección de Formato:** Identifica automáticamente si el input es Placa o Código OEM.
- **Retroalimentación de DB:** Si la placa no existe en la DB local, consulta la API externa, guarda los datos (VIN, Motor, Marca, Modelo, Año) y los sirve desde la DB local en futuras consultas.

## 5. 🖼️ UX: Parrilla Visual de Categorías

Tras identificar el vehículo, se despliega un árbol visual de categorías para evitar texto libre:

- **Motor:** Pistones, Válvulas, Distribución.
- **Suspensión:** Amortiguadores, Resortes, Trapecios.
- **Frenos:** Pastillas, Discos, Bombas.
- **Eléctrico:** Alternadores, Arrancadores, Bujías.
- **Transmisión:** Kit de Embrague, Palieres.
- **Refrigeración:** Radiadores, Bombas de agua.
- **Mantenimiento:** Filtros de Aceite, Aire, Cabina y Combustible.

## 6. 🔄 Operación ZettaBot (Lógica de Persistencia)

1. **Trigger:** Mecánico confirma "Orden de Reparación".
2. **Envío:** Laravel dispara petición a Green API con el Código OEM al WhatsApp del proveedor.
3. **Persistencia 3x3:** Laravel gestiona reintentos automáticos cada 3 minutos (máximo 3 veces).
4. **Webhook:** La respuesta del proveedor llega a Laravel y Livewire actualiza la vista del mecánico instantáneamente (Check verde/rojo).

## 7. 💰 Modelo de Ingresos y Control

- **Ingreso A:** Membresía Freemium a proveedores (10 leads gratis/mes).
- **Ingreso B:** S/ 10.00 de gestión por pedido confirmado al mecánico.
- **Ingreso C:** Margen logístico en Delivery (Delivery Forzado).
- **Seguridad:** El nombre del proveedor es invisible para el mecánico. La dirección de recojo solo la conoce el motorizado de RepuestoFijo.

## 8. 📝 Estructura de Base de Datos (Tablas Clave)

- **users:** (id, google_id, ruc_dni, role, business_name, ciiu_code).
- **vehicles:** (plate, vin, engine_code, brand, model, year).
- **categories:** (id, name, parent_id, icon).
- **oem_products:** (id, oem_code, category_id, description).
- **repair_orders:** (id, user_id, vehicle_plate, status).
- **repair_items:** (id, order_id, oem_product_id, provider_id, price, status).
