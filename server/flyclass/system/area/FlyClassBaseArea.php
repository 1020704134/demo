<?php

/**------------------------------------*
  * 创建时间：2019-07-07 11:23:05
  * ------------------------------------ */

//引入区

class FlyClassBaseArea{


    //---------- 类成员 ----------

    //类描述
    public static $classDescript = "城市区域";

    //类数据表名称
    public static $tableName = "fly_area";



    //---------- 自定义方法 ----------

    //---------- 基础方法 ----------


    /**
     * 获取数据表名称
     * 创建时间：2019-07-07 11:23:05
     * */
    public function GetTableName(){
        return self::$tableName;
    }

    /**
     * 获取类描述
     * 创建时间：2019-07-07 11:23:05
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }



    /**
     * 数据添加
     * 创建时间：2019-07-07 11:23:05
     * */
    public function FlyBaseAreaAdd($json=""){

        //--- 参数获取区 ---
	    //参数：fatherId
	    $fatherId = "none";
	    //参数：typeName
	    $typeName = GetParameterNoCode("typename",$json);
	    if(IsNull($typeName)){return JsonModelParameterNull("typename");}
	    //参数：groupName
	    $groupName = GetParameterNoCode("groupname",$json);
	    if(IsNull($groupName)){return JsonModelParameterNull("groupname");}
	    //参数：areaName
	    $areaName = GetParameterNoCode("areaname",$json);
	    if(IsNull($areaName)){return JsonModelParameterNull("areaname");}
	    //参数：areaShortName
	    $areaShortName = GetParameterNoCode("areashortname",$json);
	    if(IsNull($areaShortName)){return JsonModelParameterNull("areashortname");}
	    //参数：areaCode
	    $areaCode = GetParameterNoCode("areacode",$json);
	    if(IsNull($areaCode)){return JsonModelParameterNull("areacode");}
	    //参数：areaCodeUp
	    $areaCodeUp = GetParameterNoCode("areacodeup",$json);
	    if(IsNull($areaCodeUp)){return JsonModelParameterNull("areacodeup");}
	    //参数：areaLevel
	    $areaLevel = GetParameterNoCode("arealevel",$json);
	    if(IsNull($areaLevel)){return JsonModelParameterNull("arealevel");}
	    //参数：areaPhonetic
	    $areaPhonetic = GetParameterNoCode("areaphonetic",$json);
	    if(IsNull($areaPhonetic)){return JsonModelParameterNull("areaphonetic");}
	    //参数：areaFirstLetter
	    $areaFirstLetter = GetParameterNoCode("areafirstletter",$json);
	    if(IsNull($areaFirstLetter)){return JsonModelParameterNull("areafirstletter");}
	    //参数：areaDescript
	    $areaDescript = GetParameterNoCode("areadescript",$json);
	    if(IsNull($areaDescript)){return JsonModelParameterNull("areadescript");}
	    //参数：longitude
	    $longitude = GetParameterNoCode("longitude",$json);
	    if(IsNull($longitude)){return JsonModelParameterNull("longitude");}
	    //参数：latitude
	    $latitude = GetParameterNoCode("latitude",$json);
	    if(IsNull($latitude)){return JsonModelParameterNull("latitude");}
	    //参数：recordDescript
	    $recordDescript = GetParameterNoCode("recorddescript",$json);
	    if(IsNull($recordDescript)){return JsonModelParameterNull("recorddescript");}

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "tablename" => self::$tableName,
            "insertfield" => "fatherId,typeName,groupName,areaName,areaShortName,areaCode,areaCodeUp,areaLevel,areaPhonetic,areaFirstLetter,areaDescript,longitude,latitude,recordDescript",
            "fatherid" => $fatherId,
            "typename" => $typeName,
            "groupname" => $groupName,
            "areaname" => $areaName,
            "areashortname" => $areaShortName,
            "areacode" => $areaCode,
            "areacodeup" => $areaCodeUp,
            "arealevel" => $areaLevel,
            "areaphonetic" => $areaPhonetic,
            "areafirstletter" => $areaFirstLetter,
            "areadescript" => $areaDescript,
            "longitude" => $longitude,
            "latitude" => $latitude,
            "recorddescript" => $recordDescript,
            "keyfield" => "areaCode",
        );
        //返回结果
        return MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
    }


    /**
     * 数据分页查询
     * 创建时间：2019-07-07 11:23:05
     * */
    public function FlyBaseAreaPaging(){
        //参数：page
        $pPage = GetParameter("page","");
        if(IsNull($pPage)||!JudgeRegularIntRight($pPage)){return JsonModelParameterException("page", $pPage, 9, "值必须是正整数", __LINE__);}
        //参数：limit
        $pLimit = GetParameter("limit","");
        if(IsNull($pLimit)||!JudgeRegularIntRight($pLimit)){return JsonModelParameterException("limit", $pLimit, 9, "值必须是正整数", __LINE__);}
        //参数：条件字段
        $pWhereField = GetParameter("where_field","");
        if(!($pWhereField=="areaLevel"||$pWhereField=="areaCodeUp")){return JsonInforFalse("错误的条件字段", "areaLevel");}        
        //参数：条件字段
        $pWhereValue = GetParameter("where_value","");
        if(!JudgeRegularNumber($pWhereValue)){return JsonInforFalse("错误的条件值", "条件值需为整数");}
        
        return ServiceTableDataPagingWhere(self::$tableName, "id,areaName,areaShortName,areaCode,areaCodeUp,areaLevel,areaPhonetic,areaFirstLetter,areaDescript,longitude,latitude,recordDescript", $pWhereField, $pWhereValue, $pLimit);
    }


    /**
     * 数据修改
     * 创建时间：2019-07-07 11:23:05
     * */
    public function FlyBaseAreaSet(){
        return ServiceTableDataSet(self::$tableName,"fatherId,typeName,groupName,areaName,areaShortName,areaCode,areaCodeUp,areaLevel,areaPhonetic,areaFirstLetter,areaDescript,longitude,latitude,recordDescript,indexNumber,state");
    }


    /**
     * 数据软删除
     * 创建时间：2019-07-07 11:23:05
     * */
    public function FlyBaseAreaSetDelete(){
        return ServiceTableDataSetDelete(self::$tableName);
    }

    /**
     * 数据硬删除
     * 创建时间：2019-07-07 11:23:05
     * */
    public function FlyBaseAreaDelete(){
        return ServiceTableDataDelete(self::$tableName);
    }


}
