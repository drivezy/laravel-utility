<?php

namespace Hemantanshu\LaravelUtility\Observers;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Auth;

/**
 * Class BaseObserver
 * @package Hemantanshu\LaravelUtility\Observers
 */
class BaseObserver {
    /**
     * @var array
     */
    protected $createRules = [];
    /**
     * @var array
     */
    protected $updateRules = [];
    /**
     * @var array
     */
    protected $rules = [];
    /**
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * BaseObserver constructor.
     */
    public function __construct () {

    }

    /**
     * @param Eloquent $model
     * @return bool
     */
    public function saving (Eloquent $model) {
        if ( isset($model->id) )
            $rules = sizeof($this->updateRules) ? $this->updateRules : $this->rules;
        else
            $rules = sizeof($this->createRules) ? $this->createRules : $this->rules;

        $this->validator = \Validator::make([], $rules);
        $this->validator->setData($model->getAttributes());

        if ( $this->validator->fails() ) {
            $model->setAttribute('errors', $this->validator->errors());

            return false;
        }
    }

    /**
     * @param Eloquent $model
     */
    public function saved (Eloquent $model) {
        $this->saveObserverEvent($model);
    }

    /**
     * @param Eloquent $model
     * @return bool
     */
    public function updating (Eloquent $model) {
        $rules = sizeof($this->updateRules) ? $this->updateRules : $this->rules;

        $this->validator = \Validator::make([], $rules);
        $this->validator->setData($model->getAttributes());

        if ( $this->validator->fails() ) {
            $model->setAttribute('errors', $this->validator->errors());

            return false;
        }

        if ( Auth::check() )
            $model->updated_by = Auth::id();

    }

    /**
     * @param Eloquent $model
     */
    public function updated (Eloquent $model) {

    }

    /**
     * @param Eloquent $model
     * @return bool
     */
    public function creating (Eloquent $model) {
        $rules = sizeof($this->createRules) ? $this->createRules : $this->rules;

        $this->validator = \Validator::make([], $rules);
        $this->validator->setData($model->getAttributes());

        if ( $this->validator->fails() ) {
            $model->setAttribute('errors', $this->validator->errors());

            return false;
        }

        if ( Auth::check() ) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        }
    }

    /**
     * @param Eloquent $model
     */
    public function created (Eloquent $model) {
    }

    /**
     * @param Eloquent $model
     */
    public function deleting (Eloquent $model) {
        if ( Auth::check() ) {
            $model->updated_by = Auth::id();
        }
    }

    /**
     * @param Eloquent $model
     */
    public function deleted (Eloquent $model) {

    }

    /**
     * @param Eloquent $model
     */
    public function restoring (Eloquent $model) {
    }

    /**
     * @param Eloquent $model
     */
    public function restored (Eloquent $model) {
    }
}