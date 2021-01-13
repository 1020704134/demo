<?php

class FlyJson{
    
    //==================== Fly 系统内部参数定义区 ====================
    
    //系统字段：隐藏参数，查询记录时需要隐藏的记录字段
    public static $vFlySystemParameterHide = "fly_system_parameter_hide";       //隐藏输出参数
    //系统字段：必填参数，将接口必填的参数非必填字段转为必填字段
    public static $vFlySystemParameterMust = "fly_system_parameter_must";       //必填参数
    //系统字段：忽略参数，忽略掉要传入的参数
    public static $vFlySystemParameterIgnore = "fly_system_parameter_ignore ";  //忽略参数
    //系统字段：接口描述，接口描述
    public static $vFlySystemMethodDescript = "fly_system_method_descript";     //接口方法描述
    
    //系统字段：操作描述
    public static $vFlySystemUserType = "fly_system_user_type";                 //操作日志用户类型
    public static $vFlySystemUserTable = "fly_system_user_table";               //操作日志用户数据表
    public static $vFlySystemUserId = "fly_system_user_id";                     //操作日志用户ID
    public static $vFlySystemEvent = "fly_system_event";                        //操作日志事件描述
    public static $vFlySystemEventTable = "fly_system_event_table";             //操作日志事件描述
    
    //FlyJson字段
    public static $vFlyJsonKeyField = "key_field";                              //记录添加主键字段
    public static $vFlyJsonWhereField = "where_field";                          //条件字段
    public static $vFlyJsonWhereValue = "where_value";                          //条件值
    public static $vFlyJsonWhereSon = "where_son";                              //条件子字符串
    public static $vFlyJsonUpdateField = "update_field";                        //修改字段
    public static $vFlyJsonUpdateValue = "update_value";                        //修改值
    
    //过程控制字段
    public static $vFlyJsonExecutionStep = "execution_step";                    //执行步骤
    
    
    //==================== Fly 参数定义区 ====================
    
    //Fly参数:接口版本号
    public static $vParameterVersion = "version";
    
    //Fly参数:接口调试
    public static $vInterfaceDebug = "debug";
    
    
    //==================== Fly Json特定字段定义区 ====================
    public static $kRSCode = "scode";    //scode:系统码:系统执行编码，通过该编码可判断程序是执行结果
    public static $kRECode = "ecode";    //ecode:系统执行码:系统执行过程编码，通过该编码可判断程序执行细节
    
    public static $kFlyFileInfor = "FLY_FIELD_FILE_INFOR";      //Fly字段:文件信息
    public static $kFlyInforExtend = "FLY_FIELD_INFOR_EXTEND";  //Fly字段:扩展信息，可对该字段进行替换扩展需要的信息
    
    public static $kValue = "value";                            //value:值，单个数据请求结果
    public static $kFile = "file";                              //file:文件名
    public static $kFunction = "function";                      //function:函数名
    public static $kLine = "line";                              //line:信息行数
    
    
    //==================== Fly Json 值常量区 ====================
    
    //----- Json:result:执行结果字段 -----
    //结果:true
    public static $vResultTrue = "true";
    //结果:false
    public static $vResultFalse = "false";
    
    //----- Json:parameter:ismust:必填参数 -----
    //必填参数:yes
    public static $vParameterIsMustYes = "yes";
    //必填参数:no
    public static $vParameterIsMustNo = "no";

    //----- Json:parameter:source:参数值数据源 -----
    //参数值来源:数据库
    public static $vParameterSourceDataBaseTable = "DATABASETABLE";
    //参数值来源:数据表字段
    public static $vParameterSourceTableField = "TABLEFIELD";
    
    
    //----- Json:parameter:type:参数类型 -----   
    public static $vPTypeId = "id";                     //参数类型:id：正整数
    public static $vPTypeImage = "image";               //参数类型:图片：图片URL
    public static $vPTypeUrl = "url";                   //参数类型:URL：Url地址
    public static $vPTypeDate = "date";                 //参数类型:Date：日期格式字符串：0000-00-00 00:00:00
    public static $vPTypeInt = "int";                   //参数类型:Int：整数类型
    public static $vPTypeNumber = "number";             //参数类型:数量：正整数
    public static $vPTypeNumberLetter = "numberLetter"; //参数类型:数字字母：数字及字母组合成的字符串
    public static $vPTypePhoneCode = "phoneCode";       //参数类型:手机验证码：4或6为数字或字母
    public static $vPTypePassword = "password";         //参数类型:密码：8到16位数字或字母
    public static $vPTypeDouble = "double";             //参数类型:Double：浮点类型
    public static $vPTypeWechatOpenId = "wechatOpenId"; //参数类型:微信OpenId：28位数字字母下划线及横线
    public static $vPTypeTitle = "title";               //参数类型:标题：标题及符号
    public static $vPTypeTag = "tag";                   //参数类型:标签：文字及顿号
    public static $vPTypeRichtext = "richtext";         //参数类型:富文本：代码及文字
    public static $vPTypeFont = "font";                 //参数类型:文本：文字及符号
    public static $vPTypeOrder = "order";               //参数类型:订单：订单
    public static $vPTypeMoney = "money";               //参数类型:金额：两位小数
    public static $vPTypePhone = "phone";               //参数类型:手机号：11位手机号判断
    public static $vPTypeIdcard = "idcard";             //参数类型:身份证：15或18位身份证号
    public static $vPTypeTable = "table";               //参数类型:数据表名：小写字母及下划线组成的数据表名
    public static $vPTypeField = "field";               //参数类型:数据表字段：数据表字段名
    public static $vPTypeState = "state";               //参数类型:状态：大写单词及下划线
    
    
    //==================== Fly Json 私有方法 ====================
    
    /**
     * Json私有方法： Json字符串组合：结果
     * 整理时间：2019-10-05 15:08:27
     * */
    private static function JsonStringResult($fpResult, $fpRSCode, $fpRECode){
        
        //========== Json标准字段 ==========
        
        //----- Json:result:处理结果（r小组：result） -----
        $kResult = "result";    //result:执行结果:用于标记逻辑执行结果，true、false
        
        
        //========== Json字段组合 ==========
        
        //Json字符串
        $vJsonString  = "";
        $vJsonString .= JsonKeyValue($kResult,$fpResult);         //结果:result
        $vJsonString .= "," . JsonKeyValue(self::$kRSCode,$fpRSCode);   //系统码:scode
        $vJsonString .= "," . JsonKeyValue(self::$kRECode,$fpRECode);   //执行码:ecode
        
        return $vJsonString;
        
    }
        

