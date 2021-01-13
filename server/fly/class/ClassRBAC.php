<?php 

    // ---- Token ----
    
    /**获取Token*/
    function RBACTokenGet($id,$userType,$fpUserName,$overDay,$secret,$tips=""){
        
        //--- 参数值处理 ---
        $overDay = abs($overDay);   //超时天数转绝对值
        
        //--- 接口业务流程，返回接口信息 ---
        //--- 业务流程1：变量声明 ---
        $timeOverMinute = "";                   //超时分钟数
        $timeStart = GetTimestamp();            //token生成时间
        $timeEnd = "";                          //token结束时间
        $tokenKey = GetId("TOKEN");             //tolen编号
        $iss = $_SERVER['SERVER_NAME'];          //服务器域名
        
        //--- 业务流程2：业务数据处理 ---
        //根据天数判断超时分钟数
        if($overDay == "0"){
            $timeOverMinute = 720*60;
        }else{
            $timeOverMinute = $overDay*24*60*60;
        }
        //计算结束时间
        $timeEnd = bcadd($timeStart,$timeOverMinute);
        
        //--- 业务流程3：业务数据处理 ---
        //JWT:header:头部信息
        $jsonHeader = '{"typ":"JWT","alg":"HS256"}';
        $jsonHeader = base64_encode($jsonHeader);
        
        
        //--- JWT官方字段 ---
        //JWT:payload:主题信息，JWT规定了7个官方字段
        //JWT官方字段:iss(issuer)：签发人
        //JWT官方字段:sub(subject)：主题
        //JWT官方字段:aud(audience)：受众
        //JWT官方字段:iat(Issued At)：签发时间
        //JWT官方字段:nbf(Not Before)：生效时间
        //JWT官方字段:exp(expiration time)：过期时间
        //JWT官方字段:jti(JWT ID)：编号
        
        //--- JWT自定义字段 ---
        //JWT自定义字段:key：签发Key
        $pSub = "TOKEN";
        
        $jsonPayload  = "";
        //官方字段
        $jsonPayload .= JsonKeyValue("iss", $iss) . ",";
        $jsonPayload .= JsonKeyValue("sub", $pSub) . ",";
        $jsonPayload .= JsonKeyValue("aud", $iss) . ",";
        $jsonPayload .= JsonKeyValue("iat", $timeStart) . ",";
        $jsonPayload .= JsonKeyValue("nbf", $timeStart) . ",";
        $jsonPayload .= JsonKeyValue("exp", $timeEnd) . ",";
        $jsonPayload .= JsonKeyValue("jti", $tokenKey) . ",";
        //用户信息
        $jsonPayload .= JsonKeyValue("uid", $id) . ",";
        $jsonPayload .= JsonKeyValue("uname", $fpUserName) . ",";
        $jsonPayload .= JsonKeyValue("utype", $userType) . ",";
        
        
        //处理字符串:删除最后一个字符（逗号）
        $jsonPayload = JsonObj(HandleStringDeleteLast($jsonPayload));
        //BASE64加密
        $jsonPayload = base64_encode($jsonPayload);
        //JWT组合一阶段
        $jwtString = $jsonHeader . "." . $jsonPayload;
        //JWT:Sign:Json对象签名
        $jsonSign = hash_hmac('sha256', $jwtString, $secret);
        //JWT组合二阶段
        $jwtString = $jwtString . "." . $jsonSign;
        //返回签名结果
        if(IsNull($tips)){ $tips = "签名成功"; }
        //返回签名结果
        
        //添加Token生成日志
        $viat = date('Y-m-d H:i:s',$timeStart);
        $vnbf = $viat;
        $vexp = date('Y-m-d H:i:s',$timeEnd);
        ObjFlyTokenLog() -> SystemFlyTokenLogAdd(GetPathRemoteAddr(), $iss, $pSub, $iss, $viat, $vnbf, $vexp, $tokenKey, $userType, $id, $fpUserName);
        
        //返回Token信息
        return JsonInforTrue($tips, "Token签名", $jwtString);
    }
    
    
    /**校验签名并获取Token*/
    function RBACTokenJudge($secret){
        
        //获取header中的token
        $token = $_SERVER['HTTP_AUTHORIZATIO'];
        
        //判断token是否存在 
        if(IsNull($token)){
        	return JsonInforFalse("token获取失败", "HTTP_AUTHORIZATIO", FlyCode::$Code_Service_Token_Null);
        }
        
        //分割文本
        $tokenArray = GetArray($token, ".");
        if(sizeof($tokenArray)!=3){
            return JsonInforFalse("token格式异常", "分割后的数组成员不为3", FlyCode::$Code_Service_Token_Format);
        }
    
        //Token变量
        $tokenHeader = $tokenArray[0];
        $tokenJson = $tokenArray[1];
        $tokenSign = $tokenArray[2];
    
        //对tokenJson进行sha256散列签名
        $jsonSign = hash_hmac('sha256', $tokenHeader.".".$tokenJson, $secret);
    
        //判断签名后的字符串是否相同
        if($tokenSign != $jsonSign){
            return JsonInforFalse("签名校验失败", "签名字符串不等同", FlyCode::$Code_Service_Token_Invalid);
        }
        
        //1.字符串base64编码转化
        //2.转为Json对象
        $vToken = GetJsonObject(base64_decode($tokenJson));
        
        //--- Token有效时间校验 ---
        $tokenExp = $vToken -> exp;
        $nowTimeStamp = GetTimestamp();
        //判断当前时间大于有效时间
        if($nowTimeStamp > $tokenExp){
            return JsonInforFalse("签名校验失败", "token已失效", FlyCode::$Code_Service_Token_Overtime_Ready);
        }
        
        //--- Token签名域名校验 ---
        $vTokenIss = $vToken -> iss;
        if($vTokenIss != $_SERVER['SERVER_NAME']){ 
            return JsonInforFalse("签名域名不一致，请重新登录。", $vTokenIss.":".$_SERVER['SERVER_NAME'], __LINE__);
        }
        
        //--- Token数据记录校验 ---
        $vTokenJti = $vToken -> jti;
        $vSql = "SELECT id FROM fly_token_log WHERE jti='{$vTokenJti}'";
        if(!DBHelper::DataBoolean($vSql, null)){
            return JsonInforFalse("Token记录无效", "fly_token_log");
        }
        
        return JsonInforTrue("签名校验成功", "token", $tokenJson);
    }    
    
    // ---- Session ----
    
    /** 获取Session对象*/
    function RBACSessionGetObject(){
        if(!isset($_SESSION)){session_start();}
        return $_SESSION;
    }
    
    /** 获取Session的值*/
    function RBACSessionGetValue($key){
        if(!session_id()){session_start();}
        return !empty($_SESSION[$key])?$_SESSION[$key]:null;
    }

    /** 判断Session中的key的值是否为空*/
    function RBACSessionJudgeNull($key){
        if(!isset($_SESSION)){session_start();}
        $value = $_SESSION[$key];
        if(empty($value) || !isset($value) || $value=='' || $value==null){
            return true;
        }else{
            return false;
        }
    }
    
    /** 清除Session值记录*/
    function RBACSessionClear($name){
        if(!isset($_SESSION)){session_start();}
        return session_unset($name);
    }
    
