<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeChangeLog extends Model // AttributeChangeLog
{
    public function attributeChangeLogable()
    {
        return $this->morphTo();
    }
}