    /**
     * Json私有方法： Json字符串组合：信息
     * 整理时间：2019-10-05 15:17:50
     * */
    private static function JsonStringInfor($fpValue, $fpInfor, $fpTips, $fpTable, $fpVersion){
    
        //========== Json标准字段 ==========
        
        $kInfor = "infor";                      //infor:消息，请求执行结果，一般用于返回单个结果
        $kTips = "tips";                        //tips:提示，消息补充          
        $kTable = "table";                      //table:数据表，标明获取数据的数据表，为空时说明数据非直接从数据库获取的数据
        $kVersion = "version";                  //version:接口版本号
        $kTime = "time";                        //time:时间，接口请求时间
        $kTimestamp = "timestamp";              //timestamp:时间戳，数据时间戳版本号
                
        //========== Json字段组合 ==========
        
        $vJsonString = "";                      //Json字符串
        
        //数据请求值:value
        if(!IsNull($fpValue)){
            $vJsonString .= "," . JsonKeyValue(self::$kValue,$fpValue);
        }
        
        //消息:infor
        if(!IsNull($fpInfor)){
            $vJsonString .= "," . JsonKeyValue($kInfor,$fpInfor);
        }
        
        //消息提示:tips
        if(!IsNull($fpTips)){
            $vJsonString .= "," . JsonKeyValue($kTips,$fpTips);
        }
        
        //数据表:table
        $vJsonString .= "," . JsonKeyValue($kTable,$fpTable);
        
        //接口版本：version
        if(IsNull($fpVersion)){ $fpVersion = "1.0"; }
        $vJsonString .= "," . JsonKeyValue($kVersion,$fpVersion);   
        
        //请求时间：time
        $vJsonString .= "," . JsonKeyValue($kTime,GetTimeNow());
        
        //请求时间戳：timestamp
        $vJsonString .= "," . JsonKeyValue($kTimestamp,GetId("R"));
        
        //文件信息:file        
        $vFunctionInfor = GetStringFunctionInfor(2);
        $vJsonString .= "," . JsonKeyValue(self::$kFlyFileInfor,$vFunctionInfor);
        
        //扩展信息
        $vJsonString .= "," . JsonKeyValue(self::$kFlyInforExtend,"");
        
        return $vJsonString;
    
    }
    
    
    /**
     * Json私有方法： Json字符串组合：信息
     * 整理时间：2019-10-05 15:17:50
     * */
    private static function JsonStringParameter($fpParameterArray){
    
        //========== Json标准字段 ==========
        
        $kParameter = "parameter";                      //parameter:参数名
        $kParameterDescript = "pDescript";              //descript:参数描述
        $kParameterValue = "pValue";                    //value:参数传入值定义，?:为变量，常量为定义好的值:"常量:描述、常量:描述"
        $kParameterValueType = "pValueType";            //valueType:参数传入值类型
        $kParameterIsMust = "pIsMust";                  //isMust:参数是否必填
        $kParameterRemarks = "pRemarks";                //remarks:参数描述
        $kParameterSource = "pSource";                  //source:参数来源，参数信息源，参数在接口定义的位置
        $kParameterRegular = "pRegular";                //regular:参数判断正则
        $kParameterCheck = "pCheck";                    //check:参数校验
        $kParameterCheckDescript = "pCheckDescript";    //checkDescript:参数校验描述
        $kParameterRequestValue = "pRequestValue";      //valuestring:请求参数，参数值字符串，对参数值字符串进行编码
        $kParameterDefaultValue = "pDefaultValue";      //defaultValue:默认值，当请求到的值为空值使用默认值
        $kParameterLengthSmall = "pLengthSmall";        //lengthSmall:参数值最小长度
        $kParameterLengthMax = "pLengthMax";            //lengthMax:参数值最大程度
        $kParameterLength = "pLength";                  //valuelength:参数值长度，该字段值允许传入的最大长度
        
        
        //========== Json值获取区 ==========

        $vParameter = $fpParameterArray["parameter"];
        $vDescript = $fpParameterArray["descript"];
        $vValue = $fpParameterArray["value"];
        $vValueType = $fpParameterArray["valueType"];
        $vIsMust = $fpParameterArray["isMust"];
        $vRemarks = $fpParameterArray["remarks"];
        $vSource = $fpParameterArray["source"];
        $vRegular = $fpParameterArray["regular"];
        $vRequestValue = $fpParameterArray["requestValue"];
        $vDefaultValue = $fpParameterArray["defaultValue"];
        $vLengthSmall = $fpParameterArray["lengthSmall"];
        $vLengthMax = $fpParameterArray["lengthMax"];
        
        //参数校验结果
        $vParameterCheck = "true";
        //参数校验结果
        $vParameterCheckDescript = "";
        //参数值长度
        $vParameterLength = mb_strlen($vRequestValue);
        if(IsNull($vRequestValue)){
            $vParameterCheck = "false";
            $vParameterCheckDescript = "参数值为空";
        }else if($vParameterLength<$vLengthSmall||$vParameterLength>$vLengthMax){
            $vParameterCheck = "false";
            $vParameterCheckDescript = "参数值长度超出规定范围";
        }else if(IsNull($vRegular)){
            $vParameterCheck = "false";
            $vParameterCheckDescript = "参数校验规则未定义";
        }
        
        //正则判断
        if(!$vRegular){
            $vParameterCheck = "false";
            $vParameterCheckDescript = "参数不符合规则";
            //else if(eval("return !{$vRegular}(\"{$vValue}\");"))  //对传入字符串进行字符串代码执行的方式不可取，会为系统留下安全隐患
        }
        
        //========== Json字段组合 ==========
    
        $vJsonString  = "";
        $vJsonString .= "," . JsonKeyValue($kParameter,$vParameter);
        $vJsonString .= "," . JsonKeyValue($kParameterDescript,$vDescript);
        $vJsonString .= "," . JsonKeyValue($kParameterValue,$vValue);
        $vJsonString .= "," . JsonKeyValue($kParameterValueType,$vValueType);
        $vJsonString .= "," . JsonKeyValue($kParameterSource,$vSource);
        $vJsonString .= "," . JsonKeyValue($kParameterIsMust,$vIsMust);
        $vJsonString .= "," . JsonKeyValue($kParameterRemarks,$vRemarks);
        $vJsonString .= "," . JsonKeyValue($kParameterRegular,$vRegular?"true":"false");
        $vJsonString .= "," . JsonKeyValue($kParameterCheck,$vParameterCheck);
        $vJsonString .= "," . JsonKeyValue($kParameterCheckDescript,$vParameterCheckDescript);
        $vJsonString .= "," . JsonKeyValue($kParameterRequestValue,HandleStringFlyHtmlEncode($vRequestValue));
        $vJsonString .= "," . JsonKeyValue($kParameterDefaultValue,$vDefaultValue);
        $vJsonString .= "," . JsonKeyValue($kParameterLengthSmall,$vLengthSmall);
        $vJsonString .= "," . JsonKeyValue($kParameterLengthMax,$vLengthMax);
        $vJsonString .= "," . JsonKeyValue($kParameterLength,$vParameterLength);
    
        return $vJsonString;
    
    }
    
    
    /**
     * Json私有方法： Json字符串组合：数据
     * 整理时间：2019-10-05 15:17:50
     * */
    private static function JsonStringData($fpCount, $fpData){
        
        //========== Json标准字段 ==========
        
        $kCount = "count";      //count:记录总数，该字段显示查询到符合条件的总记录数，常用于对数据分页的计算
        $kData = "data";        //data:记录，该字段显示查询到的记录主体，以Json对象数组的方式显示
    
        
        //========== Json字段组合 ==========
        
        $vJsonString  = "";
        $vJsonString .= "," . JsonKeyValue($kCount,$fpCount);     //数据总数:count
        $vJsonString .= "," . JsonKeyString($kData,$fpData);      //数据:data
         
        return $vJsonString;
    
    }
    
  
    
    /**
     * Json私有方法： Json字符串组合：接口信息
     * 整理时间：2019-10-05 15:17:50
     * */
    private static function JsonStringInterface($fpInterfaceArray){
        
        //========== Json标准字段 ==========
        
        $kPath = "iPath";                                   //path:接口请求路径，标明接口的请求路径
        $kPathName = "iPathName";                           //pathName:接口请求路径文件名
        $kServiceLine = "iServiceLine";                     //line:业务线，接口业务线索引
        $kServiceLineDescript = "iServiceLineDescript";     //descript:接口描述，描述接口功能
        $kServiceMethod = "iServiceMethod";                 //method:业务线方法，接口业务线方法索引
        $kServiceMethodDescript = "iServiceMethodDescript"; //descript:接口描述，描述接口功能
        $kServer = "iServer";                               //server:服务器信息，执行方法所使用的服务器及项目信息
        
        
        //========== Json值获取区 ==========
        
        //接口信息
        $vIPath = $fpInterfaceArray["path"];
        $vIServiceLine = $fpInterfaceArray["serviceLine"];
        $vIServiceLineDescript = $fpInterfaceArray["serviceLineDescript"];
        $vIServiceMethod = $fpInterfaceArray["serviceMethod"];
        $vIServiceMethodDescript = $fpInterfaceArray["serviceMethodDescript"];
        $vIServer = $fpInterfaceArray["server"];
        
        
        //========== Json字段组合 ==========
        
        $vJsonString  = "";  
        //返回结果文件:file
        $vJsonString .= "," . JsonKeyValue($kPath,$vIPath);  //path
        $vJsonString .= "," . JsonKeyValue($kPathName,GetStringFileName($vIPath));  //path
        $vJsonString .= "," . JsonKeyValue($kServiceLine,$vIServiceLine);       //line
        $vJsonString .= "," . JsonKeyValue($kServiceLineDescript,$vIServiceLineDescript);             //descript
        $vJsonString .= "," . JsonKeyValue($kServiceMethod,$vIServiceMethod);   //method
        $vJsonString .= "," . JsonKeyValue($kServiceMethodDescript,$vIServiceMethodDescript);             //descript
        $vJsonString .= "," . JsonKeyValue($kServer,$vIServer);                 //server
        
        return $vJsonString;
        
    }
    
    /**
     * Json私有方法： Json字符串组合：保留信息
     * 整理时间：2019-10-05 15:17:50
     * */
    private static function JsonStringReserved($fpCode){
    
        //========== Json标准字段 ==========

        $kCode = "code";      //code:保留字段:某些前端会使用该字段进行判断，默认为空，""
        
        //========== Json字段组合 ==========
        
        $vJsonString  = "";
        $vJsonString .= "," . JsonKeyValue($kCode,$fpCode);   //code
         
        return $vJsonString;
    
    }
    
