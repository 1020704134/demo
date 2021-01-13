<?php

/**------------------------------------*
  * 作者：shark
  * 创建时间：2020-03-27 23:29:12
  * Fly编码：1585322952442FLY200482
  * 类对象名：ObjFlySystemUpdateLog()
  * ------------------------------------ */

//引入区
include_once dirname(__DIR__) . "/FlyAppConfig.php";

class FlyClassSystemUpdateLog{


    //---------- 类成员（member） ----------

    //类描述
    public static $classDescript = "系统更新日志";
    //类数据表名
    public static $tableName = "fly_system_update_log";
    
    //---------- 私有方法（private） ----------
    
    /**
     * 函数名称：获取版本号
     * 创建时间：2020-03-28 13:44:28
     * */
    private function GetVeionNumber(){
        //查询更新记录是否解决完毕
        $vSql = "SELECT TRUE FROM fly_system_update_log WHERE updateState='UPDATE_NEW' LIMIT 0,1;";
        $vUpdateLog = DBHelper::DataString($vSql, null);
        if(!IsNull($vUpdateLog)){
            return JsonInforFalse("有未解决冲突的更新记录", "fly_system_update_log");
        }
        //获取迭代版本号
        $vCoreApiPath = FlyAppConfig::$UrlHost . "/server/c/api/core";
        $vData = [
            "line" => "core",
            "method" => "versionnextnumber",
            "app_key" => PROJECT_CONFIG_DISANYUN_APPID,
            "version_number" => FS_CORE,
        ];
        return GetHttpsSendUrlPost($vCoreApiPath, $vData);
    }
    
    /**
     * 函数名称：获取版本详细信息
     * 创建时间：2020-03-28 13:56:19
     * */
    private function GetVeionInfor($fpVersionNumber){
        //获取最新版本信息
        $vCoreApiPath = FlyAppConfig::$UrlHost . "/server/c/api/core";
        $vData = [
            "line" => "core",
            "method" => "versioninfor",
            "app_key" => PROJECT_CONFIG_DISANYUN_APPID,
            "version_number" => $fpVersionNumber,
        ];
        $vJson = GetHttpsSendUrlPost($vCoreApiPath, $vData);
        if(JudgeJsonTrue($vJson)){
            //生成版本更新日志
            $vpUpdateLogFile = $_SERVER['DOCUMENT_ROOT']."/server/update/system/{$fpVersionNumber}/log.json";
            if(!is_file($vpUpdateLogFile)){
                CreatePath($vpUpdateLogFile);
                $vSaveFile = fopen($vpUpdateLogFile, "w") or die("Unable to open file!");   //打开文件
                fwrite($vSaveFile, $vJson);
                fclose($vSaveFile);    //关闭文件
            }
        }
        return $vJson;
    }
    
    //---------- 核心代码更新方法（core） ----------
    
    /**
     * 核心代码版本检测
     * 2020-03-28 14:04:48
     * */
    public function CoreCodeUpdate(){
        
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //1.获取迭代版本号
        $vJson = $this->GetVeionNumber();
        if(JudgeJsonFalse($vJson)){
            return $vJson;
        }
        $vVersionNumber = GetJsonValue($vJson, "value");
        //2.获取版本详细更新信息，返回前端进行代码更新，并刷新进度条
        return $this->GetVeionInfor($vVersionNumber);
    }
    
    /**
     * 核心代码文件下载
     * 2020-03-28 20:32:51
     * */
    public function CoreCodeDownload(){
        
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //参数:版本号
        $pVersionNumber = GetParameter('version_number', "");
        if(!JudgeRegularNumber($pVersionNumber)){return JsonParameterWrong("version_number", $pVersionNumber);}
        //参数:更新文件路径
        $pFilePath = GetParameter('file_path', "");
        if(!JudgeRegularUrl($pFilePath)){return JsonParameterWrong("file_path", $pFilePath);}
        
        //判断文件是否已存在，存在则不再进行更新
        $vSaveName = HandleStringReplace($pFilePath, "/", "-");
        $vStortUpdatePath = "/server/update/system/{$pVersionNumber}/{$vSaveName}";
        $vpUpdatePath = $_SERVER['DOCUMENT_ROOT'].$vStortUpdatePath;
        if(is_file($vpUpdatePath)&&filesize($vpUpdatePath)>0){
            return JsonInforFalse("文件已存在", $vpUpdatePath);
        }
        
        //获取最新版本信息
        $vCoreApiPath = FlyAppConfig::$UrlHost . "/server/c/api/core";
        $vData = [
            "line" => "core",
            "method" => "filedownload",
            "app_key" => PROJECT_CONFIG_DISANYUN_APPID,
            "version_number" => $pVersionNumber,
            "file_path" => $pFilePath,
        ];
        $vJson = GetHttpsSendUrlPost($vCoreApiPath, $vData);
        if(IsNull($vJson)){
            return JsonInforFalse("核心文件信息获取失败", $pFilePath);
        }
        if(JudgeJsonFalse($vJson)){ return $vJson; }
        
        //转化Json为Json对象
        $vJsonObj = GetJsonObject($vJson);
        $vSavePath = $vJsonObj -> savePath;
        $vDownloadTime = $vJsonObj -> downloadTime;
        $vFileBody = $vJsonObj -> fileBody;
        
        //创建文件
        CreatePath($vpUpdatePath);
        
        //base64转码
        $vFileBody = base64_decode($vFileBody);
        //当为页面配置文件时，需改配置内容
        if(strpos($pFilePath,"pluginconfig.js")>0){
            $vFileBody = HandleStringReplace($vFileBody, "/server/flyclass/plugins/", "/server/update/plugin/");
        }
        
        //写入文件
        $vSaveFile = fopen($vpUpdatePath, "w") or die("Unable to open file!");
        fwrite($vSaveFile, $vFileBody);
        fclose($vSaveFile);    //关闭文件
        
        //写入数据库记录
        $this -> SystemFlySystemUpdateLogAdd($pVersionNumber, $pFilePath, $vStortUpdatePath);
        
        return JsonInforTrue("文件创建完毕", $vpUpdatePath);
    }
    
