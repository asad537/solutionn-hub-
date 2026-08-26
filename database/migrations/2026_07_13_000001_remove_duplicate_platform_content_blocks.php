<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveDuplicatePlatformContentBlocks extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('platforms') || !Schema::hasColumn('platforms', 'content')) {
            return;
        }

        DB::table('platforms')->whereNotNull('content')->orderBy('id')->chunkById(100, function ($platforms) {
            foreach ($platforms as $platform) {
                $document = json_decode($platform->content, true);
                if (!is_array($document) || !isset($document['blocks']) || !is_array($document['blocks'])) {
                    continue;
                }

                $seen = [];
                $cleanBlocks = [];
                foreach ($document['blocks'] as $block) {
                    $type = $block['type'] ?? '';
                    if (in_array($type, ['header', 'paragraph'], true)) {
                        $plain = html_entity_decode(strip_tags($block['data']['text'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $key = preg_replace('/\s+/u', ' ', trim(mb_strtolower($plain)));
                        if ($key !== '' && isset($seen[$key])) {
                            continue;
                        }
                        if ($key !== '') {
                            $seen[$key] = true;
                        }
                    }
                    $cleanBlocks[] = $block;
                }

                if (count($cleanBlocks) !== count($document['blocks'])) {
                    $document['blocks'] = $cleanBlocks;
                    DB::table('platforms')->where('id', $platform->id)->update([
                        'content' => json_encode($document, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                        'updated_at' => now(),
                    ]);
                }
            }
        });
    }

    public function down()
    {
        // Duplicate generated content is intentionally not restored.
    }
}
