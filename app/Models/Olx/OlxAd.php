<?php

namespace App\Models\Olx;

use App\Models\AttributeChangeLog;
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

    protected $fillable = [
        'url',
        'title',
        'price',
        'currency',
        'description',
        'publication_at',
        'last_active_at',
        'not_found_at',
        'category',
    ];

    public function olxSearches()
    {
        return $this->belongsToMany(OlxSearch::class, 'olx_search_olx_ad');
    }

    public function attributeLogs(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(AttributeChangeLog::class, 'attribute_change_logable');
    }

}
