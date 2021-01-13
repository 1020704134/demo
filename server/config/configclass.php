<?php

    //============================== 业务类 ==============================

    /** 表对象： (2021-01-13 12:07:55) */
    function ObjTestUser(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/service/FlyClassTestUser.php";
        return new FlyClassTestUser();
    }

    /** 表对象： (2021-01-12 18:52:54) */
    function ObjTestProduct(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/service/FlyClassTestProduct.php";
        return new FlyClassTestProduct();
    }
    
    /** 表对象：订单 (2021-01-12 18:53:51) */
    function ObjTestOrder(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/service/FlyClassTestOrder.php";
        return new FlyClassTestOrder();
    }

    //============================== 业务类 ==============================

    /** 表对象：用户推荐奖励 (2020-07-21 18:40:49) */
    function ObjFlyUserRefereeMany(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/user/FlyClassUserRefereeMany.php";
        return new FlyClassUserRefereeMany();
    }
    
    /** 表对象：用户推荐积分奖励 (2020-07-22 11:37:12) */
    function ObjFlyUserRefereeIntegral(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/user/FlyClassUserRefereeIntegral.php";
        return new FlyClassUserRefereeIntegral();
    }
    
    

	//============================== 第三方类 ==============================
	
    /** 表对象：短信验证码 (2020-11-04 21:08:41) */
    function ObjThirdPhoneCode(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/phone/FlyClassPhoneCodeSend.php";
        return new FlyClassPhoneCodeSend();
    }
    
    /** 表对象：百度 (2020-05-11 14:01:13) */
    function ObjThirdBaidu(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/third/baidu/baidu.php";
        return new ThirdClassBaidu();
    }

    //============================== 系统类 ==============================
    
    
    //--- 系统类:角色类---
    
    /** 角色：管理员 */
    function ObjRoleObjectAdmin(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/role/admin/RoleAdmin.php";
        return new RoleAdmin();
    }
    
    /** 角色：用户 */
    function ObjRoleObjectUser(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/role/user/RoleUser.php";
        return new RoleUser();
    }
    
    //--- 系统类:角色辅助类---
    
    /** 表对象：用户推荐 (2019-10-30 15:32:02) */
    function ObjFlyUserReferee(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/user/FlyClassUserReferee.php";
        return new FlyClassUserReferee();
    }
    
    /** 表对象：用户积分 (2020-05-14 20:53:08) */
    function ObjFlyUserIntegral(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/user/FlyClassUserIntegral.php";
        return new FlyClassUserIntegral();
    }
    
    
    //--- 系统类:日志类 ---
    
    /** 登陆日志类 */
    function ObjFlyRoleLoginLog(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/log/FlyClassRoleLoginLog.php";
        return new FlyClassRoleLoginLog();
    }   
	
    /** 手机短信验证码日志类 */
    function ObjFlyPhoneCode(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/phone/FlyClassPhoneCode.php";
        return new FlyClassPhoneCode();
    }    

	/** Token日志表对象： (2020-02-14 11:49:13) */
	function ObjFlyTokenLog(){
		include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/token/FlyClassLogToken.php";
		return new FlyClassTokenLog();
	}
    
	/** 表对象：管理员操作日志 (2020-09-29 12:57:29) */
	function ObjFlyUserAdminOperationLog(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/log/FlyClassUserAdminOperationLog.php";
	    return new FlyClassUserAdminOperationLog();
	}
	
	/** 表对象：OnlyKey记录 (2020-10-20 11:28:46) */
	function ObjFlyLogOnlykey(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/fly/log/LogOnlykey.php";
	    return new FlyClassLogOnlykey();
	}
	
    
    //--- 系统类:接口文档 ---
    
    
    /** 接口:接口信息 */
    function ObjFlyInterface(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/interface/FlyClassInterface.php";
        return new FlyClassInterface();
    }
    
    /** 接口:接口测试用例 */
    function ObjFlyInterfaceDemo(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/interface/FlyClassInterfaceDemo.php";
        return new FlyClassInterfaceDemo();
    }
    
    /** 接口:业务线图 */
    function ObjFlyInterfaceLine(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/interface/FlyClassInterfaceLine.php";
        return new FlyClassInterfaceLine();
    }
    
    /** 接口:接口参数文档 */
    function ObjFlyInterfaceParam(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/interface/FlyClassInterfaceParam.php";
        return new FlyClassInterfaceParam();
    }
    
    /** 表对象：接口请求路径 (2019-12-23 15:16:14) */
    function ObjFlyInterfacePath(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/interface/FlyClassInterfacePath.php";
        return new FlyClassInterfacePath();
    }
    
    /** 接口:业务线图区域函数绑定 */
    function ObjFlyInterfaceService(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/interface/FlyClassInterfaceService.php";
        return new FlyClassInterfaceService();
    }
    
    //--- 系统类:RBAC ---
	
	/** RBAC:导航 */
	function ObjFlyRbacNav(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/rbac/FlyClassRbacNav.php";
	    return new FlyClassRbacNav();
	}
	
	/** RBAC:角色 */
	function ObjFlyRbacRole(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/rbac/FlyClassRbacRole.php";
	    return new FlyClassRbacRole();
	}

	/** RBAC:角色接口 */
	function ObjFlyRbacRoleInterface(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/rbac/FlyClassRbacRoleInterface.php";
	    return new FlyClassRbacRoleInterface();
	}
	
	/** RBAC:角色用户 */
	function ObjFlyRbacRoleUser(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/rbac/FlyClassRbacRoleUser.php";
	    return new FlyClassRbacRoleUser();
	}
	
	/** 表对象：RBAC角色界面 (2020-02-25 11:56:34) */
	function ObjFlyRbacRoleView(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/rbac/FlyClassRbacRoleView.php";
	    return new FlyClassRbacRoleView();
	}
	
	
	//--- 系统类:系统配置 ---
	
	/** 系统配置 */
	function ObjSystem(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/fly/class/ClassSystem.php";
	    return new ClassSystem();
	}

	//--- 系统类:插件 ---
	
	/** 系统插件 */
	function ObjFlyAppPlugin(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/flyapp/plugin/FlyClassAppPlugin.php";
	    return new FlyClassAppPlugin();
	}
	
	//--- 日志类:请求日志 ---
	
	/** 请求日志 */
	function ObjFlyRequestLog(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/request/FlyClassRequestLog.php";
	    return new FlyClassRequestLog();
	}
	
	/** 表对象：系统更新日志 (2020-03-27 23:48:47) */
	function ObjFlySystemUpdateLog(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/flyapp/core/FlyClassSystemUpdateLog.php";
	    return new FlyClassSystemUpdateLog();
	}
	
	/** 表对象：操作日志数据库SQLite (2020-04-07 17:56:35) */
	function ObjFlyDBSQLite(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/m/datamodel/service/sqlite/DBSQLite.php";
	    return new DBSQLite();
	}
	
	//--- API类:区域数据 ---
	
	/** 表对象：城市区域*/
	function ObjFlyBaseArea(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/area/FlyClassBaseArea.php";
	    return new FlyClassBaseArea();
	}
	
	
	
	//================================= 工单系统 =================================
	
	/** 表对象：工单列表 (2020-01-20 11:30:46) */
	function ObjWorkOrder(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/workorder/FlyClassWorkOrder.php";
	    return new FlyClassWorkOrder();
	}
	
	/** 表对象：工单回复记录 (2020-02-29 14:35:18) */
	function ObjFlyWorkOrderRecode(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/workorder/FlyClassWorkOrderRecode.php";
	    return new FlyClassWorkOrderRecode();
	}
	
	
	//================================= 工单目标系统 =================================
	
	/** 表对象：工作列表 (2020-03-01 19:48:32) */
	function ObjFlyWorkTask(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/worktask/FlyClassWorkTask.php";
	    return new FlyClassWorkTask();
	}
	
	/** 表对象：工作完成记录 (2020-03-02 17:20:16) */
	function ObjFlyWorkTaskRecode(){
	    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/worktask/FlyClassWorkTaskRecode.php";
	    return new FlyClassWorkTaskRecode();
	}  

    
    /** 工单沟通记录 (2020-03-14 22:49:41)  */
    function ObjFlyWorkOrder(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/workorder/FlyClassWorkOrder.php";
        return new FlyClassWorkOrder();
    }


    //================================= SQL系统 =================================
    
    /** SQL语句记录表对象：SQL (2020-04-04 21:55:51) */
    function ObjFlySql(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/sql/FlyClassSql.php";
        return new FlyClassSql();
    }
    
    
    
    //================================= 项目相关 =================================
    
    /** 表对象：项目维护 (2020-10-04 12:38:53) */
    function ObjFlyProjectUpdate(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/project/FlyClassProjectUpdate.php";
        return new FlyClassProjectUpdate();
    }
    
    /** 表对象：项目时间轴 (2020-10-05 09:28:51) */
    function ObjFlyProjectTimeaxis(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/project/timeaxis/FlyClassProjectTimeaxis.php";
        return new FlyClassProjectTimeaxis();
    }
    
    /** 表对象：项目时间轴标题 (2020-10-05 09:29:54) */
    function ObjFlyProjectTimeaxisTitle(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/project/timeaxis/FlyClassProjectTimeaxisTitle.php";
        return new FlyClassProjectTimeaxisTitle();
    }
    
    /** 表对象：项目时间轴标题内容 (2020-10-05 09:30:36) */
    function ObjFlyProjectTimeaxisTitleBody(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/project/timeaxis/FlyClassProjectTimeaxisTitleBody.php";
        return new FlyClassProjectTimeaxisTitleBody();
    }
    
    
    //================================= Fly =================================
    
    /** 表对象：Fly-操作手册-菜单 (2020-10-15 20:38:35) */
    function ObjFlyOptionMenu(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/manual/FlyClassOptionMenu.php";
        return new FlyClassOptionMenu();
    }
    
    /** 表对象：Fly-操作手册-菜单子项 (2020-10-15 20:39:42) */
    function ObjFlyOptionMenuSon(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/manual/FlyClassOptionMenuSon.php";
        return new FlyClassOptionMenuSon();
    }
    
    /** 表对象：Fly-操作手册-操作步骤 (2020-10-15 20:41:07) */
    function ObjFlyOptionMenuSonStep(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/manual/FlyClassOptionMenuSonStep.php";
        return new FlyClassOptionMenuSonStep();
    }
    
    
    //================================= 小生态系统 =================================
    
    
    //--- 线上店铺 ---
    
    /** 表对象：系统-线上店铺-店铺 (2020-09-29 10:28:08) */
    function ObjSysShopolShop(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/shopol/FlyClassSysShopolShop.php";
        return new FlyClassSysShopolShop();
    }
    
    /** 表对象：系统-线上店铺-商品 (2020-09-30 19:46:02) */
    function ObjSysShopolGoods(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/shopol/FlyClassSysShopolGoods.php";
        return new FlyClassSysShopolGoods();
    }
    
    /** 表对象：系统-线上店铺-商品库存 (2020-10-01 16:54:12) */
    function ObjSysShopolStock(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/shopol/FlyClassSysShopolStock.php";
        return new FlyClassSysShopolStock();
    }
    
    /** 表对象：系统-页面-LOGO (2020-10-13 08:46:50) */
    function ObjSysPageLogo(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/page/FlyClassSysPageLogo.php";
        return new FlyClassSysPageLogo();
    }
    
    /** 表对象：系统-页面-Banner (2020-10-13 15:55:43) */
    function ObjSysPageBanner(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/page/FlyClassSysPageBanner.php";
        return new FlyClassSysPageBanner();
    }
    
    /** 表对象：系统-页面-公告 (2020-10-13 18:53:37) */
    function ObjSysPageNotice(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/page/FlyClassSysPageNotice.php";
        return new FlyClassSysPageNotice();
    }
    
    /** 表对象：系统-页面-文章 (2020-10-13 20:43:36) */
    function ObjSysPageNews(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/page/FlyClassSysPageNews.php";
        return new FlyClassSysPageNews();
    }
    
    /** 表对象：系统-页面-帮助文档 (2020-10-15 16:05:35) */
    function ObjSysPageHelp(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/page/FlyClassSysPageHelp.php";
        return new FlyClassSysPageHelp();
    }
    
    /** 表对象：系统-页面-导航 (2020-10-15 16:54:58) */
    function ObjSysPageNav(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/page/FlyClassSysPageNav.php";
        return new FlyClassSysPageNav();
    }
    
    /** 表对象：系统-页面-图片 (2020-10-17 12:16:04) */
    function ObjSysPageImage(){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/page/FlyClassSysPageImage.php";
        return new FlyClassSysPageImage();
    }
    
    

    
    