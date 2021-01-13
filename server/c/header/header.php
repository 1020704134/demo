<?php
    
	/** ajax.php:用于请求数据的接口索引 */
	

    //---------- 错误输出 ----------
    //错误输出
    //ini_set('display_errors',1);            //错误信息
    //ini_set('display_startup_errors',1);    //php启动错误信息
    //error_reporting(-1);                    //打印出所有的 错误信息  
    
    
    //---------- 引用区 ----------
    //引入项目配置文件 [固定配置]
    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/config/config.php";
    //引入项目类对象索引文件 [固定配置]
    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/config/configclass.php";
    //引入项目配置文件 [固定配置]
    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/config/configdefine.php";
    
	
    //---------- 请求头：设置跨域请求 ----------
    //Header头设定:输出编码设定
    header('Content-Type:text/html;charset=utf-8');
    //Header头设定:请求方式
    header("Access-Control-Allow-Origin:*");	//运行所有域名请求
	header('Access-Control-Allow-Methods:PUT,POST,GET,DELETE,OPTIONS');	//跨域请求方式
    header('Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept, Authorization');	//支持的请求首部名称
    header('Access-Control-Allow-Credentials:true');	//是否运行发送cookie
    
    //---------- 传入参数说明 ----------
    //line                              业务线
    //method                            业务线方法
    //version                           接口版本
    //fly_debug_type                    接口调试类型
    //fly_debug_parameter               接口调试参数名
    
    //---------- 值获取 ----------
    //请求头信息获取
    $requestUrl = GetRequestUrl();                      //请求头:获取来源页
    //参数获取
    $line = GetParameterRequest("line");                //参数:业务线[固定]
    $lineDescript = GetParameter("line_descript");        //参数:业务线[固定]
    $method = GetParameterRequest("method");            //参数:方法[固定]
    $methodDescript = GetParameter("method_descript");    //参数:方法[固定]
    $version = GetParameterRequest("version");          //参数:版本号,eg：V1.1(大版本.小版本)，版本号用于方法的升级，对原有程序进行兼容
    
    //参数值到小写
    if(IsNull($line)){$line = HandleStringToLowerCase($line);}
    if(IsNull($method)){$method = HandleStringToLowerCase($method);}
    if(IsNull($version)){$version = HandleStringToLowerCase($version);}
    
    //---------- 常量声明（用于程序执行过程全局变量存储） ----------
    define("INTERFACE_DEBUG_TYPE",GetParameter("fly_debug_type"));              //接口：调试输出类型：INTERFACE、PARAMETER、SQL
    define("INTERFACE_DEBUG_PARAMETER",GetParameter("fly_debug_parameter"));    //接口：调试输出参数名
    
    //---------- 变量声明 ----------
    //--- RBAC索引描述 ---
    $rbacTypeDescript = "";               //RBAC索引:类型索引描述
    $rbacLineDescript = "";               //RBAC索引:业务索引描述
    $rbacMethodDescript = "";             //RBAC索引:方法索引描述
    //--- RBAC索引数组 ---
    $rbacTypeArray = array();           //RBAC数组:Type数组
    $rbacLineArray = array();           //RBAC数组:Line数组
    $rbacMethodArray = array();         //RBAC数组:Method数组
    //--- RBAC索引消息 ---
    $inforLine = "line";                //消息类型:line
    $inforMethod = "method";            //消息类型:method
    //--- 用户信息 ---
    $vDefineUserId = "";                //用户ID
    //--- 输出信息 ---
    $echoInfor = "";                    //输出信息，当输出文本不为空时输出该文本
    
    
    //---------- 函数 ----------
    
    /**功能一：添加类型索引到数组中*/
    function IndexType($value,$indexValue,$descript){
        global $rbacTypeArray;          //索引数组
        global $rbacTypeDescript;       //索引描述
        $rbacTypeArray[$indexValue] = $descript;
        $rbacTypeDescript = $descript;
        return $value==$indexValue;
    }
    
    /**功能一：添加业务线索引到数组中*/
    function IndexLine($value,$indexValue,$descript){
        global $rbacLineArray;          //索引数组
        global $rbacLineDescript;       //索引描述
        $rbacLineArray[$indexValue] = $descript;
        $rbacLineDescript = $descript;
        //$_POST["fly_system_line_descript"] = $descript;   //设置业务线描述
        return $value==$indexValue;
    }    
    
    /**校验限制传入参数*/
    function IndexCheckParameterLimit(){
        $vCheckResult = JudgeParameterLimit(GetArray(PROJECT_CONFIG_FLY_PARAMETER_LIMIT, ","));
        if(JudgeJsonTrue($vCheckResult)){
            return "";
        }
        return $vCheckResult;
    }

    /**校验RBAC接口使用权限*/
    function IndexCheckRBAC(){
        //--- 未开启RBAC权限校验 ---
        if(!PROJECT_CONFIG_RBAC_SWITCH){ return true; }
        //--- 为程序创建者 ---
        $vAdminRoleCreater = ObjRoleObjectAdmin()->SessionGetCreater(); 
        if($vAdminRoleCreater=="true"){return true;}
        //--- RBAC权限校验 ---
        //获取请求路径
        $vAccessPath = $_SERVER['PHP_SELF'];
        //接口业务线
        global $line;
        //接口业务线方法
        global $method;
        //用户ID
        global $vDefineUserId;
        if(IsNull($vDefineUserId)){
            return JsonInforFalse("RBAC用户ID未找到", "RBAC权限校验");
        }
        
        //--- 接口ID ---
        //获取:fly_interface:表ID
        //$vSql = "SELECT id FROM fly_interface WHERE interfacePath=? AND interfaceIndexLine=? AND interfaceIndexMethod=? LIMIT 0,1;";
        //$vInterfaceId = DBHelper::DataString($vSql, [$vAccessPath,$line,$method]);
        //if(IsNull($vInterfaceId)){ 
        //    return JsonInforFalse("RBAC接口未记录", "RBAC权限校验");
        //}
        //可通过替换$vInterfaceId为该SQL语句使RBAC的判断语句成为一句完成的SQL，而非需要两句SQL执行完成
        $vSqlInterfaceId = "SELECT id FROM fly_interface WHERE interfacePath='{$vAccessPath}' AND interfaceIndexLine='{$line}' AND interfaceIndexMethod='{$method}'";
        
        //--- RBAC测试日志 ---
        //0.开启RBAC权限校验 -- OK
        //0.是程序创建者 -- OK
        //1.接口文档的自动更新 -- OK
        //2.RBAC权限分配 -- OK
        //3.单独测试，RBAC默认角色 - 接口分配 - RBAC校验测试 	-- OK
        //4.单独测试，RBAC定义角色 - 接口分配 - 用户分配 - RBAC校验测试 -- OK
        //5.4角色下架测试 -- OK
        //6.3/4联合测试，4下架 -- OK
        //7.3/4联合测试，4上架 -- OK
        //8.3/4联合测试，4有接口3没有，4下架 -- OK
        //9.3/4联合测试，4有接口3没有，4上架 -- OK
        //10.4角色超时测试 -- OK
        //--- RBAC测试日志 ---
        
        //--- RBAC ---
        $vSql = "";
        //默认角色：请求路径 & 默认权限 & ROLE_DEFAULT
        $vDefaultSql = "SELECT id FROM fly_rbac_role WHERE roleAccessPath='{$vAccessPath}' AND roleName='默认权限' AND roleDescript='ROLE_DEFAULT'";
        //用户角色：请求路径 & 用户ID拥有 & 未过期的角色
        $vUserSql = "SELECT a.roleId FROM fly_rbac_role_user a LEFT JOIN fly_rbac_role b ON a.roleId = b.id WHERE a.userAccessPath='{$vAccessPath}' AND a.userId = '{$vDefineUserId}' AND (a.overTime IS NULL OR a.overTime>NOW()) AND b.shelfState='true'";
        //用户角色：指定接口ID & 用户拥有的角色 & 上架的角色接口 & 关联的角色接口
        //$vUserSql = "SELECT roleId,interfaceId,shelfState,isRelation FROM fly_rbac_role_interface WHERE interfaceId='{$vInterfaceId}' AND (roleId IN({$vUserSql}) OR roleId = ({$vDefaultSql})) AND isRelation='true'";
        $vUserSql = "SELECT roleId,interfaceId,shelfState,isRelation FROM fly_rbac_role_interface WHERE interfaceId=({$vSqlInterfaceId}) AND (roleId IN({$vUserSql}) OR roleId = ({$vDefaultSql})) AND isRelation='true'";
        //RBAC查询SQL
        $vSql = $vSql . $vUserSql;
        $vFlyRbacRoleInterfaceList = DBHelper::DataList($vSql, null, ["roleType","roleId","interfaceId","shelfState","isRelation"]);
        //未查询到任何接口记录
        if(IsNull($vFlyRbacRoleInterfaceList)){
            return JsonInforFalse("RBAC接口权限不足", "RBAC权限校验");
        }
        //将Json字符串转为对象
        $vFlyRbacRoleInterfaceListObj = GetJsonObject($vFlyRbacRoleInterfaceList);
        $vListSize = sizeof($vFlyRbacRoleInterfaceListObj);
        //--- 判断区 ---
        //当只有一个记录时
        if($vListSize>0){
            return true;
        }
        
        return JsonInforFalse("RBAC接口权限校验异常", "RBAC权限校验");

    }
    
    