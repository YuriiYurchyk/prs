<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    // todo proxy checker
    protected $table = 'proxies';
    protected $fillable = [
        'id',
        'ip_port',
        'login',
        'password',
        'alive',
    ];

    public function getAliveProxies()
    {
        return self::where('alive', true)->get();
    }

}
