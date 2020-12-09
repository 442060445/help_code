<?php


namespace App\Http\Common;


use Illuminate\Support\Facades\Log;

class Helper
{
    public static function returnFromat($code,$message = "",$data = []) {
        return response()->json([
            'code' => $code,
            'msg' => $message,
            'data' => $data,
            'powered by' => "C_Hiang",
            'sponsored by' => env('SPONSOREDBY',"")
        ]);
    }


}
