<?php 


    /** 截取字符串:从起始位置到结尾*/
    function GetStringStartToEnd($string,$num){
        return substr($string,$num);
    }
    
    /** 截取字符串:从起始截取指定长度*/
    function GetStringStartLength($string,$state,$num){
        return substr($string,$state,$num);
    }
    
    /** 获取字符串长度*/
    function GetStringLength($string){
        return strlen($string);
    }
    
    /** 匹配字符串*/
    function GetStringMatch($string,$regular,$arr){
        preg_match_all($regular,$string,$arr);
        return $arr;
    }
    
    /** 取文本右边:寻找文本位置 */
    function GetStringRightFindPos($str,$findStartText){
        return strpos($str,$findStartText);
    }
    
    /** 取文本右边:寻找文本作为始位,再次寻找文本作为结束位*/
    function GetStringRightFindFind($str,$findStartText,$findEndText){
        if(IsNull($str)){return "";}
        $startNumber = -1;
        $endNumber = -1;
        $length = 0;
    
        $startNumber = strpos($str,$findStartText);
         
        if($startNumber>-1){
            $startNumber += strlen($findStartText);
            $endNumber = strpos($str,$findEndText,$startNumber);
            $length = $endNumber - $startNumber;
            return substr($str,$startNumber,$length);
        }
        return "";
    }
    
    /** 取文本右边:寻找文本作为始位,右边直到末尾 */
    function GetStringRightFindEnd($str,$findStartText){
        if(IsNull($str)){return "";}
        $startNumber = -1;
        $endNumber = -1;
        $length = 0;
    
        $startNumber = strpos($str,$findStartText);
         
        if($startNumber>-1){
            $startNumber += strlen($findStartText);
            $endNumber = strlen($str);
            $length = $endNumber - $startNumber;
            return substr($str,$startNumber,$length);
        }
        return "";
    }
    

    /** 取文本左边:以文本开头作为起始位，寻找文本作为结束位 */
    function GetStringLeftFind($str,$findStartText){
        if(IsNull($str)){return "";}
        $endNumber = -1;
        $endNumber = strpos($str,$findStartText);
        if($endNumber>-1){
            return substr($str,0,$endNumber);
        }
        return "";
    }  
    
    /** 获取文件名 */
    function GetStringFileName($fpFile=null){
        if(IsNull($fpFile)){
            return substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);
        }
        return substr($fpFile,strrpos($fpFile,'/')+1);
        //$vFileSub = strrpos($fpFile,"\\");
        //if($vFileSub > 0){
        //    $fpFile = substr($fpFile,$vFileSub+1);
        //}
        //$vFileSub = strrpos($fpFile,".");
        //if($vFileSub > 0){
        //    $fpFile = substr($fpFile,0,$vFileSub);
        //}
        //return $fpFile;
    }
    
    /** 获取文件路径 */
    function GetStringFilePath($fpFile=null){
        if(IsNull($fpFile)){
            $vPath = $_SERVER['PHP_SELF'];
            return str_replace(str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']), "", $vPath);
        }
        return str_replace(str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']), "", $fpFile);
    }
    
    
    /** 判断布尔类型并返回相应的字符串结果 */
    function GetStringBool($bool){
        if($bool){
            return "true";
        }
        return "false";
    }

    
    /** 身份证取性别 */
    function GetStringIdCardSex($fpIdCard){
        $sexint = (int)substr($fpIdCard, 16, 1);
        return $sexint % 2 === 0 ? '女' : '男';
    }
    
    /** 身份证取生日 */
    function GetStringIdCardBirthDay($fpIdCard){
        return strlen($fpIdCard)==15 ? ('19' . substr($fpIdCard, 6, 6)) : substr($fpIdCard, 6, 8);
    }
    
    /** 身份证取年龄 */
    function GetStringIdCardAge($fpIdCard){
        $vIdCardYear = GetStringIdCardBirthDay($fpIdCard);
        $vCalc = intval(GetTimeDateNumber()) - intval($vIdCardYear);
        return intval($vCalc/10000);
    }
    
    /** 身份证取区域编码 */
    function GetStringIdCardAreaCode($fpIdCard){
        return substr($fpIdCard,0,6);
    }
    
    
    /** 获取图片后缀*/
    function GetStringSuffixImageBase64($fpImageBase64Str){
        preg_match('/^(data:\s*image\/(\w+);base64,)/', $fpImageBase64Str, $result);
        if(!IsNull($result)){
            return $result[2];
        }
        return "";
    }
    
    /** 拼接文件信息*/
    function GetStringFileInfor($fpFile,$fpFunction,$fpLine){
        return  str_replace("\\", "/", $fpFile).":".$fpFunction.":".$fpLine;
    }
    
    /** 
     * 获取函数信息
     * $fpIgnoreNumber:忽略次数，被忽略的内容将不在显示
     * */
    function GetStringFunctionInfor($fpIgnoreNumber=0){
        //根目录
        $vDocumentRoot = str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']);
        //获取函数信息
        $vFileInfor = "";
        $array = debug_backtrace();
        $array = array_reverse($array);
        $i = 0;
        foreach ($array as $row) {
            $i += 1;
            //文件
            $vFile = $row['file'];
            $vFile = str_replace("\\", "/", $vFile);
            $vFile = str_replace($vDocumentRoot, "", $vFile);
            //行数
            $vLine = $row['line'];
            //函数
            $vFunction = $row['function'];
            //类
            $vClass = $row['class'];
            //组合
            $vFileInfor .= $vFile.":".$vClass.":".$vFunction.":".$vLine.",";
            //返回被忽略的内容
            if($i == sizeof($array)-$fpIgnoreNumber){
                break;
            }
        }
        return HandleStringDeleteLast($vFileInfor);
    }
    
    /**
     * 获取驼峰命名发
     * */
    function GetStringHump($fpString,$fpBigBo=false){
        $fpArray = explode("_", $fpString);
        $fpString = "";
        for($i=0;$i<sizeof($fpArray);$i++){
            if($fpBigBo){
                $fpString .= ucfirst($fpArray[$i]);
            }else{
                if($i==0){
                    $fpString .= $fpArray[$i];
                }else{
                    $fpString .= ucfirst($fpArray[$i]);
                }    
            }
        }
        return $fpString;
    }
    
    /**
     * 获取文件注释
     * 2020-03-27 20:39:27
     * */
    function GetStringFileNotes($fpClassName){
        $rc = new ReflectionClass($fpClassName);
        return $rc->getDocComment();
    }