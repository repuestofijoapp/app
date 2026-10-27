# 📦 Plan de Trazabilidad, Pedidos y Proof of Delivery (PoD) — RepuestoFijo

**Fecha de registro:** 25 de Octubre de 2026  
**Proyecto:** RepuestoFijo (Laravel / Livewire / Blade / MySQL)

---

## 1. Resumen de Requerimientos y Alcance

### 1.1. Gestión y Validación Estricta de Direcciones (Perú)
- **Ubigeo INEI:** Selectores en cascada obligatorios (**Departamento $\rightarrow$ Provincia $\rightarrow$ Distrito**) para garantizar zonas de cobertura oficiales.
- **Google Places API:** Autocompletado acotado estrictamente a Perú (`country:pe`). El cliente debe obligatoriamente seleccionar una dirección sugerida y normalizada por la API para evitar errores de digitación.
- **Geocodificación Automática:** Al seleccionar la dirección, se capturan y almacenan automáticamente la **Latitud** y **Longitud** en la base de datos del pedido para uso logístico y cálculo de geocercas.

### 1.2. UI/UX y Diseño Responsive (Frontend)
- **Tipografía y Accesibilidad:** Uso global de fuente **Inter**, cumplimiento de contraste WCAG y formato unificado de fecha/hora estándar (`24/10/2026 06:36`). *(Completado en commit `dfbff3d0`)*.
- **Card Layout en Móvil (< 640px):** En pantallas móviles, las tablas de productos del modal de pedido se transforman en tarjetas nativas (miniatura 68x68, SKU, cantidad y subtotal alineados), idéntico al carrito de compras. *(Completado en commit `04fb8738`)*.
- **Footer de Modales Optimizado:** Botón principal de seguimiento destacado en la parte superior, botones secundarios (Comprobante SUNAT y Reportar) en grilla 50%/50%, y botón de cierre ("X" o botón inferior). *(Completado en commit `0f7d1059`)*.

### 1.3. Flujo Logístico de Trazabilidad (Proof of Delivery - PoD)
Automatización basada en **4 hitos logísticos** conectados mediante enlaces web móviles ligeros (acceso nativo a cámara y GPS sin requerir app instalada):
1. **Recojo en Proveedor:** El operario toma foto del estado inicial del repuesto antes de salir de la tienda/almacén del proveedor.
2. **Embalaje en Hub Central:** Se empaca el repuesto en caja con sello de seguridad RepuestoFijo y etiqueta QR única; se toma foto de la caja sellada.
3. **Entrega a Motorizado:** El motorizado escanea el código QR con su móvil, abriendo un enlace web protegido que registra timestamp, coordenadas GPS y foto de la recepción del paquete.
4. **Entrega Final al Cliente:** El motorizado abre el enlace al llegar a destino, toma la foto de entrega en puerta y el sistema valida en backend la **geocerca GPS** contra las coordenadas pactadas del pedido.

### 1.4. Panel de Administración ("Control de Ruta")
- **Acceso Rápido:** En la tabla de pedidos (`resources/views/livewire/admin/pedido-management.blade.php`), la columna *Acciones* incluye un botón con icono de camión titulado **🚚 Control de Ruta**.
- **Modal de Auditoría Interna:** Despliegue interactivo con detalle de cada hito:
  - Fotografías de evidencia ampliables en lightbox/pantalla completa.
  - Timestamps exactos de cada registro (`d/m/Y H:i:s`).
  - Coordenadas GPS (Lat/Long) con cálculo de distancia real respecto al destino y badges de alerta (`En rango: 25m` en verde vs `Alerta: 320m fuera de rango` en rojo).
  - Nombre y rol del responsable operativo en cada paso.

---

## 2. Diagnóstico Técnico del Repositorio

