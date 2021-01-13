<?php

/**------------------------------------*
  * 作者：shark
  * 创建时间：2019-12-23 15:36:58
  * Fly编码：1577086618152FLY820558
  * 类对象名：ObjFlyInterfacePath()
  * ------------------------------------ */

//引入区

class FlyClassInterfacePath{


    //---------- 类成员（member） ----------

    //类描述
    public static $classDescript = "接口请求路径";

    //---------- 私有方法（private） ----------
    
    /**
     * 函数名称：私有方法:组合接口请求值参数
     * 创建时间：2020-01-17 14:07:36
     * */
    private function PrivateInterfacePathJson($fpJson,$fpPathFile,$fpFilePath){
        $vPathJson = "";
        if($fpPathFile == "ajax.php"){
            $vPathJson = JsonObj(JsonKeyValue("accessPath","{$fpFilePath}{$fpPathFile}").",".JsonKeyValue("accessDescript","网站管理员").",".JsonKeyValue("accessPower","Session").",".JsonKeyValue("userPaging","line=admin&method=adminpaging").",".JsonKeyValue("userTableField","line=admin&method=adminfield")); 
        }else if($fpPathFile == "ajaxdevice.php"){
            $vPathJson = JsonObj(JsonKeyValue("accessPath","{$fpFilePath}{$fpPathFile}").",".JsonKeyValue("accessDescript","设备用户").",".JsonKeyValue("accessPower","Token"));
        }else if($fpPathFile == "ajaxfinance.php"){
            $vPathJson = JsonObj(JsonKeyValue("accessPath","{$fpFilePath}{$fpPathFile}").",".JsonKeyValue("accessDescript","财务管理员").",".JsonKeyValue("accessPower","Session"));
        }else if($fpPathFile == "ajaxhotel.php"){
            $vPathJson = JsonObj(JsonKeyValue("accessPath","{$fpFilePath}{$fpPathFile}").",".JsonKeyValue("accessDescript","酒店管理员").",".JsonKeyValue("accessPower","Session"));
        }else if($fpPathFile == "ajaxpartner.php"){
            $vPathJson = JsonObj(JsonKeyValue("accessPath","{$fpFilePath}{$fpPathFile}").",".JsonKeyValue("accessDescript","分销商管理员").",".JsonKeyValue("accessPower","Session"));
        }else if($fpPathFile == "ajaxpartneruser.php"){
            $vPathJson = JsonObj(JsonKeyValue("accessPath","{$fpFilePath}{$fpPathFile}").",".JsonKeyValue("accessDescript","分销商店员").",".JsonKeyValue("accessPower","Session"));
        }else if($fpPathFile == "ajaxspot.php"){
            $vPathJson = JsonObj(JsonKeyValue("accessPath","{$fpFilePath}{$fpPathFile}").",".JsonKeyValue("accessDescript","景区管理员").",".JsonKeyValue("accessPower","Session"));
        }else if($fpPathFile == "ajaxsuper.php"){
            $vPathJson = JsonObj(JsonKeyValue("accessPath","{$fpFilePath}{$fpPathFile}").",".JsonKeyValue("accessDescript","超级管理员").",".JsonKeyValue("accessPower","Session"));
        }else if($fpPathFile == "ajaxuser.php"){
            $vPathJson = JsonObj(JsonKeyValue("accessPath","{$fpFilePath}{$fpPathFile}").",".JsonKeyValue("accessDescript","用户").",".JsonKeyValue("accessPower","Token"));
        }else if($fpPathFile == "ajaxvisitor.php"){
            $vPathJson = JsonObj(JsonKeyValue("accessPath","{$fpFilePath}{$fpPathFile}").",".JsonKeyValue("accessDescript","游客").",".JsonKeyValue("accessPower","Visitor"));
        }else{
            $vPathJson = JsonObj(JsonKeyValue("accessPath","{$fpFilePath}{$fpPathFile}").",".JsonKeyValue("accessDescript","（待定）").",".JsonKeyValue("accessPower","（待定）"));
        }
        
        $vJson = "";
        if(IsNull($fpJson)){
            $vJson = $vPathJson;
        }else{
            $vJson = $fpJson . "," . $vPathJson;
        }
        return $vJson;
    }

    //---------- 游客方法（visitor） ----------

    /**
     * 函数名称：接口请求路径:游客:记录查询
     * 函数调用：ObjFlyInterfacePath() -> VisitorFlyInterfacePathPaging()
     * 创建时间：2019-12-23 15:36:58
     * */
    public function FlyInterfaceAccessPath(){
        
        //扫描 access 请求目录下的所有文件
        $filePath = "/server/c/access/";
        $filename = scandir($_SERVER['DOCUMENT_ROOT'] . $filePath);
        //组合Json
        $vJson = "";
        //文件数量
        $vFileNumber = 0;
        //遍历数组
        foreach($filename as $vFileIndex=>$vFileName){
            // 跳过两个特殊目录   continue跳出循环
            if($vFileName=="." || $vFileName==".."){continue;}
            //匹配为ajax的请求路径文件名
            if(preg_match("/^ajax/", $vFileName) > 0){
                $vFileNumber += 1;
                $vJson = self::PrivateInterfacePathJson($vJson, $vFileName, $filePath);
            }
        }
        
        return JsonModelSelectDataHave("请求路径", "", $vFileNumber, JsonArray($vJson));

    }



}
