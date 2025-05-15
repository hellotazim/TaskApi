<?php

namespace App\Classes;

use \Illuminate\Http\JsonResponse;

class ResponseWrapper {
    static function Start() : array {
        return array(
            "results" => null,
            "status" => "error",
            "error_type" => "",
            "code" => "",
            "message" => ""
        );
    }

    static function End($data) : JsonResponse {
        if($data['results'] !== null && ($data['error_type'] === null || $data['error_type'] === '')){
            $data['status'] = 'success';
        }
        return response()->json($data);
    }

}
