<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function renderContent()
    {
        return self::renderEditorJS($this->content);
    }

    public static function renderEditorJS($content)
    {
        if (empty($content)) return '';

        $data = json_decode($content, true);
        if (!$data || !isset($data['blocks'])) {
            return $content; // Return as is if not valid JSON (e.g. legacy HTML)
        }

        $html = '';
        foreach ($data['blocks'] as $block) {
            switch ($block['type']) {
                case 'header':
                    $text = $block['data']['text'] ?? '';
                    $level = $block['data']['level'] ?? 2;
                    if (preg_match('/<h([1-6])[^>]*>/i', $text, $matches)) {
                        $level = $matches[1];
                    }
                    $text = strip_tags($text, '<a><b><strong><i><em><u><br><span><mark><del><font>');
                    if (trim(str_replace('&nbsp;', '', $text)) === '') break;
                    $html .= "<h{$level} style='font-weight:800; color:#0F172A; margin: 2rem 0 1rem; line-height:1.3;'>" . $text . "</h{$level}>";
                    break;
                case 'paragraph':
                    $text = $block['data']['text'] ?? '';
                    if (preg_match('/^<h([1-6])[^>]*>(.*)<\/h\1>$/is', trim($text), $matches)) {
                        $level = $matches[1];
                        $text = strip_tags($matches[2], '<a><b><strong><i><em><u><br><span><mark><del><font>');
                        if (trim(str_replace('&nbsp;', '', $text)) === '') break;
                        $html .= "<h{$level} style='font-weight:800; color:#0F172A; margin: 2rem 0 1rem; line-height:1.3;'>" . $text . "</h{$level}>";
                        break;
                    }
                    $text = strip_tags($text, '<a><b><strong><i><em><u><br><span><mark><del><font>');
                    if (trim(str_replace('&nbsp;', '', $text)) === '') break;
                    $html .= "<p style='font-size:1rem; color:#334155; line-height:1.8; margin-bottom:1.5rem;'>" . $text . "</p>";
                    break;
                case 'list':
                    $tag = ($block['data']['style'] === 'ordered') ? 'ol' : 'ul';
                    $listStyle = ($block['data']['style'] === 'ordered') ? 'decimal' : 'disc';
                    $html .= "<{$tag} style='margin-bottom:1.5rem; padding-left:1.5rem; color:#334155; list-style-type: {$listStyle};'>";
                    foreach ($block['data']['items'] as $item) {
                        $itemText = is_array($item) ? ($item['content'] ?? $item['text'] ?? json_encode($item)) : $item;
                        $html .= "<li style='margin-bottom:0.5rem; line-height:1.6;'>{$itemText}</li>";
                    }
                    $html .= "</{$tag}>";
                    break;
                case 'code':
                    $code = htmlspecialchars($block['data']['code'] ?? '');
                    $html .= "<div style='margin: 2rem 0; background: #1E293B; border-radius: 8px; padding: 1.5rem; overflow-x: auto;'><pre style='margin: 0;'><code style='color: #E2E8F0; font-family: monospace; font-size: 0.9rem;'>{$code}</code></pre></div>";
                    break;
                case 'image':
                    $url = $block['data']['file']['url'] ?? '';
                    $caption = $block['data']['caption'] ?? '';
                    $stretched = ($block['data']['stretched'] ?? false) ? 'width:100%;' : 'max-width:100%; height:auto;';
                    $html .= "<figure style='margin:2.5rem auto; text-align:center;'>
                        <img src='{$url}' alt='{$caption}' style='{$stretched} border-radius:16px; box-shadow:0 20px 40px rgba(0,0,0,0.08);'>
                        <figcaption style='margin-top:1rem; color:#64748B; font-size:0.85rem; font-style:italic;'>{$caption}</figcaption>
                    </figure>";
                    break;
                case 'quote':
                    $text = $block['data']['text'] ?? '';
                    $caption = $block['data']['caption'] ?? '';
                    $html .= "<blockquote style='border-left:5px solid #FFB800; padding:2rem; background:#F8FAFC; margin:2.5rem 0; border-radius:0 12px 12px 0;'>
                        <p style='font-size:1.25rem; font-weight:500; color:#1E293B; margin-bottom:1rem; line-height:1.5;'>\"{$text}\"</p>
                        <cite style='font-size:0.95rem; color:#64748B; font-weight:600; display:block;'>— {$caption}</cite>
                    </blockquote>";
                    break;
                case 'table':
                    $html .= "<div style='overflow-x:auto; margin:2rem 0;'><table style='width:100%; border-collapse:collapse; border:1px solid #E2E8F0;'>";
                    foreach ($block['data']['content'] as $idx => $row) {
                        $bg = ($idx === 0) ? 'background:#F1F5F9;' : '';
                        $html .= "<tr style='{$bg}'>";
                        foreach ($row as $cell) {
                            $fw = ($idx === 0) ? 'font-weight:700;' : '';
                            $cellText = is_array($cell) ? ($cell['content'] ?? $cell['text'] ?? json_encode($cell)) : $cell;
                            $html .= "<td style='padding:1rem; border:1px solid #E2E8F0; color:#334155; {$fw}'>{$cellText}</td>";
                        }
                        $html .= "</tr>";
                    }
                    $html .= "</table></div>";
                    break;
                case 'checklist':
                    $html .= "<ul style='list-style:none; padding:0; margin-bottom:1.5rem;'>";
                    foreach ($block['data']['items'] as $item) {
                        $checked = $item['checked'] ? '✓' : '○';
                        $color = $item['checked'] ? '#10B981' : '#CBD5E1';
                        $html .= "<li style='display:flex; align-items:flex-start; gap:0.8rem; margin-bottom:0.6rem; color:#334155;'>
                            <span style='color:{$color}; font-weight:bold;'>{$checked}</span>
                            <span>{$item['text']}</span>
                        </li>";
                    }
                    $html .= "</ul>";
                    break;
                case 'embed':
                    $html .= "<div style='margin:2.5rem 0; border-radius:16px; overflow:hidden; box-shadow:0 15px 35px rgba(0,0,0,0.1);'>
                        <iframe src='{$block['data']['embed']}' width='100%' height='450' frameborder='0' allowfullscreen></iframe>
                    </div>";
                    break;
                case 'twoColumn':
                    $text = $block['data']['text'] ?? '';
                    $imgUrl = $block['data']['image']['url'] ?? '';
                    $imgAlt = $block['data']['image']['alt'] ?? '';
                    $reverse = ($block['data']['reverse'] ?? false) ? 'flex-direction: row-reverse;' : '';
                    $html .= "
                        <div class='render-two-column' style='display:flex; gap:40px; align-items:center; margin:3rem 0; {$reverse}'>
                            <div style='flex:1; font-size:1rem; color:#334155; line-height:1.8;'>{$text}</div>
                            <div style='flex:1;'><img src='{$imgUrl}' alt='{$imgAlt}' style='width:100%; border-radius:20px; box-shadow:0 15px 35px rgba(0,0,0,0.07);'></div>
                        </div>
                    ";
                    break;
                case 'raw':
                    $html .= $block['data']['html'];
                    break;
                case 'delimiter':
                    $html .= "<hr style='border:0; border-top:2px solid #F1F5F9; margin:3rem 0;'>";
                    break;
                default:
                    break;
            }
        }

        return $html;
    }
}
