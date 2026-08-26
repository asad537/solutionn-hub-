<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadPageSetting extends Model
{
    use HasFactory;

    protected $table = 'download_page_settings';
    
    protected $fillable = [
        'h1_heading',
        'description',
        'btn_text',
        'btn_link'
    ];
}
