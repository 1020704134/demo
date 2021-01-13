<?php

/**------------------------------------*
  * 作者：shark
  * 创建时间：2019-12-13 14:47:54
  * Fly编码：1576219674194FLY258795
  * 类对象名：ObjFlyInterfaceLine()
  * ------------------------------------ */

//引入区

class FlyClassInterfaceLine{


    //---------- 类成员（member） ----------

    //类描述
    public static $classDescript = "业务线";
    //类数据表名
    public static $tableName = "fly_interface_line";
    //类数据表字段
    public static $tableField = "id,onlyKey,indexNumber,updateTime,addTime,state,serviceLine";


    //---------- 私有方法（private） ----------

    //---------- 游客方法（visitor） ----------

    //---------- 系统方法（system） ----------

    /**
     * 业务线
     * 记录添加
     * 创建时间：2019-12-13 14:47:53
     * */
    public function SystemFlyInterfaceLineAdd($fpServiceLine){

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "serviceLine",
            "serviceline" => $fpServiceLine,
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    //---------- 用户方法（user） ----------

    //---------- 管理员方法（admin） ----------

    /**
     * 函数名称：业务线:管理员:记录添加
     * 函数调用：ObjFlyInterfaceLine() -> AdminFlyInterfaceLineAdd($fpAdminId)
     * 创建时间：2020-03-06 16:49:54
     * */
    public function AdminFlyInterfaceLineAdd($fpAdminId){


        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 变量预定义 ---
        $json="";

        //--- 参数获取区 ---
        //参数:表ID:id
        $pId = GetParameter("id",$json);

	    //参数:业务名称:serviceLine
	    $pServiceLine = GetParameterNoCode("serviceline",$json);
	    if(!JudgeRegularTitle($pServiceLine)){return JsonModelParameterException("serviceline", $pServiceLine, 64, "内容格式错误", __LINE__);}

	    //参数:业务类型:serviceType
	    $pServiceType = GetParameterNoCode("servicetype",$json);
	    if(!JudgeRegularState($pServiceType)){return JsonModelParameterException("servicetype", $pServiceType, 36, "内容格式错误", __LINE__);}

	    //参数:业务图片:serviceImage
	    $pServiceImage = GetParameterNoCode("serviceimage",$json);
	    if(!JudgeRegularUrl($pServiceImage)){return JsonModelParameterException("serviceimage", $pServiceImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:业务线流程图路径:serviceLineImage
	    $pServiceLineImage = GetParameterNoCode("servicelineimage",$json);
	    if(!JudgeRegularUrl($pServiceLineImage)){return JsonModelParameterException("servicelineimage", $pServiceLineImage, 256, "URL地址格式错误", __LINE__);}

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "serviceLine,serviceType,serviceImage,serviceLineImage",
            "serviceline" => $pServiceLine,
            "servicetype" => $pServiceType,
            "serviceimage" => $pServiceImage,
            "servicelineimage" => $pServiceLineImage,
            "key_field" => "serviceLine,serviceType",
            "update_field" => "serviceLine,serviceType,serviceImage,serviceLineImage",
            "update_value" => "{$pServiceLine},{$pServiceType},{$pServiceImage},{$pServiceLineImage}",
            "where_field" => "id",
            "where_value" => "{$pId}",
            "execution_step" => "update,insert",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    

    /**
     * 业务线
     * 记录查询
     * 创建时间：2019-12-13 14:47:54
     * */
    public function AdminFlyInterfaceLinePaging($fpAdminId){

        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 数据预定义 ---
        $json = "";

        //--- 参数获取区 ---
        //$pPage = GetParameter("page","");     //参数:页码:page
        //$pLimit = GetParameter("limit","");   //参数:条数:limit
        $pPage = 1;
        $pLimit = 1000;

        //参数：id
        $pId = GetParameter("id","");
        if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        if(!IsNull($pId)){ $pPage = 1; $pLimit = 1; }
        
        //参数:业务类型:serviceType
        $pServiceType = GetParameterNoCode("servicetype",$json);
        if(IsNull($pId)){
            if(!JudgeRegularState($pServiceType)){return JsonModelParameterException("servicetype", $pServiceType, 36, "内容格式错误", __LINE__);}    
        }
        

        //参数判断:页码:page
        if(IsNull($pPage)||!JudgeRegularIntRight($pPage)){return JsonModelParameterException("page", $pPage, 9, "值必须是正整数", __LINE__);}
        //参数判断:条数:limit
        if(IsNull($pLimit)||!JudgeRegularIntRight($pLimit)){return JsonModelParameterException("limit", $pLimit, 9, "值必须是正整数", __LINE__);}

        //参数：记录状态
        $pWhereState = GetParameter("wherestate","");
        if(!IsNull($pWhereState)&&!($pWhereState=="STATE_NORMAL"||$pWhereState=="STATE_DELETE")){return JsonModelParameterException("wherestate", $pWhereState, 64, "值必须是STATE_NORMAL/STATE_DELETE", __LINE__);}

        //参数：上下架状态
        $pWhereShelfState = GetParameter("whereshelfstate","");
        if(!IsNull($pWhereShelfState)&&!($pWhereShelfState=="true"||$pWhereShelfState=="false")){return JsonModelParameterException("whereshelfstate", $pWhereShelfState, 36, "值必须是true/false", __LINE__);}

        //参数：like
        $pLike = GetParameter("like","");
        $pLiketype = GetParameter("liketype","");
        if(!IsNull($pLiketype)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLiketype)){ return JsonInforFalse("搜索字段不存在", $pLiketype); }
        if(!IsNull($pLike)){ $pLike = HandleStringAddslashes($pLike); }

        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,indexNumber,updateTime,addTime,state,serviceLine";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "",
        	"where_value" => "",
        	"page" => $pPage,
        	"limit" => $pLimit,
        	"like_field" => $pLiketype,
        	"like_key" => $pLike,
        );

        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "serviceType", $pServiceType);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));

    }


    /**
     * 业务线
     * 记录修改
     * 创建时间：2019-12-13 14:47:54
     * */
    public function AdminFlyInterfaceLineSet($fpAdminId){

        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 数据预定义 ---
        $json = "";

        //--- 参数获取区 ---
	    //参数:表ID:id
	    $pId = GetParameter("id",$json);
	    if(!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}

	    //参数:序号:indexNumber
	    $pIndexNumber = GetParameter("indexnumber",$json);
	    if(!IsNull($pIndexNumber)&&!JudgeRegularInt($pIndexNumber)){return JsonModelParameterException("indexnumber", $pIndexNumber, 11, "值必须是整数", __LINE__);}

	    //参数:记录状态:state
	    $pState = GetParameter("state",$json);
	    if(!IsNull($pState)&&!JudgeRegularFont($pState)){return JsonModelParameterException("state", $pState, 36, "内容格式错误", __LINE__);}

	    //参数:业务线名称:serviceLine
	    $pServiceLine = GetParameter("serviceline",$json);
	    if(!IsNull($pServiceLine)&&!JudgeRegularFont($pServiceLine)){return JsonModelParameterException("serviceline", $pServiceLine, 64, "内容格式错误", __LINE__);}
	    
	    //参数:业务线流程图路径:serviceLineImage
	    $pServiceLineImage = GetParameter("servicelineimage",$json);
	    if(!IsNull($pServiceLineImage)&&!JudgeRegularUrl($pServiceLineImage)){return JsonModelParameterException("servicelineimage", $pServiceLineImage, 256, "URL格式错误", __LINE__);}

	    //--- Json组合区 ---

	    //Json数组
	    $jsonKeyValueArray = array(
	        "table_name" => self::$tableName,
	        "update_field" => "",
	        "update_value" => "",
	        "where_field" => "id",
	        "where_value" => "{$pId}",
	    );

	    //修改字段判断组合
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "indexNumber", $pIndexNumber);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "state", $pState);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "serviceLine", $pServiceLine);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "serviceLineImage", $pServiceLineImage);

	    //判断字段值是否为空
	    $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"indexnumber,state,serviceline,servicelineimage");
	    if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }

	    //返回结果
	    return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));

    }


    /**
     * 业务线
     * 记录状态修改
     * 创建时间：2019-12-13 14:47:54
     * */
    public function AdminFlyInterfaceLineSetState($fpAdminId){

        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 变量预定义 ---
        $json="";		//Json参数

        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameter("id",$json); 
        if(!JudgeRegularFont($pId)){return JsonModelParameterInfor("id","参数值不符合规则",20);}

        //参数:记录状态:state
        $pState = GetParameter("state",$json);
        if(IsNull($pState)||GetStringLength($pState)>36||!($pState=="STATE_NORMAL"||$pState=="STATE_DELETE")){return JsonModelParameterInfor("state","值必须是STATE_NORMAL/STATE_DELETE",36);}

        //--- 数据提交区 ---
        return ServiceTableDataSystemSet(self::$tableName,"state","{$pState}","id","{$pId}");
    }

    /**
     * 业务线
     * 数据上下架状态修改
     * 创建时间：2019-12-13 14:47:54
     * */
    public function AdminFlyInterfaceLineShelfState($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameter("id","");
        if(IsNull($pId)){return JsonModelParameterNull("id");}
        //参数：id：正整数正则判断
        if(!JudgeRegularIntRight($pId)){return JsonInforFalse("id必须是正整数", "id");}

        //参数：shelfstate
        $pShelfState = GetParameter("shelfstate","");
        if(IsNull($pShelfState)){return JsonModelParameterNull("shelfstate");}
        //参数：shelfstate：正整数正则判断
        if(!($pShelfState=="true"||$pShelfState=="false")){ return JsonInforFalse("上架状态值必须是true或false", "shelfstate"); }

        //--- Json提交区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "update_field" => "shelfState",
            "update_value" => "{$pShelfState}",
            "where_field" => "id",
            "where_value" => "{$pId}",
        );
        //执行:修改
        $vJsonResult = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;

    }

    /**
     * 业务线
     * 记录永久删除
     * 创建时间：2019-12-13 14:47:54
     * */
    public function AdminFlyInterfaceLineDelete($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 变量预定义 ---
        $json="";		//Json参数

        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameter("id",$json);
        if(!JudgeRegularIntRight($pId)){return JsonModelParameterInfor("id","参数值不符合规则",20);}

        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "where_field" => "id",
            "where_value" => "{$pId}",
        );
        //执行:删除
        $vJsonResult = MIndexDataDelete(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    //---------- 测试方法（test） ----------

    //---------- 基础方法（base） ----------


    /**
     * 获取数据表名称
     * 创建时间：2019-12-13 14:47:53
     * */
    public function GetTableName(){
        return self::$tableName;
    }

    /**
     * 获取类描述
     * 创建时间：2019-12-13 14:47:53
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }

    /**
     * 获取记录
     * 参数说明：$json:JSON数据，$whereField:条件字段，$sub:记录下标
     * 创建时间：2019-12-13 14:47:53
     * */
    public function GetRecodeJson($json,$whereField){
        return ServiceTableDataRecodeJson(self::$tableName, $json, $whereField);
    }

    /**
     * 判断ID是否存在
     * 参数说明：记录ID
     * 创建时间：2019-12-13 14:47:53
     * */
    public function JudgeId($id){
        //记录ID判断
        if(!JudgeJsonTrue(ServiceTableDataRecode(self::$tableName,'{"id":"'.$id.'"}', "id", 0))){
            return JsonInforFalse("id不存在", self::$classDescript);
        }
        //记录上下架状态判断
        if(!JudgeJsonTrue(ServiceTableDataRecode(self::$tableName,'{"id":"'.$id.'","shelfState":"true"}', "id,shelfState", 0))){
            return JsonInforFalse("记录已下架", self::$classDescript);
        }
        return JsonInforTrue("记录正常", self::$classDescript);
    }

    /**
     * 操作：类数据表创建
     * 创建时间：2019-12-13 14:47:53
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `fly_interface_line` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `state` varchar(36) DEFAULT 'NORMAL' COMMENT '记录状态',  `serviceLine` varchar(64) DEFAULT NULL COMMENT '业务线名称',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"tableName":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }

    /**
     * 操作：类数据表基础字段检测
     * 创建时间：2019-12-13 14:47:53
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck('{"tablename":"'.self::$tableName.'"}');
    }

    /**
     * 操作：类数据表字段检测
     * 创建时间：2019-12-13 14:47:53
     * */
    public function OprationTableFieldCheck(){
        //表字段信息
        $vF1 = array("field"=>"id" , "type"=>"bigint(20)" , "default"=>"" , "null"=>"NOT NULL" , "comment"=>"表ID" , "key"=>"PRI" , "extra"=>"auto_increment");
        $vF2 = array("field"=>"onlyKey" , "type"=>"varchar(36)" , "default"=>"" , "null"=>"NULL" , "comment"=>"唯一Key" , "key"=>"" , "extra"=>"");
        $vF3 = array("field"=>"indexNumber" , "type"=>"int(11)" , "default"=>"-1" , "null"=>"NULL" , "comment"=>"序号" , "key"=>"" , "extra"=>"");
        $vF4 = array("field"=>"updateTime" , "type"=>"timestamp" , "default"=>"" , "null"=>"NULL" , "comment"=>"修改时间" , "key"=>"" , "extra"=>"");
        $vF5 = array("field"=>"addTime" , "type"=>"timestamp" , "default"=>"CURRENT_TIMESTAMP" , "null"=>"NOT NULL" , "comment"=>"添加时间" , "key"=>"" , "extra"=>"");
        $vF6 = array("field"=>"state" , "type"=>"varchar(36)" , "default"=>"NORMAL" , "null"=>"NULL" , "comment"=>"记录状态" , "key"=>"" , "extra"=>"");
        $vF7 = array("field"=>"serviceLine" , "type"=>"varchar(64)" , "default"=>"" , "null"=>"NULL" , "comment"=>"业务线名称" , "key"=>"" , "extra"=>"");
        //表字段数组
        $vFieldArray = array($vF1,$vF2,$vF3,$vF4,$vF5,$vF6,$vF7);
        //表字段判断
        return DBMySQLServiceJson::OprationFieldCheck(self::$tableName, $vFieldArray);
    }




}
