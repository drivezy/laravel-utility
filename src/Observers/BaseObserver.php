<?php

namespace Drivezy\LaravelUtility\Observers;

use Drivezy\LaravelAccessManager\ImpersonationManager;
use Drivezy\LaravelRecordManager\Jobs\ObserverEventManagerJob;
use Drivezy\LaravelRecordManager\Library\BusinessRuleManager;
use Exception;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Auth;
use Drivezy\LaravelRecordManager\Observers\DynamoEloquentObserverTrait as DynamoObserverTrait;
use Validator;

/**
 * Class BaseObserver
 * @package Drivezy\LaravelUtility\Observers
 */
class BaseObserver
{
    use DynamoObserverTrait;

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
    public function __construct ()
    {

    }

    /**
     * @param Eloquent $model
     * @return Eloquent
     */
    public function retrieved (Eloquent $model)
    {
        //check if dynamo trait is applicable or else return back
        if ( !isset($model->prefetch_dynamo_columns) ) return $model;

        //check if there are any columns that are to be pre fetched from the db
        if ( !$model->prefetch_dynamo_columns ) return $model;

        $data = $model->getDynamoAttributes();
        foreach ($data  as $key => $value )
            $model->{$key} = $value;

        return $model;
    }

    /**
     * @param Eloquent $model
     * @return bool
     */
    public function saving (Eloquent $model)
    {
        if ( isset($model->id) )
            $rules = sizeof($this->updateRules) ? $this->updateRules : $this->rules;
        else
            $rules = sizeof($this->createRules) ? $this->createRules : $this->rules;

        $this->validator = Validator::make([], $rules);
        $this->validator->setData($model->getAttributes());

        if ( $this->validator->fails() ) {
            $model->setAttribute('errors', $this->validator->errors());

            return false;
        }

        //check for columns that are marked as dynamo db element
        //segregate them and push them to the dynamo columns attribute
        $this->preSaving($model);
    }

    /**
     * @param Eloquent $model
     * @throws Exception
     */
    public function saved (Eloquent $model)
    {
        //push this one for audit log
        $this->saveObserverEvent($model);

        //set the dynamo columns back to the dynamo db
        $this->postSaved($model);
    }

    /**
     * @param Eloquent $model
     * @return bool
     */
    public function updating (Eloquent $model)
    {
        $rules = sizeof($this->updateRules) ? $this->updateRules : $this->rules;

        $this->validator = Validator::make([], $rules);
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
    public function updated (Eloquent $model)
    {
        BusinessRuleManager::handleUpdateRules($model);
    }

    /**
     * @param Eloquent $model
     * @return bool
     */
    public function creating (Eloquent $model)
    {
        $rules = sizeof($this->createRules) ? $this->createRules : $this->rules;

        $this->validator = Validator::make([], $rules);
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
    public function created (Eloquent $model)
    {
        BusinessRuleManager::handleCreatedRules($model);
    }

    /**
     * @param Eloquent $model
     * @return bool
     */
    public function deleting (Eloquent $model)
    {
        $model = BusinessRuleManager::handleDeletingRules($model);
        //find all the rules that are matching the update rule
        if ( $model->abort ) return false;

        if ( Auth::check() ) {
            $model->updated_by = ImpersonationManager::getActualLoggedUser()->id;
        }
    }

    /**
     * @param Eloquent $model
     * @throws Exception
     */
    public function deleted (Eloquent $model)
    {
        BusinessRuleManager::handleDeletedRules($model);

        //push this one for audit log
        $this->saveObserverEvent($model);
    }

    /**
     * @param Eloquent $model
     */
    public function restoring (Eloquent $model)
    {
    }

    /**
     * @param Eloquent $model
     */
    public function restored (Eloquent $model)
    {
    }

    /**
     * @param Eloquent $model
     * @throws Exception
     */
    protected function saveObserverEvent (Eloquent $model)
    {
        //create object against the observer event
        $obj = new ObserverEventManagerJob($model);

        //see if the dispatching fails then run the job serially in the system.
        //only applicable for those events wherein the request size is extremely big
        //cannot do for all as this request will take little more time to move ahead
        try {
            dispatch($obj);
        } catch ( Exception $e ) {
            ( $obj )->handle();
        }
    }

    /**
     * @param Eloquent $model
     * @param $attribute
     * @return bool
     */
    protected function hasAttributeChanged (Eloquent $model, $attribute)
    {
        if ( !$model->id ) return true;

        if ( $model->getOriginal($attribute) !== $model->getAttribute($attribute) )
            return true;

        return false;
    }
}
