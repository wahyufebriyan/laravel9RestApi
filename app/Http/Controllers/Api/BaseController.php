<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [
            'status' => true,
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages =[])
    {
        $response = [
            'status' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages))
        {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, 404);
    }
}
