<?php

namespace App\Models;

// todo добавить колонку катерогии
//  добавить колонку избранные

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OlxAd extends Model
{
    // todo валюту перенести в окрему таблицю
    protected $dates = [
        'deleted_at',
        'publication_at',
        'last_active_at',
        'not_found_at',
    ];

    // динаміку зміни ціни можна зберігати в json разом з датою зміни

    protected $fillable = [
        'url',
        'title',
        'price',
        'currency',
        'description',
        'publication_at',
        'last_active_at',
        'not_found_at',
    ];

    public static function firstOrNewByUrl($url): self
    {
        $obj = static::where('url', $url)->firstOrNew();

        return $obj;
    }

}