    /**
     * Json基础组成方法
     * $fp*：JsonValue
     * 整理时间：2019-09-26 13:05:24
     * */
    private static function JsonBase($fpResult,$fpInfor,$fpData,$fpParameter,$fpInterface,$fpRetained){
        $vJsonString = $fpResult . $fpInfor . $fpData . $fpParameter . $fpInterface . $fpRetained;
        return JsonObj($vJsonString);
    }
    

    //==================== Fly Json 标准模板 ====================
    
    /**
     * Json信息模型方法
     * $fp*：JsonValue
     * 整理时间：2019-09-26 15:07:51
     * */
    public static function JsonModelInfor($fpResult, $fpRSCode, $fpRECode, $fpValue, $fpInfor, $fpTips, $fpTable){
        $fpVersion = self::JsonGetVersion("");
        //Json组字符串
        $vResult = self::JsonStringResult($fpResult, $fpRSCode, $fpRECode);
        $vInfor = self::JsonStringInfor($fpValue,$fpInfor, $fpTips, $fpTable, $fpVersion);
        $vData = "";
        $vParameter = "";
        $vInterface = "";
        $vRetained = self::JsonStringReserved("");
        //Json结果
        return self::JsonBase($vResult, $vInfor, $vData, $vParameter, $vInterface, $vRetained);
    }
    
    /**
     * Json数据模型方法
     * $fp*：JsonValue
     * 整理时间：2019-09-26 15:15:58
     * */
    public static function JsonModelData($fpResult, $fpRSCode, $fpRECode, $fpInfor, $fpTips, $fpTable, $fpCount, $fpData){
        $fpVersion = self::JsonGetVersion("");
        //Json组字符串
        $vResult = self::JsonStringResult($fpResult, $fpRSCode, $fpRECode);
        $vInfor = self::JsonStringInfor("", $fpInfor, $fpTips, $fpTable, $fpVersion);
        $vData = self::JsonStringData($fpCount, $fpData);
        $vParameter = "";
        $vInterface = "";
        $vRetained = self::JsonStringReserved("");
        //Json结果
        return self::JsonBase($vResult, $vInfor, $vData, $vParameter, $vInterface, $vRetained);
    }
    

    /**
     * Json参数模型方法
     * $fp*：JsonValue
     * 整理时间：2019-09-26 15:29:50
     * */
    public static function JsonModelParameter($fpResult, $fpRSCode, $fpRECode, $fpInfor, $fpTips, $fpParameterArray){
        $fpVersion = self::JsonGetVersion("");
        //Json组字符串
        $vResult = self::JsonStringResult($fpResult, $fpRSCode, $fpRECode);
        $vInfor = self::JsonStringInfor("", $fpInfor, $fpTips, "", $fpVersion);
        $vData = "";
        $vParameter = self::JsonStringParameter($fpParameterArray);
        $vInterface = "";
        $vRetained = self::JsonStringReserved("");
        //Json结果
        return self::JsonBase($vResult, $vInfor, $vData, $vParameter, $vInterface, $vRetained);
    }
    
    
    /**
     * Json参数模型方法
     * $fp*：Interface infor
     * 整理时间：2020-01-30 20:26:54
     * */
    public static function JsonModelInterface($fpInterfaceArray){
        $fpVersion = self::JsonGetVersion("");
        //Json组字符串
        $vResult = self::JsonStringResult(FlyJson::$vResultTrue, FlyCode::$Code_Run_Debug, FlyCode::$Code_Function_Debug);
        $vInfor = self::JsonStringInfor("", "", "", "", $fpVersion);
        $vData = "";
        $vParameter = "";
        $vInterface = self::JsonStringInterface($fpInterfaceArray);
        $vRetained = self::JsonStringReserved("");
        //Json结果
        return self::JsonBase($vResult, $vInfor, $vData, $vParameter, $vInterface, $vRetained);
    }    
    
    
    //==================== Fly Json 字段值 组合区 ====================
    
    /**
     * Json接口参数定义
     * $fpParameter：参数名称
     * $fpDescript：参数描述
     * $fpIsMust：参数是否必填
     * $fpIndex：参数索引
     * $fpConstant：参数常量
     * $fpRegular：参数正则
     * $fpValue：参数值
     * $fpLength：参数值长度
     * $fpLengthSmall：参数值允许最大长度
     * $fpLengthMax：参数值允许最小长度
     * $fpJson：补充参数，用于程序升级的扩展使用
     * 整理时间：2019-09-29 10:10:16
     * */
    public static function ParameterArray($fpParameter,$fpDescript,$fpValue,$fpValueType,$fpIsMust,$fpRemarks,$fpParameterSource,$fpLengthSmall,$fpLengthMax,$fpDefaultValue="",$fpRegular="",$fpRequestValue="",$fpJson=""){
        return array(
            "parameter" => $fpParameter,
            "parameterHump" => GetStringHump($fpParameter),
            "descript" => $fpDescript,
            "value" => $fpValue,
            "valueType" => $fpValueType,
            "isMust" => $fpIsMust,
            "remarks" => $fpRemarks,
            "source" => $fpParameterSource,
            "regular" => $fpRegular,
            "requestValue" => HandleStringFlyHtmlEncode($fpRequestValue),
            "defaultValue" => $fpDefaultValue,
            "lengthSmall" => $fpLengthSmall,
            "lengthMax" => $fpLengthMax,            
        );
    }
    
    /**
     * 设置参数数组值及正则名称
     * 时间：2020-02-03 10:31:37
     * */
    public static function ParameterArraySetValue($fpParameterArray,$fpRequestValue){
        $vValueType = $fpParameterArray["valueType"];
        if(IsNull($vValueType)){
            $fpParameterArray["regular"] = "";
        }else{
            $vValueType = ucwords($vValueType);
            $fpParameterArray["regular"] = "JudgeRegular".$vValueType;
        }
        if(IsNull($fpRequestValue)){
            $fpParameterArray["requestValue"] = "";
        }else{
            $fpParameterArray["requestValue"] = $fpRequestValue;
        }
        return $fpParameterArray;
    }
   
    /**
     * 获取参数数组参数名
     * 时间：2020-02-02 17:22:50
     * */
    public static function GetParameterName($fpParameterArray){
        return $fpParameterArray["parameter"];
    }
           
    /**
     * 获取参数数组参数值
     * 时间：2020-02-03 10:53:49
     * */
    public static function GetParameterValue($fpParameterArray,$fpParameterName){
        //声明参数数组
        $vParameterArray = [];
        //遍历参数
        foreach($fpParameterArray as $vParameterMember){
            if($vParameterMember["parameter"] == $fpParameterName||$vParameterMember["parameterHump"] == $fpParameterName){
                $vParameterArray = $vParameterMember;
            }
        }
        //参数值获取
        $vValueType = $vParameterArray["valueType"];
        $vRequestValue = $vParameterArray["requestValue"];
        if($vValueType == self::$vPTypeRichtext){
            return HandleStringFlyHtmlEncode($vRequestValue);
        }
        //当请求传入值为空时，获取参数默认定义值
        if(IsNull($vRequestValue)){
            $vRequestValue = $vParameterArray["defaultValue"];
        }
        return $vRequestValue;
    }
    
    /**
     * 常用参数数组定义
     * 数据表参数数组
     * 返回值类型：数组
     * 时间：2019-11-24 17:08:37
     * */
    public static function JsonArrayParameterTableName($fpTableName){
        return self::JsonArrayParameter("tablename", "数据表名", self::$vParameterSourceDataBaseTable, self::$vParameterIsMustYes, "0", "", "JudgeRegularTable", $fpTableName, "1", "64");
    }
    
    /**
     * 常用参数数组定义
     * 数据表参数数组
     * 返回值类型：数组
     * 时间：2019-11-24 17:08:37
     * */
    public static function JsonArrayParameterTableFieldName($fpFieldName){
        return self::JsonArrayParameter("fieldname", "数据表字段名", self::$vParameterSourceTableField, self::$vParameterIsMustYes, "0", "", "JudgeRegularField", $fpFieldName, "1", "64");
    }
    
    //==================== Fly Json 字段值 获取区 ====================

    
    /**
     * Json Interface 接口版本号
     * 整理时间：2019-09-29 11:45:35
     * */
    public static function JsonGetVersion($fpJson){
        $vVersion = GetParameter(self::$vParameterVersion,$fpJson);
        if(IsNull($vVersion)){
            return "1.0";
        }
        return $vVersion;
    }
    
