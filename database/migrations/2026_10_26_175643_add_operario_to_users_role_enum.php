<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Añade 'operario' al ENUM de la columna role en users.
     * Usamos ALTER TABLE directo porque Doctrine/Laravel no siempre puede ampliar ENUMs en MySQL 5.x/8.x.
     */
    public function up(): void
    {
        // Lectura segura: ampliamos el ENUM existente añadiendo 'operario'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM(
            'admin','manager','mechanic','workshop','store','transporte','provider','operario'
        ) NOT NULL DEFAULT 'mechanic'");
    }

    public function down(): void
    {
        // Revertimos quitando 'operario' (sólo si ningún usuario lo tiene)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM(
            'admin','manager','mechanic','workshop','store','transporte','provider'
        ) NOT NULL DEFAULT 'mechanic'");
    }
};
