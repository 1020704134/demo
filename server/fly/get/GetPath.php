<?php 
    
    
    /** 获取HTTP协议类型*/
    function GetHttpType(){
        return ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    }
  
    /** 获取域名或主机地址:#localhost*/
    function GetPathHost(){
        return $_SERVER['HTTP_HOST'];
    }
    
    /** 获取网页地址:#/blog/testurl.php*/
    function GetPathPage(){
        return $_SERVER['PHP_SELF'];
    }
    
    /** 获取网址参数:#id=5*/
    function GetPathParameter(){
        return $_SERVER["QUERY_STRING"];
    }
    
    /** 获取用户引用*/
    function GetPathReferer(){
        return $_SERVER['HTTP_REFERER'];
    }
    
    /** 获取用户IP:远程地址*/
    function GetPathRemoteAddr(){
        return $_SERVER['REMOTE_ADDR'];
    }
    
    /** 获取完整的url:页面*/
    function GetPathUrl(){
        return GetHttpType().$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
    
    /** 获取完整的url:页面+参数*/
    function GetPathUrlParameter(){
        return GetHttpType().$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
    }
    
    /** 获取完整的url:页面+参数:#http://localhost:80/blog/testurl.php?id=5*/
    function GetPathUrlPortParameter(){
        return GetHttpType().$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    }
    
    
    /**获取文件名*/
    function GetPathFileName(){
        return substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);
    }
    
    /**获取文件路径*/
    function GetPath($fpPath,$fpUpNumber){
        $fpUpNumber = abs($fpUpNumber);
        $vPathArray = explode("\\",$fpPath);
        $vPathString = "";
        for($i=0;$i<sizeof($vPathArray);$i++){
            if(sizeof($vPathArray)-$i<=$fpUpNumber){
                break;
            }
            $vPathString .= $vPathArray[$i] . "\\";
        }
        return $vPathString;
    }
    
    /** 处理路径*/
    function GetPathHandle($fpPath){
        //处理路径
        $fpPath = HandleStringReplace($fpPath,"\\","/");            //路径前置：\替换为/
        $vDoc = $_SERVER['DOCUMENT_ROOT'];                          //正则前置：获取项目根目录
        $vDoc = HandleStringReplace($vDoc,"/","\/");                //正则前置：替换/为\/
        $fpPath = HandleStringPregReplace($fpPath,'/^'.$vDoc.'/','');  //正则替换替换传入路径根目录
        $fpPath = $_SERVER['DOCUMENT_ROOT'] . $fpPath;     //组合完整路径
        return $fpPath;
    }
    
    /** 获取指定路径函数名数组*/
    function GetPathFunctionArray($fpPath){
        $fpPath = GetPathHandle($fpPath);
        if(!is_file($fpPath)){ return array(); }
        $file = fopen($fpPath,"r");
        $content = array();
        while(!feof($file)){
            $line_content = fgets($file);
            preg_match("/^\s+function\s+/", $line_content, $pat_array);
            if(isset($pat_array[0])){
                $line_content = preg_replace("/.*?function\b|\(.*?{|\s+/",'',$line_content);
                $content[] = $line_content;
            }
        }
        fclose($file);
        return $content;
    }
    
    /** 获取指定路径类名*/
    function GetPathClassName($fpPath){
        $fpPath = GetPathHandle($fpPath);
        if(!is_file($fpPath)){ return ""; }
        $file = fopen($fpPath,"r");
        while(!feof($file)){
            $line_content = fgets($file);
            preg_match("/^(\s*?)class\s+/", $line_content, $pat_array);
            if(isset($pat_array[0])){               
                $line_content = preg_replace("/.*?class\s+|\{|\s+/",'',$line_content);
                fclose($file);
                return $line_content;
            }
        }
        fclose($file);
        return "";
        
    }
