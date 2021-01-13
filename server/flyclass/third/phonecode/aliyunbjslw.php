<?php

/** 阿里云市场：北京三六五科技有限公司 */
class ThirdClassPhoneCodeALiYunSLW{
    
    private static $apiSource = "三五六短信API接口";
    private static $appHost = "https://zwp.market.alicloudapi.com";
    private static $appCode = "a4d753b1369c45f0bcb2f4fb14f51e72";
    

	//发送短信验证码
    public function PhoneCodeSend($fpPhone,$fpCode,$sendType,$fpSignaHead,$fpModelInfor){	  
	    
		//北京互联三六五科技有限公司 API 短信接口
	    $vApiSource = self::$apiSource; 
	    $host = self::$appHost;
	    $path = "/sms/sendv2";
	    $method = "GET";
	    $appcode = self::$appCode;
	    $headers = array();
	    array_push($headers, "Authorization:APPCODE " . $appcode);
	    
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
	    $querys = "content={$signature}{$content}&mobile={$fpPhone}";
	    $bodys = "";
	    $url = $host . $path . "?" . $querys;
	
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($curl, CURLOPT_FAILONERROR, false);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HEADER, true);
	    if (1 == strpos("$".$host, "https://")){
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    }
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

	    //签名
	    $vSignHead = GetParameter('sign_head', "");
	    if(IsNull($vSignHead)){return JsonModelParameterNull("sign_head");}
	    //模板内容
	    $vModelContent = GetParameter('content', "");
	    if(IsNull($vModelContent)){return JsonModelParameterNull("content");}
	    $vModelContent = HandleStringReplace($vModelContent,'^wn;','#');
	    
		//北京互联三六五科技有限公司 API 短信接口
	    $host = self::$appHost;
	    $path = "/sms/edittemplete";
	    $method = "POST";
	    $appcode = self::$appCode;
	    $headers = array();
	    array_push($headers, "Authorization:APPCODE " . $appcode);
	    #在此修改签名(URL编码)
	    $signature = strtolower(rawurlencode($vSignHead));
	    #在此修改模板内容(URL编码)
	    //$content = strtolower(rawurlencode("您正在注册数据人数据分析平台，验证码为#code#，若非本人操作，请联系平台客服。"));
	    $content = strtolower(rawurlencode($vModelContent));
	    #组合模板提交字符串
	    $querys = "content={$content}&signature={$signature}";
	    $bodys = "";
	    $url = $host . $path . "?" . $querys;
	
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($curl, CURLOPT_FAILONERROR, false);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HEADER, true);
	    if (1 == strpos("$".$host, "https://")){
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    }
	    $result = curl_exec($curl);
	    if(strpos($result,'{"error_code":0,"reason":"成功"')>=0){
	    	return '{"result":"true","infor":"发送成功"}';
	    }else{
	    	$result = str_replace('"',"'",$result);
	    	return $result;	
	    }
	}
	
	//发送短信验证码模板列表
	public function PhoneCodeModelList(){
	    $host = self::$appHost;
	    $path = "/sms/tmplist";
	    $method = "GET";
	    $appcode = self::$appCode;
	    $headers = array();
	    array_push($headers, "Authorization:APPCODE " . $appcode);
	    $querys = "";
	    $bodys = "";
	    $url = $host . $path;
	
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($curl, CURLOPT_FAILONERROR, false);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HEADER, true);
	    if (1 == strpos("$".$host, "https://")){
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    }
	    $vResult = curl_exec($curl);
	    $vFind = '{"error_code"'; 
	    $vFindSub = stripos($vResult,$vFind);
	    if($vFindSub>=0){
	        return substr($vResult,$vFindSub);
	    }
	    return $vResult;
	}	

}

