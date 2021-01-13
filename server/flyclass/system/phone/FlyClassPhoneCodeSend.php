<?php

//引入类
//include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/third/phonecode/aliyunbjslw.php";  //阿里云市场：北京365短信API
include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/third/phonecode/bjslw.php";        //官网：北京365短信API

//短信验证码发送类
class FlyClassPhoneCodeSend{
    
    //短信签名
    private static $vSignaHead = "【汽车质保系统】";
    
    //获取短信验证码发送对象（需配置项）
    private function ObjectGet(){
        return new ThirdClassPhoneCodeSLW();   
    }
    
    //发送模板定义:模板获取（需配置项）
    private function ModelGet($fpModelType,$fpCode){
        if($fpModelType=="品牌用户注册"){
            return ["11","您正在注册汽车质保系统，验证码为#code#，若非本人操作，请联系平台客服。","您正在注册汽车质保系统，验证码为{$fpCode}，若非本人操作，请联系平台客服。"];
        }else if($fpModelType=="用户注册"){
            return ["12","您正在找回密码，验证码为#code#，若非本人操作，请联系平台客服。","您正在找回密码，验证码为{$fpCode}，若非本人操作，请联系平台客服。"];
        }else if($fpModelType=="品牌用户密码找回"){
            return ["13","您正在修改密码，验证码为#code#，若非本人操作，请联系平台客服。","您正在修改密码，验证码为{$fpCode}，若非本人操作，请联系平台客服。"];
        }else if($fpModelType=="品牌用户保单提交"){
            return ["15","您正在提交保单，验证码为#code#，若非本人操作，请联系平台客服。","您正在提交保单，验证码为{$fpCode}，若非本人操作，请联系平台客服。"];
        }else if($fpModelType=="品牌用户保单修改"){
            return ["16","您正在修改保单，验证码为#code#，若非本人操作，请联系平台客服。","您正在修改保单，验证码为{$fpCode}，若非本人操作，请联系平台客服。"];
        }else if($fpModelType=="品牌用户保单申请"){
            return ["17","您正在申请保单，验证码为#code#，若非本人操作，请联系平台客服。","您正在申请保单，验证码为{{$fpCode}}，若非本人操作，请联系平台客服。"];
        }
        return null;
    }
    
    //发送短信验证码
    public function PhoneCodeSend($fpPhone,$fpCode,$sendType,$sendEventId){
        $vModelInfor = self::ModelGet($sendType, $fpCode);
        $sendEventSign = $sendType;
        if(!IsNull($sendEventId)){$sendEventSign .= ":{$sendEventId}";}
        return $this->ObjectGet() -> PhoneCodeSend($fpPhone,$fpCode,$sendEventSign,self::$vSignaHead,$vModelInfor);
    }
    
    //发送短信验证码验证
    public function PhoneCodeJudge($fpPhone,$fpCode,$sendEvent){
        return ObjFlyPhoneCode() -> SystemPhoneCodeJudge($fpPhone, $fpCode, $sendEvent);
    }
    
    //发送短信验证码模板添加
    public function PhoneCodeModelAdd(){
        return $this->ObjectGet() -> PhoneCodeModelAdd();
    }
    
    //发送短信验证码模板列表
    public function PhoneCodeModelList(){
        return $this->ObjectGet() -> PhoneCodeModelList();
    }
    
    //短信验证码剩余条数
    public function PhoneCodeNumbers(){
        return $this->ObjectGet() -> PhoneCodeNumbers();
    }
    
}
