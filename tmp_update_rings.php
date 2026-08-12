<?php
use App\Models\Product;

$img = 'products/product_1772362544_69a41b30a06b2.jpg';
$items = [
    ['codes' => ['SWD-20009'], 'oem' => '92067238', 'engine' => ['X18XEV'], 'vehicles' => 'OPTRA 1.8', 'specs' => ['raw' => '81.6MM 1.2X1.5X3.0', 'radial' => ['3.1', '3.3', '2.9'], 'shape' => 'BF-IB / TUC / NIFF-S']],
    ['codes' => ['SWD-20006'], 'oem' => '92062027', 'engine' => ['D-TEC 2.2'], 'vehicles' => 'PRINCE', 'specs' => ['raw' => '86.0MM 1.5X1.5X3.0', 'radial' => ['3.45', '3.5', '3.0'], 'shape' => 'BF / TUC / NIFF-S']],
    ['codes' => ['SWG-10026'], 'oem' => '93740229', 'engine' => ['F14D3', '85CB', '89CB'], 'vehicles' => 'LACETTI', 'specs' => ['raw' => '77.88MM 1.2X1.5X2.5', 'radial' => ['2.85', '3.3', '2.9'], 'shape' => 'BF / TUC / NIFF-H']],
    ['codes' => ['SWG-10029'], 'oem' => '93235611', 'engine' => ['E-TEC I 1.6L', 'X16XE1'], 'vehicles' => 'CORSA 1.6', 'specs' => ['raw' => '79.0MM 1.2X1.5X3.0', 'radial' => ['3.05', '3.3', '3.0'], 'shape' => 'BF / TUC / NIFF-S']],
    ['codes' => ['SWD-20002', 'SWL-02395'], 'oem' => '12140-78B00, 94581409', 'engine' => ['3EA', 'F6B', 'S-TEC I 0.8L'], 'vehicles' => 'MATIZ, TICO, FINO', 'specs' => ['raw' => '68.5MM 1.2X1.5X2.8', 'radial' => ['2.5', '2.8', '2.8'], 'shape' => 'BF / T1 / NIFF-S']],
    ['codes' => ['SWD-20003', 'SWL-02419'], 'oem' => 'NP960', 'engine' => ['NP960'], 'vehicles' => 'LANOS, ESPERO, CIELO', 'specs' => ['raw' => '76.5MM 1.5X1.5X3.0', 'radial' => ['2.65', '3.2', '2.9'], 'shape' => 'BF / TUC / NIFF-S']],
    ['codes' => ['SWD-20004', 'SWL-03047'], 'oem' => 'S1220014, 93740225', 'engine' => ['D-TEC 1.6'], 'vehicles' => 'LANOS, NUBIRA, ESPERO, AVEO 1.6', 'specs' => ['raw' => '79.0MM 1.2X1.5X3.0', 'radial' => ['3.05', '3.3', '3.0'], 'shape' => 'BF / TUC / NIFF-S']],
    ['codes' => ['SWD-20005'], 'oem' => '96325192, 93742293', 'engine' => ['4EA'], 'vehicles' => 'MATIZ, SPARK', 'specs' => ['raw' => '68.5MM 1.2X1.2X2.5', 'radial' => ['2.65', '2.8', '2.8'], 'shape' => 'BF-IB / T1 / NIFF-S']]
];
foreach ($items as $row) {
    foreach ($row['codes'] as $c) {
        Product::where('supplier_code', 'like', '%' . $c . '%')->get()->each(function ($p) use ($row, $img) {
            $p->oem_code = $row['oem'];
            $p->compatible_engines = $row['engine'];
            $p->compatible_vehicles = $row['vehicles'];
            $p->specs = $row['specs'];
            $p->image_path = $img;
            $p->save();
            echo "UPDATED: " . $p->supplier_code . " " . $p->oversize . "\n";
        });
    }
}
// Also update ALL other rings starting with SWD/SWG/SWL if they are empty
Product::where(function ($q) {
    $q->where('supplier_code', 'like', 'SW%D-%')->orWhere('supplier_code', 'like', 'SWG-%')->orWhere('supplier_code', 'like', 'SWL-%'); })
    ->whereNull('image_path')
    ->update(['image_path' => $img]);
