<?php

namespace App\Models\Olx;

// todo добавить колонку катерогии
//  добавить колонку избранные

use App\Models\AttributeChangeLog;
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
        'tracked_olx_searches',
    ];

    public function olxSearches()
    {
        return $this->belongsToMany(OlxSearch::class, 'olx_search_olx_ad');
    }

    public function attributeLogs(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(AttributeChangeLog::class, 'logable');
    }

}
