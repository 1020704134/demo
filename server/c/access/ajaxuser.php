<?php

    //请求头
    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/c/header/header.php";
    
    //变量引用:全局变量引用
    global $line;                   //参数：业务线
    global $lineDescript;           //参数：业务线描述
    global $method;                 //参数：方法
    global $methodDescript;         //参数：方法描述
    global $version;                //参数：版本
    
    //---------- 变量声明 ----------
    //--- RBAC索引描述 ---
    global $rbacTypeDescript;               //RBAC索引:类型索引描述
    global $rbacLineDescript;               //RBAC索引:业务索引描述
    global $rbacMethodDescript;             //RBAC索引:方法索引描述
    //--- RBAC索引数组 ---
    global $rbacTypeArray;                  //RBAC数组:Type数组
    global $rbacLineArray;                  //RBAC数组:Line数组
    global $rbacMethodArray;                //RBAC数组:Method数组
    //--- RBAC索引消息 ---
    global $inforLine;                      //消息类型:line
    global $inforMethod;                    //消息类型:line
    //--- 用户信息 ---
    global $vDefineUserId;                  //用户ID:line
    //--- 输出信息 ---
    global $echoInfor;                      //输出信息，当输出文本不为空时输出该文本
    //--- 全局变量引用 ---
    global $rbacInterfaceSwitch;            //变量:接口文档权限校验开关，该变量在config.php中
    

    
    //---------- 自定义实现 ----------
    
    /**
     * 功能一：添加方法索引到数组中
     * 功能二：对需要进行权限判断的角色及接口进行权限判断
     * 功能三：对不需要进行权限判断的接口跳过判断
     * */
    function IndexMethod($value,$indexValue,$descript){
        
        global $rbacMethodArray;        //索引数组
        
        global $rbacTypeDescript;       //索引描述
        global $rbacLineDescript;       //索引描述
        global $rbacMethodDescript;     //索引描述
        
        global $line;                   //参数:索引:业务线
        global $method;                 //参数:索引:方法
        
        global $objLoginUser;           //变量:登录对象
        global $echoInfor;              //变量:输出信息
        global $rbacIndexSwitch;        //变量:接口权限校验开关，该变量在config.php中
        
        //添加注释到RBAC索引数组中
        $rbacMethodArray[$indexValue] = $descript;
        $rbacMethodDescript = $descript;
        
        //对参数进行到小写处理
        $value = HandleStringToLowerCase($value);
        $indexValue = HandleStringToLowerCase($indexValue);
        
        //对参数进行比较
        $judgeBo = $value==$indexValue;
        
        //---请求日志---
        
        return $judgeBo;
    }
    
    //========== 参数校验区 ==========
    IndexCheckParameterLimit(); //校验限制传入参数
    if(!IsNull($echoInfor)){return WriteEcho($echoInfor);}  //输入校验内容


	//---------------------- ↓↓↓ 以下是　用户　相关接口 ↓↓↓ ----------------------
	
    
	//---------------------- ↓↓↓ 游客业务线 ↓↓↓ ----------------------
    

    //---------------------- ↓↓↓ 登录业务线 ↓↓↓ ----------------------
	
    //用户 - 登陆业务线
    if(IndexLine($line, "visitor", "游客业务线")){
        
        if(IndexMethod($method, "username", "用户姓名")){return WriteEcho(ObjFlyUserReferee() -> VisitorNickName());}
        
        $vAdminId = "1";
        //-test_user:用户:2021-01-13 12:07:55-
        if(IndexMethod($method, "testuserpaging", "TestUser查询")){return WriteEcho(ObjTestUser() -> AdminTestUserPaging($vAdminId));}
        if(IndexMethod($method, "testuseradd", "TestUser添加")){return WriteEcho(ObjTestUser() -> AdminTestUserAdd($vAdminId));}
        if(IndexMethod($method, "testuserset", "TestUser修改")){return WriteEcho(ObjTestUser() -> AdminTestUserSet($vAdminId));}
        if(IndexMethod($method, "testusersetshelfstate", "TestUser上下架")){return WriteEcho(ObjTestUser() -> AdminTestUserShelfState($vAdminId));}
        //if(IndexMethod($method, "testusersetstate", "TestUser状态修改")){return WriteEcho(ObjTestUser() -> AdminTestUserSetState($vAdminId));}
        if(IndexMethod($method, "testuserdelete", "TestUser删除")){return WriteEcho(ObjTestUser() -> AdminTestUserDelete($vAdminId));}
        if(IndexMethod($method, "testusergettablefield", "TestUser表字段")){return WriteEcho(ObjTestUser() -> GetTableField());}
        
        //-test_order:订单:2021-01-13 12:09:11-
        if(IndexMethod($method, "testorderpaging", "订单查询")){return WriteEcho(ObjTestOrder() -> AdminTestOrderPaging($vAdminId));}
        if(IndexMethod($method, "testorderadd", "订单添加")){return WriteEcho(ObjTestOrder() -> AdminTestOrderAdd($vAdminId));}
        if(IndexMethod($method, "testorderset", "订单修改")){return WriteEcho(ObjTestOrder() -> AdminTestOrderSet($vAdminId));}
        if(IndexMethod($method, "testordersetshelfstate", "订单上下架")){return WriteEcho(ObjTestOrder() -> AdminTestOrderShelfState($vAdminId));}
        //if(IndexMethod($method, "testordersetstate", "订单状态修改")){return WriteEcho(ObjTestOrder() -> AdminTestOrderSetState($vAdminId));}
        if(IndexMethod($method, "testorderdelete", "订单删除")){return WriteEcho(ObjTestOrder() -> AdminTestOrderDelete($vAdminId));}
        if(IndexMethod($method, "testordergettablefield", "订单表字段")){return WriteEcho(ObjTestOrder() -> GetTableField());}
        
        //-test_product:产品:2021-01-13 12:09:45-
        if(IndexMethod($method, "testproductpaging", "TestProduct查询")){return WriteEcho(ObjTestProduct() -> AdminTestProductPaging($vAdminId));}
        if(IndexMethod($method, "testproductadd", "TestProduct添加")){return WriteEcho(ObjTestProduct() -> AdminTestProductAdd($vAdminId));}
        if(IndexMethod($method, "testproductset", "TestProduct修改")){return WriteEcho(ObjTestProduct() -> AdminTestProductSet($vAdminId));}
        if(IndexMethod($method, "testproductsetshelfstate", "TestProduct上下架")){return WriteEcho(ObjTestProduct() -> AdminTestProductShelfState($vAdminId));}
        //if(IndexMethod($method, "testproductsetstate", "TestProduct状态修改")){return WriteEcho(ObjTestProduct() -> AdminTestProductSetState($vAdminId));}
        if(IndexMethod($method, "testproductdelete", "TestProduct删除")){return WriteEcho(ObjTestProduct() -> AdminTestProductDelete($vAdminId));}
        if(IndexMethod($method, "testproductgettablefield", "TestProduct表字段")){return WriteEcho(ObjTestProduct() -> GetTableField());}
        
        //--- RBAC ---
        if($method=="rbac"){return WriteEcho(JsonFlyLineArray($rbacMethodArray,$line,$rbacLineDescript,$method,$rbacMethodDescript));}    //rbac数组
        if(!IsNull($echoInfor)){return WriteEcho($echoInfor);}  //输入内容不为空时,输出输入信息
        return WriteEchoNotFint($line,$method,$inforMethod); //索引未找到（方法错误）
    } 


    //--- RBAC ---
    if($line=="rbac"){
        if($method=="infor"){return WriteEcho(JsonModelDataArray($rbacLineArray));}    //RBAC业务线 -- 信息
        return WriteEchoNotFint($line,$method,$inforMethod); //索引未找到（方法错误）
    }
    
    //没有找到任何索引
    return WriteEchoNotFint($line,$method,$inforLine);    //索引未找到（业务线错误）
	
    