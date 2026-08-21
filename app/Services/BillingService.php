<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BillingService
{
    /**
     * Generate an electronic Boleta using APIsPerú.
     * Note: NRUS (Régimen Único Simplificado) only allows issuing Boletas.
     * 
     * @param \App\Models\Pedido $pedido
     * @param string $billingType Ignored (forced to 'boleta')
     * @param array $customerData Contains DNI/RUC and Name
     * @return array
     */
    public function generateInvoice($pedido, $billingType, $customerData)
    {
        Log::info("Iniciando generación de boleta electrónica para el pedido #{$pedido->id}");

        $apiUrl = env('APISPERU_API_URL', 'https://facturacion.apisperu.com/api/v1');
        $token = env('APISPERU_TOKEN');
        $companyRuc = env('APISPERU_COMPANY_RUC', '20000000001');
        $companyName = env('APISPERU_COMPANY_NAME', 'Repuesto Fijo');
        $companyAddress = env('APISPERU_COMPANY_ADDRESS', 'Dirección de la Empresa');
        $companyDep = env('APISPERU_COMPANY_DEPARTAMENTO', 'LIMA');
        $companyProv = env('APISPERU_COMPANY_PROVINCIA', 'LIMA');
        $companyDist = env('APISPERU_COMPANY_DISTRITO', 'LIMA');
        $companyUbigeo = env('APISPERU_COMPANY_UBIGEO', '150101');

        if (empty($token)) {
            Log::warning("APISPERU_TOKEN no configurado en .env. Usando modo simulación para desarrollo.");
            return $this->mockInvoiceResponse($pedido);
        }

        // Prepare Client Info
        $numDoc = preg_replace('/[^0-9]/', '', $customerData['ruc_dni'] ?? $customerData['ruc'] ?? '');
        $tipoDocClient = '1'; // Default DNI
        if (strlen($numDoc) === 11) {
            $tipoDocClient = '6'; // RUC
        } elseif (strlen($numDoc) < 8) {
            $tipoDocClient = '0'; // Sin documento / no domiciliado
            $numDoc = '00000000';
        }

        $clientName = trim($customerData['business_name'] ?? $customerData['name'] ?? 'Cliente General');

        // Formulate details and calculate totals
        $details = [];
        $totalMtoOperGravadas = 0;
        $totalMtoIGV = 0;
        $totalValorVenta = 0;
        $totalImpuestos = 0;
        $totalSubTotal = 0;

        foreach ($pedido->items as $index => $item) {
            $qty = $item->cantidad;
            $priceWithIgv = floatval($item->precio_unitario);
            
            // Calculations
            $subTotalItem = round($priceWithIgv * $qty, 2);
            $valorUnitario = round($priceWithIgv / 1.18, 4);
            $valorVenta = round($valorUnitario * $qty, 2);
            
            // Adjust to ensure subtotal matches exactly
            $igvItem = round($subTotalItem - $valorVenta, 2);
            
            // Build oversize label for the product code (supplier_code + super/oversize)
            $oversizeLabel = '';
            
            $itemOversize = $item->oversize;
            if (empty($itemOversize) && $item->product) {
                $itemOversize = $item->product->oversize;
            }

            if (!empty($itemOversize)) {
                $oversizeLabel = ($itemOversize === 'STD')
                    ? ' STD'
                    : ' +' . $itemOversize;
            }

            // Supplier code always shown WITH oversize next to it
            $codProducto = ($item->product->supplier_code ?? ('P' . str_pad($item->product_id, 3, '0', STR_PAD_LEFT))) . $oversizeLabel;

            // Description: product name without oversize suffixes (super already shown in code)
            // Strip common oversize patterns that may be baked into the product name
            $productName = trim($item->product->name ?? '');
            $productName = preg_replace('/\s+(STD|\+?0[257][05]|\+?1\.?0+|UNI)\s*$/i', '', $productName);
            $productName = trim($productName) ?: 'Repuesto';

            // Append OEM codes to description as additional reference (oem_code + additional_oem_codes)
            $oemParts = [];
            if (!empty($item->product->oem_code)) {
                $oemParts[] = $item->product->oem_code;
            }
            $addOems = $item->product->additional_oem_codes ?? [];
            if (is_array($addOems)) {
                foreach (array_slice($addOems, 0, 2) as $aOem) {
                    if (!empty(trim($aOem))) {
                        $oemParts[] = trim($aOem);
                    }
                }
            }
            $descripcion = $productName . (!empty($oemParts) ? ' - Ref: ' . implode(' / ', $oemParts) : '');

            $details[] = [
                'codProducto' => $codProducto,
                'unidad' => 'NIU',
                'descripcion' => $descripcion,
                'cantidad' => $qty,
                'mtoValorUnitario' => round($valorUnitario, 2),
                'mtoValorVenta' => $valorVenta,
                'mtoBaseIgv' => $valorVenta,
                'porcentajeIgv' => 18,
                'igv' => $igvItem,
                'tipAfeIgv' => '10', // Gravado - Operación Onerosa
                'totalImpuestos' => $igvItem,
                'mtoPrecioUnitario' => $priceWithIgv,
            ];

            $totalMtoOperGravadas += $valorVenta;
            $totalMtoIGV += $igvItem;
            $totalValorVenta += $valorVenta;
            $totalImpuestos += $igvItem;
            $totalSubTotal += $subTotalItem;
        }

        // Add Shipping cost as a service item
        if (floatval($pedido->costo_envio) > 0) {
            $envio = floatval($pedido->costo_envio);
            $valorUnitarioEnvio = round($envio / 1.18, 4);
            $valorVentaEnvio = round($valorUnitarioEnvio, 2);
            $igvEnvio = round($envio - $valorVentaEnvio, 2);

            $details[] = [
                'codProducto' => 'SENV',
                'unidad' => 'ZZ', // Servicio
                'descripcion' => 'Costo de Envío',
                'cantidad' => 1,
                'mtoValorUnitario' => round($valorUnitarioEnvio, 2),
                'mtoValorVenta' => $valorVentaEnvio,
                'mtoBaseIgv' => $valorVentaEnvio,
                'porcentajeIgv' => 18,
                'igv' => $igvEnvio,
                'tipAfeIgv' => '10',
                'totalImpuestos' => $igvEnvio,
                'mtoPrecioUnitario' => $envio,
            ];

            $totalMtoOperGravadas += $valorVentaEnvio;
            $totalMtoIGV += $igvEnvio;
            $totalValorVenta += $valorVentaEnvio;
            $totalImpuestos += $igvEnvio;
            $totalSubTotal += $envio;
        }

        $totalSubTotal = round($totalSubTotal, 2);
        $totalMtoOperGravadas = round($totalMtoOperGravadas, 2);
        $totalMtoIGV = round($totalMtoIGV, 2);
        $totalValorVenta = round($totalValorVenta, 2);
        $totalImpuestos = round($totalImpuestos, 2);

        $legendText = $this->numberToWords($totalSubTotal);

        // Build Payload according to APIsPERU Swagger Spec (Boleta)
        $payload = [
            'ublVersion' => '2.1',
            'tipoOperacion' => '0101',
            'tipoDoc' => '03', // Boleta de Venta
            'serie' => 'B001',
            'correlativo' => (string) $pedido->id,
            'fechaEmision' => now()->setTimezone('America/Lima')->format('Y-m-d\TH:i:sP'),
            'formaPago' => [
                'moneda' => 'PEN',
                'tipo' => 'Contado'
            ],
            'tipoMoneda' => 'PEN',
            'client' => [
                'tipoDoc' => $tipoDocClient,
                'numDoc' => $numDoc,
                'rznSocial' => $clientName,
                'address' => [
                    'direccion' => trim(($pedido->direccion ?? '') . ' ' . ($pedido->distrito ?? '')) ?: 'Dirección del Cliente'
                ]
            ],
            'company' => [
                'ruc' => $companyRuc,
                'razonSocial' => $companyName,
                'nombreComercial' => $companyName,
                'address' => [
                    'direccion' => $companyAddress,
                    'provincia' => $companyProv,
                    'departamento' => $companyDep,
                    'distrito' => $companyDist,
                    'ubigueo' => $companyUbigeo
                ]
            ],
            'mtoOperGravadas' => $totalMtoOperGravadas,
            'mtoIGV' => $totalMtoIGV,
            'valorVenta' => $totalValorVenta,
            'totalImpuestos' => $totalImpuestos,
            'subTotal' => $totalSubTotal,
            'mtoImpVenta' => $totalSubTotal,
            'details' => $details,
            'legends' => [
                [
                    'code' => '1000',
                    'value' => $legendText
                ]
            ]
        ];

        try {
            Log::info("Enviando comprobante electrónico a APIsPERU...", ['payload' => $payload]);

            $response = Http::withToken($token)
                ->timeout(15)
                ->post("{$apiUrl}/invoice/send", $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                $pdfUrl = $data['pdfUrl'] ?? null;
                $xmlUrl = $data['xmlUrl'] ?? null;

                if (!$pdfUrl) {
                    // Try to request PDF generation explicitly if not in send invoice response
                    $pdfResponse = Http::withToken($token)
                        ->timeout(10)
                        ->post("{$apiUrl}/invoice/pdf", $payload);
                    if ($pdfResponse->successful()) {
                        $pdfContent = $pdfResponse->body();
                        $filename = "boleta-{$pedido->id}.pdf";
                        $publicPath = public_path("invoices");
                        if (!file_exists($publicPath)) {
                            mkdir($publicPath, 0755, true);
                        }
                        file_put_contents("{$publicPath}/{$filename}", $pdfContent);
                        $pdfUrl = asset("invoices/{$filename}");
                    }
                }

                Log::info("Boleta electrónica emitida exitosamente por APIsPERU. Serie: " . ($data['sunatResponse']['cdrResponse']['id'] ?? 'B001-' . $pedido->id));

                $pedido->update([
                    'billing_type' => 'boleta',
                    'invoice_url' => $pdfUrl ?: '#',
                    'invoice_xml' => $xmlUrl ?: '#',
                ]);

                return [
                    'success' => true,
                    'message' => 'Boleta electrónica emitida correctamente.',
                    'data' => [
                        'number' => 'B001-' . $pedido->id,
                        'pdf_url' => $pdfUrl,
                        'xml_url' => $xmlUrl,
                    ]
                ];
            } else {
                Log::error("Error de APIsPERU: " . $response->body());
                return [
                    'success' => false,
                    'message' => 'Error de APIsPERU: ' . ($response->json()['message'] ?? 'Error desconocido al emitir.'),
                ];
            }
        } catch (\Exception $e) {
            Log::error("Excepción al emitir comprobante electrónico: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error de conexión con la SUNAT/APIsPERU.',
            ];
        }
    }

    private function mockInvoiceResponse($pedido)
    {
        $fakeInvoiceNumber = 'B001-' . str_pad($pedido->id, 6, '0', STR_PAD_LEFT);
        $mockResponse = [
            'success' => true,
            'message' => 'Comprobante emitido correctamente (Modo Simulación).',
            'data' => [
                'number' => $fakeInvoiceNumber,
                'pdf_url' => "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
                'xml_url' => "#",
            ]
        ];

        $pedido->update([
            'billing_type' => 'boleta',
            'invoice_url' => $mockResponse['data']['pdf_url'],
            'invoice_xml' => $mockResponse['data']['xml_url'],
        ]);

        return $mockResponse;
    }

    private function numberToWords($number)
    {
        $cents = round(($number - floor($number)) * 100);
        $integer = floor($number);
        
        if ($integer == 0) return "CERO CON " . str_pad($cents, 2, '0', STR_PAD_LEFT) . "/100 SOLES";
        
        $parts = [];
        
        // Thousands
        $thousands = floor($integer / 1000);
        $integer %= 1000;
        if ($thousands > 0) {
            if ($thousands == 1) {
                $parts[] = 'MIL';
            } else {
                $parts[] = $this->convertGroup($thousands) . ' MIL';
            }
        }
        
        // Hundreds, Tens, Units
        if ($integer > 0) {
            if ($integer == 100) {
                $parts[] = 'CIEN';
            } else {
                $parts[] = $this->convertGroup($integer);
            }
        }
        
        $words = implode(' ', array_filter($parts));
        return "SON " . $words . " CON " . str_pad($cents, 2, '0', STR_PAD_LEFT) . "/100 SOLES";
    }

    private function convertGroup($num)
    {
        $units = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $tens = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $teens = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
        $hundreds = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

        $parts = [];
        
        $h = floor($num / 100);
        $rem = $num % 100;
        
        if ($h > 0) {
            $parts[] = $hundreds[$h];
        }
        
        if ($rem > 0) {
            if ($rem < 10) {
                $parts[] = $units[$rem];
            } elseif ($rem >= 10 && $rem < 20) {
                $parts[] = $teens[$rem - 10];
            } else {
                $t = floor($rem / 10);
                $u = $rem % 10;
                if ($u > 0) {
                    $parts[] = $tens[$t] . ' Y ' . $units[$u];
                } else {
                    $parts[] = $tens[$t];
                }
            }
        }
        
        return implode(' ', $parts);
    }
}
