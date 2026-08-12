<?php
$prompt = 'Eres un extractor de datos de catálogos técnicos de anillos de motor (piston rings). Analiza la imagen de la tabla del catálogo y devuelve un JSON. Devuelve SOLO el JSON sin texto adicional ni markdown.';
$imageData = base64_encode(file_get_contents('public/demo/SWH30114.png')); // We will test with the temp uploaded image or a mock one. Let us write a log file of what Ollama returned last time, or execute a test request directly.
