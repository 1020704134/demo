<?php

/** 系统配置*/
class ClassSystem{
    
    /**获取项目配置名称*/
    private function GetSystemConfigName($fpConfigDefine){
        if($fpConfigDefine=="PROJECT_CONFIG_SERVER"){
            return "服务器标识";
        }else if($fpConfigDefine=="PROJECT_CONFIG_PARAMETER_CHECK"){
            return "是否开启参数严格校验检测";
        }else if($fpConfigDefine=="PROJECT_CONFIG_RBAC_SWITCH"){
            return "是否开启RBAC权限校验";
        }else if($fpConfigDefine=="PROJECT_CONFIG_SYSTEM_PASSWORD"){
            return "系统密码";
        }else if($fpConfigDefine=="PROJECT_CONFIG_SYSTEM_PASSWORD_SWITCH"){
            return "系统密码校验";
        }else if($fpConfigDefine=="PROJECT_CONFIG_TOKEN_SECRET_USER"){    
            return "用户Token加解密Secret";
        }else if($fpConfigDefine=="PROJECT_CONFIG_DISANYUN_APPID"){
            return "第三云API";
        }else if($fpConfigDefine=="PROJECT_CONFIG_DEBUG_LEVEL"){
            return "调试信息等级";
        }else if($fpConfigDefine=="PROJECT_CONFIG_SYSTEM_RUN_LOG"){
            return "是否开启系统运行日志";
        }else if($fpConfigDefine=="PROJECT_CONFIG_IMAGE_PATH"){
            return "图片存储路径";
        }else if($fpConfigDefine=="PROJECT_CONFIG_DB_DESCRIPT"){
            return "数据库说明";
        }else if($fpConfigDefine=="PROJECT_CONFIG_DB_TYPE"){
            return "数据库类型";
        }else if($fpConfigDefine=="PROJECT_CONFIG_DB_HOST"){
            return "数据库IP";
        }else if($fpConfigDefine=="PROJECT_CONFIG_DB_NAME"){
            return "数据库名称";
        }else if($fpConfigDefine=="PROJECT_CONFIG_DB_USER"){
            return "数据库用户名";
        }else if($fpConfigDefine=="PROJECT_CONFIG_DB_PASSWORD"){
            return "数据库密码";
        }else if($fpConfigDefine=="PROJECT_CONFIG_DB_PORT"){
            return "数据库端口";
        }else if($fpConfigDefine=="PROJECT_CONFIG_FLY_PARAMETER_LIMIT"){            
            return "参数限制字段";
        }
        return "";
    }
    
    /**
     * 修改系统常量
     * 2020-03-29 16:00:58
     * */
    public function SetSystemDefine($fpDefineKey,$fpDefineValue){
        $file = FS_CONFIG_PATH;
        $vFileString = file_get_contents($file);
        $vFileString = preg_replace("/define\(\"{$fpDefineKey}\",\".*?\"\)/", "define(\"{$fpDefineKey}\",\"{$fpDefineValue}\")", $vFileString);
        return file_put_contents($file, $vFileString);
    }
    
    /**
     * 获取配置文件常量
     * */
    public function GetSystemConfigDefined(){
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //获取用户定义常量
        $vArray = get_defined_constants(true);
        $vUserDefined = $vArray['user'];
        //获取系统定义配置
        $json = "";
        $vCount = 0;
        foreach($vUserDefined as $key=>$value){
            $vCount += 1;
            if(strpos($key,"PROJECT_CONFIG_")>-1){
                if(gettype($value)=="boolean"){ $value = $value?"true":"false"; }
                $vConfigDescript = self::GetSystemConfigName($key);
                $json .= JsonObj('"key"'.':'.JsonQuotes($key).",".'"value"'.':'.JsonQuotes($value).",".JsonKeyValue("descript", $vConfigDescript)).",";
            }
        }
        if(IsNull($json)){
            return JsonInforFalse("常量配置不存在", "系统常量获取");
        }
        return JsonModelDataString(JsonArray(HandleStringDeleteLast($json)), $vCount);
    }
    
