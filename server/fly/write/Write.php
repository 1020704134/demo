<?php 

    /**
     * 打印请求未找到信息
     * $fpLine：业务线（请求索引）
     * $fpMethod：业务线方法（请求索引）
     * $fpInforType：提示输出类型
     * 整理时间：2019-09-24 17:46:09
     * */
    function WriteEchoNotFint($fpLine,$fpMethod){
        if(IsNull($fpLine)){
            echo JsonInforFalse("line不得为空","业务线索引未找到");
            return;
        }else if(IsNull($fpMethod)){
            echo JsonInforFalse("method不得为空","业务线方法索引未找到");
            return;
        }
        echo JsonInforFalse("{$fpLine} > {$fpMethod}","接口方法索引未找到");
    }
   
    /**
     * 打印请求未找到信息
     * $fpString：要打印输出的字符串
     * 整理时间：2019-09-24 17:47:56
     * */
    function WriteEcho($fpString){
        echo $fpString;
    }
    
    /**
     * 打印请求未找到信息
     * $fpString：要打印输出的字符串
     * 整理时间：2020-03-13 19:27:53
     * */
    function WriteBr($fpString){
        echo $fpString."<br/>";
    }
    
    /**
     * 打印请求未找到信息
     * $fpString：要打印输出的字符串
     * 整理时间：2020-03-13 19:27:53
     * */
    function Writen($fpString){
        echo $fpString."\n";
    }
    
    /**
     * 写日志
     * $fpFailPath：文件路径
     * $fpFileInfor：文件信息
     * $fpBody：日志信息
     * 整理时间：2019-09-24 17:47:56
     * */
    function WriteLog($fpFailPath,$fpLogType,$fpLogTitle,$fpFileInfor,$fpBody,$fpSQL){
        $vLog  = "";
        $vLog .= "----------- " . date('Y-m-d H:i:s',time()) . " -----------".PHP_EOL;
        $vLog .= "type:{$fpLogType}".PHP_EOL;
        $vLog .= "descript:{$fpLogTitle}".PHP_EOL;
        $vLog .= "file:{$fpFileInfor}".PHP_EOL;
        $vLog .= "body:{$fpBody}".PHP_EOL;
        $vLog .= "sql:{$fpSQL}".PHP_EOL;
        $vLog .= PHP_EOL;
        //判断文件是否存在，不存在则进行创建
        //if(!file_exists($fpFailPath)){ fopen($fpFailPath, "w"); }
        //异常文件写入
        $vLogFile = fopen($fpFailPath, "ab+") or die("Unable to open file!");
        fwrite($vLogFile, $vLog);
        fclose($vLogFile);    //关闭文件
    }
    
    /**
     * 输出函数代码
     * 整理时间：2020-10-06 11:59:57
     * */
    function WriteFunctionCode($fpFile,$fpFunction){
        //获取文件内容
        $handle = fopen($fpFile, "r");
        $vFileBody = fread($handle, filesize($fpFile));
        fclose($handle);
        //查找文件位置
        $vFindStart = "function {$fpFunction}(";
        $vFunctionStart = strpos($vFileBody,$vFindStart);
        if(!$vFunctionStart>-1){
            return "";
        }
        $vFunctionStart = $vFunctionStart + strlen($vFindStart);
        //查找结束字符串
        $vFindEnd = false;              //结束查找标识
        $vFindSub = $vFunctionStart;    //字符寻找下标
        $vBraceLeftTimes = 0;           //左括号次数
        $vBraceRightTimes = 0;          //右括号次数
        $vBraceLeftSub = 0;             //左括号下标
        $vBraceRightSub = 0;            //左括号下标
        while(!$vFindEnd){
            $vBraceLeftSub = stripos($vFileBody,"{",$vFindSub);
            $vBraceRightSub = stripos($vFileBody,"}",$vFindSub);
            if($vBraceLeftSub<$vBraceRightSub){
                $vFindSub = $vBraceLeftSub+1;
                $vBraceLeftTimes += 1;
            }else{
                $vFindSub = $vBraceRightSub+1;
                $vBraceRightTimes += 1;
            }
            if($vBraceLeftTimes==$vBraceRightTimes){
                $vFindEnd = true;
                break;
            }
        }
        if($vFindSub<$vFunctionStart){
            return "";
        }
        return substr($vFileBody,$vFunctionStart,$vFindSub-$vFunctionStart);
    }
