<?php

/**------------------------------------*
  * 作者：shark
  * 创建时间：2019-07-20 14:38:35
  * Fly编码：1563604715958FLY853983
  * ------------------------------------ */

//引入区

class FlyClassRbacNav{


    //---------- 类成员 ----------

    //类描述
    public static $classDescript = "RBAC菜单";

    //类数据表名称
    public static $tableName = "fly_rbac_nav";



    //---------- 自定义方法 ----------

    //---------- 基础方法 ----------


    /**
     * 获取数据表名称
     * 创建时间：2019-07-20 14:38:35
     * */
    public function GetTableName(){
        return self::$tableName;
    }

    /**
     * 获取类描述
     * 创建时间：2019-07-20 14:38:35
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }



    /**
     * 数据添加
     * 创建时间：2019-07-20 14:38:35
     * */
    public function FlyRbacNavAdd($json=""){

        //--- 参数获取区 ---
	    //参数：fatherId
	    $fatherId = GetParameter("fatherid",$json);
	    if(!JudgeRegularInt($fatherId)){return JsonInforFalse("值不符合规则", "fatherid");}
	    //参数：typeName
	    $typeName = GetParameter("typename",$json);
	    if(IsNull($typeName)){return JsonModelParameterNull("typename");}
	    //参数：groupName
	    $groupName = GetParameter("groupname",$json);
	    if(IsNull($groupName)){return JsonModelParameterNull("groupname");}
	    //参数：navName
	    $navName = GetParameter("navname",$json);
	    if(IsNull($navName)){return JsonModelParameterNull("navname");}
	    //参数：navUpId
	    $navUpId = GetParameter("navupid",$json);
	    if(IsNull($navUpId)){return JsonModelParameterNull("navupid");}
	    //参数：navIcon
	    $navIcon = GetParameter("navicon",$json);
	    if(IsNull($navIcon)){return JsonModelParameterNull("navicon");}
	    //参数：navIconClass
	    $navIconClass = GetParameter("naviconclass",$json);
	    if(IsNull($navIconClass)){return JsonModelParameterNull("naviconclass");}
	    //参数：navHref
	    $navHref = GetParameter("navhref",$json);
	    if(IsNull($navHref)){return JsonModelParameterNull("navhref");}
	    //参数：navLevel
	    $navLevel = GetParameter("navlevel",$json);
	    if(IsNull($navLevel)){return JsonModelParameterNull("navlevel");}

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "fatherId,typeName,groupName,navName,navUpId,navIcon,navIconClass,navHref,navLevel",
            "fatherid" => $fatherId,
            "typename" => $typeName,
            "groupname" => $groupName,
            "navname" => $navName,
            "navupid" => $navUpId,
            "navicon" => $navIcon,
            "naviconclass" => $navIconClass,
            "navhref" => $navHref,
            "navlevel" => $navLevel,
        );
        //返回结果
        return MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
    }


    /**
     * 数据分页查询
     * 创建时间：2019-07-20 14:38:35
     * */
    public function FlyRbacNavPaging(){
        return ServiceTableDataPaging(self::$tableName,"id,onlyKey,fatherId,typeName,groupName,indexNumber,updateTime,addTime,state,navName,navUpId,navIcon,navIconClass,navHref,navLevel");
    }


    /**
     * 数据修改
     * 创建时间：2019-07-20 14:38:35
     * */
    public function FlyRbacNavSet(){
        return ServiceTableDataSet(self::$tableName,"fatherId,typeName,groupName,indexNumber,state,navName,navUpId,navIcon,navIconClass,navHref,navLevel");
    }


    /**
     * 数据软删除
     * 创建时间：2019-07-20 14:38:35
     * */
    public function FlyRbacNavSetDelete(){
        return ServiceTableDataSetDelete(self::$tableName);
    }

    /**
     * 数据硬删除
     * 创建时间：2019-07-20 14:38:35
     * */
    public function FlyRbacNavDelete(){
        return ServiceTableDataDelete(self::$tableName);
    }


}
