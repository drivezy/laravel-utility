<?php

namespace Drivezy\LaravelUtility\Library;

use Drivezy\LaravelRecordManager\Models\DeviceToken;
use Illuminate\Http\Request;

/**
 * Class DeviceTokenManager
 * @package Drivezy\LaravelUtility\Library
 */
class DeviceTokenManager
{
    /**
     * @param Request $request
     * @return bool
     */
    public static function captureDeviceToken (Request $request)
    {
        //check if all the headers are present which is required for the device token capture
        $headers = ['u-firebase-token', 'u-app-version', 'u-token-source-id', 'u-platform-id'];
        foreach ( $headers as $header ) {
            if ( !$request->headers->has($header) ) return false;
        }

        //find device with the given device token
        $token = DeviceToken::where('token', $request->headers->get('u-firebase-token'))->firstOrNew();

        $token->version = $request->headers->get('u-app-version');
        $token->token_source_id = $request->headers->get('u-token-source-id');
        $token->platform_id = $request->headers->get('u-platform-id');

        $token->save();
    }
}