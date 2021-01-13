<?php 
    

    /**获取所有Header信息*/
    function GetRequestHeaders(){
        $vRequestHeaders = "";
        foreach ($_SERVER as $key => $value) {
            $vRequestHeaders .= "Header:".$key."--".$value." | ";
        }
        if(!IsNull($vRequestHeaders)){
            $vRequestHeaders = HandleStringDeleteLastTwo($vRequestHeaders);
        }
        return $vRequestHeaders;
    }

    /**获取请求来源页（GET/POST）*/
    function GetRequestUrl(){
        $requestUrl = "";
        if(isset($_SERVER['HTTP_REFERER'])){
            $requestUrl = $_SERVER['HTTP_REFERER'];
        }
        return $requestUrl;
    }
    
    /**获取请求IP*/
    
    function GetIp(){
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        }
        if (getenv('HTTP_X_REAL_IP')) {
            $ip = getenv('HTTP_X_REAL_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
            $ips = explode(',', $ip);
            $ip = $ips[0];
        } elseif (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        } else {
            $ip = '0.0.0.0';
        }
        return $ip;
    }