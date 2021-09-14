<?php

namespace App\Traits;

trait ReturnData
{
    public function returnError($errNum=404, $msg)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }

    public function returnSuccessMessage($errNum = 200 , $msg = "")
    {
        return [
            'status' => true,
            'errNum' => $errNum,
            'msg' => $msg
        ];
    }

    public function returnData($key, $value, $msg = "")
    {
        return response()->json([
            'status' => true,
            'errNum' => 200,
            'msg' => $msg,
            $key => $value
        ]);
    }

    //////////////////
    public function returnValidationError($code = "404", $validator)
    {
        return $this->returnError($code, $validator->errors()->first());
    }
}
?>
