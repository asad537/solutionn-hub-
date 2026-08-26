<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$request = Illuminate\Http\Request::create('/analyze', 'POST', [
    'video_url' => 'https://youtu.be/9O6iYYAS9rA'
]);
$request->headers->set('X-Requested-With', 'XMLHttpRequest'); // simulate ajax

$response = app()->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";
