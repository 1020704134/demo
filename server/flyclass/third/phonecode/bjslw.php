<?php

/** 北京三六五科技有限公司 - 官方API */
class ThirdClassPhoneCodeSLW{
    
    private static $apiSource = "三五六短信API接口";
    private static $appHost = "http://underlineapis.hl365store.com";
    private static $appKey = "fb399a431c2e4f1bac1210457c9402f3";

	//发送短信验证码
    public function PhoneCodeSend($fpPhone,$fpCode,$sendType,$fpSignaHead,$fpModelInfor){
        //--- 变量 ---
        $vApiKey = self::$appKey;
        $vApiHost = self::$appHost;
        $vApiSource = self::$apiSource;
        
        //--- 发送准备 ---
        //模板信息
        $vModelInfor = $fpModelInfor;
        if(IsNull($vModelInfor)){return JsonInforFalse("未找到短信发送模板", $sendType);}
        $vModelId = $vModelInfor[0];
        $vModelString = $vModelInfor[1];
        $vModelBody = $vModelInfor[2];
        
        #签名开头(URL编码)
        $signature = strtolower(rawurlencode($fpSignaHead));
        #发送内容(URL编码)
        $modelId = $vModelId;
        $sendBody = $vModelBody;
        $vRecodeBody = $sendBody;
        $content = strtolower(rawurlencode($sendBody));
        $vJudgeNumber = 6;
        
        //添加短信验证码记录
        $vSendResult = ObjFlyPhoneCode() -> SystemPhoneCodeAdd($vApiSource,$fpPhone, $fpCode, $sendType, $vRecodeBody, $modelId, $vJudgeNumber);
        if(JudgeJsonFalse($vSendResult)){return $vSendResult;}
        
        #发送内容
        $querys = "content={$signature}{$content}&mobile={$fpPhone}&key={$vApiKey}";
        $bodys = "";
        $url = $vApiHost . "/sms/sendv2" . "?" . $querys;
        
        #发送短信
        $curl = curl_init();    //初始化CURL
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        
        $result = curl_exec($curl);
        $sendResult = "";
        $returnResult = "";
        if(strpos($result,'{"error_code":0,"reason":"成功"')>=0){
            $sendResult = "true";
            $returnResult = '{"result":"true","infor":"发送成功"}';
        }else{
            $sendResult = "false";
            $result = str_replace('"',"'",$result);
            $returnResult = '{"result":"false","infor":"发送失败","tips":"'.$result.'"}';
        }
        return $returnResult;
	    
	}
	
	//发送短信验证码模板添加
	public function PhoneCodeModelAdd(){

	    //--- 获取参数 ---
	    //签名
	    $vSignHead = GetParameter('sign_head', "");
	    if(IsNull($vSignHead)){return JsonModelParameterNull("sign_head");}
	    $vSignHead = strtolower(rawurlencode($vSignHead));
	    //模板内容
	    $vModelContent = GetParameter('content', "");
	    if(IsNull($vModelContent)){return JsonModelParameterNull("content");}
	    $vModelContent = HandleStringReplace($vModelContent,'^wn;','#');
	    $vModelContent = strtolower(rawurlencode($vModelContent));
	    
	    //--- 变量 ---
	    $vApiKey = self::$appKey;
	    $vApiHost = self::$appHost;
	    
	    //--- curl ---
		//北京互联三六五科技有限公司 API 短信接口
	    $curl = curl_init();
	    curl_setopt_array($curl, array(
	        CURLOPT_URL => "{$vApiHost}/sms/edittemplete?content={$vModelContent}&signature={$vSignHead}&key={$vApiKey}",
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_ENCODING => "",
	        CURLOPT_MAXREDIRS => 10,
	        CURLOPT_TIMEOUT => 30,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	        CURLOPT_CUSTOMREQUEST => "GET",
	    ));
	    $result = curl_exec($curl);
	    curl_close($curl);
	    if(strpos($result,'{"error_code":0,"reason":"成功"')>=0){
	        return JsonInforTrue("提交成功", "短信模板添加");
	    }else{
	        $result = str_replace('"',"'",$result);
	        return $result;
	    }
	}
	
	//发送短信验证码模板列表
	public function PhoneCodeModelList(){
	    $vApiKey = self::$appKey;
	    $vApiHost = self::$appHost;
	    #curl
	    $curl = curl_init();
	    curl_setopt_array($curl, array(
	        CURLOPT_URL => "{$vApiHost}/sms/tmplist?&key={$vApiKey}",
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_ENCODING => "",
	        CURLOPT_MAXREDIRS => 10,
	        CURLOPT_TIMEOUT => 30,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	        CURLOPT_CUSTOMREQUEST => "GET",
	    ));
	    $response = curl_exec($curl);
	    $err = curl_error($curl);
	    curl_close($curl);
	    if ($err) {
	        return "cURL Error #:" . $err;
	    }
	    return $response;
	}	
	
	
	//发送短信剩余条数
	public function PhoneCodeNumbers(){
	    $vApiKey = self::$appKey;
	    $vApiHost = self::$appHost;
	    #curl
	    $curl = curl_init();
	    curl_setopt_array($curl, array(
	        CURLOPT_URL => "{$vApiHost}/sms/status?&key={$vApiKey}",
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_ENCODING => "",
	        CURLOPT_MAXREDIRS => 10,
	        CURLOPT_TIMEOUT => 30,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	        CURLOPT_CUSTOMREQUEST => "GET",
	    ));
	    $result = curl_exec($curl);
	    curl_close($curl);
	    if(strpos($result,'{"error_code":0,"reason":"成功"')>=0){
	        return JsonInforTrue(GetJsonValue($result, "result"), "短信剩余条数");
	    }
	    return $result;
	}	

}

