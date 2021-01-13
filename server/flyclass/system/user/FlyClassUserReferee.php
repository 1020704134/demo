<?php

/**------------------------------------*
 * 作者：shark
 * 创建时间：2020-07-21 20:49:14
 * Fly编码：1595335754190FLY895203
 * 类对象名：ObjFlyUserReferee()
 * ------------------------------------ */

//引入区

class FlyClassUserReferee{
    
    
    //---------- 类成员（member） ----------
    
    //类描述
    public static $classDescript = "用户推荐关系";
    //类数据表名
    public static $tableName = "fly_user_referee";
    
    
    //---------- 私有方法（private） ----------
    
    /**
     * 函数名称：用户推荐关系计算
     * 创建时间：2020-07-21 21:15:54
     * */
    private function UserReferee($fpUserId){
        if(IsNull($fpUserId)){return "none";}
        $vUserId = DBHelper::DataString("SELECT refereeId FROM fly_user WHERE id='{$fpUserId}';", null);
        if(IsNull($vUserId)){return "none";}
        return $vUserId;
    }
    
    /**
     * 函数名称：用户亲推数量
     * 创建时间：2020-07-22 11:28:38
     * */
    private function UserRefereeNumber($fpUserId,$fpNumber){
        $vRefereeNumber = DBHelper::DataString("SELECT COUNT(TRUE) COUNT FROM fly_user WHERE refereeId = '{$fpUserId}';", null);
        return $vRefereeNumber >= $fpNumber;
    }
    
    //---------- 游客方法（visitor） ----------
    
