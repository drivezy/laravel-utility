<?php

namespace Hemantanshu\LaravelUtility\Models;


use Illuminate\Database\Eloquent\Model;

class LookupValue extends Model {
    protected $table = 'hm_lookup_values';
    protected $guarded = ['created_at', 'updated_at'];

    public function lookup_type () {
        return $this->belongsTo(LookupType::class);
    }

}