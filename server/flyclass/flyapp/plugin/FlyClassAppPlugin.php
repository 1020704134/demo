<?php

/**
 * 插件管理
 * 2020-03-24 21:20:07 
 * */

include_once dirname(__DIR__) . "/FlyAppConfig.php";

class FlyClassAppPlugin{
    
    /**
     * 获取插件列表
     * 2020-03-24 21:20:07
     * */
    public function PluginList(){
        
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //参数:分页
        $page = GetParameter('page', "");
        if(IsNull($page)){return JsonModelParameterNull("page");}
        if(intval($page)<0){$page=1;}
        //参数:条数
        $limit = GetParameter('limit', "");
        if(IsNull($limit)){return JsonModelParameterNull("limit");}
        
        $vPluginApiPath = FlyAppConfig::$UrlHost . "/server/c/api/plugin";
        $vData = [
            "line" => "plugin",
            "method" => "pluginlist",
            "page" => $page,
            "limit" => $limit,
            "app_key" => PROJECT_CONFIG_DISANYUN_APPID,
        ];
        return GetHttpsSendUrlPost($vPluginApiPath, $vData);
        
    }
    
    /**
     * 获取插件版本
     * 2020-03-24 23:55:02
     * */
    public function PluginVersion(){
    
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //参数:插件名称
        $pPluginName = GetParameter('plugin_name', "");
        if(IsNull($pPluginName)){return JsonModelParameterNull("plugin_name");}
    
        $vPluginApiPath = FlyAppConfig::$UrlHost . "/server/c/api/plugin";
        $vData = [
            "line" => "plugin",
            "method" => "pluginversion",
            "app_key" => PROJECT_CONFIG_DISANYUN_APPID,
            "plugin_name" => $pPluginName,
        ];
        return GetHttpsSendUrlPost($vPluginApiPath, $vData);
    
    }
    
    
    /**
     * 下载插件
     * 2020-03-25 13:52:55
     * */
    public function PluginDownload(){
    
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //参数:插件名称
        $pPluginName = GetParameter('plugin_name', "");
        if(IsNull($pPluginName)){return JsonModelParameterNull("plugin_name");}
        //参数:插件名称
        $pPluginVersion = GetParameter('plugin_version', "");
        if(IsNull($pPluginVersion)){return JsonModelParameterNull("plugin_version");}
    
        $vPluginApiPath = FlyAppConfig::$UrlHost . "/server/c/api/plugin";
        $vData = [
            "line" => "plugin",
            "method" => "pluginversioninfor",
            "app_key" => PROJECT_CONFIG_DISANYUN_APPID,
            "plugin_name" => $pPluginName,
            "plugin_version" => $pPluginVersion,
        ];
        $vJson = GetHttpsSendUrlPost($vPluginApiPath, $vData);
        if(IsNull($vJson)){
            return JsonInforFalse("版本信息获取失败", $pPluginName);
        }
        if(JudgeJsonFalse($vJson)){
            return $vJson;
        }
        $vJsonObj = GetJsonObject($vJson);
        $vJsonData = $vJsonObj -> data;
        $vJsonData = $vJsonData[0];
        
        $vPluginVersion = $vJsonData -> version;
        $vPluginCreateTime = $vJsonData -> createTime;
        $vPluginDBNumber = $vJsonData -> dbNumber;
        $vPluginFileNumber = $vJsonData -> fileNumber;
        $vPluginKey = $vJsonData -> pluginKey;
        $vPluginPath = $vJsonData -> pluginPath;
        $vPluginFilePath = $vJsonData -> pluginFilePath;
        $vPluginTable = $vJsonData -> pluginTable;
        $vPluginSeePath = $vJsonData -> pluginSeePath;
        
        //数据表字段对比信息
        $vFieldJudgeJson = "";
        //检测次数
        $vCheckTimes = 0;
        
        //遍历字段数组
        for($i=0;$i<sizeof($vPluginTable);$i++){
            //获取数组单个对象（表:字段）
            $vJsonTableObj = $vPluginTable[$i];
            $vTableName = $vJsonTableObj -> tableName;
            $vTableSql = $vJsonTableObj -> tableSql;
            $vTableField = $vJsonTableObj -> tableField;
            //判断数据表是否存在
            if(!DBMySQLJudge::JudgeTable($vTableName)){
                $vThisJson = "";
                //数据表不存在时，进行创建
                DBHelper::DataSubmit($vTableSql, null);
                if(DBMySQLJudge::JudgeTable($vTableName)){
                    $vCheckTimes += 1;
                    $vThisJson .= JsonKeyValue("reult", "true") . ",";
                    $vThisJson .= JsonKeyValue("checkType", "table") . ",";
                    $vThisJson .= JsonKeyValue("tableName", $vTableName) . ",";
                    $vThisJson .= JsonKeyValue("infor", "数据表创建成功") . ",";
                    $vFieldJudgeJson .= JsonObj(HandleStringDeleteLast($vThisJson)).",";
                }else{
                    $vCheckTimes += 1;
                    $vThisJson .= JsonKeyValue("reult", "false") . ",";
                    $vThisJson .= JsonKeyValue("checkType", "table") . ",";
                    $vThisJson .= JsonKeyValue("infor", "数据表创建失败") . ",";
                    $vThisJson .= JsonKeyValue("tableName", $vTableName) . ",";
                    $vFieldJudgeJson .= JsonObj(HandleStringDeleteLast($vThisJson)).",";
                }
            }else{
                //数据表存在时，判断字段
                $vTableFieldList = DBMySQLSelect::TableFieldAll($vTableName);
                $vTableFieldListArray = GetJsonObject($vTableFieldList);
                //数据表字段转化
                $vTableField = HandleStringReplace($vTableField, "'", "\"");
                $vTableFieldArray = GetJsonObject($vTableField);
                //遍历对比字段
                for($c=0;$c<sizeof($vTableFieldArray);$c++){
                    //Json
                    $vThisJson = "";
                    $vJudgeDifferent = false;
                    $vDescript = "";
                    //字段判断
                    $vTableFieldObj = $vTableFieldArray[$c];
                    $vFieldName = $vTableFieldObj -> Field;
                    $vFieldUp = "";
                    if($c>0){ $vFieldUp = $vTableFieldArray[$c-1] -> Field; }
                    $vFieldType = $vTableFieldObj -> Type;
                    $vFieldCollation = $vTableFieldObj -> Collation;
                    $vFieldNull = $vTableFieldObj -> Null;
                    $vFieldKey = $vTableFieldObj -> Key;
                    $vFieldDefault = $vTableFieldObj -> Default;
                    $vFieldComment = $vTableFieldObj -> Comment;
                    for($j=0;$j<sizeof($vTableFieldListArray);$j++){
                        //字段判断结果初始化
                        $vFieldNewBo = "true";
                        //数据表字段
                        $vTableField = $vTableFieldListArray[$j];
                        $vTableFieldName = $vTableField -> Field;
                        $vTableFieldType = $vTableField -> Type;
                        $vTableFieldCollation = $vTableField -> Collation;
                        $vTableFieldNull = $vTableField -> Null;
                        $vTableFieldKey = $vTableField -> Key;
                        $vTableFieldDefault = $vTableField -> Default;
                        $vTableFieldComment = $vTableField -> Comment;
                        //判断字段是否一致
                        if($vFieldName == $vTableFieldName){
                            $vFieldNewBo = "false";
                            if($vFieldType!=$vTableFieldType){ $vJudgeDifferent=true; $vDescript.="数据类型,"; }
                            if($vFieldCollation!=$vTableFieldCollation){ $vJudgeDifferent=true; $vDescript.="编码类型,"; }
                            if($vFieldNull!=$vTableFieldNull){ $vJudgeDifferent=true; $vDescript.="可空,"; }
                            if($vFieldKey!=$vTableFieldKey){ $vJudgeDifferent=true; $vDescript.="PK,"; }
                            if($vFieldDefault!=$vTableFieldDefault){ $vJudgeDifferent=true; $vDescript.="默认值,"; }
                            if($vFieldComment!=$vTableFieldComment){ $vJudgeDifferent=true; $vDescript.="描述,"; }
                            break;
                        }
                    }
                    
                    if($vJudgeDifferent||$vFieldNewBo=="true"){
                        $vCheckTimes += 1;
                        $vThisJson .= JsonKeyValue("reult", "false") . ",";
                        $vThisJson .= JsonKeyValue("checkType", "field") . ",";
                        if($vFieldNewBo=="true"){
                            $vThisJson .= JsonKeyValue("infor", "新增字段") . ",";
                        }else{
                            $vThisJson .= JsonKeyValue("infor", "字段属性不一致（".HandleStringDeleteLast($vDescript)."）") . ",";
                        }
                        $vThisJson .= JsonKeyValue("tableName", $vTableName) . ",";
                        $vThisJson .= JsonKeyValue("fieldName", $vFieldName) . ",";
                        $vThisJson .= JsonKeyValue("fieldUp", $vFieldUp) . ",";
                        $vThisJson .= JsonKeyValue("fieldNew", $vFieldNewBo) . ",";
                        $vThisJson .= JsonKeyValue("fieldType", $vFieldType) . ",";
                        $vThisJson .= JsonKeyValue("fieldCollation", $vFieldCollation) . ",";
                        $vThisJson .= JsonKeyValue("fieldNull", $vFieldNull) . ",";
                        $vThisJson .= JsonKeyValue("fieldKey", $vFieldKey) . ",";
                        $vThisJson .= JsonKeyValue("fieldDefault", $vFieldDefault) . ",";
                        $vThisJson .= JsonKeyValue("fieldComment", $vFieldComment) . ",";
                        $vThisJson = JsonObj(HandleStringDeleteLast($vThisJson));
                        $vFieldJudgeJson .= $vThisJson . ",";
                    }
                    
                }
                
            }
            
        }
        
        //插件字段检测结果
        $vJson = JsonInforData("插件信息检测", "{$pPluginName}:{$pPluginVersion}", "", $vCheckTimes, JsonArray(HandleStringDeleteLast($vFieldJudgeJson)));
        //扩展插件路径信息
        $vJson = JsonHandleFlyInforExtend($vJson, JsonKeyString("pluginFilePath", json_encode($vPluginFilePath)));
        //扩展插件演示路径
        $vJson = JsonHandleFlyInforExtend($vJson, JsonKeyValue("pluginSeePath", $vPluginSeePath));
        return $vJson;
    }
    
    
    /**
     * 插件字段更新
     * 2020-03-26 14:01:44
     * */
    public function PluginTableFieldUpdate($fpJson){
        
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //--- 参数获取区 ---
        $tableName = GetParameter("table_name",$fpJson);
        if(IsNull($tableName)){return JsonModelParameterNull("table_name");}
        
        $fieldNew = GetParameterRequest("field_new",false);
        if(IsNull($fieldNew)){return JsonModelParameterNull("field_new");}
        
        $fieldName = GetParameterRequest("field_name",false);
        if(IsNull($fieldName)){return JsonModelParameterNull("field_name");}
        
        $fieldUp = GetParameterRequest("field_up",false);
        
        $fieldType = GetParameterRequest("field_type",false);
        if(IsNull($fieldType)){return JsonModelParameterNull("field_type");}
        
        $pFieldCollation = GetParameterRequest("field_collation",false);
        $fieldCollation = $pFieldCollation;
        
        $pFieldNull = GetParameterRequest("field_null",false);
        if(IsNull($pFieldNull)){return JsonModelParameterNull("field_null");}
        $fieldNull = $pFieldNull;
        
        $pFieldDefault = GetParameterRequest("field_default",false);
        $fieldDefault = $pFieldDefault;
        
        $pFieldKey = GetParameterRequest("field_key",false);
        $fieldKey = $pFieldKey;
        
        $pFieldComment = GetParameterRequest("field_comment",false);
        $fieldComment = $pFieldComment;
        
        //--- 参数值处理 --- 
        //字段为可空判断
        if($fieldNull=="YES"){
            $fieldNull = " NULL ";
        }else{
            $fieldNull = " NOT NULL ";
        }
        
        //字段编码
        if(!IsNull($fieldCollation)){
            $fieldCollation = " CHARACTER SET utf8 COLLATE {$fieldCollation} ";
        }
        
        //默认值判断
        if(!IsNull($fieldDefault)){
            $fieldDefault = " DEFAULT '{$fieldDefault}' ";
        }
        
        //PK
        $vPkAutoId = "";
        if($fieldKey=="PRI"){
            $fieldKey = " ,ADD PRIMARY KEY(`{$fieldName}`) "; 
            $fieldCollation = "";
            $vPkAutoId = " AUTO_INCREMENT ";
            $fieldDefault = "";
        }
        
        if(IsNull($fieldUp)){
            $fieldUp = " FIRST ";
        }else{
            $fieldUp = " AFTER `{$fieldUp}` ";
        }
        
        if($fieldNew=="YES"){
            $sql = "ALTER TABLE `{$tableName}` ADD COLUMN `{$fieldName}` {$fieldType} {$fieldCollation} {$fieldNull} {$vPkAutoId} COMMENT '{$fieldComment}' {$fieldUp}{$fieldKey};";
            DBHelper::DataSubmit($sql, null);
            if(DBMySQLJudge::JudgeTableField($tableName, $fieldName)){
                return JsonInforTrue($fieldName."字段添加成功", $tableName);
            }
            return JsonInforFalse($fieldName."字段添加失败", $tableName);
        }else if($fieldNew=="NO"){
            //当前数据库字段获取
            $vFieldList = DBMySQLSelect::TableFieldAll($tableName,$fieldName);
            if(IsNull($vFieldList)){ return JsonInforFalse("字段未找到", "{$tableName}:{$fieldName}"); }
            $vFieldList = GetJsonObject($vFieldList);
            $vFieldList = $vFieldList[0];
            $vTableField = $vFieldList -> Field;
            $vTableType = $vFieldList -> Type;
            $vTableCollation = $vFieldList -> Collation;
            $vTableNull = $vFieldList -> Null;
            $vTableKey = $vFieldList -> Key;
            $vTableDefault = $vFieldList -> Default;
            $vTableComment = $vFieldList -> Comment;
            
            //判断字段名是否相同
            if($fieldName!=$vTableField){
                return JsonInforFalse("字段名不一致", $tableName);
            }
            
            //判断本地数据库数据
            if($vTableKey=="PRI"){
                $fieldKey = "";
            }
            
            //修改字段字符串拼接
            $vSql = "ALTER TABLE `{$tableName}` CHANGE `{$fieldName}` `{$fieldName}` {$fieldType} {$fieldCollation} {$fieldDefault} {$fieldNull} {$vPkAutoId} COMMENT '{$fieldComment}'{$fieldKey};";
            //修改字段提交
            DBHelper::DataSubmit($vSql, null);
            //修改后字段获取
            $vFieldList = DBMySQLSelect::TableFieldAll($tableName,$fieldName);
            if(IsNull($vFieldList)){ return JsonInforFalse("字段未找到", "{$tableName}:{$fieldName}"); }
            $vFieldList = GetJsonObject($vFieldList);
            $vFieldList = $vFieldList[0];
            $vTableField = $vFieldList -> Field;
            $vTableType = $vFieldList -> Type;
            $vTableCollation = $vFieldList -> Collation;
            $vTableNull = $vFieldList -> Null;
            $vTableKey = $vFieldList -> Key;
            $vTableDefault = $vFieldList -> Default;
            $vTableComment = $vFieldList -> Comment;
            //字段对比
            $vJudgeBo = true;
            if($fieldType!=$vTableType){$vJudgeBo=false;}
            if($pFieldCollation!=$vTableCollation){$vJudgeBo=false;}
            if($pFieldNull!=$vTableNull){$vJudgeBo=false;}
            if($pFieldDefault!=$vTableDefault){$vJudgeBo=false;}
            if($pFieldKey!=$vTableKey){$vJudgeBo=false;}
            if($pFieldComment!=$vTableComment){$vJudgeBo=false;}
            //返回修改结果
            if($vJudgeBo){
                return JsonInforTrue($fieldName."字段修改成功", $tableName);
            }else{
                return JsonInforFalse($fieldName."字段修改失败", $tableName);
            }
        }
        
        return JsonInforFalse("未找到处理方式", "field_new");
        
    }
    
    

