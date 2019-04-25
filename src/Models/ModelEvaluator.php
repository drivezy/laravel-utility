<?php

namespace Drivezy\LaravelUtility\Models;


/**
 * Check for the model operations done at the system
 * Trait ModelEvaluator
 * @package Drivezy\LaravelUtility\Models
 */
trait ModelEvaluator {

    /**
     * Check if the column has changed its value
     * @param $column
     * @return bool
     */
    public function hasChanged ($column) {
        if ( $this->isNewRecord() ) return true;
        if ( $this->getOriginal($column) != $this->getAttribute($column) ) return true;

        return false;
    }

    /**
     * Check if the column is empty or null
     * @param $column
     * @return bool
     */
    public function isNull ($column) {
        $value = $this->getAttribute($column);
        if ( is_null($value) || empty($value) ) return true;

        return false;
    }

    /**
     * check if the column has some value
     * @param $column
     * @return bool
     */
    public function isNotNull ($column) {
        return !$this->isNull($column);
    }

    /**
     * greater than operator check
     * @param $column
     * @param $value
     * @return bool
     */
    public function gt ($column, $value) {
        if ( $this->getAttribute($column) > $value ) return true;

        return false;
    }

    /**
     * greater than equal to operator
     * @param $column
     * @param $value
     * @return bool
     */
    public function gteq ($column, $value) {
        if ( $this->getAttribute($column) >= $value ) return true;

        return false;
    }

    /**
     * less than operator
     * @param $column
     * @param $value
     * @return bool
     */
    public function lt ($column, $value) {
        if ( $this->getAttribute($column) < $value ) return true;

        return false;
    }

    /**
     * less than equal to operator
     * @param $column
     * @param $value
     * @return bool
     */
    public function lteq ($column, $value) {
        if ( $this->getAttribute($column) <= $value ) return true;

        return false;
    }

    /**
     * between operator
     * @param $column
     * @param $start
     * @param $end
     * @return bool
     */
    public function between ($column, $start, $end) {
        $value = $this->getAttribute($column);
        if ( $value >= $start && $value <= $end ) return true;

        return false;
    }

    /**
     * not between operator
     * @param $column
     * @param $start
     * @param $end
     * @return bool
     */
    public function notBetween ($column, $start, $end) {
        return !$this->between($column, $start, $end);
    }

    /**
     * equality check operator
     * @param $column
     * @param $value
     * @return bool
     */
    public function equals ($column, $value) {
        if ( $this->getAttribute($column) == $value ) return true;

        return false;
    }

    /**
     * not equals operator
     * @param $column
     * @param $value
     * @return bool
     */
    public function notEquals ($column, $value) {
        return !$this->equals($column, $value);
    }

    /**
     * get original value of the record before being modified
     * @param $column
     * @return mixed
     */
    public function originalValue ($column) {
        return $this->getOriginal($column);
    }

    /**
     * check if the given record is absolutely new
     * @return bool
     */
    public function isNewRecord () {
        if ( $this->getOriginal('id') != $this->getAttribute('id') ) return true;

        return false;
    }

    /**
     * @return bool
     */
    public function isTrashed () {
        return $this->getAttribute('deleted_at') ? true : false;
    }

    /**
     * Check if the upcoming record is duplicate or not
     * @param array $args
     * @return bool
     */
    public function isDuplicateRecord ($args = []) {
        //put check for empty array
        if ( !sizeof($args) ) return false;

        //get class name
        $model = $this->getActualClassNameForMorph($this->getMorphClass());

        //create search condition on the model
        $condition = [];
        foreach ( $args as $key )
            $condition[ $key ] = $this->{$key};

        //find any record on the model which matches against it
        $record = $model->where($condition)->first();

        //if record is found and record doesnt match the parent id then its duplicate
        if ( $record && $record->id != $this->id ) return true;

        return false;
    }
}
