<?php

namespace Drivezy\LaravelUtility\Models;

use Drivezy\LaravelUtility\LaravelUtility;
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
        return $this->belongsTo(LaravelUtility::getUserModelFullQualifiedName(), 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updated_user () {
        return $this->belongsTo(LaravelUtility::getUserModelFullQualifiedName(), 'updated_by');
    }

    /**
     * @return array
     */
    public function toArray () {
        if ( self::$hide_columns )
            $this->addHidden(self::$hide_columns);

        return parent::toArray();
    }

    /**
     * @param $columns
     */
    public static function hideColumn ($columns) {
        self::$hide_columns = $columns;
    }
}