    /**
     * debug 接口调试模式
     * 整理时间：2019-11-22 10:40:21
     * */
    public static function JsonGetDebug($fpJson){
        $vInterfaceDebug = GetParameter(self::$vInterfaceDebug,$fpJson);
        return $vInterfaceDebug;
    }
     
    /**
     * Fly Json Parameter Value Type：Fly Json 参数值 数据调试类型
     * 整理时间：2019-09-29 11:45:35
     * */
    public static function JsonGetAccessArray(){
        
        $vRequestArray = array(
            
            "rServerPort" => $_SERVER["SERVER_PORT"],                       //服务器请求端口
            "rServerProtocol" => $_SERVER["SERVER_PROTOCOL"],               //请求协议
            "rServerSoftware" => $_SERVER["SERVER_SOFTWARE"],               //请求信息
            
            "rPhpSelf" => $_SERVER["PHP_SELF"],                             //请求路径
            
            "rDocumentRoot" => $_SERVER["DOCUMENT_ROOT"],                   //项目根目录
            
            "rHttpAccept" => $_SERVER["HTTP_ACCEPT"],                       //Accept信息
            "rHttpAcceptEncoding" => $_SERVER["HTTP_ACCEPT_ENCODING"],      //浏览器编码类型
            "rHttpAcceptLanguage" => $_SERVER["HTTP_ACCEPT_LANGUAGE"],      //浏览器语言
            "rHttpUserAgent" => $_SERVER["HTTP_USER_AGENT"],                //浏览器信息
            "rHttpHost" => $_SERVER["HTTP_HOST"],                           //项目网址
            
            "rRemoteAddr" => $_SERVER["REMOTE_ADDR"],                       //请求IP地址
            "rRequestTime" => $_SERVER["REQUEST_TIME"],                     //请求时间
            "rRequestMethod" => $_SERVER["REQUEST_METHOD"],                 //请求方式
            
            "rCookie" => HandleArrayKeyValue($_COOKIE),
            
        );
        
        return $vRequestArray;
    }
    
    /**
     * 函数:集合数据组合
     * 说明:传入关键字数组和集合中的每个字符串组合成为Json对象字符串
     * 形式:	{"id":"1","onlykey":"kki"}
     * 时间:October 2, 2018 14:21:00
     * @throws Exception
     * */
    public static function JsonKeyArrayList($fieldArray,$commentArray,$list){
    
        if(IsNull($list)||IsNull($fieldArray)){
            return "";
        }
         
        $list = json_decode($list);
         
        $stringArray = $list[0];
         
        $thisJson = "";
        for($i=0;$i<sizeof($fieldArray);$i++){
            $thisJson .= JsonObj(JsonKeyValue("field", $fieldArray[$i]).",".JsonKeyValue("comment", $commentArray[$i]).",".JsonKeyValue("value",GetJsonObjectValue($stringArray, $fieldArray[$i]))).",";
        }
        return substr($thisJson,0,-1);
    }
    
    
    //==================== Fly Json ====================
    
    /**
     * Fly:接口信息
     * 整理时间：2020-01-31 10:10:38
     * */
    public static function JsonFlyInterfaceInfor($fpMethodDescript,$fpParameterArray,$fpJson){
        
        //参数
        $pUserType = GetParameterJson(FlyJson::$vFlySystemUserType,$fpJson);
        $pVersion = GetParameter("version");
        if(IsNull($pVersion)){ $pVersion = "1.0"; }
        $pLine = GetParameter("line");
        $pMethod = GetParameter("method");
        $pFlyParameterHide = GetParameterJson(self::$vFlySystemParameterHide,$fpJson);
        $pFlyFunctionMethodDescript = GetParameterJson(self::$vFlySystemMethodDescript,$fpJson);
        if(!IsNull($pFlyFunctionMethodDescript)){ $fpMethodDescript = $pFlyFunctionMethodDescript; }
        $pFlyParameterHideArray = [];
        if(!IsNull($pFlyParameterHide)){
            $pFlyParameterHideArray = GetArray($pFlyParameterHide, ",");
        }
        
        //路径
        $vPath = $_SERVER['PHP_SELF'];
        //$vPath = str_replace(str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']), "", $vPath);
        
        $fpInterfaceArray = [
            "path" => $vPath,
            "serviceLine" => $pLine,
            "serviceLineDescript" => GetName($pLine),
            "serviceMethod" => $pMethod,
            "serviceMethodDescript" => $fpMethodDescript,
            "server" => PROJECT_CONFIG_SERVER,
        ];
        
        $fpCount = sizeof($fpParameterArray);
        $fpData = "[]";
        if($fpCount>0){
            $fpData = "";
            for($i=0;$i<$fpCount;$i++){
                $vThisJson = "";
                $vThisParameterArray = $fpParameterArray[$i];
                $vThisParameterName = $vThisParameterArray["parameter"];
                $vThisBreakBo = false;
                for($c=0;$c<sizeof($pFlyParameterHideArray);$c++){
                    if($pFlyParameterHideArray[$c]==$vThisParameterName){
                        $vThisBreakBo = true;
                        break;
                    } 
                }
                if($vThisBreakBo){continue;}
                foreach($vThisParameterArray as $key => $value){
                    $vThisJson .= JsonKeyValue($key, $value) . ",";
                }    
                $fpData .= JsonObj(HandleStringDeleteLast($vThisJson)) . ",";
            }
            $fpData = HandleStringDeleteLast($fpData);
            $fpData = JsonArray($fpData);
        }
        
        //组合Json
        $fpVersion = self::JsonGetVersion("");
        //Json组字符串
        $vResult = self::JsonStringResult(FlyJson::$vResultTrue, FlyCode::$Code_Run_Debug, FlyCode::$Code_Function_Debug);
        $vInfor = self::JsonStringInfor("", "接口信息", "调试模式", "", $fpVersion);
        $vData = self::JsonStringData($fpCount, $fpData);
        $vParameter = "";
        $vInterface = self::JsonStringInterface($fpInterfaceArray);
        $vRetained = self::JsonStringReserved("");
        //Json结果
        return self::JsonBase($vResult, $vInfor, $vData, $vParameter, $vInterface, $vRetained);
    }

    
    /**
     * Fly:接口信息
     * 整理时间：2020-01-31 10:10:38
     * */
    public static function JsonFlyArrayMerge($fpParameterArray,$fpCustomParameterArray,$fpJson){
        //必填参数
        $pFlyParameterMust = GetParameterJson(self::$vFlySystemParameterMust,$fpJson);
        $pFlyParameterMustArray = explode(",",$pFlyParameterMust);
        //忽略参数
        $pFlyParameterIgnore = GetParameterJson(self::$vFlySystemParameterIgnore,$fpJson);
        $pFlyParameterIgnoreArray = explode(",",$pFlyParameterIgnore);
        //要返回的数组定义
        $vResultArray = [];
        //判断自定义数组是否存在
        if(IsNull($fpCustomParameterArray)){
            $vResultArray = $fpParameterArray;
        }else{
            //自定义参数名数组
            $vCustomParameterNameArray = [];
            //遍历自定义数组，将参数名存入变量数组参数名数组中
            foreach($fpCustomParameterArray as $vCustomParameterMember){
                $vParameterName = FlyJson::GetParameterName($vCustomParameterMember);
                array_push($vCustomParameterNameArray, $vParameterName);
            }
            //遍历原有参数数组，将自定义参数数组中不存在的成员加入自定义数组中
            foreach($fpParameterArray as $vParameterMember){
                $vParameterName = FlyJson::GetParameterName($vParameterMember);
                $vJudgeBo = false;
                for($i=0;$i<sizeof($vCustomParameterNameArray);$i++){
                    if($vCustomParameterNameArray[$i] == $vParameterName){
                        $vJudgeBo = true;
                        break;
                    }
                }
                if(!$vJudgeBo){
                    array_push($fpCustomParameterArray, $vParameterMember);
                }
            }
            //将处理好的数组赋值给声明的结果数组
            $vResultArray = $vCustomParameterNameArray;
        }
        //参数是否必填判断
        $vResultHandleArray = [];
        for($c=0;$c<sizeof($vResultArray);$c++){
            $vParameterMember = $vResultArray[$c];
            $vParameterName = FlyJson::GetParameterName($vParameterMember);
            //跳过才循环
            $vContinue = false;
            //判断是否是可忽略参数
            if(!IsNull($pFlyParameterIgnore)){
                for($i=0;$i<sizeof($pFlyParameterIgnoreArray);$i++){
                    $vField = $pFlyParameterIgnoreArray[$i];
                    if($vParameterName == $vField){
                        $vContinue = true;
                        break;
                    }
                }
            }
            //如果为可忽略参数，则将该参数跳过
            if($vContinue){continue;}
            //将可空参数该为必填参数
            for($i=0;$i<sizeof($pFlyParameterMustArray);$i++){
                $vField = $pFlyParameterMustArray[$i];
                if($vParameterName == $vField){
                    $vParameterMember["isMust"] = "yes";
                    break;
                }
            }
            //将处理好的成员加入到可用数组参数中
            array_push($vResultHandleArray, $vParameterMember);
        }
        //返回处理后的参数数组
        return $vResultHandleArray;
    }
   
