<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;

$cats = Category::all();
foreach($cats as $c1) {
    foreach($cats as $c2) {
        if ($c1->id < $c2->id) {
            $sim = 0;
            similar_text(strtolower($c1->name), strtolower($c2->name), $sim);
            if ($sim > 85 && $sim < 100) {
                echo "Similarity Found (>85%): '{$c1->name}' (ID: {$c1->id}) and '{$c2->name}' (ID: {$c2->id})\n";
            }
        }
    }
}
