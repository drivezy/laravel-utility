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
    use ModelEvaluator;

    /**
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by'];

    public static $hide_columns = [];
    public static $default_hidden_columns = ['created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by'];

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
        $this->handleHiddenColumns();

        return parent::toArray();
    }

    /**
     * check if there is any overriding on the hidden columns against the requested class.
     */
    private function handleHiddenColumns () {
        $callingClass = get_called_class();

        if ( isset(self::$hide_columns[ $callingClass ]) )
            $this->setHidden(self::$hide_columns[ $callingClass ]);
    }
}