| Módulo | Estado | Detalle |
|---|:---:|---|
| **Ubigeo INEI (Cascada)** | ❌ Pendiente | Se usa lista plana estática `$limaZones` y texto libre para provincia en `MainSearch.php`. Falta catálogo INEI y cascada. |
| **Google Places Autocomplete** | ❌ Pendiente | Falta script de Google Maps Places API y restricción `country:pe`. |
| **Coordenadas Lat/Lng en Pedidos** | ❌ Pendiente | La tabla `pedidos` carece de campos `lat`, `lng`, `ubigeo_code`, `delivery_token`. |
| **Estilos, Fuente Inter & Responsive** | ✅ Completado | Integrado el 24/10/2026 en layout global y modal de detalle. |
| **Dual-Render Tabla / Tarjetas Móvil** | ✅ Completado | Integrado el 24/10/2026 (`04fb8738`) en `main-search.blade.php`. |
| **Jerarquía Footer de Modales** | ✅ Completado | Integrado el 24/10/2026 (`0f7d1059`) en `main-search.blade.php`. |
| **Estructura de Base de Datos PoD** | ❌ Pendiente | Falta tabla `pedido_hitos` para guardar fotos, GPS, timestamps y operarios. |
| **Web App Móvil PoD (Cámara/GPS/QR)** | ❌ Pendiente | Falta interfaz web ligera con token de seguridad para operario y motorizado. |
| **Botón y Modal "Control de Ruta"** | ❌ Pendiente | Falta botón 🚚 en `PedidoManagement.php` y modal de auditoría de evidencias. |

---

## 3. Arquitectura de Base de Datos Propuesta

### Migración 1: Ampliación de tabla `pedidos`
```php
Schema::table('pedidos', function (Blueprint $table) {
    $table->decimal('lat', 10, 7)->nullable()->after('direccion');
    $table->decimal('lng', 10, 7)->nullable()->after('lat');
    $table->string('ubigeo_code', 6)->nullable()->after('distrito');
    $table->string('departamento', 60)->nullable()->after('distrito');
    $table->string('provincia', 60)->nullable()->after('departamento');
    $table->string('delivery_token', 64)->unique()->nullable()->after('clave_secreta');
});
```

### Migración 2: Nueva tabla `pedido_hitos`
```php
Schema::create('pedido_hitos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
    $table->enum('tipo_hito', [
        'recojo_proveedor',
        'embalaje_hub',
        'entrega_motorizado',
        'entrega_cliente'
    ]);
    $table->string('foto_url');
    $table->decimal('lat', 10, 7)->nullable();
    $table->decimal('lng', 10, 7)->nullable();
    $table->decimal('distancia_metros', 8, 2)->nullable();
    $table->boolean('dentro_de_rango')->default(true);
    $table->string('operador_nombre');
    $table->foreignId('operador_user_id')->nullable()->constrained('users')->nullOnDelete();
    $table->text('observaciones')->nullable();
    $table->timestamps();

    $table->index(['pedido_id', 'tipo_hito']);
});
```

---

## 4. Plan de Implementación por Fases

### Fase 1: Georreferenciación y Validación de Direcciones (Checkout)
1. Ejecutar migración de campos de geolocalización en `pedidos`.
2. Integrar catálogo JSON INEI de Departamentos, Provincias y Distritos con selects dependientes.
3. Incorporar Google Places API (`country:pe`) con captura automática de Lat/Lng al confirmar dirección.

### Fase 2: Backend y Modelo de Datos de Trazabilidad
1. Crear modelo y migración `PedidoHito`.
2. Servicio con fórmula de Haversine para validación de distancia geodésica y geocercas.
3. Generación automática de `delivery_token` y código QR al confirmarse el pedido.

### Fase 3: Web App Móvil PoD (Operarios y Motorizados)
1. Ruta web `/pod/{token}/{hito}` sin requerir login complejo.
2. Captura forzada de cámara nativa (`capture="environment"`) y geolocalización (`navigator.geolocation`).
3. Compresión de imagen en el cliente (canvas) antes de la subida para velocidad en redes móviles.

### Fase 4: Panel Admin "Control de Ruta" y Auditoría
1. Añadir botón 🚚 en `resources/views/livewire/admin/pedido-management.blade.php`.
2. Crear modal de auditoría interna con visor de fotos, timestamps, alertas de distancia y enlaces a Google Maps.
3. Sincronizar el modal de cliente para que muestre los datos reales de los hitos completados.

### Fase 5: Pruebas de Calidad y Validación
1. Prueba de autocompletado en direcciones de Lima y Provincias.
2. Prueba móvil de cámara + GPS en los 4 hitos.
3. Verificación de alertas de geocerca en el panel de control.