    /**
     * 校验接口输出类型类接口信息
     * 时间：2020-02-03 20:08:46
     * */
    public static function JudgeInterfaceDebugInfor(){
        return strtoupper(INTERFACE_DEBUG_TYPE)=="INTERFACE";
    }
    
    /**
     * 校验接口输出类型类参数信息
     * 时间：2020-02-03 21:17:27
     * */
    public static function JudgeInterfaceDebugParameter(){
        return strtoupper(INTERFACE_DEBUG_TYPE)=="PARAMETER";
    }
    
    /**
     * 校验接口输出类型SQL
     * 时间：2020-02-03 21:17:27
     * */
    public static function JudgeInterfaceDebugSql(){
        return strtoupper(INTERFACE_DEBUG_TYPE)=="SQL";
    }
    
    /**
     * 判断要调试输出的参数
     * 时间：2020-02-03 21:23:32
     * */
    public static function JudgeParameterDebug($fpParameter){
        return strtolower(INTERFACE_DEBUG_PARAMETER)==strtolower($fpParameter);
    }
    
    /**
     * 判断获取到的参数值
     * 时间：2020-02-16 11:13:23
     * */
    public static function JudgeParameterBoolean($fpParameter){
        $fpParameter = strtolower($fpParameter);
        return $fpParameter=="false" || $fpParameter=="true";
    }

    
}



/**
 * Json模板：分页数据模板（有数据）
 * 整理时间：2019-09-26 16:06:25
 * */
function JsonInforData($fpInfor, $fpTips, $fpTable, $fpCount, $fpData){
    if($fpCount=="0"||IsNull($fpCount)){
        return FlyJson::JsonModelData(FlyJson::$vResultTrue, FlyCode::$Code_Run_Success, FlyCode::$Code_Select_Success_Null, $fpInfor, $fpTips, $fpTable, "0", "[]");
    }else{
        return FlyJson::JsonModelData(FlyJson::$vResultTrue, FlyCode::$Code_Run_Success, FlyCode::$Code_Select_Success_Have, $fpInfor, $fpTips, $fpTable, $fpCount, $fpData);
    }
}

/**
 * Json模板：正确信息
 * 整理时间：2019-09-26 16:21:05
 * */
function JsonInforTrue($fpInfor, $fpTips, $fpValue="", $fpTable="", $fpEcode=""){
    if(IsNull($fpEcode)){$fpEcode = FlyCode::$Code_Function_Success;}
    return FlyJson::JsonModelInfor(FlyJson::$vResultTrue, FlyCode::$Code_Run_Success, $fpEcode, $fpValue, $fpInfor, $fpTips, $fpTable);
}

/**
 * Json模板：错误信息
 * 整理时间：2019-09-26 16:24:39
 * */
function JsonInforFalse($fpInfor, $fpTips, $fpRecode="", $fpTable=""){
    if(IsNull($fpRecode)){
        $fpRecode = FlyCode::$Code_Function_Fail;
    }
    return FlyJson::JsonModelInfor(FlyJson::$vResultFalse, FlyCode::$Code_Run_Fail, $fpRecode, "", $fpInfor, $fpTips, $fpTable);
}

/**
 * Json模板：异常信息
 * 整理时间：2019-11-22 15:12:59
 * */
function JsonInforException($fpInfor, $fpTips, $fpTable=""){
    return FlyJson::JsonModelInfor(FlyJson::$vResultFalse, FlyCode::$Code_Run_Fail, FlyCode::$Code_Run_Exception, "", $fpInfor, $fpTips, $fpTable);
}

/**
 * Json模板：参数信息：异常信息
 * 整理时间：2019-11-22 15:12:59
 * */
function JsonParameterException($fpParameterArray,$fpRequestValue){
    //参数获取
    $vValueType = $fpParameterArray["valueType"];
    //设置正则文本
    if(IsNull($vValueType)){
        $fpParameterArray["regular"] = "";
    }else{
        $vValueType = ucwords($vValueType);
        $fpParameterArray["regular"] = "JudgeRegular".$vValueType;
    }
    //设置请求值
    if(IsNull($fpRequestValue)){
        $fpParameterArray["requestValue"] = "";
    }else{
        $fpParameterArray["requestValue"] = HandleStringFlyHtmlEncode($fpRequestValue);
    }
    return FlyJson::JsonModelParameter(FlyJson::$vResultFalse, FlyCode::$Code_Run_Fail, FlyCode::$Code_Parameter_Regular, "参数不符合规则", "参数校验", $fpParameterArray);
}


/**
 * Json模板：参数信息：错误信息
 * 整理时间：2019-11-22 15:12:59
 * */
function JsonParameterWrong($fpParameter,$fpRequestValue){
    $json  = "";
    $vTipsInfor = "参数不得为空";
    if(!IsNull($fpRequestValue)){ $vTipsInfor = "参数不符合规则"; }
    $json .= JsonKeyValue("result", "false").",";
    $json .= JsonKeyValue("code", "").",";
    $json .= JsonKeyValue("infor", "参数错误").",";
    $json .= JsonKeyValue("language", "PHP").",";
    $json .= JsonKeyValue("tips", $vTipsInfor).",";
    $json .= JsonKeyValue("parameter", strtolower($fpParameter));
    return JsonObj($json);
}

/**
 * Json模板：参数信息：调试信息
 * 整理时间：2020-02-04 10:00:52
 * */
function JsonParameterDebug($fpParameterArray,$fpRequestValue){
    //参数获取
    $vValueType = $fpParameterArray["valueType"];
    //设置正则文本
    if(IsNull($vValueType)){
        $fpParameterArray["regular"] = "";
    }else{
        $vValueType = ucwords($vValueType);
        $fpParameterArray["regular"] = "JudgeRegular".$vValueType;
    }
    //设置请求值
    if(IsNull($fpRequestValue)){
        $fpParameterArray["requestValue"] = "";
    }else{
        $fpParameterArray["requestValue"] = HandleStringFlyHtmlEncode($fpRequestValue);
    }
    return FlyJson::JsonModelParameter(FlyJson::$vResultFalse, FlyCode::$Code_Run_Debug, FlyCode::$Code_Parameter_Debug, "参数信息", "调试模式", $fpParameterArray);
}


/**
 * 参数校验
 * $fpParameterArray：参数数组
 * 整理时间：2019-09-27 14:49:27
 * */
