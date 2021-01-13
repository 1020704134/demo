<?php 
	
	/** 
	 * 该类用于对Function中的文件进行加载管理
	 * 修改时间：November 29,2018 15:39 
	 * */

    /**API*/
    
    /**Class：整体类*/
    require_once 'class/ClassFlyException.php';
    require_once 'class/ClassPlugIns.php';
    require_once 'class/ClassRBAC.php';
    require_once 'class/ClassService.php';
    require_once 'class/ClassSystem.php';
    
    /**Create：创建类*/
    require_once 'create/CreateFile.php';
    require_once 'create/CreateObject.php';
    
    /**Fly：核心类*/
    require_once 'fly/FlyCode.php';
    require_once 'fly/FlyJson.php';

    /**工具：获取类*/
    require_once 'get/GetArray.php';
    require_once 'get/GetFolder.php';
	require_once 'get/GetHttp.php';
	require_once 'get/GetId.php';
	require_once 'get/GetJson.php';
	require_once 'get/GetName.php';
	require_once 'get/GetParameter.php';
	require_once 'get/GetPath.php';
	require_once 'get/GetRequest.php';
	require_once 'get/GetString.php';
	require_once 'get/GetTime.php';
    
	/**工具：处理类*/
	require_once 'handle/HandleArray.php';
	require_once 'handle/HandleData.php';
	require_once 'handle/HandleDate.php';
	require_once 'handle/HandleField.php';
	require_once 'handle/HandleImage.php';
	require_once 'handle/HandleJson.php';
	require_once 'handle/HandleNull.php';
    require_once 'handle/HandleRequest.php';
    require_once 'handle/HandleString.php';
    
    /**工具：判断类*/
    require_once 'judge/JudgeArray.php';
    require_once 'judge/JudgeDate.php';
    require_once 'judge/JudgeNumber.php';
    require_once 'judge/JudgeJson.php';
    require_once 'judge/JudgeNull.php';
    require_once 'judge/JudgeParamter.php';
    require_once 'judge/JudgeRegular.php';
    require_once 'judge/JudgeString.php';

    /**工具：日志类*/
    require_once 'log/Log.php';
    
    /**工具：设置类*/
    require_once 'set/SetSession.php';
    
    /**工具：上传类*/
    require_once 'upload/Uploadimage.php';
    
    /**工具：写出类*/
    include_once 'write/Write.php';
    
    

