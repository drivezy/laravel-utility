<?php

namespace Drivezy\LaravelUtility\Observers;

use Drivezy\LaravelAccessManager\ImpersonationManager;
use Drivezy\LaravelRecordManager\Jobs\ObserverEventManagerJob;
use Drivezy\LaravelRecordManager\Library\BusinessRuleManager;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Auth;

/**
 * Class BaseObserver
 * @package Drivezy\LaravelUtility\Observers
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
        //push this one for audit log
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

        $model = BusinessRuleManager::handleUpdateRules($model);
        //find all the rules that are matching the update rule
        if ( $model->abort ) return false;

        if ( Auth::check() )
            $model->updated_by = ImpersonationManager::getActualLoggedUser()->id;

    }

    /**
     * @param Eloquent $model
     */
    public function updated (Eloquent $model) {
        BusinessRuleManager::handleUpdateRules($model);
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

        $model = BusinessRuleManager::handleCreatingRules($model);
        //find all the rules that are matching the update rule
        if ( $model->abort ) return false;

        if ( Auth::check() ) {
            $model->created_by = ImpersonationManager::getActualLoggedUser()->id;
            $model->updated_by = ImpersonationManager::getActualLoggedUser()->id;
        }
    }

    /**
     * @param Eloquent $model
     */
    public function created (Eloquent $model) {
        BusinessRuleManager::handleCreatedRules($model);
    }

    /**
     * @param Eloquent $model
     */
    public function deleting (Eloquent $model) {
        $model = BusinessRuleManager::handleDeletingRules($model);
        //find all the rules that are matching the update rule
        if ( $model->abort ) return false;

        if ( Auth::check() ) {
            $model->updated_by = ImpersonationManager::getActualLoggedUser()->id;
        }
    }

    /**
     * @param Eloquent $model
     */
    public function deleted (Eloquent $model) {
        BusinessRuleManager::handleDeletedRules($model);

        //push this one for audit log
        $this->saveObserverEvent($model);
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

    /**
     * @param Eloquent $model
     * @throws \Exception
     */
    protected function saveObserverEvent (Eloquent $model) {
        //create object against the observer event
        $obj = new ObserverEventManagerJob($model);

        //see if the dispatching fails then run the job serially in the system.
        //only applicable for those events wherein the request size is extremely big
        //cannot do for all as this request will take little more time to move ahead
        try {
            dispatch($obj);
        } catch ( \Exception $e ) {
            ( $obj )->handle();
        }
    }

    /**
     * @param Eloquent $model
     * @param $attribute
     * @return bool
     */
    protected function hasAttributeChanged (Eloquent $model, $attribute) {
        if ( !$model->id ) return true;

        if ( $model->getOriginal($attribute) !== $model->getAttribute($attribute) )
            return true;

        return false;
    }
}