    /**
     * 插件文件更新
     * 2020-03-26 22:41:15
     * */
    public function PluginFileUpdate(){
        
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //参数:插件名称
        $pPluginName = GetParameter('plugin_name', "");
        if(IsNull($pPluginName)){return JsonModelParameterNull("plugin_name");}
        //参数:插件名称
        $pPluginVersion = GetParameter('plugin_version', "");
        if(IsNull($pPluginVersion)){return JsonModelParameterNull("plugin_version");}
        //参数:插件文件路径
        $pFilePath = GetParameter('file_path', "");
        if(IsNull($pFilePath)){return JsonModelParameterNull("file_path");}
        
        //替换路径
        $vpFilePath = "";        
        
        if(strpos($pFilePath,"server/flyclass/plugins/")>0){
            $vpFilePath = $_SERVER['DOCUMENT_ROOT'].HandleStringReplace($pFilePath, "/server/flyclass/plugins/", "/server/update/plugin/");
        }else if(strpos($pFilePath,"view/page/admin/plugins/")>0){
            $vpFilePath = $_SERVER['DOCUMENT_ROOT'].HandleStringReplace($pFilePath, "/view/page/admin/plugins/", "/view/page/update/plugin/");
        }
        
        //目录文件检测
        if(is_file($vpFilePath)){
            return JsonInforTrue("文件已创建", $pFilePath);
        }
        
        
        $vPluginApiPath = FlyAppConfig::$UrlHost . "/server/c/api/plugin";
        $vData = [
            "line" => "plugin",
            "method" => "pluginversionfiledownload",
            "app_key" => PROJECT_CONFIG_DISANYUN_APPID,
            "plugin_name" => $pPluginName,
            "plugin_version" => $pPluginVersion,
            "file_path" => $pFilePath,
        ];
        $vJson = GetHttpsSendUrlPost($vPluginApiPath, $vData);
        if(IsNull($vJson)){
            return JsonInforFalse("版本信息获取失败", $pPluginName);
        }
        if(JudgeJsonFalse($vJson)){ return $vJson; }
        
        //转化Json为Json对象
        $vJsonObj = GetJsonObject($vJson);
        $vPluginPath = $vJsonObj -> savePath;
        $vNextPath = $vJsonObj -> nextPath;
        $vDownloadTime = $vJsonObj -> downloadTime;
        $vFileBody = $vJsonObj -> fileBody;
        
        //创建文件
        CreatePath($vpFilePath);
        
        //base64转码
        $vFileBody = base64_decode($vFileBody);
        //当为页面配置文件时，需改配置内容
        if(strpos($pFilePath,"pluginconfig.js")>0){
            $vFileBody = HandleStringReplace($vFileBody, "/server/flyclass/plugins/", "/server/update/plugin/");
        }
        
        //写入文件
        $vSaveFile = fopen($vpFilePath, "w") or die("Unable to open file!");
        fwrite($vSaveFile, $vFileBody);
        fclose($vSaveFile);    //关闭文件
        
        return JsonInforTrue("文件创建完毕", $vpFilePath);
        
    }
    
    
    /**
     * 插件演示
     * 2020-03-27 01:24:59
     * */
    public function PluginSee(){
        
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //参数:插件演示路径
        $pSeePath = GetParameter('see_path', "");
        if(IsNull($pSeePath)){return JsonModelParameterNull("see_path");}
        
        //替换路径
        $vpFilePath = "";
        $vFilePathReplace = "";
        if(strpos($pSeePath,"server/flyclass/plugins/")>0){
            $vFilePathReplace = HandleStringReplace($pSeePath, "/server/flyclass/plugins/", "/server/update/plugin/");
            $vpFilePath = $_SERVER['DOCUMENT_ROOT'].$vFilePathReplace;
        }else if(strpos($pSeePath,"view/page/admin/plugins/")>0){
            $vFilePathReplace = HandleStringReplace($pSeePath, "/view/page/admin/plugins/", "/view/page/update/plugin/");
            $vpFilePath = $_SERVER['DOCUMENT_ROOT'].$vFilePathReplace;
        }
        
        if(!is_file($vpFilePath)){
            return JsonInforFalse("插件演示路径不存在", $pSeePath);
        }
        
        $vSeePath = GetHttpType() . $_SERVER['HTTP_HOST'] . $vFilePathReplace; 
        return JsonInforTrue($vSeePath, "插件演示路径");
        
    }
    
}