    /**
     * 修改配置文件
     * */
    public function SetSystemConfig(){
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //参数获取区
        $pProjectConfigServer = GetParameterNoCode("project_config_server");
        $pProjectConfigParameterCheck = GetParameterNoCode("project_config_parameter_check");
        $pProjectConfigRbacSwitch = GetParameterNoCode("project_config_rbac_switch");
        $pProjectConfigSystemPassword = GetParameterNoCode("project_config_system_password");
        $pProjectConfigSystemPasswordSwitch = GetParameterNoCode("project_config_system_password_switch");
        $pProjectConfigTokenSecretUser = GetParameterNoCode("project_config_token_secret_user");
        $pProjectConfigDisanyunAppid = GetParameterNoCode("project_config_disanyun_appid");
        $pProjectConfigDebugLevel = GetParameterNoCode("project_config_debug_level");
        $pProjectConfigSystemRunLog = GetParameterNoCode("project_config_system_run_log");
        $pProjectConfigImagePath = GetParameterNoCode("project_config_image_path");
        $pProjectConfigFlyParameterLimit = GetParameterNoCode("project_config_fly_parameter_limit");
        $pProjectConfigDbDescript = GetParameterNoCode("project_config_db_descript");
        $pProjectConfigDbType = GetParameterNoCode("project_config_db_type");
        $pProjectConfigDbHost = GetParameterNoCode("project_config_db_host");
        $pProjectConfigDbName = GetParameterNoCode("project_config_db_name");
        $pProjectConfigDbUser = GetParameterNoCode("project_config_db_user");
        $pProjectConfigDbPassword = GetParameterNoCode("project_config_db_password");
        $pProjectConfigDbPort = GetParameterNoCode("project_config_db_port");
        //配置数量
        $pProjectConfigNumber = GetParameterNoCode("config_number");
        
        //--- 参数校验区 ---
        if(!JudgeRegularUrl($pProjectConfigServer)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_SERVER");
            return JsonInforFalse("{$vConfigDescript} - 值必须版本或URL格式字符串", "PROJECT_CONFIG_SERVER");
        }
        
        //是否开启参数严格校验检测
        $pProjectConfigParameterCheck = strtolower($pProjectConfigParameterCheck);
        if(!FlyJson::JudgeParameterBoolean($pProjectConfigParameterCheck)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_PARAMETER_CHECK");
            return JsonInforFalse("{$vConfigDescript} - 值必须是true或false", "PROJECT_CONFIG_PARAMETER_CHECK");
        }
        
        //是否开启RBAC权限校验
        $pProjectConfigRbacSwitch = strtolower($pProjectConfigRbacSwitch);
        if(!FlyJson::JudgeParameterBoolean($pProjectConfigRbacSwitch)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_RBAC_SWITCH");
            return JsonInforFalse("{$vConfigDescript} - 值必须是true或false", "PROJECT_CONFIG_RBAC_SWITCH");
        }
        
        //系统密码
        if(!JudgeRegularParameter($pProjectConfigSystemPassword)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_SYSTEM_PASSWORD");
            return JsonInforFalse("{$vConfigDescript} - 值必须数字或字母", "PROJECT_CONFIG_SYSTEM_PASSWORD");
        }            
        
        //系统密码校验
        $pProjectConfigSystemPasswordSwitch = strtolower($pProjectConfigSystemPasswordSwitch);
        if(!FlyJson::JudgeParameterBoolean($pProjectConfigSystemPasswordSwitch)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_SYSTEM_PASSWORD_SWITCH");
            return JsonInforFalse("{$vConfigDescript} - 值必须是true或false", "PROJECT_CONFIG_SYSTEM_PASSWORD_SWITCH");
        }
        
        //用户Token加解密Key
        if(!JudgeRegularKey($pProjectConfigTokenSecretUser)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_TOKEN_SECRET_USER");
            return JsonInforFalse("{$vConfigDescript} - 值必须Key字符串", "PROJECT_CONFIG_TOKEN_SECRET_USER");
        }
        
        //第三云API
        if(!JudgeRegularKey($pProjectConfigDisanyunAppid)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_DISANYUN_APPID");
            return JsonInforFalse("{$vConfigDescript} - 值必须Key字符串", "PROJECT_CONFIG_DISANYUN_APPID");
        }
        
        //调试信息等级
        if(!JudgeRegularKey($pProjectConfigDebugLevel)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_DEBUG_LEVEL");
            return JsonInforFalse("{$vConfigDescript} - 值必须是INFOR、WRONG、ERROR", "PROJECT_CONFIG_DEBUG_LEVEL");
        }
        
        //是否开启系统运行日志
        $pProjectConfigSystemRunLog = strtolower($pProjectConfigSystemRunLog);
        if(!FlyJson::JudgeParameterBoolean($pProjectConfigSystemRunLog)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_SYSTEM_RUN_LOG");
            return JsonInforFalse("{$vConfigDescript} - 值必须是true或false", "PROJECT_CONFIG_SYSTEM_RUN_LOG");
        }
        
        //图片存储路径
        if(!JudgeRegularUrl($pProjectConfigImagePath)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_IMAGE_PATH");
            return JsonInforFalse("{$vConfigDescript} - 值必须是图片文件路径", "PROJECT_CONFIG_IMAGE_PATH");
        }
        
        //参数限制字段
        if(!JudgeRegularParameter($pProjectConfigFlyParameterLimit)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_FLY_PARAMETER_LIMIT");
            return JsonInforFalse("{$vConfigDescript} - 值必须是字段字符串", "PROJECT_CONFIG_FLY_PARAMETER_LIMIT");
        }
        
        //数据库说明
        if(!JudgeRegularTitle($pProjectConfigDbDescript)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_DB_DESCRIPT");
            return JsonInforFalse("{$vConfigDescript} - 值必须是描述文本", "PROJECT_CONFIG_DB_DESCRIPT");
        }
        
        //数据库类型
        if(!JudgeRegularTitle($pProjectConfigDbType)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_DB_TYPE");
            return JsonInforFalse("{$vConfigDescript} - 值必须是描述文本", "PROJECT_CONFIG_DB_TYPE");
        }
        
        //数据库地址
        if(!JudgeRegularUrl($pProjectConfigDbHost)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_DB_HOST");
            return JsonInforFalse("{$vConfigDescript} - 值必须是IP地址", "PROJECT_CONFIG_DB_HOST");
        }
        
        //数据库名称
        if(!JudgeRegularTable($pProjectConfigDbName)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_DB_NAME");
            return JsonInforFalse("{$vConfigDescript} - 值必须符合数据库命名规则", "PROJECT_CONFIG_DB_NAME");
        }
        
        //数据库用户名
        if(!JudgeRegularTitle($pProjectConfigDbUser)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_DB_USER");
            return JsonInforFalse("{$vConfigDescript} - 值需符合用户名命名规则", "PROJECT_CONFIG_DB_USER");
        }
        
        //数据库密码
        if(!JudgeRegularTitle($pProjectConfigDbPassword)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_DB_PASSWORD");
            return JsonInforFalse("{$vConfigDescript} - 值需符合密码命名规则", "PROJECT_CONFIG_DB_PASSWORD");
        }
        
        //数据库端口
        if(!JudgeRegularNumber($pProjectConfigDbPort)){
            $vConfigDescript = self::GetSystemConfigName("PROJECT_CONFIG_DB_PORT");
            return JsonInforFalse("{$vConfigDescript} - 值需符合端口规则", "PROJECT_CONFIG_DB_PORT");
        }

        //配置文件数量
        $pProjectConfigNumber = intval($pProjectConfigNumber) + 1;
        
        //变量
        $vFsCore = FS_CORE; //核销版本号
        
        //--- 组合文件常量内容并写入配置文件 ---
        //文件路径
        $fildPath = $_SERVER['DOCUMENT_ROOT'] . "/server/config/configdefine.php";
        //打开文件
        $file = fopen($fildPath, "wb") or die("Unable to open file!");
        //写入文件
        fwrite($file, "<?php \n");
        fwrite($file, "\n");
        fwrite($file, "/**\n");
        fwrite($file, " * 系统配置文件\n");
        fwrite($file, " * 配置数量：{$pProjectConfigNumber}\n");
        fwrite($file, " * 上次修改时间：".GetTimeNow()."\n");
        fwrite($file, " * */\n");
        fwrite($file, "\n");
        fwrite($file, "//当前程序服务器版本\n");
        fwrite($file, "define(\"PROJECT_CONFIG_SERVER\",\"{$pProjectConfigServer}\");\n");
        fwrite($file, "\n");
        fwrite($file, "//接口传入参数校验：true（开启校验）、false（关闭校验）\n");
        fwrite($file, "define(\"PROJECT_CONFIG_PARAMETER_CHECK\",{$pProjectConfigParameterCheck});\n");
        fwrite($file, "\n");
        fwrite($file, "//接口角色权限RBAC校验：true（开启校验）、false（关闭校验）\n");
        fwrite($file, "define(\"PROJECT_CONFIG_RBAC_SWITCH\",{$pProjectConfigRbacSwitch});\n");
        fwrite($file, "\n");
        fwrite($file, "//系统密码:用于检测：数据库连接、基础数据表检测\n");
        fwrite($file, "define(\"PROJECT_CONFIG_SYSTEM_PASSWORD\",\"{$pProjectConfigSystemPassword}\");\n");
        fwrite($file, "\n");
        fwrite($file, "//系统密码权限：true（开启系统密码权限）、false（关闭系统密码权限）\n");
        fwrite($file, "define(\"PROJECT_CONFIG_SYSTEM_PASSWORD_SWITCH\",{$pProjectConfigSystemPasswordSwitch});\n");
        fwrite($file, "\n");
        fwrite($file, "//用户（ajaxuser）Token加密解密Key\n");
        fwrite($file, "define(\"PROJECT_CONFIG_TOKEN_SECRET_USER\",\"{$pProjectConfigTokenSecretUser}\");\n");
        fwrite($file, "\n");
        fwrite($file, "//第三云（disanyun.com）应用Key\n");
        fwrite($file, "define(\"PROJECT_CONFIG_DISANYUN_APPID\",\"{$pProjectConfigDisanyunAppid}\");\n");
        fwrite($file, "\n");
        fwrite($file, "//Debug输出等级：INFOR（SQL信息、接口信息等） < WRONG（异常捕获信息、信息等） < ERROR（全部信息及异常）\n");
        fwrite($file, "define(\"PROJECT_CONFIG_DEBUG_LEVEL\",\"{$pProjectConfigDebugLevel}\");\n");
        fwrite($file, "\n");
        fwrite($file, "//系统日志：true（开启系统日志）、false（关闭系统日志）\n");
        fwrite($file, "define(\"PROJECT_CONFIG_SYSTEM_RUN_LOG\",{$pProjectConfigSystemRunLog});\n");
        fwrite($file, "\n");
        fwrite($file, "//图片存储路径\n");
        fwrite($file, "define(\"PROJECT_CONFIG_IMAGE_PATH\",\"{$pProjectConfigImagePath}\");\n");
        fwrite($file, "\n");
        fwrite($file, "//Fly限制传入参数定义\n");
        fwrite($file, "define(\"PROJECT_CONFIG_FLY_PARAMETER_LIMIT\",\"{$pProjectConfigFlyParameterLimit}\");\n");
        fwrite($file, "\n");
        fwrite($file, "//---------- 数据库配置 ----------\n");
        fwrite($file, "//数据库描述\n");
        fwrite($file, "define(\"PROJECT_CONFIG_DB_DESCRIPT\",\"{$pProjectConfigDbDescript}\");\n");
        fwrite($file, "//数据库类型\n");
        fwrite($file, "define(\"PROJECT_CONFIG_DB_TYPE\",\"{$pProjectConfigDbType}\");\n");
        fwrite($file, "//数据库IP\n");
        fwrite($file, "define(\"PROJECT_CONFIG_DB_HOST\",\"{$pProjectConfigDbHost}\");\n");
        fwrite($file, "//数据库名称\n");
        fwrite($file, "define(\"PROJECT_CONFIG_DB_NAME\",\"{$pProjectConfigDbName}\");\n");
        fwrite($file, "//数据库用户名\n");
        fwrite($file, "define(\"PROJECT_CONFIG_DB_USER\",\"{$pProjectConfigDbUser}\");\n");
        fwrite($file, "//数据库密码\n");
        fwrite($file, "define(\"PROJECT_CONFIG_DB_PASSWORD\",\"{$pProjectConfigDbPassword}\");\n");
        fwrite($file, "//数据库端口\n");
        fwrite($file, "define(\"PROJECT_CONFIG_DB_PORT\",\"{$pProjectConfigDbPort}\");\n");
        //--- 系统运行 ---
        fwrite($file, "\n");
        fwrite($file, "//---------- 系统运行 ----------\n");
        fwrite($file, "//配置文件位置\n");
        fwrite($file, "define(\"FS_CONFIG_PATH\",__FILE__);\n");
        fwrite($file, "//系统占位符:Fly system placeholder\n");
        fwrite($file, "define(\"FS_P\",\"^R?V;\");\n");
        fwrite($file, "//系统核心版本号:System Core\n");
        fwrite($file, "define(\"FS_CORE\",\"{$vFsCore}\");\n");
        //关闭文件
        fclose($file);
        return JsonInforTrue("系统配置修改成功", "configdefine.php");
 
    }
    
    

