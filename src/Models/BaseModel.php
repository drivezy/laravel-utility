<?php

namespace Hemantanshu\LaravelUtility\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 * @package Hemantanshu\LaravelUtility\Models
 */
class BaseModel extends Model {

    /**
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_user () {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updated_user () {
        return $this->belongsTo(User::class, 'updated_by');
    }
}