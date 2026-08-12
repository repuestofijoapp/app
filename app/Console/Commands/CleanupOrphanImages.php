<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CleanupOrphanImages extends Command
{
    protected $signature = 'storage:cleanup-orphans
                            {--dry-run : Solo mostrar qué se borraría, sin eliminar nada}
                            {--force  : Forzar sin pedir confirmación}';

    protected $description = 'Elimina imágenes de productos huérfanas (no referenciadas en la BD) y temporales antiguas de Livewire';

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('🔍 MODO SIMULACIÓN (--dry-run): No se eliminará nada.');
        }

        // ── 1. Imágenes huérfanas en storage/app/public/products ─────────────
        $this->info("\n--- PASO 1: Imágenes huérfanas en products/ ---");

        $diskFiles = collect(Storage::disk('public')->files('products'));

        $usedPaths = DB::table('products')
            ->whereNotNull('image_path')
            ->pluck('image_path')
            ->map(fn($p) => str_replace('\\', '/', $p))
            ->toArray();

        $orphans = $diskFiles->filter(function ($file) use ($usedPaths) {
            $normalised = str_replace('\\', '/', $file);
            return !in_array($normalised, $usedPaths);
        });

        $this->info("Archivos en disco: {$diskFiles->count()}");
        $this->info("Archivos referenciados en BD: " . count($usedPaths));
        $this->warn("Huérfanos encontrados: {$orphans->count()}");

        $deletedOrphans = 0;
        foreach ($orphans as $orphan) {
            $this->line("  🗑 {$orphan}");
            if (!$isDryRun) {
                Storage::disk('public')->delete($orphan);
                $deletedOrphans++;
            }
        }

        if (!$isDryRun) {
            $this->info("✅ {$deletedOrphans} imágenes huérfanas eliminadas.");
        }

        // ── 2. Archivos temporales de Livewire (>24h) ────────────────────────
        $this->info("\n--- PASO 2: Temporales de Livewire (>24h) ---");

        $livewireTmpDisk = Storage::disk('local');
        $tmpPath = 'livewire-tmp';

        if (!$livewireTmpDisk->exists($tmpPath)) {
            $this->line("  No existe carpeta livewire-tmp. Nada que limpiar.");
        } else {
            $tmpFiles = $livewireTmpDisk->files($tmpPath);
            $cutoff   = now()->subHours(24)->timestamp;
            $deletedTmp = 0;

            foreach ($tmpFiles as $tmpFile) {
                $lastModified = $livewireTmpDisk->lastModified($tmpFile);
                if ($lastModified < $cutoff) {
                    $this->line("  🗑 {$tmpFile}");
                    if (!$isDryRun) {
                        $livewireTmpDisk->delete($tmpFile);
                        $deletedTmp++;
                    }
                }
            }

            if (!$isDryRun) {
                $this->info("✅ {$deletedTmp} archivos temporales de Livewire eliminados.");
            } else {
                $oldCount = collect($tmpFiles)->filter(fn($f) => $livewireTmpDisk->lastModified($f) < $cutoff)->count();
                $this->warn("  Temporales a eliminar: {$oldCount}");
            }
        }

        // ── 3. Resumen ───────────────────────────────────────────────────────
        $this->info("\n=== RESUMEN ===");
        $remaining = Storage::disk('public')->files('products');
        $this->table(['', 'Cantidad'], [
            ['Imágenes de productos en disco (tras limpieza)', count($remaining)],
            ['Imágenes referenciadas en BD', count($usedPaths)],
            ['Huérfanas eliminadas', $isDryRun ? "(simulación)" : $deletedOrphans],
        ]);

        return Command::SUCCESS;
    }
}
