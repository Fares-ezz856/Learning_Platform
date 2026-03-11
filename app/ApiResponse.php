<?php

namespace App;

trait ApiResponse
{
    public function success($msg,$code=200,$data=null){
        $response=[
            'msg'=>$msg,
            'code'=>$code,
        ];
        if($data!=null){
            $response['data']=$data;
        }
        return response()->json($response,$code);
    }
    public function error($msg,$code){
        return response()->json([
            'msg'=>$msg,
            'code'=>$code
        ],$code);
    }
}