    /**
     * 类对象注册
     * 2020-03-14 20:51:17
     * */
    public function SystemConfigClass(){
        
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //参数:描述
        $pDescript = GetParameterNoCode('descript', "");
        if(IsNull($pDescript)){return JsonModelParameterNull("descript");}
        
        //参数:路径
        $pPath = GetParameterNoCode('path', "");
        if(!JudgeRegularUrl($pPath,true)){return JsonInforFalse("值为空或不符合规则","path");}
        
        //获取处理后的路径
        $pHandlePath = GetPathHandle($pPath);
        
        //判断路径是否存在
        if(!is_file($pHandlePath)){
            return JsonInforFalse("类文件不存在", "文件路径错误");
        }
        
        //获取路径
        $vFileFullName = pathinfo($pPath, PATHINFO_BASENAME);
        $vFileNameClass = pathinfo($pPath, PATHINFO_FILENAME);
        $vFileNameObj = "Obj".HandleStringPregReplace($vFileNameClass,'/^FlyClass/', "Fly");
        //配置文件路径
        $vConfigClass = "/server/config/configclass.php";
        //获取配置文件函数集
        $vFunctionArray = GetPathFunctionArray($vConfigClass); 
        //判断函数是否已存在
        if(JudgeArrayMember($vFunctionArray, $vFileNameObj)){
            return JsonInforFalse("该函数名已注册", "请通过查看 {$vConfigClass} 配置");
        }
        $vNowTime = GetTimeNow();
        $vKey = '$_SERVER';
        
        //--- 配置文件：注册类对象 ---
        //文件路径
        $fildPath = $_SERVER['DOCUMENT_ROOT'] . $vConfigClass;
        //打开文件：追加文件
        $file = fopen($fildPath, "a") or die("Unable to open file!");
        //写入文件
        fwrite($file, "    \n");
        fwrite($file, "    /** {$pDescript} ({$vNowTime})  */\n");
        fwrite($file, "    function {$vFileNameObj}(){\n");
        fwrite($file, "        include_once {$vKey}['DOCUMENT_ROOT'] . \"{$pPath}\";\n");
        fwrite($file, "        return new {$vFileNameClass}();\n");
        fwrite($file, "    }\n");
        //关闭文件
        fclose($file);
        return JsonInforTrue("类对象配置成功", "configdefine.php");
        
    }
    

    /**
     * Base64转码
     * 创建时间：2019-08-15 15:53:00
     * 说明：对传入的Base64数据进行转码，输出转码后的字符串
     * */
    public function Base64DeCode(){
         
        //--- 参数获取区 ---
        //参数:数据地址
        $pData = GetParameterNoCode('data', "");
        if(IsNull($pData)){return JsonModelParameterNull("data");}
    
        $vBase64Decode = @base64_decode($pData);
        if(IsNull($vBase64Decode)){
            return JsonInforFalse("转化后数据为空", "Base64数据转化失败");
        }
        return JsonInforTrue(HandleStringFlyHtmlEncode($vBase64Decode), "Base64数据转化成功");
    }
    
