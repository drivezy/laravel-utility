<?php

namespace Hemantanshu\LaravelUtility\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model {
    protected $table = 'hm_properties';
    protected $guarded = ['created_at', 'updated_at'];
}