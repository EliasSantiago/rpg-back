<?php

namespace App\Helpers;

/**
 * Helper trait that contains handy methods to set json response,
 * with appropriate headers.
 */
trait SetsJsonResponse
{
    /**
     * Sets json response with given data and status code.
     *
     * @param  array  $data
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function setJsonResponse($data, $statusCode = 200)
    {
        return response()->json($data, $statusCode);
    }
}