<?php

use Drivezy\LaravelUtility\Library\CustomLogging;

/**
 * @return \Illuminate\Http\JsonResponse
 */
function invalid_operation () {
    return Response::json(['success' => false, 'permission' => false, 'response' => 'Oops! You do not have sufficient permission. Please contact admin']);
}

/**
 * @return mixed
 */
function unsupported_operation () {
    return Response::json(['success' => false, 'response' => 'This operation is not supported']);
}

/**
 * @param $message
 * @param null $errorCode
 * @return \Illuminate\Http\JsonResponse
 */
function failed_response ($message, $errorCode = null) {
    return Response::json(['success' => false, 'response' => $message, 'error_code' => $errorCode]);
}

/**
 * @param $message
 * @return \Illuminate\Http\JsonResponse
 */
function success_response ($message) {
    return Response::json(success_message($message));
}

/**
 * @param $response
 * @return mixed
 */
function fixed_response ($response) {
    return Response::json($response);
}

/**
 * @param $message
 * @return array
 */
function success_message ($message) {
    $logs = CustomLogging::getResponseMessage();

    return ['success' => true, 'response' => $message, $logs];
}

/**
 * @param $message
 * @return array
 */
function failure_message ($message) {
    $logs = CustomLogging::getResponseMessage();

    return ['success' => false, 'response' => $message, $logs];
}

