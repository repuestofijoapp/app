<?php

use App\Models\Category;

$mappings = [
    'Motor' => 'images/categorias/Motor.png',
    'Suspensión' => 'images/categorias/Suspension.png',
    'Dirección' => 'images/categorias/Direccion.png',
    'Frenos' => 'images/categorias/Frenos.png',
    'Eléctrico' => 'images/categorias/Sistema_electrico.png',
    'Iluminación' => 'images/categorias/Iluminacion.png',
    'Transmisión' => 'images/categorias/Arboles_transmision_y_diferenciales.png',
    'Embrague' => 'images/categorias/Embrague.png',
    'Refrigeración' => 'images/categorias/Sistema_refrigeracion_motor.png',
    'Carrocería' => 'images/categorias/Carroceria.png',
    'Accesorios' => 'images/categorias/Accesorios_auto.png',
    'Filtros' => 'images/categorias/Filtros.png',
    'Mantenimiento' => 'images/categorias/Filtros.png',
    'Amortiguadores' => 'images/categorias/Amortiguacion.png',
    'Caja de cambios' => 'images/categorias/Caja_de_cambios.png',
    'Escape' => 'images/categorias/Escape.png',
    'Encendido' => 'images/categorias/Encendido_precalentamiento.png',
    'Aire acondicionado' => 'images/categorias/Aire_acondicionado.png',
    'Faros' => 'images/categorias/Iluminacion.png',
    'Bujías' => 'images/categorias/Encendido_precalentamiento.png',
    'Radiador' => 'images/categorias/Sistema_refrigeracion_motor.png',
    'Rodamientos' => 'images/categorias/Rodamientos.png',
    'Sensores' => 'images/categorias/Sensores_reles_unidades_de_control.png',
    'Combustible' => 'images/categorias/Sistema_combustible.png',
    'Limpiaparabrisas' => 'images/categorias/Sistema_limpiaparabrisas.png',
    'Interior' => 'images/categorias/Interior.png',
    'Cuidado' => 'images/categorias/Productos_cuidado_auto.png',
    'Aceites' => 'images/categorias/Aceites_liquidos.png',
    'Herramientas' => 'images/categorias/Herramientas_equipo.png',
    'Pistones' => 'images/categorias/Motor.png',
    'Anillos' => 'images/categorias/Motor.png',
    'Válvulas' => 'images/categorias/Motor.png',
    'Cigüeñal' => 'images/categorias/Motor.png',
    'Resortes' => 'images/categorias/Suspension.png',
    'Trapecios' => 'images/categorias/Suspension.png',
    'Rótulas' => 'images/categorias/Direccion.png',
    'Bocinas' => 'images/categorias/Sistema_electrico.png',
    'Pastillas' => 'images/categorias/Frenos.png',
    'Zapatas' => 'images/categorias/Frenos.png',
];

$count = 0;
foreach ($mappings as $keyword => $img) {
    $updated = Category::where('name', 'like', '%' . $keyword . '%')
        ->where(function ($q) {
        $q->whereNull('image_url')->orWhere('image_url', '');
    })
        ->update(['image_url' => $img]);
    $count += $updated;
}

echo "Mapeo completado. Se actualizaron $count categorías.\n";
