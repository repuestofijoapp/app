<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    /**
     * Sube un CSV para actualizar precio y stock (is_active) de un proveedor.
     */
    public function uploadCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
            'provider_id' => 'required|exists:providers,id',
        ]);

        $file = $request->file('csv_file');
        $providerId = $request->input('provider_id');
        
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle, 1000, ','); // Saltar cabecera
        
        $updatedCount = 0;
        $notFoundCount = 0;

        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            // Asumimos formato: código_proveedor, precio, stock
            // Ejemplo: BOS-S4-13, 445.50, 10
            if (count($data) < 3) continue;

            $code = trim($data[0]);
            $price = floatval($data[1]);
            $stock = intval($data[2]);

            $product = Product::where('provider_id', $providerId)
                              ->where('supplier_code', $code)
                              ->first();

            if ($product) {
                $product->update([
                    'price' => $price,
                    'is_active' => $stock > 0,
                ]);
                $updatedCount++;
            } else {
                $notFoundCount++;
            }
        }
        fclose($handle);

        return response()->json([
            'message' => 'Procesamiento completado',
            'updated' => $updatedCount,
            'not_found' => $notFoundCount,
        ]);
    }
}
