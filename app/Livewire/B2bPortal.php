<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\PedidoItem;
use App\Models\Product;

class B2bPortal extends Component
{
    use WithFileUploads;

    public $currentTab = 'dashboard';
    public $csvFile;

    public function logout()
    {
        Auth::guard('provider')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('b2b.login');
    }

    public function setTab($tab)
    {
        $this->currentTab = $tab;
    }

    public function uploadInventory()
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $providerId = Auth::guard('provider')->id();
        
        // This logic simulates the InventoryController logic, but scoped to the logged-in provider.
        $path = $this->csvFile->store('temp');
        $fullPath = storage_path('app/' . $path);

        $file = fopen($fullPath, 'r');
        
        // Read header
        $header = fgetcsv($file);
        // Clean BOM
        $header[0] = preg_replace('/\x{FEFF}/u', '', $header[0]);
        
        $headerMap = array_map('trim', $header);
        
        $colProveedor = array_search('sku_proveedor', $headerMap);
        $colOem = array_search('oem_code', $headerMap);
        $colName = array_search('name', $headerMap);
        $colPrice = array_search('price', $headerMap);
        $colStock = array_search('stock', $headerMap);

        if ($colProveedor === false || $colPrice === false || $colStock === false) {
            session()->flash('error', 'El CSV debe contener las columnas: sku_proveedor, price, stock.');
            return;
        }

        $updated = 0;
        $created = 0;

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < 3) continue;

            $sku = trim($row[$colProveedor]);
            $price = floatval(trim($row[$colPrice]));
            $stock = intval(trim($row[$colStock]));
            
            $oem = $colOem !== false && isset($row[$colOem]) ? trim($row[$colOem]) : null;
            $name = $colName !== false && isset($row[$colName]) ? trim($row[$colName]) : 'Repuesto ' . $sku;

            $isActive = $stock > 0;

            $product = Product::where('provider_id', $providerId)
                              ->where('supplier_code', $sku)
                              ->first();

            if ($product) {
                $product->update([
                    'price' => $price,
                    'is_active' => $isActive,
                ]);
                $updated++;
            } else {
                // If we want to allow creating products from CSV
                Product::create([
                    'provider_id' => $providerId,
                    'supplier_code' => $sku,
                    'oem_code' => $oem,
                    'name' => $name,
                    'price' => $price,
                    'is_active' => $isActive,
                    // Category could be generic if not provided
                    'category_id' => 1, 
                ]);
                $created++;
            }
        }

        fclose($file);
        unlink($fullPath);

        session()->flash('success', "Inventario actualizado: {$updated} actualizados, {$created} creados.");
        $this->csvFile = null;
    }

    public function render()
    {
        $provider = Auth::guard('provider')->user();
        
        // Pendientes (En preparación)
        $pendingItems = PedidoItem::with(['pedido.customer', 'product'])
            ->where('provider_id', $provider->id)
            ->whereHas('pedido', function($q) {
                $q->whereIn('status', ['por_confirmar', 'en_preparacion']);
            })
            ->latest()
            ->get();

        // Productos del catálogo
        $products = Product::where('provider_id', $provider->id)->latest()->paginate(20);

        return view('livewire.b2b-portal', compact('provider', 'pendingItems', 'products'))
            ->layout('layouts.b2b');
    }
}
