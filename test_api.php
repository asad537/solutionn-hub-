<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$urls = [
    'YouTube' => 'https://youtu.be/9O6iYYAS9rA',
    'Facebook' => 'https://www.facebook.com/watch/?v=1101968831006126',
    'TikTok' => 'https://www.tiktok.com/@tiktok/video/7106594312292453678',
    'Instagram' => 'https://www.instagram.com/reel/C8_j3w_A-_C/',
    'Twitter' => 'https://x.com/SpaceX/status/1768273011270250684'
];

$pluginEndpoint = 'https://api.vidssave.com/api/contentsite_api/media/parse';

foreach ($urls as $platform => $url) {
    $pluginToken = base64_encode('vidssave_brower_plugin_' . round(microtime(true) * 1000));
    $response = \Illuminate\Support\Facades\Http::asForm()->post($pluginEndpoint, [
        'auth' => '20250901majwlqo',
        'domain' => 'api-ak.vidssave.com',
        'origin' => 'source',
        'link' => $url,
        'plugin_token' => $pluginToken
    ]);

    $data = $response->json();
    echo "$platform: Status " . ($data['status'] ?? 'Unknown') . " - " . ($data['msg'] ?? 'OK') . "\n";
}