    /**
     * 查看版本日志
     * 2020-03-28 22:07:46
     * */
    public function CoreCodeVersionLog(){
        
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //参数:版本号
        $pVersionNumber = GetParameter('version_number', "");
        if(!JudgeRegularNumber($pVersionNumber)){return JsonParameterWrong("version_number", $pVersionNumber);}
        
        $vpUpdatePath = $_SERVER['DOCUMENT_ROOT']."/server/update/system/{$pVersionNumber}/log.json";
        if(!is_file($vpUpdatePath)){
            return JsonInforFalse("{$pVersionNumber}版本更新日志文件未找到", $pVersionNumber);
        }
        
        $handle = fopen($vpUpdatePath, "r");
        $vJsonString = fread($handle, filesize($vpUpdatePath));
        fclose($handle);
        if(!JudgeJsonString($vJsonString)){
            return JsonInforFalse("错误的更新日志文件", $pVersionNumber);
        }
        return $vJsonString;
    }
    
    /**
     * 修改冲突更新文件
     * 2020-03-29 15:05:00
     * */
    public function ConflictConfrontUpdateFile(){
        
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //参数:文件路径
        $pFilePath = GetParameterNoCode('filepath', "");
        if(!JudgeRegularUrl($pFilePath)){return JsonModelParameterNull("filepath");}
        //参数:文件路径
        $pFileCode = GetParameterNoCode('filecode', "");
        if(IsNull($pFileCode)){return JsonModelParameterNull("filecode");}
        
        //判断是否是文件
        if(!file_exists($pFilePath)){
            $pFilePath = $_SERVER['DOCUMENT_ROOT'] ."/". $pFilePath;
            if(!file_exists($pFilePath)){
                return JsonInforFalse("文件不存在", $pFilePath);
            }
        }
        
        //--- 组合文件常量内容并写入配置文件 ---
        //文件路径
        $fildPath = $pFilePath;
        //打开文件
        $file = fopen($fildPath, "wb") or die("Unable to open file!");
        //写入文件
        fwrite($file, $pFileCode);
        //关闭文件
        fclose($file);
        
        return JsonInforTrue("文件保存成功", "{$pFilePath}:".strlen($pFileCode));
        
    }
    
    /**
     * 修改冲突解决记录状态
     * 2020-03-28 22:42:06
     * */
    public function ConflictConfrontUpdateState(){
        
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //冲突记录ID
        $pId = GetParameter('id', "");
        if(!JudgeRegularNumber($pId)){return JsonParameterWrong("id", $pId);}
        //参数:版本号
        $pVersionNumber = GetParameter('version_number', "");
        if(!JudgeRegularNumber($pVersionNumber)){return JsonParameterWrong("version_number", $pVersionNumber);}
        //参数:ID
        $vSql = "UPDATE fly_system_update_log SET updateState='UPDATE_READY' WHERE id='{$pId}'";
        if(DBHelper::DataSubmit($vSql, null)){
            //查询该版本的冲突是否全部解决完毕
            $vSql = "SELECT TRUE FROM fly_system_update_log WHERE versionNumber='{$pVersionNumber}' AND updateState='UPDATE_NEW'";
            $vVersionRecode = DBHelper::DataString($vSql, null);
            if(IsNull($vVersionRecode)){
                ObjSystem() -> SetSystemDefine("FS_CORE", $pVersionNumber);
            }
            //返回更新成功结果信息
            return JsonInforTrue("已标记为解决状态", "fly_system_update_log");
        }
        $vSql = "SELECT updateState FROM fly_system_update_log WHERE id='{$pId}'";
        $vState = DBHelper::DataString($vSql, null);
        if($vState=="UPDATE_READY"){
            return JsonInforFalse("已标记为解决状态", "fly_system_update_log");
        }
        return JsonInforFalse("修改失败", "fly_system_update_log");
    }
    
