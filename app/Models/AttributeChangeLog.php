<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeChangeLog extends Model // AttributeChangeLog
{
    protected $fillable = [
        'attribute',
        'value',
    ];

    public function attributeChangeLogable()
    {
        return $this->morphTo();
    }
}