function JudgeParameter($fpParameterArray,$fpRequestValue){

    //参数获取
    $vValueType = $fpParameterArray["valueType"];
    $vLengthSmall = $fpParameterArray["lengthSmall"];
    $vLengthMax = $fpParameterArray["lengthMax"];
    $vIsMust = $fpParameterArray["isMust"];

    //类型转化
    $vLengthSmall = intval($vLengthSmall);
    $vLengthMax = intval($vLengthMax);
    if(IsNull($vLengthSmall)){$vLengthSmall = 1;}
    if(IsNull($vLengthMax)){$vLengthMax = 1;}
    if(IsNull($vIsMust)){ $vIsMust = "no"; }
    $vIsMust = strtolower($vIsMust);

    //判断是否为空（非必填）
    if(IsNull($fpRequestValue)&&$vIsMust=="no"){
        return true;
    }
    
    //判断是否为空（必填且为空）
    if(IsNull($fpRequestValue)&&$vIsMust=="yes"){
        return false;
    }
    
    //参数值长度判断
    $vValueLength = mb_strlen($fpRequestValue);
    if($vValueLength<$vLengthSmall||$vValueLength>$vLengthMax){
        return false;
    }

    //类型判断
    if($vValueType==FlyJson::$vPTypeId){
        return JudgeRegularId($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeImage){
        return JudgeRegularImage($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeUrl){
        return JudgeRegularUrl($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeDate){
        return JudgeRegularDate($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeInt){
        return JudgeRegularInt($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeNumber){
        return JudgeRegularNumber($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeNumberLetter){
        return JudgeRegularNumberLetter($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypePhoneCode){
        return JudgeRegularPhoneCode($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypePassword){
        return JudgeRegularPassword($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeDouble){
        return JudgeRegularDouble($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeWechatOpenId){
        return JudgeRegularWechatOpenId($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeTitle){
        return JudgeRegularTitle($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeTag){
        return JudgeRegularTag($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeRichtext){
        return true;
    }else if($vValueType==FlyJson::$vPTypeFont){
        return JudgeRegularFont($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeOrder){
        return JudgeRegularOrder($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeMoney){
        return JudgeRegularMoney($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypePhone){
        return JudgeRegularPhone($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeIdcard){
        return JudgeRegularIdcard($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeTable){
        return JudgeRegularTable($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeField){
        return JudgeRegularField($fpRequestValue);
    }else if($vValueType==FlyJson::$vPTypeState){
        return JudgeRegularState($fpRequestValue);
    }
    
    return false;
     
}


/** 在Fly标准的Json中判断修改字段值 */
function JudgeFlyJsonAddUpdateField($jsonArray,$fpUpdateField){
    //updateField
    $updateField = @$jsonArray['update_field'];
    if(IsNull($updateField)){
        return JsonInforFalse($fpUpdateField, "必须提交任意一项修改字段");        
    }
    //updateValue
    $updateValue = @$jsonArray['update_value'];
    if(IsNull($updateValue)){
        return JsonInforFalse($fpUpdateField, "修改字段值不得为空");
    }
    //Pass
    return JsonInforTrue("审核通过", "修改字段校验");
}


/**
 * 判断Json结果是否是正确
 * $fpJson：Json字符串
 * 整理时间：2019-09-24 18:54:17
 * */
function JudgeJsonTrue($fpJson){
    return JudgeJsonValue($fpJson,"result","true");
}

/**
 * 判断中间语言是否是错误结果
 * 创建时间：December 1,2018 11:21
 * 说明：判断M层返回的中间语言Json是否是错误的结果
 * 检测：逻辑
 * 检测时间：December 1,2018 11:22
 * */
function JudgeJsonFalse($json){
    return !JudgeJsonValue($json,"result","true");
}

/**
 * 判断中间语言是否是错误结果
 * 创建时间：December 1,2018 11:21
 * 说明：判断M层返回的中间语言Json是否是错误的结果
 * 检测：逻辑
 * 检测时间：December 1,2018 11:22
 * */
function JudgeJsonFalseString($json){
    return JudgeJsonValue($json,"result","false");
}


/**
 * 判断中间语言数据数量是否为0
 * 创建时间：December 1,2018 11:23
 * 说明：判断M层返回的中间语言Json数据总数是否为0
 * 检测：逻辑
 * 检测时间：December 1,2018 11:24
 * */
function JudgeJsonCountZero($json){
    return JudgeJsonValue($json,"result","true")&&JudgeJsonValue($json,"count","0");
}

/**
 * 系统码判断
 * 2020-02-11 19:52:19
 * */
function JudgeJsonEcode($json,$fpEcode){
    return GetJsonValue($json, "ecode")==$fpEcode;
}

/**
 * 系统码判断:无记录
 * 2020-02-11 19:52:35
 * */
function JudgeJsonEcodeDataNull($json){
    return GetJsonValue($json, "ecode")==FlyCode::$Code_Select_Success_Null;
}

/**
 * 系统码判断:无记录
 * 2020-02-11 19:52:44
 * */
function JudgeJsonEcodeDataHave($json){
    return GetJsonValue($json, "ecode")==FlyCode::$Code_Select_Success_Have;
}

//-------------------- Fly 相关 Json 数据处理 --------------------

/**
 * 在Fly标准的Json字符串下进行处理
 * 说明：只获取第一层字段相关数据
 * 时间：2019-11-22 17:29:44
 * */
function JsonHandleFlyFieldData($jsonString){
    $fieldName = "data";
    if(IsNull($jsonString)){return "";}
    $vJsonFieldValue = GetJsonValue($jsonString, $fieldName);
    if(IsNull($vJsonFieldValue)){return "";}
    $fieldName = "\"{$fieldName}\":";
    $vFieldSub = strpos($jsonString,$fieldName) + strlen($fieldName);
    $vFieldCodeSub = strrpos($jsonString,",\"code\":\"");
    if($vFieldCodeSub>-1){
        $jsonString = substr($jsonString,$vFieldSub,$vFieldCodeSub-$vFieldSub);
    }else{
        $jsonString = substr($jsonString,$vFieldSub);
    }
    return $jsonString;
}


/**
 * 在Fly标准的Json字符串下进行处理
 * 说明：接口调用过程文件信息
 * 时间：2019-11-23 11:38:50
 * */
function JsonHandleFlyFileInfor($fpJsonString, $fpFile, $fpFunction, $fpLine){
    $vFindString = '"'.FlyJson::$kFlyFileInfor.'":[';
    $vFileSub = strrpos($fpJsonString,$vFindString);
    if($vFileSub > 0){
        $fpFile = GetStringFileName($fpFile);
        $vJsonString = JsonObj(JsonKeyValue(FlyJson::$kFile, $fpFile).",".JsonKeyValue(FlyJson::$kFunction, $fpFunction).",".JsonKeyValue(FlyJson::$kLine, $fpLine)).",";
        $fpJsonString = substr_replace($fpJsonString, $vJsonString, $vFileSub+strlen($vFindString), 0);
    }
    return $fpJsonString;
}


/**
 * 在Fly标准的Json字符串下进行处理
 * 说明：接口扩展信息，可通过对该字段的替换或插入实现信息的新增
 * 时间：2019-11-23 12:17:46
 * */
function JsonHandleFlyInforExtend($fpJsonString, $fpJsonExtend){
    $fpJsonExtend = preg_replace('/^\s+/','',$fpJsonExtend);
    if(!preg_match('/^,/',$fpJsonExtend)){
        $fpJsonExtend = ",{$fpJsonExtend}";
    }
    $vFindString = '"'.FlyJson::$kFlyInforExtend.'":""';
    $vStringSub = strrpos($fpJsonString,$vFindString);
    if($vStringSub > 0){
        $fpJsonString = substr_replace($fpJsonString, $fpJsonExtend, $vStringSub+strlen($vFindString), 0);
    }
    return $fpJsonString;
}

/**
 * 在Fly标准的Json字符串下进行处理
 * 说明：接口扩展信息，可通过对该字段的替换或插入实现信息的新增
 * 时间：2019-11-23 12:17:46
 * */
function JsonHandleExtend($fpJsonString, $fpJsonField, $fpJsonExtend){
    $vFindString = '"'.$fpJsonField.'":"",';
    $fpJsonExtend = $fpJsonExtend.",";
    $vStringSub = strrpos($fpJsonString,$vFindString);
    if($vStringSub > 0){
        $fpJsonString = substr_replace($fpJsonString, $fpJsonExtend, $vStringSub+strlen($vFindString), 0);
    }
    return $fpJsonString;
}
 


//--- Fly Json Array ---

/** 在Fly标准的Json中添加一个添加字段值 */
function HandleFlyJsonAddInsertField($jsonArray,$fieldName,$fieldValue){
    if(IsNull($fieldValue)||IsNull($fieldName)){return $jsonArray;}
    $insertField = @$jsonArray['insert_field'];
    if(IsNull($insertField)){
        $insertField = $fieldName;
    }else{
        $insertFieldArray = GetArray($insertField, ",");
        for($i=0;$i<sizeof($insertFieldArray);$i++){
            if(strtolower($insertFieldArray[$i])==strtolower($fieldName)){
                return $jsonArray;
            }
        }
        $insertField.= ",".$fieldName;
    }
    $jsonArray['insert_field'] = $insertField;
    //insertValue
    $fieldValue = HandleStringReplace($fieldValue,',','，');
    $jsonArray[$fieldName] = $fieldValue;
    return $jsonArray;
}

/** 在Fly标准的Json中添加一个条件字段值 */
function HandleFlyJsonAddWhereField($jsonArray,$whereFieldName,$whereFieldValue){
    if(IsNull($whereFieldName)||IsNull($whereFieldValue)){return $jsonArray;}
    //whereField
    $whereField = @$jsonArray['where_field'];
    if(IsNull($whereField)){
        $whereField = $whereFieldName;
    }else{
        $whereFieldArray = GetArray($whereField, ",");
        for($i=0;$i<sizeof($whereFieldArray);$i++){
            if(strtolower($whereFieldArray[$i])==strtolower($whereFieldName)){
                return $jsonArray;
            }
        }
        $whereField.= ",".$whereFieldName;
    }
    $jsonArray['where_field'] = $whereField;
    //whereValue
    $whereFieldValue = HandleStringReplace($whereFieldValue,',','，');
    $whereValue = @$jsonArray['where_value'];
    if(IsNull($whereValue)){
        $whereValue = $whereFieldValue;
    }else{
        $whereValue.= ",".$whereFieldValue;
    }
    $jsonArray['where_value'] = $whereValue;
    return $jsonArray;
}


/** 在Fly标准的Json中判断Where的值 */
function HandleFlyJsonJudgeWhereField($jsonArray,$fpValueArray){
    //逻辑
    $whereField = @$jsonArray['where_field'];
    $whereValue = @$jsonArray['where_value'];
    if(IsNull($whereField)){ return $jsonArray; }
    //whereValue有值和无值两种情况
    if(IsNull($whereValue)){ 
        $whereFieldArray = explode(",", $whereField);
        $vStringField = "";
        $vStringValue = "";
        for($i=0;$i<sizeof($whereFieldArray);$i++){
            $vThisField = $whereFieldArray[$i];
            $vThisValue = FlyJson::GetParameterValue($fpValueArray,$vThisField);
            if(!IsNull($vThisValue)&&!IsNone($vThisValue)){
                $vStringField .= $vThisField . ",";
                $vStringValue .= $vThisValue . ",";
            }
        }
        if(!IsNull($vStringField)){
            $vStringField = HandleStringDeleteLast($vStringField);
            $vStringValue = HandleStringDeleteLast($vStringValue);
        }
        $jsonArray["where_field"] = $vStringField;
        $jsonArray["where_value"] = $vStringValue;
        return $jsonArray;
    }else{
        if(IsNull($whereValue)){ return $jsonArray; }
        $whereFieldArray = explode(",", $whereField);
        $whereValueArray = explode(",", $whereValue);
        if(sizeof($whereFieldArray)!=sizeof($whereValueArray)){ return $jsonArray; }
        $vStringValue = "";
        for($i=0;$i<sizeof($whereValueArray);$i++){
            $vThisField = $whereFieldArray[$i];
            $vThisValue = $whereValueArray[$i];
            if($vThisValue == "?"){
                $vStringValue .= FlyJson::GetParameterValue($fpValueArray,$vThisField);
            }else{
                $vStringValue .= $vThisValue;
            }
            $vStringValue .= ",";
        }
        if(!IsNull($vStringValue)){
            $vStringValue = HandleStringDeleteLast($vStringValue);
        }
        $jsonArray["where_value"] = $vStringValue;
        return $jsonArray;
    }
}

/** 在Fly标准的Json中添加一个修改字段值
 * @param $jsonArray
 * @param $updateFieldName
 * @param $updateFieldValue
 * @param bool $allowEmpty 是否允许
 * @return mixed
 */
function HandleFlyJsonAddUpdateField($jsonArray,$updateFieldName,$updateFieldValue,$fpJudgeField=""){
    if(IsNull($updateFieldName)){return $jsonArray;}
    if(IsNull($updateFieldValue)||IsNone($updateFieldValue)){return $jsonArray;};
    //判断传入的字段是否在规定的字段当中
    $vJudgeBo = false;
    if(IsNull($fpJudgeField)){
        $vJudgeBo = true;
    }else{
        $vJudgeFieldArray = explode(",", $fpJudgeField);
        for($i=0;$i<sizeof($vJudgeFieldArray);$i++){
            if($vJudgeFieldArray[$i]==$updateFieldName){
                $vJudgeBo = true;
                break;
            }
        }
    }
    if(!$vJudgeBo){return $jsonArray;}
    //updateField
    $updateField = @$jsonArray['update_field'];
    if(IsNull($updateField)){
        $updateField = $updateFieldName;
    }else{
        $updateFieldArray = GetArray($updateField, ",");
        for($i=0;$i<sizeof($updateFieldArray);$i++){
            if(strtolower($updateFieldArray[$i])==strtolower($updateFieldName)){
                return $jsonArray;
            }
        }
        $updateField.= ",".$updateFieldName;
    }
    $jsonArray['update_field'] = $updateField;
    //updateValue
    $updateFieldValue = HandleStringReplace($updateFieldValue,',','，');
    $updateValue = @$jsonArray['update_value'];
    if(IsNull($updateValue)){
        $updateValue = $updateFieldValue;
    }else{
        $updateValue.= ",".$updateFieldValue;
    }
    $jsonArray['update_value'] = $updateValue;
    return $jsonArray;
}


/** 在Fly标准的Json中忽略添加字段字段值 */
function JudgeFlyJsonIgnoreInsertField($jsonArray,$fpIgnoreField){
    if(IsNull($fpIgnoreField)){
        return $jsonArray;
    }
    //insertField
    $insertField = @$jsonArray['insert_field'];
    if(IsNull($insertField)){
        return JsonInforFalse($insertField, "必须提交记录添加字段");
    }
    //insertField String
    $insertFieldArray = GetArray($insertField, ",");
    $ignoreFieldArray = GetArray($fpIgnoreField, ",");
    $vInsertFieldString = "";
    for($i=0;$i<sizeof($insertFieldArray);$i++){
        $vJudgeBo = true;
        $vMember = $insertFieldArray[$i];
        $vInsertFieldLower = strtolower($vMember);
        for($c=0;$c<sizeof($ignoreFieldArray);$c++){
            $vIgnoreField = str_replace("_","",$ignoreFieldArray[$c]);
            if($vInsertFieldLower==$vIgnoreField){
                $vJudgeBo = false;
                break;
            }
        }
        if($vJudgeBo){
            $vInsertFieldString .= $vMember . ",";
        }
    }
    if(!IsNull($vInsertFieldString)){
        $vInsertFieldString = HandleStringDeleteLast($vInsertFieldString);
    }
    $jsonArray['insert_field'] = $vInsertFieldString;
    return $jsonArray;
}

/** 
 * 重新组合修 改字段update_field 和 修改值update_value 去除为空和为none的值
 */
function HandleFlyJsonUpdateField($jsonArray,$vParameterArray=""){
    if(IsNull($vParameterArray)){
        //updateField
        $updateField = @$jsonArray['update_field'];
        $updateValue = @$jsonArray['update_value'];
        if(IsNull($updateField)||IsNull($updateValue)){
            return $jsonArray;
        }
        $updateFieldArray = GetArray($updateField, ",");
        $updateValueArray = GetArray($updateValue, ",");
        if(sizeof($updateFieldArray)!=sizeof($updateValueArray)){
            return $jsonArray;
        }
        //判断值和字段
        $vUpdateField = "";
        $vUpdateValue = "";
        for($i=0;$i<sizeof($updateFieldArray);$i++){
            if(!IsNull($updateValueArray[$i])&&!IsNone($updateValueArray[$i])){
                $vUpdateField .= $updateFieldArray[$i].",";
                $vUpdateValue .= $updateValueArray[$i].",";
            }
        }
        $jsonArray['update_field'] = HandleStringDeleteLast($vUpdateField);
        $jsonArray['update_value'] = HandleStringDeleteLast($vUpdateValue);
        return $jsonArray;
    }else{
        //updateField
        $updateField = @$jsonArray['update_field'];
        if(IsNull($updateField)){
            return $jsonArray;
        }
        $updateFieldArray = GetArray($updateField, ",");
        $vStringField = "";
        $vStringValue = "";
        for($i=0;$i<sizeof($updateFieldArray);$i++){
            $vThisField = $updateFieldArray[$i];
            $vThisValue = FlyJson::GetParameterValue($vParameterArray,$vThisField);
            if(!(IsNull($vThisValue)||IsNone($vThisValue))){
                $vStringField .= $vThisField.",";
                $vStringValue .= $vThisValue.",";
            }
        }
        if(!IsNull($vStringField)){
            $vStringField = HandleStringDeleteLast($vStringField);
            $vStringValue = HandleStringDeleteLast($vStringValue);
        }
        $jsonArray["update_field"] = $vStringField;
        $jsonArray["update_value"] = $vStringValue;
        return $jsonArray;
    }
    
}

/** Json数据信息*/
function JsonFlyLineArray($dataArray,$line,$lineDescript,$method,$methodDescript){
    $json  = "";
    $json .= JsonKeyValue("result", "true").",";
    $json .= JsonKeyValue("code", "").",";
    $json .= JsonKeyValue("fcode", FlyCode::$Code_Run_Success).",";
    $json .= JsonKeyValue("count", GetArraySize($dataArray)).",";
    $json .= JsonKeyValue("language", "PHP").",";
    $json .= JsonKeyValue("line", $line).",";
    $json .= JsonKeyValue("lineDescript", $lineDescript).",";
    $json .= JsonKeyValue("method", $method).",";
    $json .= JsonKeyValue("methodDescript", $methodDescript).",";
    $json .= JsonKeyArray("data", JsonHandleArrayKeyValue($dataArray));
    return JsonObj($json);
}


/** 获取debug */
function GetParameterDebug($fpJson){
    $sqlDebug = GetParameterJson("sql_debug",$fpJson);
    if(strtolower($sqlDebug)=="true"||$sqlDebug=="1"){return true;}
    //request:fly_debug_type=sql?true:false
    return FlyJson::JudgeInterfaceDebugSql()?true:false;
}

//-------------------- Json 保留模板（1.0版本历史保留函数） --------------------


/**
 * 函数:基础数据模板
 * 版本：1.0版本历史保留函数
 * 说明:查询数据返回模板，用于对数据进行查询，并返回标准json数据格式
 * 时间:September 30, 2018 14:37:00
 * */
function JsonModelDataBase($result,$code,$fcode,$msg,$version,$count,$data,$infor,$tips){
    $jsonString = "";
    $jsonString = JsonKeyValue("result",$result);
    $jsonString .= "," . JsonKeyValue("code",$code);
    $jsonString .= "," . JsonKeyValue("fcode",$fcode);
    $jsonString .= "," . JsonKeyValue("msg",$msg);
    if(!IsNull($version)){ $jsonString .= "," . JsonKeyValue("version",$version); }
    if(!IsNull($count)){ $jsonString .= "," . JsonKeyValue("count",$count); }
    if(!IsNull($data)){ $jsonString .= "," . JsonKeyString("data",$data); }
    if(!IsNull($infor)){ $jsonString .= "," . JsonKeyValue("infor",$infor); }
    if(!IsNull($tips)){ $jsonString .= "," . JsonKeyValue("tips",$tips); }
    $jsonString .= "," . JsonKeyValue(FlyJson::$kFlyInforExtend, "");
    return JsonObj($jsonString);
}


/**
 * 函数:数据记录为空
 * 版本：1.0版本历史保留函数
 * 说明:查询数据为空，返回为空提示的Json字符串
 * 时间:October 13, 2018 18:55:00
 * */
function JsonModelDataNull($infor,$tips){
    return JsonModelDataBase("true", "", FlyCode::$Code_Select_Success_Null, "没有记录", GetId("R"), "0", "", $infor, $tips);
}

/**
 * 函数:数据有记录
 * 版本：1.0版本历史保留函数
 * 说明:查询数据有记录，返回Json字符串
 * 时间:October 13, 2018 18:55:00
 * */
function JsonModelSelectDataHave($infor,$tips,$count,$data){
    if(IsNull($count)){$count = "0";}
    if(IsNull($data)){$data = "[]";}
    return JsonModelDataBase("true", "", FlyCode::$Code_Select_Success_Have, "有记录", GetId("R"), $count, $data, $infor, $tips);
}

/** Json参数提示(历史函数：1.0保留函数)*/
function JsonModelParameterException($fpParameterName,$fpParameter,$fpLength,$fpInfor,$fpLine){
    $vParameterTips = $fpInfor;
    if(IsNull($fpParameter)){ $vParameterTips = "参数值不得为空"; }
    if($fpLength>0&&mb_strlen($fpParameter)>$fpLength){ $vParameterTips = "参数值超过限制长度"; }
    //Json组合
    $json  = "";
    $json .= JsonKeyValue("result", "false").",";
    $json .= JsonKeyValue("code", "").",";
    $json .= JsonKeyValue("language", "PHP").",";
    $json .= JsonKeyValue("parameter", $fpParameterName).",";
    $json .= JsonKeyValue("infor", $vParameterTips).",";
    $json .= JsonKeyValue("tips", "").",";
    $json .= JsonKeyValue("line", $fpLine);
    return JsonObj($json);
}


/** Json提示信息及执行结果(历史函数：1.0保留函数)*/
function JsonModelInsert($result,$infor,$tips,$table){
    $json  = "";
    $json .= JsonKeyValue("result", $result).",";
    $json .= JsonKeyValue("code", "").",";
    $json .= JsonKeyValue("infor", $infor).",";
    $json .= JsonKeyValue("tips", $tips).",";
    $json .= JsonKeyValue("table", $table);
    return JsonObj($json);
}

/** Json参数提示(历史函数：1.0保留函数)*/
function JsonModelParameterNull($parameter){
    $json  = "";
    $json .= JsonKeyValue("result", "false").",";
    $json .= JsonKeyValue("code", "").",";
    $json .= JsonKeyValue("infor", "参数缺失").",";
    $json .= JsonKeyValue("language", "PHP").",";
    $json .= JsonKeyValue("tips", "请用none或-1表示空值").",";
    $json .= JsonKeyValue("parameter", strtolower($parameter));
    return JsonObj($json);
}

/** Json参数提示(历史函数：1.0保留函数)*/
function JsonModelParameterRegular($parameter){
    $json  = "";
    $json .= JsonKeyValue("result", "false").",";
    $json .= JsonKeyValue("code", "").",";
    $json .= JsonKeyValue("infor", "参数不符合规则").",";
    $json .= JsonKeyValue("language", "PHP").",";
    $json .= JsonKeyValue("tips", "请用none或-1表示空值").",";
    $json .= JsonKeyValue("parameter", strtolower($parameter));
    return JsonObj($json);
}

/** Json参数提示(历史函数：1.0保留函数)*/
function JsonModelParameterLength($parameter,$fpParameterLength,$fpFieldLength){
    $json  = "";
    $json .= JsonKeyValue("result", "false").",";
    $json .= JsonKeyValue("code", "").",";
    $json .= JsonKeyValue("infor", "参数值超出字段长度").",";
    $json .= JsonKeyValue("language", "PHP").",";
    $json .= JsonKeyValue("tips", "{$fpParameterLength}>{$fpFieldLength}").",";
    $json .= JsonKeyValue("parameter", strtolower($parameter));
    return JsonObj($json);
}

/** Json数据信息(历史函数：1.0保留函数)*/
function JsonModelDataString($data,$count,$infor="",$fpResult=""){
    $json  = "";
    $json .= JsonKeyValue("result", IsNull($fpResult)?"true":$fpResult).",";
    $json .= JsonKeyValue("code", "").",";
    $json .= JsonKeyValue("fcode", FlyCode::$Code_Run_Success).",";
    $json .= JsonKeyValue("infor", $infor).",";
    $json .= JsonKeyValue("version", GetId("R")).",";
    $json .= JsonKeyValue("count", $count).",";
    $json .= JsonKeyValue("language", "PHP").",";
    $json .= JsonKeyString("data", $data);
    return JsonObj($json);
}

/** Json数据信息(历史函数：1.0保留函数)*/
function JsonModelDataArray($dataArray,$count=""){
    $json  = "";
    $json .= JsonKeyValue("result", "true").",";
    $json .= JsonKeyValue("code", "").",";
    $json .= JsonKeyValue("fcode", FlyCode::$Code_Run_Success).",";
    $json .= JsonKeyValue("count", IsNull($count)?GetArraySize($dataArray):$count).",";
    $json .= JsonKeyValue("language", "PHP").",";
    $json .= JsonKeyArray("data", JsonHandleArrayKeyValue($dataArray));
    return JsonObj($json);
}

/** Json参数提示(历史函数：1.0保留函数)*/
function JsonModelParameterInfor($fpParameter,$fpInfor,$fpFieldLength=0){
    $json  = "";
    $json .= JsonKeyValue("result", "false").",";
    $json .= JsonKeyValue("code", "").",";
    $json .= JsonKeyValue("infor", "参数缺失或值不符合规则").",";
    $json .= JsonKeyValue("parameter", strtolower($fpParameter)).",";
    $json .= JsonKeyValue("tips", "请用none或-1表示空值").",";
    $json .= JsonKeyValue("rule", $fpInfor).",";
    if($fpFieldLength!="0"){
        $json .= JsonKeyValue("fieldLength", $fpFieldLength).",";
    }
    $json .= JsonKeyValue("language", "PHP");
    return JsonObj($json);
}