    /**
     * 获取系统文件
     * 创建时间：2020-03-03 21:08:13
     * 说明：获取项目目录文件
     * */
    public function GetSystemFile(){
        
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //参数:目录名
        $pFileName = GetParameterNoCode('filename', "");
        if(!IsNull($pFileName)&&!JudgeRegularUrl($pFileName)){return JsonModelParameterNull("filename");}
        //参数:分页
        $page = GetParameter('page', "");
        if(IsNull($page)){return JsonModelParameterNull("page");}
        if(intval($page)<0){$page=1;}
        //参数:条数
        $limit = GetParameter('limit', "");
        if(IsNull($limit)){return JsonModelParameterNull("limit");}
        
        $dir = "";
        if(IsNull($pFileName)){
            $dir = $_SERVER['DOCUMENT_ROOT']."/";
        }else{
            $dir = $_SERVER['DOCUMENT_ROOT']."/".$pFileName . "/";
        }
        
        //判断是否是目录
        if(!is_dir($dir)){
            return JsonInforFalse("不是正确的目录名", $dir);
        }
        
        
        //按照名称排序
        if ($dh = opendir($dir)) {
            $i = 0;
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..") {
                    $files[$i]["type"] = filetype($dir.$file);//获取文件类型
                    $files[$i]["name"] = $file;//获取文件名称
                    $i++;
                }
            }
        }
        closedir($dh);
        foreach($files as $k=>$v){
            //$name[$k] = $v['name'];   //按照name排序是解开并使用
            $type[$k] = $v['type'];
        }
        array_multisort($type,SORT_STRING , $files);   //按名字排序
        
        $filesnames = [];
        foreach($files as $k=>$v){
            array_push($filesnames, $v['name']);
        }
        
        //$filesnames = scandir($dir); //得到所有的文件
        $iLimit = 0;
        $vFileJsonCount = 0;
        $vFileJsonSize = 0;
        $vFileJson = "";
        foreach($filesnames as $filename) {
            if($filename != "." && $filename != ".."){
                $vFileJsonCount ++;
                if($vFileJsonCount > ($page*$limit-$limit) && $iLimit < $limit){
                    $iLimit ++;
                    $vThisCode = "";
                    $vThisFilePath = $dir . $filename;
                    $VFilePathInfor = pathinfo($vThisFilePath);
                    $vLocalPath = $VFilePathInfor["dirname"]."/".$VFilePathInfor["basename"];
                    $vThisCode .= JsonKeyValue("localPath", $vLocalPath).",";
                    $vWebUrl = $_SERVER['SERVER_NAME']."/".$pFileName."/".$VFilePathInfor["basename"];
                    $vThisCode .= JsonKeyValue("webPath", GetHttpType().$vWebUrl).",";
                    $vThisCode .= JsonKeyValue("fileName", $filename).",";
                    $vThisCode .= JsonKeyValue("fileType", filetype($vThisFilePath)).",";
                    $vFileSize = filesize($vThisFilePath);
                    $vThisCode .= JsonKeyValue("fileSize", $vFileSize).",";
                    $vThisCode .= JsonKeyValue("filePower", substr(sprintf('%o',fileperms($vThisFilePath)), -4)).",";
                    $vThisCode .= JsonKeyValue("fileCreateTime", date('Y-m-d H:i:s',filectime($vThisFilePath))).",";
                    $vThisCode .= JsonKeyValue("fileSetTime", date('Y-m-d H:i:s',filemtime($vThisFilePath))).",";
                    $vThisCode .= JsonKeyValue("fileVisitTime", date('Y-m-d H:i:s',fileatime($vThisFilePath)));
                    $vFileJson .= JsonObj($vThisCode).",";
                    $vFileJsonSize += $vFileSize;
                }
            }
        }
        return JsonInforData("项目文件获取", "{$vFileJsonSize}", "", $vFileJsonCount, JsonArray(HandleStringDeleteLast($vFileJson)));
    } 
    
    
    /**
     * 获取服务器信息
     * 创建时间：2020-03-15 00:31:40
     * 说明：获取项目目录文件
     * */
    public function GetServerInfor(){
        
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        echo phpinfo();
        
    }
    
    /**
     * 获取系统文件内容
     * 创建时间：2020-03-04 22:03:44
     * 说明：获取项目目录文件内容
     * */
    public function GetSystemFileBody(){
    
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return $vAdmin;}
        
        //参数:文件路径
        $pFilePath = GetParameterNoCode('filepath', "");
        if(!JudgeRegularUrl($pFilePath)){return JsonModelParameterNull("filepath");}
        
        //判断是否是文件
        if(!file_exists($pFilePath)){
            $pFilePath = $_SERVER['DOCUMENT_ROOT'] ."/". $pFilePath;
            if(!file_exists($pFilePath)){
                return JsonInforFalse("文件不存在", $pFilePath);
            }
        }
        
        //读取文件
        if(file_exists($pFilePath)){
            $fp = fopen($pFilePath,"r");
            $str = fread($fp,filesize($pFilePath));     //指定读取大小，这里把整个文件内容读取出来
            echo $str;
            fclose($fp);
        }
    
        return "";
    }     
    
    /**
     * 保存系统文件内容
     * 创建时间：2020-03-04 22:51:54
     * 说明：保存项目目录文件内容
     * */
    public function SaveSystemFileBody(){
        
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
            return JsonInforFalse("文件不存在", $pFilePath);
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
    
    
    //=========== 系统权限 =========== 
    
    /**
     * 系统密码校验
     * 2020-03-08 16:07:33
     * */
    function CheckSystemPassword(){
        
        //参数:系统密码
        $pSystemPassword = GetParameterNoCode('system_password', "");
        if(!JudgeRegularLetterNumber($pSystemPassword)){return JsonParameterWrong("system_password", $pSystemPassword);}
        
        //判断系统密码权限是否开启
        if(!PROJECT_CONFIG_SYSTEM_PASSWORD_SWITCH){
            return JsonInforFalse("系统密码权限未开启", "系统密码权限校验");
        }
        
        //判断管理员数据表是否存在
        if(!DBMySQLJudge::JudgeTable("fly_user_admin")){
            return JsonInforFalse("管理员数据表不存在，请创建。", "fly_user_admin");
        }

        //当存在程序创建者权限时，系统密码权限作废
        $vAdminList = ObjRoleObjectAdmin() -> JudgeAdminCreater();
        if(JudgeJsonTrue($vAdminList)){ return JsonInforFalse("请使用程序创建者权限", "系统密码权限作废"); }
        
        //判断传入的系统密码与系统配置是否一致
        if($pSystemPassword!=PROJECT_CONFIG_SYSTEM_PASSWORD){
            return JsonInforFalse("系统密码错误", "系统密码权限校验");
        }
        
        return JsonInforTrue("系统密码校验成功", "系统密码权限校验");
    }
    
    /**
     * 检测数据表连接
     * 2020-03-08 14:23:59
     * */
    function CheckDataBaseLink(){
        $vPdo = DBHelper::GetConnection();
        if(IsNull($vPdo)){
            return JsonInforFalse("数据库链接创建失败", "数据库链接检测");
        }
        $vBool = DBHelper::JudgeConnection($vPdo);
        if($vBool){
            return JsonInforTrue("数据库链接成功", "数据库链接检测");
        }else{
            return JsonInforFalse("数据库链接失败", "数据库链接检测");
        }
    }
    
    /**
     * 检测系统基础支持数据表
     * 2020-03-08 14:23:59
     * */
    function CheckSytemBaseTable(){
        
        //检测数据表连接，链接失败返回检测结果
        $dbLink = $this -> CheckDataBaseLink();
        if(JudgeJsonFalse($dbLink)){ return $dbLink; }
        //判断当前ID是否是程序创建者
        $vJudgeAdminTableBo = DBMySQLJudge::JudgeTable("fly_user_admin"); //判断管理员数据表是否存在
        if($vJudgeAdminTableBo){
            $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
            if(JudgeJsonFalse($vAdmin)){return WriteEcho($vAdmin);}    
        }
            
        //----- 检测数据表，不存在的进行创建 -----
        $vTableArray = [
            //角色
            "fly_user_admin" => "CREATE TABLE `fly_user_admin` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `phoneNumber` varchar(36) DEFAULT NULL COMMENT '管理员手机号',  `password` varchar(64) DEFAULT NULL COMMENT '密码',  `department` varchar(64) DEFAULT NULL COMMENT '部门',  `position` varchar(64) DEFAULT NULL COMMENT '部门职位',  `loginTimes` int(11) DEFAULT '0' COMMENT '登陆次数',  `loginTime` timestamp NULL DEFAULT NULL COMMENT '登陆时间',  `loginIp` varchar(36) DEFAULT NULL COMMENT '登陆IP',  `loginMac` varchar(36) DEFAULT NULL COMMENT '登陆端MAC',  `adminName` varchar(36) DEFAULT NULL COMMENT '管理员姓名',  `adminSex` varchar(36) DEFAULT '男' COMMENT '管理员性别',  `adminIdCard` varchar(36) DEFAULT NULL COMMENT '管理员身份证号',  `adminIdCardPhoto` varchar(128) DEFAULT NULL COMMENT '管理员身份证照片',  `adminCheck` varchar(36) DEFAULT NULL COMMENT '管理员审核',  `isRoleTester` varchar(36) DEFAULT 'false' COMMENT '是否为测试者',  `isRoleDeveloper` varchar(36) DEFAULT 'false' COMMENT '是否为开发程序员',  `isRoleCreater` varchar(36) DEFAULT 'false' COMMENT '是否为程序创建者',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员'",
            "fly_user" => "CREATE TABLE `fly_user` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `userName` varchar(36) DEFAULT NULL COMMENT '用户名[登陆标识]', `eMail` varchar(36) DEFAULT NULL COMMENT '邮箱[登陆标识]', `phoneNumber` varchar(11) DEFAULT NULL COMMENT '手机号码[登陆标识]', `password` varchar(64) DEFAULT NULL COMMENT '密码', `loginTimes` int(11) DEFAULT '0' COMMENT '登陆次数', `loginTime` timestamp NULL DEFAULT NULL COMMENT '登陆时间', `loginIp` varchar(36) DEFAULT NULL COMMENT '登陆IP', `loginMac` varchar(36) DEFAULT NULL COMMENT '登陆端MAC', `refereeId` varchar(36) DEFAULT NULL COMMENT '用户推荐人', `userIdCard` varchar(36) DEFAULT NULL COMMENT '用户身份证', `userIdCardPhoto` varchar(256) DEFAULT NULL COMMENT '用户手持身份证照片', `userIdCardName` varchar(36) DEFAULT NULL COMMENT '用户身份证姓名', `userNick` varchar(36) DEFAULT NULL COMMENT '用户昵称', `userSex` varchar(10) DEFAULT NULL COMMENT '用户性别', `userPhoto` varchar(128) DEFAULT NULL COMMENT '用户照片', `userCheck` varchar(36) DEFAULT 'false' COMMENT '用户审核', `userCheckAdminId` int(11) DEFAULT NULL COMMENT '用户审核管理员ID', `userRegisterType` varchar(16) DEFAULT NULL COMMENT '用户注册类型', `userRegisterDescript` varchar(64) DEFAULT NULL COMMENT '用户注册描述', `userRegisterAdminId` int(11) DEFAULT NULL COMMENT '用户注册管理员ID[当该用户为管理员注册用户时]', `userState` varchar(36) DEFAULT 'USER_NORMAL' COMMENT '用户状态', `wecharOpenId` varchar(36) DEFAULT NULL COMMENT '微信OpenId', `wechatAvatarUrl` varchar(256) DEFAULT NULL COMMENT '微信头像', `wechatNickName` varchar(64) DEFAULT NULL COMMENT '微信昵称', `wechatPublicOpenId` varchar(64) DEFAULT NULL COMMENT '微信公众号OpenId', `wechatUnionId` varchar(64) DEFAULT NULL COMMENT '用户微信unionid',  PRIMARY KEY (`id`) USING BTREE,  UNIQUE KEY `phonenumber` (`phoneNumber`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户';",
            //角色扩展项
            "fly_user_integral" => "CREATE TABLE `fly_user_integral` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `userId` varchar(36) DEFAULT NULL COMMENT '用户ID',  `integralEvent` varchar(36) DEFAULT NULL COMMENT '积分事件',  `integralNumber` int(11) DEFAULT NULL COMMENT '积分数',  `integralSum` int(11) DEFAULT NULL COMMENT '积分总数',  `integralDate` datetime DEFAULT NULL COMMENT '积分日期',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户积分'",
            "fly_user_referee" => "CREATE TABLE `fly_user_referee` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `userId` int(11) DEFAULT NULL COMMENT '用户ID',  `upOneUserId` int(11) DEFAULT NULL COMMENT '上一级用户ID',  `upTwoUserId` int(11) DEFAULT NULL COMMENT '上二级用户ID',  `upThreeUserId` int(11) DEFAULT NULL COMMENT '上三级用户ID',  `upFourUserId` int(11) DEFAULT NULL COMMENT '上四级用户ID',  `upFiveUserId` int(11) DEFAULT NULL COMMENT '上五级用户ID',  `upSixUserId` int(11) DEFAULT NULL COMMENT '上六级用户ID',  `upSevenUserId` int(11) DEFAULT NULL COMMENT '上七级用户ID',  `upEightUserId` int(11) DEFAULT NULL COMMENT '上八级用户ID',  `upNineUserId` int(11) DEFAULT NULL COMMENT '上九级用户ID',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户推荐关系'",
            "fly_user_referee_integral" => "CREATE TABLE `fly_user_referee_integral` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `userId` int(11) DEFAULT NULL COMMENT '推荐用户ID', `registerUserId` int(11) DEFAULT NULL COMMENT '注册用户ID', `integral` int(11) DEFAULT NULL COMMENT '奖励积分', `registerType` varchar(10) DEFAULT '推荐奖励' COMMENT '积分类型', `integralDescript` varchar(32) DEFAULT NULL COMMENT '积分描述', `adminId` int(11) DEFAULT NULL COMMENT '管理员ID',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户推荐积分奖励';",
            "fly_user_referee_many" => "CREATE TABLE `fly_user_referee_many` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `refereeUserId` int(11) DEFAULT NULL COMMENT '推荐用户ID', `userId` int(11) DEFAULT NULL COMMENT '用户ID', `money` decimal(10,2) DEFAULT NULL COMMENT '奖励金额', `moneyDescript` varchar(36) DEFAULT NULL COMMENT '奖励备注',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户推荐现金奖励';",
            //管理员操作日志
            "fly_user_admin_operation_log" => "CREATE TABLE `fly_user_admin_operation_log` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `adminId` int(11) DEFAULT '-1' COMMENT '管理员ID',  `className` varchar(64) DEFAULT '' COMMENT '类名',  `functionName` varchar(64) DEFAULT '' COMMENT '函数名',  `operationType` varchar(36) DEFAULT '' COMMENT '操作类型',  `operationId` bigint(20) DEFAULT NULL COMMENT '操作ID',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员操作日志'",
            //Token日志
            "fly_token_log" => "CREATE TABLE `fly_token_log` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `requestIp` varchar(36) DEFAULT NULL COMMENT '请求IP', `iss` varchar(36) DEFAULT NULL COMMENT '签发人', `sub` varchar(36) DEFAULT NULL COMMENT '主题', `aud` varchar(36) DEFAULT NULL COMMENT '受众', `iat` timestamp NULL DEFAULT NULL COMMENT '签发时间', `nbf` timestamp NULL DEFAULT NULL COMMENT '生效时间', `exp` timestamp NULL DEFAULT NULL COMMENT '过期时间', `jti` varchar(64) DEFAULT NULL COMMENT '编号', `utype` varchar(64) DEFAULT NULL COMMENT '用户类型', `uid` varchar(64) DEFAULT NULL COMMENT '用户Id', `uName` varchar(36) DEFAULT NULL COMMENT '用户名', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Token生成日志'",
            //登陆日志
            "fly_role_login_log" => "CREATE TABLE `fly_role_login_log` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `userTable` varchar(36) DEFAULT NULL COMMENT '用户数据表', `typeName` varchar(36) DEFAULT NULL COMMENT '类名称', `groupName` varchar(36) DEFAULT NULL COMMENT '组名称', `loginType` varchar(36) DEFAULT NULL COMMENT '登陆类型', `adminId` bigint(20) DEFAULT NULL COMMENT '管理员ID', `loginUser` varchar(36) DEFAULT NULL COMMENT '登陆用户', `loginUserName` varchar(36) DEFAULT NULL COMMENT '登陆用户名', `loginState` varchar(36) DEFAULT NULL COMMENT '登陆状态', `loginIp` varchar(36) DEFAULT NULL COMMENT '登陆IP', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `state` varchar(36) DEFAULT 'NORMAL' COMMENT '记录状态', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色登陆日志'",
            //请求日志
            "fly_request_log" => "CREATE TABLE `fly_request_log` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `userType` varchar(36) DEFAULT NULL COMMENT '用户类型', `userTable` varchar(36) DEFAULT NULL COMMENT '用户数据表', `userId` bigint(20) DEFAULT NULL COMMENT '用户ID', `requestIp` varchar(36) DEFAULT NULL COMMENT '请求IP', `requestEvent` varchar(64) DEFAULT NULL COMMENT '请求事件', `eventTable` varchar(36) DEFAULT NULL COMMENT '事件数据表', `eventKeyField` varchar(64) DEFAULT NULL COMMENT '事件主键字段', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='请求日志'",
            //系统更新日志
            "fly_system_update_log" => "CREATE TABLE `fly_system_update_log` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `versionNumber` varchar(12) DEFAULT NULL COMMENT '版本号', `fileUpdateTime` timestamp NULL DEFAULT NULL COMMENT '文件更新时间', `filePath` varchar(256) DEFAULT NULL COMMENT '文件路径', `updatePath` varchar(256) DEFAULT NULL COMMENT '更新文件路径', `updateState` varchar(36) DEFAULT 'UPDATE_NEW' COMMENT '更新状态', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统更新日志'",
            //手机验证码
            "fly_phone_code" => "CREATE TABLE `fly_phone_code` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `recodeName` varchar(36) DEFAULT NULL COMMENT '记录名称',  `recodeGroup` varchar(36) DEFAULT NULL COMMENT '记录组',  `apiSource` varchar(36) DEFAULT NULL COMMENT 'API来源',  `modelId` varchar(36) DEFAULT NULL COMMENT '验证码模板',  `body` text COMMENT '验证码内容',  `phonenumber` varchar(36) DEFAULT NULL COMMENT '电话号码',  `phoneCode` varchar(36) DEFAULT NULL COMMENT '短信验证码',  `sendEvent` varchar(36) DEFAULT NULL COMMENT '发送事件',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='手机短信验证码'",
            //RBAC
            "fly_rbac_nav" => "CREATE TABLE `fly_rbac_nav` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `fatherId` varchar(36) DEFAULT NULL COMMENT '父ID', `typeName` varchar(36) DEFAULT NULL COMMENT '类名称', `groupName` varchar(36) DEFAULT NULL COMMENT '组名称', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `navName` varchar(64) DEFAULT NULL COMMENT '导航名称', `navUpId` varchar(36) DEFAULT NULL COMMENT '导航上级ID', `navIcon` varchar(128) DEFAULT NULL COMMENT '导航图标', `navIconClass` varchar(128) DEFAULT NULL COMMENT '导航图标样式', `navHref` varchar(128) DEFAULT NULL COMMENT '导航链接', `navLevel` varchar(64) DEFAULT NULL COMMENT '导航等级', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='RBAC导航'",
            "fly_rbac_role" => "CREATE TABLE `fly_rbac_role` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `roleType` varchar(36) DEFAULT NULL COMMENT '角色类型[管理员|用户]', `roleAccessPath` varchar(128) DEFAULT NULL COMMENT '角色请求路径', `roleName` varchar(36) DEFAULT NULL COMMENT '角色名称', `roleDescript` varchar(128) DEFAULT NULL COMMENT '角色描述', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='RBAC角色'",
            "fly_rbac_role_interface" => "CREATE TABLE `fly_rbac_role_interface` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `roleId` varchar(36) DEFAULT NULL COMMENT '角色ID', `interfaceId` varchar(36) DEFAULT NULL COMMENT '接口ID', `interfaceKey` varchar(128) DEFAULT NULL COMMENT '接口Key', `isRelation` varchar(36) DEFAULT 'true' COMMENT '是否关联[true|false]', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='RBAC角色接口'",
            "fly_rbac_role_user" => "CREATE TABLE `fly_rbac_role_user` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT 'none' COMMENT '唯一Key', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `state` varchar(36) DEFAULT 'NORMAL' COMMENT '记录状态', `userAccessPath` varchar(36) DEFAULT NULL COMMENT '用户请求路径', `roleId` varchar(36) DEFAULT NULL COMMENT '角色ID', `userId` varchar(36) DEFAULT NULL COMMENT '用户ID', `overTime` timestamp NULL DEFAULT NULL COMMENT '到期时间', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='RBAC角色用户'",
            "fly_rbac_role_view" => "CREATE TABLE `fly_rbac_role_view` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `roleId` varchar(36) DEFAULT NULL COMMENT '角色ID', `roleAccessPage` varchar(128) DEFAULT NULL COMMENT '角色请求页面', `selector` varchar(64) DEFAULT NULL COMMENT '选择器', `operationType` varchar(36) DEFAULT NULL COMMENT '操作类型', `operationWeight` int(11) DEFAULT NULL COMMENT '操作权重', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='RBAC角色界面'",
            //接口
            "fly_interface" => "CREATE TABLE `fly_interface` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'NORMAL' COMMENT '记录状态', `interfacePath` varchar(128) DEFAULT NULL COMMENT '接口路径', `interfaceIndexLine` varchar(128) DEFAULT NULL COMMENT '接口索引业务线', `interfaceIndexLineDescript` varchar(128) DEFAULT NULL COMMENT '接口索引业务线描述', `interfaceIndexMethod` varchar(128) DEFAULT NULL COMMENT '接口索引业务线方法', `interfaceIndexMethodDescript` varchar(128) DEFAULT NULL COMMENT '接口索引业务线方法描述', `interfaceAccessMethod` varchar(36) DEFAULT 'POST、GET' COMMENT '接口请求方式', `interfacePower` varchar(36) DEFAULT NULL COMMENT '接口权限', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口文档'",
            "fly_interface_demo" => "CREATE TABLE `fly_interface_demo` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `state` varchar(36) DEFAULT 'NORMAL' COMMENT '记录状态', `adminId` varchar(36) DEFAULT NULL COMMENT '管理员ID', `adminName` varchar(36) DEFAULT NULL COMMENT '管理员名字', `interfacePath` varchar(128) DEFAULT NULL COMMENT '接口路径', `interfaceIndexLine` varchar(128) DEFAULT NULL COMMENT '接口索引业务线', `interfaceIndexMethod` varchar(128) DEFAULT NULL COMMENT '接口索引业务线方法', `interfaceToken` text COMMENT '接口Token', `interfaceParameter` text COMMENT '接口参数', `interfaceResponse` text COMMENT '接口结果', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口测试用例'",
            "fly_interface_line" => "CREATE TABLE `fly_interface_line` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `state` varchar(36) DEFAULT 'NORMAL' COMMENT '记录状态', `serviceLine` varchar(64) DEFAULT NULL COMMENT '业务名称', `serviceType` varchar(36) DEFAULT NULL COMMENT '业务类型', `serviceImage` varchar(128) DEFAULT NULL COMMENT '业务图片', `serviceLineImage` varchar(256) DEFAULT NULL COMMENT '业务线流程图页面路径', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='业务线文档'",
            "fly_interface_param" => "CREATE TABLE `fly_interface_param` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT 'none' COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `interfaceId` int(11) DEFAULT NULL COMMENT '接口ID', `parameterName` varchar(64) DEFAULT NULL COMMENT '参数名称', `parameterValue` text COMMENT '参数值', `parameterType` varchar(36) DEFAULT NULL COMMENT '参数值类型', `parameterMust` varchar(36) DEFAULT 'YES' COMMENT '参数是否必填', `parameterDescript` varchar(64) DEFAULT NULL COMMENT '参数说明', `parameterRemarks` varchar(128) DEFAULT NULL COMMENT '参数备注', `parameterDemo` varchar(64) DEFAULT NULL COMMENT '参数例程', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口参数文档'",
            "fly_interface_service" => "CREATE TABLE `fly_interface_service` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `servicePage` varchar(36) DEFAULT NULL COMMENT '业务线页面', `serviceArea` varchar(128) DEFAULT NULL COMMENT '业务区域', `serviceDescript` varchar(128) DEFAULT NULL COMMENT '业务描述', `interfaceDescript` varchar(128) DEFAULT NULL COMMENT '接口描述', `interfacePath` varchar(128) DEFAULT NULL COMMENT '接口请求路径', `interfaceLine` varchar(128) DEFAULT NULL COMMENT '接口业务线', `interfaceLineMethod` varchar(128) DEFAULT NULL COMMENT '接口业务线方法', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口业务线文档'",
            //工单系统
            "fly_work_order" => "CREATE TABLE `fly_work_order` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `originatorId` int(11) DEFAULT NULL COMMENT '发起人ID', `originatorName` varchar(36) DEFAULT NULL COMMENT '发起人姓名', `receiverId` int(11) DEFAULT NULL COMMENT '接收人ID', `receiverName` varchar(36) DEFAULT NULL COMMENT '接收人姓名', `workOrderType` varchar(36) DEFAULT NULL COMMENT '工单类型[需求|BUG]', `workOrderTypeIndex` int(11) DEFAULT NULL COMMENT '工单类型索引', `vWorkOrderPowerType` varchar(36) DEFAULT NULL COMMENT '工单权重类型[普通|紧急]', `vWorkOrderPowerTypeIndex` int(11) DEFAULT NULL COMMENT '工单权重索引', `workOrderQuestionsTimes` int(11) DEFAULT '0' COMMENT '提问次数', `workOrderReplyTimes` int(11) DEFAULT '0' COMMENT '回复次数', `workOrderTitle` varchar(128) DEFAULT NULL COMMENT '工单标题', `workOrderBody` text COMMENT '工单内容', `workOrderOneImage` varchar(128) DEFAULT NULL COMMENT '工单问题图片一', `workOrderTwoImage` varchar(128) DEFAULT NULL COMMENT '工单问题图片二', `workOrderThreeImage` varchar(128) DEFAULT NULL COMMENT '工单问题图片三', `workOrderFourImage` varchar(128) DEFAULT NULL COMMENT '工单问题图片四', `workOrderFiveImage` varchar(128) DEFAULT NULL COMMENT '工单问题图片五', `workOrderTime` timestamp NULL DEFAULT NULL COMMENT '工单最迟解决时间', `workOrderState` varchar(36) DEFAULT 'WORK_ORDER_CREATE' COMMENT '工单状态', `workOrderStateIndex` int(11) DEFAULT '100' COMMENT '工单状态索引', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='工单列表'",
            "fly_work_order_recode" => "CREATE TABLE `fly_work_order_recode` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `workorderId` int(11) DEFAULT NULL COMMENT '工单ID', `responderId` int(11) DEFAULT NULL COMMENT '回复者ID', `replyType` varchar(36) DEFAULT NULL COMMENT '回复类型[提问|回复]', `replyBody` varchar(512) DEFAULT NULL COMMENT '回复内容', `replyOneImage` varchar(128) DEFAULT NULL COMMENT '回复图片一', `replyTwoImage` varchar(128) DEFAULT NULL COMMENT '回复图片二', `replyThreeImage` varchar(128) DEFAULT NULL COMMENT '回复图片三', `replyFourImage` varchar(128) DEFAULT NULL COMMENT '回复图片四', `replyFiveImage` varchar(128) DEFAULT NULL COMMENT '回复图片五', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='工单回复记录'",
            //工作目标系统
            "fly_work_task" => "CREATE TABLE `fly_work_task` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `workerId` int(11) DEFAULT NULL COMMENT '工作者ID', `workerName` varchar(36) DEFAULT NULL COMMENT '工作者姓名', `workType` varchar(36) DEFAULT NULL COMMENT '工作类型[需求|BUG]', `workTypeIndex` int(11) DEFAULT NULL COMMENT '工作类型索引', `workPowerType` varchar(36) DEFAULT NULL COMMENT '工作权重类型[普通|紧急]', `workPowerTypeIndex` int(11) DEFAULT NULL COMMENT '工作权重索引', `workTitle` varchar(128) DEFAULT NULL COMMENT '工作标题', `workBody` text COMMENT '工作内容', `workOneImage` varchar(128) DEFAULT NULL COMMENT '工作图片一', `workTwoImage` varchar(128) DEFAULT NULL COMMENT '工作图片二', `workThreeImage` varchar(128) DEFAULT NULL COMMENT '工作图片三', `workFourImage` varchar(128) DEFAULT NULL COMMENT '工作图片四', `workFiveImage` varchar(128) DEFAULT NULL COMMENT '工作图片五', `workTime` timestamp NULL DEFAULT NULL COMMENT '工作最迟解决时间', `workState` varchar(36) DEFAULT 'WORK_CREATE' COMMENT '工作状态', `workStateIndex` int(11) DEFAULT '100' COMMENT '工作状态', `workReplaceTimes` int(11) DEFAULT '0' COMMENT '任务更新次数', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='工作目标列表'",
            "fly_work_task_recode" => "CREATE TABLE `fly_work_task_recode` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `workTaskId` int(11) DEFAULT NULL COMMENT '工作任务ID', `workBody` varchar(512) DEFAULT NULL COMMENT '完成内容', `workOneImage` varchar(128) DEFAULT NULL COMMENT '图片一', `workTwoImage` varchar(128) DEFAULT NULL COMMENT '图片二', `workThreeImage` varchar(128) DEFAULT NULL COMMENT '图片三', `workFourImage` varchar(128) DEFAULT NULL COMMENT '图片四', `workFiveImage` varchar(128) DEFAULT NULL COMMENT '图片五', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='工作完成记录'",
            //SQL
            "fly_sql" => "CREATE TABLE `fly_sql` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', `adminId` bigint(20) DEFAULT NULL COMMENT '管理员ID', `relationId` varchar(512) DEFAULT NULL COMMENT '关联ID', `sqlString` text COMMENT 'SQL', `sqlDescript` varchar(64) DEFAULT NULL COMMENT 'SQL描述', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='SQL'",
            //BASE
            "fly_base" => "CREATE TABLE `fly_base` ( `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID', `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key', `descript` varchar(36) DEFAULT NULL COMMENT '记录描述', `indexNumber` int(11) DEFAULT '-1' COMMENT '序号', `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间', `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间', `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态', `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态', PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='基础结构数据表'",
            //项目日志
            "fly_project_timeaxis" => "CREATE TABLE `fly_project_timeaxis` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `versionNumber` varchar(20) DEFAULT NULL COMMENT '版本号',  `versionTime` timestamp NULL DEFAULT NULL COMMENT '版本时间',  `versionBlockquote` varchar(128) DEFAULT NULL COMMENT '版本块内容',  `versionBody` varchar(256) DEFAULT NULL COMMENT '版本内容',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目时间轴'",
            "fly_project_timeaxis_title" => "CREATE TABLE `fly_project_timeaxis_title` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `versionId` varchar(20) DEFAULT NULL COMMENT '版本ID',  `versionTitle` varchar(64) DEFAULT NULL COMMENT '版本标题',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目时间轴标题'",
            "fly_project_timeaxis_title_body" => "CREATE TABLE `fly_project_timeaxis_title_body` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `versionTitleId` varchar(20) DEFAULT NULL COMMENT '版本标题ID',  `versionBodyType` varchar(36) DEFAULT NULL COMMENT '内容类型',  `versionBody` varchar(128) DEFAULT NULL COMMENT '标题内容',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目时间轴标题内容'",
            //项目维护
            "fly_project_update" => "CREATE TABLE `fly_project_update` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `adminId` int(11) DEFAULT NULL COMMENT '管理员ID',  `updateId` int(11) DEFAULT NULL COMMENT '维护ID',  `updateType` varchar(36) DEFAULT NULL COMMENT '维护类型',  `updateTitle` varchar(64) DEFAULT NULL COMMENT '维护标题',  `updateBody` varchar(512) DEFAULT NULL COMMENT '维护内容',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目维护'",
            //项目操作手册
            "fly_option_menu" => "CREATE TABLE `fly_option_menu` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `menuName` varchar(36) DEFAULT NULL COMMENT '菜单名称',  `menuIcon` varchar(36) DEFAULT NULL COMMENT '菜单图标',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Fly-操作手册-菜单'",
            "fly_option_menu_son" => "CREATE TABLE `fly_option_menu_son` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `menuId` int(11) DEFAULT NULL COMMENT '菜单ID',  `menuSonName` varchar(36) DEFAULT NULL COMMENT '菜单子项名称',  `menuSonDescript` varchar(256) DEFAULT NULL COMMENT '菜单子项描述',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Fly-操作手册-菜单子项'",
            "fly_option_menu_son_step" => "CREATE TABLE `fly_option_menu_son_step` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `menuSonId` int(11) DEFAULT NULL COMMENT '菜单子项ID',  `stepTitle` varchar(64) DEFAULT NULL COMMENT '步骤标题',  `stepTitleDescript` varchar(256) DEFAULT NULL COMMENT '步骤标题描述',  `stepNumber` int(11) DEFAULT NULL COMMENT '步骤编号',  `stepDescript` varchar(256) DEFAULT NULL COMMENT '步骤描述',  `stepImage` varchar(128) DEFAULT NULL COMMENT '步骤图片一',  `stepImageTwo` varchar(128) DEFAULT NULL COMMENT '步骤图片二',  `stepImageThree` varchar(128) DEFAULT NULL COMMENT '步骤图片三',  `stepImageFour` varchar(128) DEFAULT NULL COMMENT '步骤图片四',  `stepImageFive` varchar(128) DEFAULT NULL COMMENT '步骤图片五',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Fly-操作手册-操作步骤'",
            //页面相关
            "sys_page_banner" => "CREATE TABLE `sys_page_banner` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `bannerGroup` varchar(36) DEFAULT NULL COMMENT 'Banner组',  `bannerUrl` varchar(128) DEFAULT NULL COMMENT 'Banner图片地址',  `bannerHref` varchar(128) DEFAULT NULL COMMENT 'Banner链接',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统-页面-Banner'",
            "sys_page_help" => "CREATE TABLE `sys_page_help` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `recodeName` varchar(36) DEFAULT NULL COMMENT '记录名称',  `recodeGroup` varchar(36) DEFAULT NULL COMMENT '记录组',  `recodeUpId` varchar(36) DEFAULT NULL COMMENT '上级记录ID',  `helpTitle` varchar(64) DEFAULT NULL COMMENT '帮助标题',  `helpBody` text COMMENT '帮助内容',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统-页面-帮助文档'",
            "sys_page_logo" => "CREATE TABLE `sys_page_logo` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `logoWidth` varchar(10) DEFAULT NULL COMMENT 'LOGO宽',  `logoHeight` varchar(10) DEFAULT NULL COMMENT 'LOGO高',  `logoName` varchar(36) DEFAULT NULL COMMENT 'LOGO名称',  `logoUrl` varchar(128) DEFAULT NULL COMMENT 'LOGO图片地址',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统-页面-LOGO'",
            "sys_page_nav" => "CREATE TABLE `sys_page_nav` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `recodeName` varchar(36) DEFAULT NULL COMMENT '记录名称',  `recodeGroup` varchar(36) DEFAULT NULL COMMENT '记录组',  `recodeUpId` varchar(36) DEFAULT NULL COMMENT '上级记录ID',  `navIcon` varchar(128) DEFAULT NULL COMMENT '导航图标图片',  `navName` varchar(36) DEFAULT NULL COMMENT '导航名称',  `navHref` varchar(128) DEFAULT NULL COMMENT '导航链接',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统-页面-导航'",
            "sys_page_news" => "CREATE TABLE `sys_page_news` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `recodeName` varchar(36) DEFAULT NULL COMMENT '记录名称',  `recodeGroup` varchar(36) DEFAULT NULL COMMENT '记录组',  `newsTitle` varchar(64) DEFAULT NULL COMMENT '文章标题',  `newsLogo` varchar(128) DEFAULT NULL COMMENT '文章图片',  `newsDescript` varchar(256) DEFAULT NULL COMMENT '文章描述',  `newsTags` varchar(128) DEFAULT NULL COMMENT '文章标签',  `newsContent` text COMMENT '文章内容',  `newsAuthor` varchar(36) DEFAULT NULL COMMENT '文章作者',  `newsSeeTimes` int(11) DEFAULT '0' COMMENT '文章查看次数',  `newsSetTimes` int(11) DEFAULT '0' COMMENT '文章修改次数',  `adminNewsSeeTimes` int(11) DEFAULT '0' COMMENT '管理员文章查看次数',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统-页面-文章'",
            "sys_page_notice" => "CREATE TABLE `sys_page_notice` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `recodeName` varchar(36) DEFAULT NULL COMMENT '记录名称',  `recodeGroup` varchar(36) DEFAULT NULL COMMENT '记录组',  `noticeImage` varchar(128) DEFAULT NULL COMMENT '公告图片',  `noticeTitle` varchar(64) DEFAULT NULL COMMENT '公告标题',  `noticeDescript` text COMMENT '公告描述',  `noticeBody` text COMMENT '公告内容',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统-页面-公告'",
            //系统 -- 线上店铺
            "sys_shopol_goods" => "CREATE TABLE `sys_shopol_goods` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `shopId` bigint(20) DEFAULT NULL COMMENT '店铺ID',  `goodsName` varchar(36) DEFAULT NULL COMMENT '商品名称',  `goodsLogo` varchar(128) DEFAULT NULL COMMENT '商品LOGO',  `goodsImgOne` varchar(128) DEFAULT NULL COMMENT '商品图片一',  `goodsImgTwo` varchar(128) DEFAULT NULL COMMENT '商品图片二',  `goodsImgThree` varchar(128) DEFAULT NULL COMMENT '商品图片三',  `goodsImgFour` varchar(128) DEFAULT NULL COMMENT '商品图片四',  `goodsPrice` decimal(10,2) DEFAULT NULL COMMENT '商品价格',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统-线上店铺-商品'",
            "sys_shopol_shop" => "CREATE TABLE `sys_shopol_shop` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `userId` bigint(20) DEFAULT NULL COMMENT '用户ID',  `shopName` varchar(36) DEFAULT NULL COMMENT '店铺名称',  `shopLogo` varchar(128) DEFAULT NULL COMMENT '店铺LOGO',  `shopBrand` varchar(36) DEFAULT NULL COMMENT '店铺品牌',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统-线上店铺-店铺'",
            "sys_shopol_stock" => "CREATE TABLE `sys_shopol_stock` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `shopId` bigint(20) DEFAULT NULL COMMENT '店铺ID',  `goodsId` bigint(20) DEFAULT NULL COMMENT '商品ID',  `userId` bigint(20) DEFAULT NULL COMMENT '用户ID',  `operationType` varchar(36) DEFAULT NULL COMMENT '操作类型',  `number` int(11) DEFAULT NULL COMMENT '数量',  `stockDescript` varchar(36) DEFAULT NULL COMMENT '库存操作描述',  `stockSum` int(11) DEFAULT NULL COMMENT '库存总量',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统-线上店铺-商品库存'",
        ];
        

        $vCreateTimes = 0;  //成功创建次数
        $vFailTimes = 0;    //失败创建次数，已存在的数据表
        //遍历数据表
        foreach ($vTableArray as $key => $value) {
            $table = $key;
            if(!DBMySQLJudge::JudgeTable($table)){
                $sql = $value;
                DBHelper::DataSubmit($sql, null);
                $vCreateTimes += 1;
            }else{
                $vFailTimes += 1;
            }
        }
        
        return JsonInforTrue("检测完成，成功创建 {$vCreateTimes} 个，已存在 {$vFailTimes} 个", "系统数据表检测");
    }
    
    
    /**
     * 检测系统创建者
     * 2020-03-08 14:23:59
     * */
    function CheckCreater(){
        
        //检测数据表连接
        $dbLink = $this -> CheckDataBaseLink();
        if(JudgeJsonFalse($dbLink)){ return $dbLink; }
        
        //检测管理员数据表是否存在
        $table = "fly_user_admin";
        if(!DBMySQLJudge::JudgeTable($table)){
            return JsonInforFalse("管理员数据表不存在", "fly_user_admin");
        }
        
        //获取创建者信息
        //获取:fly_user_admin:管理员手机号
        $vSql = "SELECT phoneNumber FROM fly_user_admin WHERE isRoleCreater=? LIMIT 0,1;";
        $vPhoneNumber = DBHelper::DataString($vSql, ["true"]);
        if(IsNull($vPhoneNumber)){ 
            return JsonInforFalse("不存在程序创建者", "fly_user_admin"); 
        }
        return JsonInforTrue($vPhoneNumber, "程序创建者已创建");
        
    }
    
    
}
    

   
    
    
 