    /**
     * 当前代码版本获取
     * 2020-03-29 16:33:32
     * */
    public function GetVersionNumber(){
        return JsonInforTrue(FS_CORE, "当前系统版本号");
    }

    //---------- 系统方法（system） ----------

    /**
     * 函数名称：系统更新日志:系统:记录添加
     * 函数调用：ObjFlySystemUpdateLog() -> SystemFlySystemUpdateLogAdd
     * 创建时间：2020-03-27 23:29:12
     * */
    public function SystemFlySystemUpdateLogAdd($fpVersionNumber,$fpFilePath,$fpUpdatePath){
        $vValue  = "";
        $vValue .= HandleStringAddQuotation(GetId("R")).",";
        $vValue .= HandleStringAddQuotation(self::$classDescript).",";
        $vValue .= HandleStringAddQuotation($fpVersionNumber).",";
        $vValue .= HandleStringAddQuotation(GetTimeNow()).",";
        $vValue .= HandleStringAddQuotation($fpFilePath).",";
        $vValue .= HandleStringAddQuotation($fpUpdatePath);
        $vSql = "INSERT IGNORE INTO ".self::$tableName."(onlyKey,descript,versionNumber,fileUpdateTime,filePath,updatePath) VALUES (".$vValue.");";
        if(DBHelper::DataSubmit($vSql,null)){
            return JsonInforTrue("核心代码更新日志添加成功", self::$tableName);
        };
        return JsonInforFalse("核心代码更新日志添加失败", self::$tableName);        
    }



    //---------- 管理员方法（admin） ----------

   
    /**
     * 函数名称：系统更新日志:管理员:记录查询
     * 函数调用：ObjFlySystemUpdateLog() -> AdminFlySystemUpdateLogPaging($fpAdminId)
     * 创建时间：2020-03-27 23:29:12
     * */
    public function AdminFlySystemUpdateLogPaging($fpAdminId){

        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 数据预定义 ---
        $json = "";

        //--- 参数获取区 ---
        $pPage = GetParameterNoCode("page","");     //参数:页码:page
        $pLimit = GetParameterNoCode("limit","");   //参数:条数:limit

        //参数：id
        $pId = GetParameterNoCode("id","");
        if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        if(!IsNull($pId)){ $pPage = 1; $pLimit = 1; }

        //参数判断:页码:page
        if(IsNull($pPage)||!JudgeRegularIntRight($pPage)){return JsonModelParameterException("page", $pPage, 9, "值必须是正整数", __LINE__);}
        //参数判断:条数:limit
        if(IsNull($pLimit)||!JudgeRegularIntRight($pLimit)){return JsonModelParameterException("limit", $pLimit, 9, "值必须是正整数", __LINE__);}

        //参数：记录状态
        $pWhereState = GetParameterNoCode("wherestate","");
        if(!IsNull($pWhereState)&&!($pWhereState=="STATE_NORMAL"||$pWhereState=="STATE_DELETE")){return JsonModelParameterException("wherestate", $pWhereState, 64, "值必须是STATE_NORMAL/STATE_DELETE", __LINE__);}

        //参数：上下架状态
        $pWhereShelfState = GetParameterNoCode("whereshelfstate","");
        if(!IsNull($pWhereShelfState)&&!($pWhereShelfState=="true"||$pWhereShelfState=="false")){return JsonModelParameterException("whereshelfstate", $pWhereShelfState, 36, "值必须是true/false", __LINE__);}

        //参数：like
        $pLikeField = GetParameterNoCode("likefield","");
        if(!IsNull($pLikeField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLikeField)){ return JsonInforFalse("搜索字段不存在", $pLikeField,__LINE__); }
        $pLikeKey = GetParameterNoCode("likekey","");
        if(!IsNull($pLikeKey)){ $pLikeKey = HandleStringAddslashes($pLikeKey); }

        //参数：state
        $pStateField = GetParameterNoCode("statefield","");
        if(!IsNull($pStateField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pStateField)){ return JsonInforFalse("状态字段不存在", $pStateField,__LINE__); }
        $pStateKey = GetParameterNoCode("statekey","");
        if(!IsNull($pStateKey)&&!JudgeRegularState($pStateKey)){ return JsonInforFalse("状态码错误", $pStateKey,__LINE__); }

        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,versionNumber,filePath,fileUpdateTime,updatePath,updateState";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "",
        	"where_value" => "",
        	"page" => $pPage,
        	"limit" => $pLimit,
        	//"descbo" => "true",
        	//"orderby" => "id",
        	"like_field" => $pLikeField,
        	"like_key" => $pLikeKey,
        );

        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, $pStateField, $pStateKey);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));

    }


 


}
