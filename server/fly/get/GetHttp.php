<?php 
    
    /** 以Get的方式读取URL*/
    function GetHttp($fpSendUrl,$charset="utf-8"){
        
        //file_get_contents方式请求
        $vUrlCode = file_get_contents($fpSendUrl);
        if(!IsNull($vUrlCode)){ return $vUrlCode; }
        
        //curl方式请求
        $curl = curl_init($fpSendUrl);
        //header 设置
        $header=array(
            "Accept: application/json",
            "Content-Type: application/json;charset={$charset}",
        );
        curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
        //其他设置
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        $vUrlCode = curl_exec($curl);
        curl_close($curl);
        return $vUrlCode;
    }
    

    /** 以Post的方式读取URL（Http协议）*/
    function GetHttpSendUrlPost($url, $data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return "cc:".$output;
    }
 
    /** 以Post的方式读取URL（Https协议）*/
    function GetHttpsSendUrlPost($url,$data){ // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        //信息定义
        //$vUserAgent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)";
        $vHeader = array(
            'Accept-Language: zh-cn',
            'Connection: Keep-Alive',
            'Cache-Control: no-cache',
            'Content-Type: Application/json;charset=utf-8',
            "X-Requested-With: XMLHttpRequest"
        );
        $json = json_encode($data); //data转json 
        //请求数据
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_HTTPHEADER, $vHeader);  //设置请求头
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json); // Post提交的数据包
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10); // 成功链接服务器需要等待的时间
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 接收缓存完成前的时间
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            return 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }
    

    
    
