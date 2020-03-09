<?php

namespace Drivezy\LaravelUtility\Models;

use Drivezy\LaravelUtility\Observers\CommentDetailobserver;

class CommentDetail extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'dz_comment_details';

    /**
     * Load the observer rule against the model
     */
    public static function boot ()
    {
        parent::boot();
        self::observe(new CommentDetailobserver());
    }

}
