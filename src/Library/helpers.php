<?php

use Drivezy\LaravelUtility\Library\Message;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Debug\Exception\FatalThrowableError;

/**
 * @return \Illuminate\Http\JsonResponse
 */
function invalid_operation ()
{
    return Response::json(insufficient_permission());
}

/**
 * @return mixed
 */
function unsupported_operation ()
{
    return Response::json(['success' => false, 'response' => 'This operation is not supported']);
}

/**
 * @param $message
 * @param null $errorCode
 * @return \Illuminate\Http\JsonResponse
 */
function failed_response ($message, $errorCode = null)
{
    return Response::json(failure_message($message, $errorCode));
}

/**
 * @param $message
 * @return \Illuminate\Http\JsonResponse
 */
function success_response ($message)
{
    return Response::json(success_message($message));
}

/**
 * @param $response
 * @return mixed
 */
function fixed_response ($response)
{
    return Response::json($response);
}

/**
 * @param $message
 * @return array
 */
function success_message ($message)
{
    return array_merge(['success' => true, 'response' => $message], Message::$message);
}

/**
 * @param $message
 * @param $errorCode
 * @return array
 */
function failure_message ($message, $errorCode = null)
{
    return array_merge(['success' => false, 'response' => $message, 'error_code' => $errorCode], Message::$message);
}

/**
 * @return array
 */
function insufficient_permission ()
{
    return array_merge(['success' => false, 'permission' => false, 'response' => 'Oops! You do not have sufficient permission. Please contact admin'], Message::$message);
}

/**
 * @param $request array of request params from front-end.
 * @param $requiredKeys array of required parameters.
 * @return array success/failure message.
 */
function required_parameter_check ($request, $requiredKeys)
{
    $missingParams = array_diff($requiredKeys, array_keys($request));
    if ( count($missingParams) )
        return failure_message(implode(',', $missingParams) . ' missing from params');

    return success_message(true);
}

/**
 * @param $__php
 * @param $__data
 * @return string
 * @throws Exception
 * @throws FatalThrowableError
 */
function render ($__php, $__data)
{
    $obLevel = ob_get_level();
    ob_start();
    extract($__data, EXTR_SKIP);
    try {
        eval('?' . '>' . $__php);
    } catch ( Exception $e ) {
        while ( ob_get_level() > $obLevel ) ob_end_clean();
        throw $e;
    } catch ( Throwable $e ) {
        while ( ob_get_level() > $obLevel ) ob_end_clean();
        throw new FatalThrowableError($e);
    }

    return ob_get_clean();
}

/**
 * return the set of db results from the query
 * @param $query
 * @return array
 */
function sql ($query)
{
    return DB::select(DB::raw($query));
}