    /**
     * 函数名称：用户推荐关系:游客:记录查询
     * 函数调用：ObjFlyUserReferee() -> VisitorFlyUserRefereePaging()
     * 创建时间：2020-07-21 20:49:14
     * */
    public function VisitorFlyUserRefereePaging(){
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,userId,upOneUserId,upTwoUserId,upThreeUserId,upFourUserId,upFiveUserId,upSixUserId,upSevenUserId,upEightUserId,upNineUserId";
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "shelfState",
            "where_value" => "true",
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
    
    
    /**
     * 函数名称：用户名字
     * 函数调用：ObjFlyUserReferee() -> VisitorNickName()
     * 创建时间：2020-07-21 21:13:08
     * */
    public function VisitorNickName(){
        $vUserId = GetParameter('user_id', "");
        if(!JudgeRegularNumber($vUserId)){return JsonInforFalse("推荐人ID格式异常", "user_id");}
        
        $sql  = "SELECT userNick FROM fly_user WHERE id='{$vUserId}';";
        $vUserNick = DBHelper::DataString($sql, null);
        if(IsNull($vUserNick)){
            return JsonInforFalse("记录不存在", "用户姓名");
        }
        return JsonInforTrue($vUserNick, "用户姓名");
    }
    
    
    
    //---------- 系统方法（system） ----------
    
    /**
     * 函数名称：用户推荐关系:系统:记录添加
     * 函数调用：ObjFlyUserReferee() -> SystemFlyUserRefereeAdd
     * 创建时间：2020-07-21 20:49:13
     * */
    public function SystemFlyUserRefereeAdd($fpUserId,$fpUpOneUserId,$fpUpTwoUserId,$fpUpThreeUserId,$fpUpFourUserId,$fpUpFiveUserId,$fpUpSixUserId,$fpUpSevenUserId,$fpUpEightUserId,$fpUpNineUserId){
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,userId,upOneUserId,upTwoUserId,upThreeUserId,upFourUserId,upFiveUserId,upSixUserId,upSevenUserId,upEightUserId,upNineUserId",
            "descript" => self::$classDescript,
            "shelfstate" => "false",
            "userid" => $fpUserId,
            "uponeuserid" => $fpUpOneUserId,
            "uptwouserid" => $fpUpTwoUserId,
            "upthreeuserid" => $fpUpThreeUserId,
            "upfouruserid" => $fpUpFourUserId,
            "upfiveuserid" => $fpUpFiveUserId,
            "upsixuserid" => $fpUpSixUserId,
            "upsevenuserid" => $fpUpSevenUserId,
            "upeightuserid" => $fpUpEightUserId,
            "upnineuserid" => $fpUpNineUserId,
            "key_field" => "userId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    /**
     * 函数名称：用户推荐关系:计算推荐关系
     * 函数调用：ObjFlyUserReferee() -> SystemFlyUserRefereeCalc($fpUserId)
     * 创建时间：2020-07-21 21:13:08
     * */
    public function SystemFlyUserRefereeCalc($fpUserId){
        $vRefereeOne = self::UserReferee($fpUserId);
        $vRefereeTwo = self::UserReferee($vRefereeOne);
        $vRefereeThree = self::UserReferee($vRefereeTwo);
        $vRefereeFour = self::UserReferee($vRefereeThree);
        $vRefereeFive = self::UserReferee($vRefereeFour);
        $vRefereeSix = self::UserReferee($vRefereeFive);
        $vRefereeSeven = self::UserReferee($vRefereeSix);
        $vRefereeEight = self::UserReferee($vRefereeSeven);
        $vRefereeNine = self::UserReferee($vRefereeEight);
        return self::SystemFlyUserRefereeAdd($fpUserId, $vRefereeOne, $vRefereeTwo, $vRefereeThree, $vRefereeFour, $vRefereeFive, $vRefereeSix, $vRefereeSeven, $vRefereeEight, $vRefereeNine);
    }
    
    
    
    /**
     * 函数名称：用户推荐积分计算
     * 函数调用：ObjFlyUserReferee() -> SystemFlyUserIntegralCalc($fpUserId)
     * 创建时间：2020-07-21 21:13:08
     * */
    public function SystemFlyUserIntegralCalc($fpUserId){
        $sql = "SELECT upOneUserId,upTwoUserId,upThreeUserId,upFourUserId,upFiveUserId,upSixUserId,upSevenUserId,upEightUserId,upNineUserId FROM fly_user_referee WHERE userId='{$fpUserId}'";
        $obj = DBHelper::DataList($sql, null, ["upOneUserId","upTwoUserId","upThreeUserId","upFourUserId","upFiveUserId","upSixUserId","upSevenUserId","upEightUserId","upNineUserId"]);
        if(IsNull($obj)){return;}
        $obj = GetJsonObject($obj);
        $obj = $obj[0];
        $vUpOneUserId = $obj -> upOneUserId;
        $vUpTwoUserId = $obj -> upTwoUserId;
        $vUpThreeUserId = $obj -> upThreeUserId;
        $vUpFourUserId = $obj -> upFourUserId;
        $vUpFiveUserId = $obj -> upFiveUserId;
        $vUpSixUserId = $obj -> upSixUserId;
        $vUpSevenUserId = $obj -> upSevenUserId;
        $vUpEightUserId = $obj -> upEightUserId;
        $vUpNineUserId = $obj -> upNineUserId;
        
        $vIntegral = "16";
        
        //一代
        //if(self::UserRefereeNumber($vUpOneUserId,10)){
        //    ObjFlyUserRefereeIntegral() -> SystemFlyUserRefereeIntegralAdd($vUpOneUserId, $fpUserId, $vIntegral, "推荐用户直系一代积分奖励");
        //}
        
        //二代
        //if(self::UserRefereeNumber($vUpTwoUserId,10)){
        ObjFlyUserRefereeIntegral() -> SystemFlyUserRefereeIntegralAdd($vUpTwoUserId, $fpUserId, $vIntegral, "推荐用户直系二代积分奖励");
        
        //三代
        //if(self::UserRefereeNumber($vUpThreeUserId,10)){
        ObjFlyUserRefereeIntegral() -> SystemFlyUserRefereeIntegralAdd($vUpThreeUserId, $fpUserId, $vIntegral, "推荐用户直系三代积分奖励");
        
        //四代
        //if(self::UserRefereeNumber($vUpFourUserId,10)){
        ObjFlyUserRefereeIntegral() -> SystemFlyUserRefereeIntegralAdd($vUpFourUserId, $fpUserId, $vIntegral, "推荐用户直系四代积分奖励");
        
        //五代
        //if(self::UserRefereeNumber($vUpFiveUserId,10)){
        ObjFlyUserRefereeIntegral() -> SystemFlyUserRefereeIntegralAdd($vUpFiveUserId, $fpUserId, $vIntegral, "推荐用户直系五代积分奖励");
        
        //六代
        if(self::UserRefereeNumber($vUpSixUserId,30)){
            ObjFlyUserRefereeIntegral() -> SystemFlyUserRefereeIntegralAdd($vUpSixUserId, $fpUserId, $vIntegral, "推荐用户直系六代积分奖励");
        }
        
        
    }
    
    
    /**
     * 函数名称：用户推荐信息
     * 函数调用：ObjFlyUserReferee() -> UserRefereeInfor()
     * 创建时间：2020-07-21 21:13:08
     * */
    public function UserRefereeInfor($fpUserId=null){
    	
    	$vUserId = "";
        if(IsNull($fpUserId)){
            $vUserId = GetParameter('user_id', "");
            if(!JudgeRegularNumber($vUserId)){return JsonInforFalse("用户ID格式异常", "user_id");}
        }else{
        	$vUserId = $fpUserId;
        }
        
        $sql  = "SELECT (SELECT COUNT(TRUE) COUNT FROM fly_user_referee WHERE upOneUserId='{$vUserId}') one,";
        $sql .= "(SELECT COUNT(TRUE) COUNT FROM fly_user_referee WHERE upTwoUserId='{$vUserId}') two,";
        $sql .= "(SELECT COUNT(TRUE) COUNT FROM fly_user_referee WHERE upThreeUserId='{$vUserId}') three,"; 
        $sql .= "(SELECT COUNT(TRUE) COUNT FROM fly_user_referee WHERE upFourUserId='{$vUserId}') four,"; 
        $sql .= "(SELECT COUNT(TRUE) COUNT FROM fly_user_referee WHERE upFiveUserId='{$vUserId}') five,"; 
        $sql .= "(SELECT COUNT(TRUE) COUNT FROM fly_user_referee WHERE upSixUserId='{$vUserId}') six,"; 
        $sql .= "(SELECT SUM(money) money FROM fly_user_referee_many WHERE refereeUserId='{$vUserId}') money,";
        $sql .= "(SELECT SUM(integral) FROM fly_user_referee_integral WHERE userId='{$vUserId}') integral";
        
        $data = DBHelper::DataList($sql, null, ["one","two","three","four","five","six","money","integral"]);
        return JsonModelDataString($data,1);
        
    }
    
    
    //---------- 用户方法（user） ----------
    
    /**
     * 函数名称：用户推荐关系:用户:记录查询
     * 函数调用：ObjFlyUserReferee() -> UserFlyUserRefereePaging()
     * 创建时间：2020-07-21 20:49:14
     * */
    public function UserFlyUserRefereePaging($fpUserId=null){
        
        $vUserId = "";
        if(IsNull($fpUserId)){
            $vUserId = GetParameter('user_id', "");
            if(!JudgeRegularNumber($vUserId)){return JsonInforFalse("用户ID格式异常", "user_id");}
        }else{
            $vUserId = $fpUserId;
        }
        
        $vRefereeNumber = GetParameter('referee_number', "");
        if(!JudgeRegularNumber($vRefereeNumber)){return JsonInforFalse("推荐代数格式异常", "referee_number");}
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,userId,upOneUserId,upTwoUserId,upThreeUserId,upFourUserId,upFiveUserId,upSixUserId,upSevenUserId,upEightUserId,upNineUserId";
        
        $whereField = "upOneUserId";
        if($vRefereeNumber=="1"){
            $whereField = "upOneUserId";
        }else if($vRefereeNumber=="2"){
            $whereField = "upTwoUserId";
        }else if($vRefereeNumber=="3"){
            $whereField = "upThreeUserId";
        }else if($vRefereeNumber=="4"){
            $whereField = "upFourUserId";
        }else if($vRefereeNumber=="5"){
            $whereField = "upFiveUserId";
        }else if($vRefereeNumber=="6"){
            $whereField = "upSixUserId";
        }
        
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => "id,userId,phoneNumber,userNick,rUserNick",
            "where_field" => "",
            "where_value" => "",
            "page" => $pPage,
            "limit" => $pLimit,
            //"descbo" => "true",
            //"orderby" => "id",
            "like_field" => $pLikeField,
            "like_key" => $pLikeKey,
            "sql" => "SELECT fly_user_referee.id id,fly_user_referee.userId,fly_user.phoneNumber,fly_user.userNick,fly_user.refereeId,b.userNick rUserNick FROM fly_user_referee LEFT JOIN fly_user ON fly_user_referee.userId = fly_user.id LEFT JOIN fly_user b ON fly_user.refereeId = b.id WHERE fly_user_referee.{$whereField} = '{$vUserId}'",
            "sql_count" => "SELECT fly_user_referee.id id,fly_user_referee.userId,fly_user.phoneNumber,fly_user.userNick,fly_user.refereeId,b.userNick rUserNick FROM fly_user_referee LEFT JOIN fly_user ON fly_user_referee.userId = fly_user.id LEFT JOIN fly_user b ON fly_user.refereeId = b.id WHERE fly_user_referee.{$whereField} = '{$vUserId}'",
        );
        
        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, $pStateField, $pStateKey);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        
    }
    
    
    
