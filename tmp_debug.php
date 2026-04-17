<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$request = Illuminate\Http\Request::create('/login', 'GET');
$response = $app->handle($request);
echo $response->getStatusCode() . PHP_EOL;
echo (string) $response->getContent();
