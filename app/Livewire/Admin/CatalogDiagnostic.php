<?php

namespace App\Livewire\Admin;

use App\Models\Make;
use App\Models\CarModel;
use App\Models\Engine;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CatalogDiagnostic extends Component
{
    // Filters
    public string $selectedMake = '';
    public int $activeTab = 0; // 0=duplicates, 1=engines, 2=pivot

    // Results
    public array $duplicateGroups  = [];
    public array $engineGroups     = [];
    public array $pivotMissing     = [];

    // Stats
    public int $totalModels  = 0;
    public int $totalEngines = 0;
    public int $totalProducts = 0;
    public int $pivotRows    = 0;

    // Merge UI state
    public ?int $mergeSourceId = null;
    public ?int $mergeTargetId = null;
    public string $mergeConfirmName = '';
    public string $mergeTargetName  = '';
    public string $mergeCustomName  = '';


    // Engine unify state
    public ?int   $unifyEngineGroupKey = null;
    public string $unifyDisplacement   = '';

    // Flash message
    public string $flashMsg  = '';
    public string $flashType = 'success';

    public function mount(): void
    {
        $this->loadStats();
    }

    // ──────────────────────────────────────────────
    //  Stats bar
    // ──────────────────────────────────────────────
    private function loadStats(): void
    {
        $this->totalModels   = CarModel::count();
        $this->totalEngines  = Engine::count();
        $this->totalProducts = Product::count();
        $this->pivotRows     = DB::table('product_compatibilities')->count();
    }

    // ──────────────────────────────────────────────
    //  Run analysis when make changes
    // ──────────────────────────────────────────────
    public function updatedSelectedMake(): void
    {
        $this->duplicateGroups = [];
        $this->engineGroups    = [];
        $this->pivotMissing    = [];
        $this->mergeSourceId   = null;
        $this->mergeTargetId   = null;

        if ($this->selectedMake) {
            $this->analyzeAll();
        }
    }

    public function analyzeAll(): void
    {
        $this->analyzeDuplicateModels();
        $this->analyzeEngines();
        $this->analyzePivotMissing();
    }

    // ──────────────────────────────────────────────
    //  Tab 0 — Duplicate models
    // ──────────────────────────────────────────────
    private function analyzeDuplicateModels(): void
    {
        $make = Make::where('name', $this->selectedMake)->first();
        if (!$make) {
            $this->duplicateGroups = [];
            return;
        }

        $models = CarModel::where('make_id', $make->id)
            ->orderBy('name')
            ->get(['id', 'name', 'start_year', 'end_year']);

        // Group by normalized name (uppercase, remove spaces/hyphens)
        $groups = [];
        foreach ($models as $m) {
            $key = preg_replace('/[\s\-_]+/', '', strtoupper($m->name));
            $groups[$key][] = $m;
        }

        // Keep only groups with more than one model
        $duplicates = [];
        foreach ($groups as $key => $group) {
            if (count($group) > 1) {
                $enriched = [];
                foreach ($group as $m) {
                    $productCount = Product::whereJsonContains('compatible_model_ids', $m->id)->count();
                    $enriched[] = [
                        'id'            => $m->id,
                        'name'          => $m->name,
                        'start_year'    => $m->start_year,
                        'end_year'      => $m->end_year,
                        'product_count' => $productCount,
                    ];
                }
                // Sort: most products first (likely the "correct" one)
                usort($enriched, fn($a, $b) => $b['product_count'] <=> $a['product_count']);
                $duplicates[] = [
                    'key'    => $key,
                    'models' => $enriched,
                ];
            }
        }

        $this->duplicateGroups = $duplicates;
    }

    // ──────────────────────────────────────────────
    //  Tab 1 — Engines with same code, different displacement
    // ──────────────────────────────────────────────
    private function analyzeEngines(): void
    {
        $make = Make::where('name', $this->selectedMake)->first();
        if (!$make) {
            $this->engineGroups = [];
            return;
        }

        $modelIds = CarModel::where('make_id', $make->id)->pluck('id');

        $engines = Engine::whereIn('car_model_id', $modelIds)
            ->orderBy('engine_code')
            ->get();

        // Group by engine_code
        $groups = [];
        foreach ($engines as $e) {
            $code = strtoupper(trim($e->engine_code));
            $groups[$code][] = [
                'id'           => $e->id,
                'engine_code'  => $e->engine_code,
                'displacement' => $e->displacement,
                'fuel_type'    => $e->fuel_type,
                'car_model_id' => $e->car_model_id,
                'model_name'   => $e->carModel->name ?? '?',
            ];
        }

        // Keep groups where displacement varies
        $inconsistent = [];
        foreach ($groups as $code => $rows) {
            $displacements = array_unique(array_column($rows, 'displacement'));
            if (count($displacements) > 1) {
                $inconsistent[] = [
                    'code'          => $code,
                    'engines'       => $rows,
                    'displacements' => $displacements,
                ];
            }
        }

        $this->engineGroups = $inconsistent;
    }

    // ──────────────────────────────────────────────
    //  Tab 2 — Products missing from pivot
    // ──────────────────────────────────────────────
    private function analyzePivotMissing(): void
    {
        $make = Make::where('name', $this->selectedMake)->first();
        if (!$make) {
            $this->pivotMissing = [];
            return;
        }

        // Products for this make that have model IDs but zero pivot rows
        $products = Product::where('vehicle_make', $this->selectedMake)
            ->whereNotNull('compatible_model_ids')
            ->get(['id', 'name', 'supplier_code', 'compatible_model_ids', 'compatible_engine_ids']);

        $missing = [];
        foreach ($products as $p) {
            $pivotCount = DB::table('product_compatibilities')
                ->where('product_id', $p->id)
                ->count();

            if ($pivotCount === 0) {
                $modelIds  = $p->compatible_model_ids  ?? [];
                $engineIds = $p->compatible_engine_ids ?? [];

                // Check for orphaned IDs (references to deleted models/engines)
                $existingModels  = CarModel::whereIn('id', $modelIds)->pluck('id')->toArray();
                $existingEngines = !empty($engineIds)
                    ? Engine::whereIn('id', $engineIds)->pluck('id')->toArray()
                    : [];

                $orphanModels  = array_values(array_diff($modelIds, $existingModels));
                $orphanEngines = array_values(array_diff($engineIds, $existingEngines));

                $hasValidModels  = !empty($existingModels);
                $isOrphaned      = !$hasValidModels; // can't sync if no valid models exist

                $orphanDetail = [];
                if (!empty($orphanModels)) {
                    $orphanDetail[] = count($orphanModels) . ' modelo(s) eliminado(s): [' . implode(', ', $orphanModels) . ']';
                }
                if (!empty($orphanEngines)) {
                    $orphanDetail[] = count($orphanEngines) . ' motor(es) eliminado(s): [' . implode(', ', $orphanEngines) . ']';
                }

                $missing[] = [
                    'id'             => $p->id,
                    'supplier_code'  => $p->supplier_code,
                    'name'           => $p->name,
                    'model_ids'      => $modelIds,
                    'engine_ids'     => $engineIds,
                    'valid_models'   => $existingModels,
                    'valid_engines'  => $existingEngines,
                    'orphan_models'  => $orphanModels,
                    'orphan_engines' => $orphanEngines,
                    'status'         => $isOrphaned ? 'orphaned' : 'syncable',
                    'orphan_detail'  => implode(' · ', $orphanDetail),
                ];
            }
        }

        $this->pivotMissing = $missing;
    }

    // ──────────────────────────────────────────────
    //  Actions — Model merge
    // ──────────────────────────────────────────────

    /**
     * Start a merge: set source (the duplicate to absorb) and target (the one to keep).
     */
    public function startMerge(int $sourceId, int $targetId): void
    {
        $source = CarModel::find($sourceId);
        $target = CarModel::find($targetId);
        if (!$source || !$target) return;

        $this->mergeSourceId    = $sourceId;
        $this->mergeTargetId    = $targetId;
        $this->mergeConfirmName = $source->name;
        $this->mergeTargetName  = $target->name;
        $this->mergeCustomName  = $target->name;
    }

    /**
     * Swap source and target roles (invert which model absorbs which).
     */
    public function swapMergeDirection(): void
    {
        if (!$this->mergeSourceId || !$this->mergeTargetId) return;

        $temp = $this->mergeSourceId;
        $this->mergeSourceId = $this->mergeTargetId;
        $this->mergeTargetId = $temp;

        $source = CarModel::find($this->mergeSourceId);
        $target = CarModel::find($this->mergeTargetId);
        if ($source && $target) {
            $this->mergeConfirmName = $source->name;
            $this->mergeTargetName  = $target->name;
            $this->mergeCustomName  = $target->name;
        }
    }

    public function cancelMerge(): void
    {
        $this->mergeSourceId    = null;
        $this->mergeTargetId    = null;
        $this->mergeConfirmName = '';
        $this->mergeTargetName  = '';
        $this->mergeCustomName  = '';
    }

    /**
     * Execute the merge: reassign all data from source → target, optionally rename target, then delete source.
     */
    public function executeMerge(): void
    {
        $sourceId = $this->mergeSourceId;
        $targetId = $this->mergeTargetId;

        if (!$sourceId || !$targetId || $sourceId === $targetId) return;

        $source = CarModel::find($sourceId);
        $target = CarModel::find($targetId);
        if (!$source || !$target) return;

        $finalName = trim($this->mergeCustomName) ?: $target->name;

        DB::transaction(function () use ($sourceId, $targetId, $source, $target, $finalName) {
            // 0. Update target model name if changed
            if ($target->name !== $finalName) {
                $target->update(['name' => $finalName]);
            }

            // 1. Move engines from source model to target (avoid duplicating engine codes)
            $targetEngineCodes = Engine::where('car_model_id', $targetId)
                ->pluck('engine_code')
                ->map(fn($c) => strtoupper(trim($c)))
                ->toArray();

            $sourceEngines = Engine::where('car_model_id', $sourceId)->get();
            foreach ($sourceEngines as $eng) {
                $code = strtoupper(trim($eng->engine_code));
                if (in_array($code, $targetEngineCodes)) {
                    // Engine code already exists on target — update pivot references to target's engine
                    $targetEngine = Engine::where('car_model_id', $targetId)
                        ->whereRaw('UPPER(engine_code) = ?', [$code])
                        ->first();
                    if ($targetEngine) {
                        DB::table('product_compatibilities')
                            ->where('engine_id', $eng->id)
                            ->update(['engine_id' => $targetEngine->id, 'car_model_id' => $targetId]);
                    }
                    $eng->delete();
                } else {
                    // Move engine to target model
                    $eng->update(['car_model_id' => $targetId]);
                    DB::table('product_compatibilities')
                        ->where('engine_id', $eng->id)
                        ->update(['car_model_id' => $targetId]);
                }
            }

            // 2. Update pivot rows that reference the source model directly (engine_id = null)
            DB::table('product_compatibilities')
                ->where('car_model_id', $sourceId)
                ->update(['car_model_id' => $targetId]);

            // 3. Update products JSON columns for source products
            $productsWithSource = Product::whereJsonContains('compatible_model_ids', $sourceId)->get();
            foreach ($productsWithSource as $p) {
                $modelIds = array_map('intval', $p->compatible_model_ids ?? []);
                if (!in_array($targetId, $modelIds)) {
                    $modelIds[] = $targetId;
                }
                $modelIds = array_values(array_filter($modelIds, fn($id) => $id !== $sourceId));
                $p->compatible_model_ids = $modelIds;

                // Also update compatible_vehicles text
                $vehicles = collect(CarModel::whereIn('id', $modelIds)->pluck('name'))
                    ->unique()->values()->implode(', ');
                $p->compatible_vehicles = $vehicles ?: null;

                $p->save();
            }

            // 3b. Refresh compatible_vehicles for all products containing targetId to reflect finalName
            $productsWithTarget = Product::whereJsonContains('compatible_model_ids', $targetId)->get();
            foreach ($productsWithTarget as $p) {
                $modelIds = array_map('intval', $p->compatible_model_ids ?? []);
                $vehicles = collect(CarModel::whereIn('id', $modelIds)->pluck('name'))
                    ->unique()->values()->implode(', ');
                $p->compatible_vehicles = $vehicles ?: null;
                $p->save();
            }

            // 4. Delete source model
            $source->delete();
        });

        $this->flash('success', "Modelo «{$source->name}» fusionado en «{$finalName}» correctamente.");
        $this->mergeSourceId    = null;
        $this->mergeTargetId    = null;
        $this->mergeConfirmName = '';
        $this->mergeTargetName  = '';
        $this->mergeCustomName  = '';
        $this->loadStats();
        $this->analyzeAll();
    }


    // ──────────────────────────────────────────────
    //  Actions — Engine displacement unify
    // ──────────────────────────────────────────────
    public function startUnify(int $groupIndex): void
    {
        $this->unifyEngineGroupKey = $groupIndex;
        $group = $this->engineGroups[$groupIndex] ?? null;
        if ($group) {
            // Pre-fill with the most common displacement
            $disps = array_column($group['engines'], 'displacement');
            $counts = array_count_values(array_filter($disps));
            arsort($counts);
            $this->unifyDisplacement = array_key_first($counts) ?? '';
        }
    }

    public function cancelUnify(): void
    {
        $this->unifyEngineGroupKey = null;
        $this->unifyDisplacement   = '';
    }

    public function executeUnify(): void
    {
        $idx = $this->unifyEngineGroupKey;
        if ($idx === null) return;

        $group = $this->engineGroups[$idx] ?? null;
        if (!$group) return;

        $disp = trim($this->unifyDisplacement) ?: null;
        $ids  = array_column($group['engines'], 'id');

        Engine::whereIn('id', $ids)->update(['displacement' => $disp]);

        $this->flash('success', "Motor «{$group['code']}» unificado con displacement «{$disp}».");
        $this->unifyEngineGroupKey = null;
        $this->unifyDisplacement   = '';
        $this->analyzeEngines();
    }

    // ──────────────────────────────────────────────
    //  Actions — Pivot resync
    // ──────────────────────────────────────────────
    public function resyncProduct(int $productId): void
    {
        $p = Product::find($productId);
        if (!$p) return;

        $this->doResyncProduct($p);
        $this->flash('success', "Producto #{$p->supplier_code} re-sincronizado.");
        $this->analyzePivotMissing();
        $this->loadStats();
    }

    public function resyncAll(): void
    {
        $synced  = 0;
        $skipped = 0;
        foreach ($this->pivotMissing as $row) {
            if (($row['status'] ?? '') === 'orphaned') {
                $skipped++;
                continue;
            }
            $p = Product::find($row['id']);
            if ($p) {
                $this->doResyncProduct($p);
                $synced++;
            }
        }
        $msg = "{$synced} producto(s) re-sincronizados.";
        if ($skipped > 0) {
            $msg .= " {$skipped} omitido(s) por tener IDs huérfanos (usa 'Limpiar IDs' primero).";
        }
        $this->flash($skipped > 0 ? 'warning' : 'success', $msg);
        $this->analyzePivotMissing();
        $this->loadStats();
    }

    /**
     * Clear orphaned model/engine IDs from a product so it can be reassigned manually.
     */
    public function clearOrphanedIds(int $productId): void
    {
        $p = Product::find($productId);
        if (!$p) return;

        $row = collect($this->pivotMissing)->firstWhere('id', $productId);
        if (!$row) return;

        // Keep only IDs that still exist in the DB
        $cleanModelIds  = array_values($row['valid_models']  ?? []);
        $cleanEngineIds = array_values($row['valid_engines'] ?? []);

        $p->compatible_model_ids  = !empty($cleanModelIds)  ? $cleanModelIds  : null;
        $p->compatible_engine_ids = !empty($cleanEngineIds) ? $cleanEngineIds : null;

        // Rebuild compatible_vehicles text
        if (!empty($cleanModelIds)) {
            $vehicles = CarModel::whereIn('id', $cleanModelIds)->pluck('name')->unique()->implode(', ');
            $p->compatible_vehicles = $vehicles ?: null;
        } else {
            $p->compatible_vehicles = null;
        }

        $p->save();

        $removed = count($row['orphan_models'] ?? []) + count($row['orphan_engines'] ?? []);
        $this->flash('warning', "Producto #{$p->supplier_code}: {$removed} ID(s) huérfano(s) eliminados. Reasigna modelos/motores en Gestión de Repuestos.");
        $this->analyzePivotMissing();
    }

    private function doResyncProduct(Product $p): void
    {
        DB::table('product_compatibilities')->where('product_id', $p->id)->delete();

        $newCompat = [];
        $engineIds = $p->compatible_engine_ids ?? [];
        $modelIds  = $p->compatible_model_ids  ?? [];

        if (!empty($engineIds)) {
            $engs = Engine::whereIn('id', $engineIds)->with('carModel')->get();
            foreach ($engs as $eng) {
                $newCompat[] = [
                    'product_id'   => $p->id,
                    'make_id'      => $eng->carModel->make_id ?? null,
                    'car_model_id' => $eng->car_model_id,
                    'engine_id'    => $eng->id,
                    'source'       => 'manual',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }
            $coveredModels = array_unique(array_column($newCompat, 'car_model_id'));
            $remaining = array_diff($modelIds, $coveredModels);
        } else {
            $remaining = $modelIds;
        }

        if (!empty($remaining)) {
            $mods = CarModel::whereIn('id', $remaining)->get();
            foreach ($mods as $mod) {
                $newCompat[] = [
                    'product_id'   => $p->id,
                    'make_id'      => $mod->make_id,
                    'car_model_id' => $mod->id,
                    'engine_id'    => null,
                    'source'       => 'manual',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }
        }

        if (!empty($newCompat)) {
            DB::table('product_compatibilities')->insertOrIgnore($newCompat);
        }
    }

    // ──────────────────────────────────────────────
    //  Helper
    // ──────────────────────────────────────────────
    private function flash(string $type, string $msg): void
    {
        $this->flashType = $type;
        $this->flashMsg  = $msg;
        $this->dispatch('notify', ['type' => $type, 'message' => $msg]);
    }

    public function render()
    {
        $makes = Make::orderBy('name')->pluck('name')->toArray();

        return view('livewire.admin.catalog-diagnostic', [
            'makes' => $makes,
        ])->layout('layouts.app');
    }
}
