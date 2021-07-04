<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use ReflectionClass;
use Illuminate\Routing\Controller as BaseController;


abstract class Base extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * Response Statuses
     */
    const SUCCESS = 200;
    const SUCCESS_EMPTY = 204;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const CONFLICT = 409;
    const NOT_FOUND = 404;

    protected $authUser;

    /**
     * Get authenticated user if any
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function getAuthUser () {
        return Auth::user();
    }

    /**
     * Return an error
     * @param array|string $message
     * @param int $code
     * @param array $headers
     * @param int $options
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respond ($message, $code = self::SUCCESS, $headers = [], $options = 0) {
        $code = $this->_validResponseCode($code);
        return $this->_normalizedResponse($message, $code, $headers, $options);
    }

    /**
     * Validate response code with a default valid code in case of error
     * @param $code
     * @return int
     */
    protected function _validResponseCode ($code) {
        try {
            $codes = $this::getCodes();
            return in_array($code, $codes) ? $code : $this::BAD_REQUEST;
        } catch (\Exception $e) {
            return $this::BAD_REQUEST;
        }
    }

    /**
     * A normalized json response for closure methods
     * @param $data
     * @param $code
     * @param array $headers
     * @param int $options
     * @return \Illuminate\Http\JsonResponse
     */
    protected function _normalizedResponse ($data, $code, $headers, $options) {
        return response()->json(array_merge($data,['username' => request()->session()->get('username')]), $code, $headers, $options);
    }

    /**
     * Get all response codes
     * @return mixed
     * @throws \ReflectionException
     */
    static function getCodes() {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