    //---------- 管理员方法（admin） ----------
    
    /**
     * 函数名称：用户推荐关系:管理员:记录添加
     * 函数调用：ObjFlyUserReferee() -> AdminFlyUserRefereeAdd($fpAdminId)
     * 创建时间：2020-07-21 20:49:13
     * */
    public function AdminFlyUserRefereeAdd($fpAdminId){
        
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 变量预定义 ---
        $json="";
        
        //--- 参数获取区 ---
        
        //参数:记录描述:descript
        $pDescript = self::$classDescript;
        
        //参数:上下架状态:shelfState
        $pShelfState = "true";
        
        //参数:用户ID:userId
        $pUserId = GetParameterNoCode("userid",$json);
        if(!JudgeRegularInt($pUserId)){return JsonModelParameterException("userid", $pUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上一级用户ID:upOneUserId
        $pUpOneUserId = GetParameterNoCode("uponeuserid",$json);
        if(!JudgeRegularInt($pUpOneUserId)){return JsonModelParameterException("uponeuserid", $pUpOneUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上二级用户ID:upTwoUserId
        $pUpTwoUserId = GetParameterNoCode("uptwouserid",$json);
        if(!JudgeRegularInt($pUpTwoUserId)){return JsonModelParameterException("uptwouserid", $pUpTwoUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上三级用户ID:upThreeUserId
        $pUpThreeUserId = GetParameterNoCode("upthreeuserid",$json);
        if(!JudgeRegularInt($pUpThreeUserId)){return JsonModelParameterException("upthreeuserid", $pUpThreeUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上四级用户ID:upFourUserId
        $pUpFourUserId = GetParameterNoCode("upfouruserid",$json);
        if(!JudgeRegularInt($pUpFourUserId)){return JsonModelParameterException("upfouruserid", $pUpFourUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上五级用户ID:upFiveUserId
        $pUpFiveUserId = GetParameterNoCode("upfiveuserid",$json);
        if(!JudgeRegularInt($pUpFiveUserId)){return JsonModelParameterException("upfiveuserid", $pUpFiveUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上六级用户ID:upSixUserId
        $pUpSixUserId = GetParameterNoCode("upsixuserid",$json);
        if(!JudgeRegularInt($pUpSixUserId)){return JsonModelParameterException("upsixuserid", $pUpSixUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上七级用户ID:upSevenUserId
        $pUpSevenUserId = GetParameterNoCode("upsevenuserid",$json);
        if(!JudgeRegularInt($pUpSevenUserId)){return JsonModelParameterException("upsevenuserid", $pUpSevenUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上八级用户ID:upEightUserId
        $pUpEightUserId = GetParameterNoCode("upeightuserid",$json);
        if(!JudgeRegularInt($pUpEightUserId)){return JsonModelParameterException("upeightuserid", $pUpEightUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上九级用户ID:upNineUserId
        $pUpNineUserId = GetParameterNoCode("upnineuserid",$json);
        if(!JudgeRegularInt($pUpNineUserId)){return JsonModelParameterException("upnineuserid", $pUpNineUserId, 11, "值必须是整数", __LINE__);}
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,userId,upOneUserId,upTwoUserId,upThreeUserId,upFourUserId,upFiveUserId,upSixUserId,upSevenUserId,upEightUserId,upNineUserId",
            "descript" => self::$classDescript,
            "shelfstate" => $pShelfState,
            "userid" => $pUserId,
            "uponeuserid" => $pUpOneUserId,
            "uptwouserid" => $pUpTwoUserId,
            "upthreeuserid" => $pUpThreeUserId,
            "upfouruserid" => $pUpFourUserId,
            "upfiveuserid" => $pUpFiveUserId,
            "upsixuserid" => $pUpSixUserId,
            "upsevenuserid" => $pUpSevenUserId,
            "upeightuserid" => $pUpEightUserId,
            "upnineuserid" => $pUpNineUserId,
            "key_field" => "userId,upOneUserId,upTwoUserId,upThreeUserId,upFourUserId,upFiveUserId,upSixUserId,upSevenUserId,upEightUserId,upNineUserId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    /**
     * 函数名称：用户推荐关系:管理员:记录查询
     * 函数调用：ObjFlyUserReferee() -> AdminFlyUserRefereePaging($fpAdminId)
     * 创建时间：2020-07-21 20:49:14
     * */
    public function AdminFlyUserRefereePaging($fpAdminId){
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,userId,upOneUserId,upTwoUserId,upThreeUserId,upFourUserId,upFiveUserId,upSixUserId,upSevenUserId,upEightUserId,upNineUserId";
        
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
    
    
    /**
     * 函数名称：用户推荐关系:管理员:记录修改
     * 函数调用：ObjFlyUserReferee() -> AdminFlyUserRefereeSet($fpAdminId)
     * 创建时间：2020-07-21 20:49:13
     * */
    public function AdminFlyUserRefereeSet($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 数据预定义 ---
        $json = "";
        
        //--- 参数获取区 ---
        //参数:表ID:id
        $pId = GetParameterNoCode("id",$json);
        if(!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        
        //参数:记录描述:descript
        $pDescript = GetParameterNoCode("descript",$json);
        if(!IsNull($pDescript)&&!JudgeRegularFont($pDescript)){return JsonModelParameterException("descript", $pDescript, 36, "内容格式错误", __LINE__);}
        
        //参数:序号:indexNumber
        $pIndexNumber = GetParameterNoCode("indexnumber",$json);
        if(!IsNull($pIndexNumber)&&!JudgeRegularInt($pIndexNumber)){return JsonModelParameterException("indexnumber", $pIndexNumber, 11, "值必须是整数", __LINE__);}
        
        //参数:上下架状态:shelfState
        $pShelfState = GetParameterNoCode("shelfstate",$json);
        if(!IsNull($pShelfState)&&!JudgeRegularState($pShelfState)){return JsonModelParameterException("shelfstate", $pShelfState, 36, "状态值格式错误", __LINE__);}
        
        //参数:记录状态:state
        $pState = GetParameterNoCode("state",$json);
        if(!IsNull($pState)&&!JudgeRegularFont($pState)){return JsonModelParameterException("state", $pState, 36, "内容格式错误", __LINE__);}
        
        //参数:用户ID:userId
        $pUserId = GetParameterNoCode("userid",$json);
        if(!IsNull($pUserId)&&!JudgeRegularInt($pUserId)){return JsonModelParameterException("userid", $pUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上一级用户ID:upOneUserId
        $pUpOneUserId = GetParameterNoCode("uponeuserid",$json);
        if(!IsNull($pUpOneUserId)&&!JudgeRegularInt($pUpOneUserId)){return JsonModelParameterException("uponeuserid", $pUpOneUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上二级用户ID:upTwoUserId
        $pUpTwoUserId = GetParameterNoCode("uptwouserid",$json);
        if(!IsNull($pUpTwoUserId)&&!JudgeRegularInt($pUpTwoUserId)){return JsonModelParameterException("uptwouserid", $pUpTwoUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上三级用户ID:upThreeUserId
        $pUpThreeUserId = GetParameterNoCode("upthreeuserid",$json);
        if(!IsNull($pUpThreeUserId)&&!JudgeRegularInt($pUpThreeUserId)){return JsonModelParameterException("upthreeuserid", $pUpThreeUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上四级用户ID:upFourUserId
        $pUpFourUserId = GetParameterNoCode("upfouruserid",$json);
        if(!IsNull($pUpFourUserId)&&!JudgeRegularInt($pUpFourUserId)){return JsonModelParameterException("upfouruserid", $pUpFourUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上五级用户ID:upFiveUserId
        $pUpFiveUserId = GetParameterNoCode("upfiveuserid",$json);
        if(!IsNull($pUpFiveUserId)&&!JudgeRegularInt($pUpFiveUserId)){return JsonModelParameterException("upfiveuserid", $pUpFiveUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上六级用户ID:upSixUserId
        $pUpSixUserId = GetParameterNoCode("upsixuserid",$json);
        if(!IsNull($pUpSixUserId)&&!JudgeRegularInt($pUpSixUserId)){return JsonModelParameterException("upsixuserid", $pUpSixUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上七级用户ID:upSevenUserId
        $pUpSevenUserId = GetParameterNoCode("upsevenuserid",$json);
        if(!IsNull($pUpSevenUserId)&&!JudgeRegularInt($pUpSevenUserId)){return JsonModelParameterException("upsevenuserid", $pUpSevenUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上八级用户ID:upEightUserId
        $pUpEightUserId = GetParameterNoCode("upeightuserid",$json);
        if(!IsNull($pUpEightUserId)&&!JudgeRegularInt($pUpEightUserId)){return JsonModelParameterException("upeightuserid", $pUpEightUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:上九级用户ID:upNineUserId
        $pUpNineUserId = GetParameterNoCode("upnineuserid",$json);
        if(!IsNull($pUpNineUserId)&&!JudgeRegularInt($pUpNineUserId)){return JsonModelParameterException("upnineuserid", $pUpNineUserId, 11, "值必须是整数", __LINE__);}
        
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
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "descript", $pDescript);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "indexNumber", $pIndexNumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "shelfState", $pShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "state", $pState);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userId", $pUserId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "upOneUserId", $pUpOneUserId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "upTwoUserId", $pUpTwoUserId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "upThreeUserId", $pUpThreeUserId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "upFourUserId", $pUpFourUserId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "upFiveUserId", $pUpFiveUserId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "upSixUserId", $pUpSixUserId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "upSevenUserId", $pUpSevenUserId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "upEightUserId", $pUpEightUserId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "upNineUserId", $pUpNineUserId);
        
        //判断字段值是否为空
        $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,userid,uponeuserid,uptwouserid,upthreeuserid,upfouruserid,upfiveuserid,upsixuserid,upsevenuserid,upeightuserid,upnineuserid");
        if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }
        
        //返回结果
        return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        
    }
    
    
    /**
     * 函数名称：用户推荐关系:管理员:记录状态修改
     * 函数调用：ObjFlyUserReferee() -> AdminFlyUserRefereeSetState($fpAdminId)
     * 创建时间：2020-07-21 20:49:14
     * */
    public function AdminFlyUserRefereeSetState($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 变量预定义 ---
        $json="";		//Json参数
        
        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameterNoCode("id",$json);
        if(!JudgeRegularFont($pId)){return JsonModelParameterInfor("id","参数值不符合规则",20);}
        
        //参数:记录状态:state
        $pState = GetParameterNoCode("state",$json);
        if(IsNull($pState)||GetStringLength($pState)>36||!($pState=="STATE_NORMAL"||$pState=="STATE_DELETE")){return JsonModelParameterInfor("state","值必须是STATE_NORMAL/STATE_DELETE",36);}
        
        //--- 数据提交区 ---
        return ServiceTableDataSystemSet(self::$tableName,"state","{$pState}","id","{$pId}");
    }
    
    /**
     * 函数名称：用户推荐关系:管理员:数据上下架状态修改
     * 函数调用：ObjFlyUserReferee() -> AdminFlyUserRefereeShelfState($fpAdminId)
     * 创建时间：2020-07-21 20:49:14
     * */
    public function AdminFlyUserRefereeShelfState($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameterNoCode("id","");
        if(IsNull($pId)){return JsonModelParameterNull("id");}
        //参数：id：正整数正则判断
        if(!JudgeRegularIntRight($pId)){return JsonInforFalse("id必须是正整数", "id");}
        
        //参数：shelfstate
        $pShelfState = GetParameterNoCode("shelfstate","");
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
     * 函数名称：用户推荐关系:管理员:记录永久删除
     * 函数调用：ObjFlyUserReferee() -> AdminFlyUserRefereeDelete($fpAdminId)
     * 创建时间：2020-07-21 20:49:14
     * */
    public function AdminFlyUserRefereeDelete($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 变量预定义 ---
        $json="";		//Json参数
        
        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameterNoCode("id",$json);
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
    
    
    /**
     * 函数名称：用户推荐关系:管理员修改用户推荐关系
     * 函数调用：ObjFlyUserReferee() -> AdminRefereeRelationshipSet($fpAdminId)
     * 创建时间：2020-08-03 11:38:57
     * */
    public function AdminRefereeRelationshipSet($fpAdminId){
        
        $vUserId = GetParameter('user_id', "");
        if(!JudgeRegularNumber($vUserId)){return JsonInforFalse("用户ID格式异常", "user_id");}
        $vDbUserId = DBHelper::DataString("SELECT id FROM fly_user WHERE id = '{$vUserId}'", null);
        if(IsNull($vDbUserId)){
            return JsonInforFalse("用户不存在", "用户ID获取");
        }
        
        $vRefereePhone = GetParameter('referee_phone', "");
        if(!JudgeRegularPhone($vRefereePhone)){return JsonInforFalse("推荐人手机号格式异常", "referee_phone");}
        
        $vRefereeUserId = DBHelper::DataString("SELECT id FROM fly_user WHERE phoneNumber = '{$vRefereePhone}'", null);
        if(IsNull($vRefereeUserId)){
            return JsonInforFalse("推荐人不存在", "推荐人ID获取");
        }
        if($vRefereeUserId==$vUserId){
            return JsonInforFalse("推荐人不得为自己", "推荐人ID对比");
        }
        
        $vTableName = "fly_user";
        
        //修改用户上级推荐人ID
        DBHelper::DataSubmit("UPDATE fly_user SET refereeId='{$vRefereeUserId}' WHERE id='{$vUserId}'", null);
        //用户推荐关系修改
        $vRefereeOne = $vRefereeUserId;
        $vRefereeTwo = self::UserReferee($vRefereeOne);
        $vRefereeThree = self::UserReferee($vRefereeTwo);
        $vRefereeFour = self::UserReferee($vRefereeThree);
        $vRefereeFive = self::UserReferee($vRefereeFour);
        $vRefereeSix = self::UserReferee($vRefereeFive);
        $vRefereeSeven = self::UserReferee($vRefereeSix);
        $vRefereeEight = self::UserReferee($vRefereeSeven);
        $vRefereeNine = self::UserReferee($vRefereeEight);
        DBHelper::DataSubmit("UPDATE fly_user_referee SET upOneUserId='{$vRefereeOne}',upTwoUserId='{$vRefereeTwo}',upThreeUserId='{$vRefereeThree}',upFourUserId='{$vRefereeFour}',upFiveUserId='{$vRefereeFive}',upSixUserId='{$vRefereeSix}',upSevenUserId='{$vRefereeSeven}',upEightUserId='{$vRefereeEight}',upNineUserId='{$vRefereeNine}' WHERE userId='{$vUserId}'", null);
        //用户奖励金额修改
        DBHelper::DataSubmit("UPDATE fly_user_referee_many SET refereeUserId='{$vRefereeOne}' WHERE userId='{$vUserId}'", null);
        //用户奖励积分修改
        DBHelper::DataSubmit("DELETE FROM fly_user_referee_integral WHERE registerUserId = '{$vUserId}';", null);
        //计算推荐积分
        ObjFlyUserReferee() -> SystemFlyUserIntegralCalc($vUserId);
        return JsonInforTrue("上级推荐关系修改完成", "推荐关系修改");
    }
    
    
    //---------- 测试方法（test） ----------
    
    //---------- 基础方法（base） ----------
    
    
    /**
     * 函数名称：获取数据表名称
     * 函数调用：ObjFlyUserReferee() -> GetTableName()
     * 创建时间：2020-07-21 20:49:13
     * */
    public function GetTableName(){
        return self::$tableName;
    }
    
    /**
     * 函数名称：获取类描述
     * 函数调用：ObjFlyUserReferee() -> GetClassDescript()
     * 创建时间：2020-07-21 20:49:13
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }
    
    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjFlyUserReferee() -> GetTableField()
     * 创建时间：2020-07-21 20:49:13
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjFlyUserReferee() -> OprationCreateTable()
     * 创建时间：2020-07-21 20:49:13
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `fly_user_referee` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `userId` int(11) DEFAULT NULL COMMENT '用户ID',  `upOneUserId` int(11) DEFAULT NULL COMMENT '上一级用户ID',  `upTwoUserId` int(11) DEFAULT NULL COMMENT '上二级用户ID',  `upThreeUserId` int(11) DEFAULT NULL COMMENT '上三级用户ID',  `upFourUserId` int(11) DEFAULT NULL COMMENT '上四级用户ID',  `upFiveUserId` int(11) DEFAULT NULL COMMENT '上五级用户ID',  `upSixUserId` int(11) DEFAULT NULL COMMENT '上六级用户ID',  `upSevenUserId` int(11) DEFAULT NULL COMMENT '上七级用户ID',  `upEightUserId` int(11) DEFAULT NULL COMMENT '上八级用户ID',  `upNineUserId` int(11) DEFAULT NULL COMMENT '上九级用户ID',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户推荐奖励'";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"table_name":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }
    
    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjFlyUserReferee() -> OprationTableFieldBaseCheck()
     * 创建时间：2020-07-21 20:49:13
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    
    
    
}
