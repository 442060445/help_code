<?php


namespace App\Http\Common;


use Illuminate\Support\Facades\Log;

class Helper
{
    /**
     * @function-remark:统一返回格式
     * @param $code
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     * @author Lin ShiXuan
     * @date: 2021/1/4 9:48
     */
    public static function returnFromat($code,$message = "",$data = []) {
        return response()->json([
            'code' => $code,
            'msg' => $message,
            'data' => $data,
            'powered by' => "C_Hiang",
            'sponsored by' => env('SPONSOREDBY',"")
        ]);
    }

    /**
     * @function-remark:校验码
     * @param $type
     * @param $code
     * @return bool
     * @author Lin ShiXuan
     * @date: 2021/1/4 9:48
     */
    public static function checkCode($type,$code){
        //获取配置
        $conditionArray = config('typeToCheck.'.$type);
        //如果为空（空数组，没有定义任何规则）
        if(empty($conditionArray)){
            return true;
        }
        //循环所有条件进行测试
        foreach ($conditionArray as $condition => $result){
            //出现一个不符合条件的，直接返回校验失败
            if (preg_match($condition, $code) == $result) {
                return false;
            }
        }
        //所有都循环完了，然后都没有出去，表示正常，所以就将他返回为检验成功。
        return true;
    }


}
