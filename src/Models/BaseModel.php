<?php

namespace Drivezy\LaravelUtility\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BaseModel
 * @package Drivezy\LaravelUtility\Models
 */
class BaseModel extends Model {
    use SoftDeletes;
    /**
     * @var
     */
    public static $hide_columns;
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

    /**
     * @return array
     */
    public function toArray () {
        $this->addHidden(self::$hide_columns);

        return parent::toArray();
    }
}