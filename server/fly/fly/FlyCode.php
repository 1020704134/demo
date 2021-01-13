<?php

class FlyCode{
    
    
    // ----- 格式:内部系统码 ----- //
    
    //格式常量：时间格式常量
    public static $Format_Date_Y = "Y";
    public static $Format_Date_m = "m";
    public static $Format_Date_d = "d";
    public static $Format_Date_Ym = "Y-m";
    public static $Format_Date_Ymd = "Y-m-d";
    public static $Format_Date_YmdHis = "Y-m-d H:i:s";
    public static $Format_Date_His = "H:i:s";
    
    
    //----- Fly系统码:通用系统码/Json:fcode -----
    
    //执行成功 10000
    public static $Code_Run_Success = "100000";
    public static $Code_Run_Success_Msg = "执行成功";
    //执行失败
    public static $Code_Run_Fail = "400001";
    public static $Code_Run_Fail_Msg = "执行失败";
    //执行异常
    public static $Code_Run_Exception = "400002";
    public static $Code_Run_Exception_Msg = "执行异常";
    //执行调试
    public static $Code_Run_Debug = "400003";
    public static $Code_Run_Debug_Msg = "执行调试";
    
    
    //----- Fly执行码:通用系统码/Json:ecode:Execute Code -----
    
    //添加成功
    public static $Code_Insert_Success = "00001";
    public static $Code_Insert_Success_Msg = "添加成功";
    //添加失败
    public static $Code_Insert_Fail = "00002";
    public static $Code_Insert_Fail_Msg = "添加失败";
    //添加语句异常
    public static $Code_Insert_Exception = "00003";
    public static $Code_Insert_Exception_Msg = "添加异常";
    //记录已存在
    public static $Code_Insert_Already_Exist = "00004";
    public static $Code_Insert_Already_Exist_Msg = "记录已存在";
    
    //修改成功
    public static $Code_Update_Success = "00011";
    public static $Code_Update_Success_Msg = "修改成功";
    //修改失败
    public static $Code_Update_Fail = "00012";
    public static $Code_Update_Fail_Msg = "修改失败";
    //修改语句异常
    public static $Code_Update_Exception = "00013";
    public static $Code_Update_Exception_Msg = "修改异常";
    
    //删除成功
    public static $Code_Delete_Success = "00021";
    public static $Code_Delete_Success_Msg = "删除成功";
    //删除失败
    public static $Code_Delete_Fail = "00022";
    public static $Code_Delete_Fail_Msg = "删除失败";
    //删除语句异常
    public static $Code_Delete_Exception = "00023";
    public static $Code_Delete_Exception_Msg = "删除异常";
    
    //查询有记录
    public static $Code_Select_Success_Have = "00031";
    public static $Code_Select_Success_Have_Msg = "有记录";
    //查询无记录
    public static $Code_Select_Success_Null = "00032";
    public static $Code_Select_Success_Null_Msg = "无记录";
    //查询语句异常
    public static $Code_Select_Exception = "00033";
    public static $Code_Select_Exception_Msg = "查询异常";
    
    //----- 00080:SQL -----
    //SQL语句异常
    public static $Code_SQL_Exception = "00081";
    public static $Code_SQL_Exception_Msg = "SQL语句异常";
    //SQL语句Stmt对象校验异常
    public static $Code_SQL_Stmt_Check = "00082";
    public static $Code_SQL_Stmt_Check_Msg = "SQL语句Stmt校验异常";
    //SQL语句Where条件不得为空
    public static $Code_SQL_Where_Is_Null = "00083";
    public static $Code_SQL_Where_Is_Null_Msg = "SQL语句Where条件为空";
    //SQL语句影响记录数为0
    public static $Code_SQL_Recode_Zero = "00084";
    public static $Code_SQL_Recode_Zero_Msg = "SQL影响记录数为0";
    
    
    //----- 00100:函数执行 -----
    //正常
    public static $Code_Function_Success = "00101";
    public static $Code_Function_Success_Msg = "正常执行";
    //错误
    public static $Code_Function_Fail = "00102";
    public static $Code_Function_Fail_Msg = "业务异常";
    //错误
    public static $Code_Function_Exception = "00103";
    public static $Code_Function_Exception_Msg = "捕获异常";
    //调试
    public static $Code_Function_Debug = "00104";
    public static $Code_Function_Debug_Msg = "调试信息";
    //数组成员数量对比不一致
    public static $Code_Function_Array_Size_Different = "00105";
    public static $Code_Function_Array_Size_Different_Msg = "两个数组成员数量对比不一致";
    
    
    
    //----- 00500:Parameter -----
    //参数为空
    public static $Code_Parameter_Null = "00501";
    public static $Code_Parameter_Null_Msg = "参数为空";
    //参数值不符合规则
    public static $Code_Parameter_Regular = "00502";
    public static $Code_Parameter_Regular_Msg = "参数值不符合规则";
    //参数调试
    public static $Code_Parameter_Debug = "00503";
    public static $Code_Parameter_Debug_Msg = "参数调试";
    //参数：多余参数被传入
    public static $Code_Parameter_SURPLUS = "00504";
    public static $Code_Parameter_SURPLUS_Msg = "参数超出限制";       
    
    //----- 40100:Token -----
    //业务处理：Token为空
    public static $Code_Service_Token_Null = "40101";
    public static $Code_Service_Token_Null_Msg = "Token为空";
    //业务处理：Token格式错误
    public static $Code_Service_Token_Format = "40102";
    public static $Code_Service_Token_Format_Msg = "Token格式错误";
    //业务处理：Token即将过期
    public static $Code_Service_Token_Overtime_Will = "40103";
    public static $Code_Service_Token_Overtime_Will_Msg = "Token即将过期";
    //业务处理：Token已过期
    public static $Code_Service_Token_Overtime_Ready = "40104";
    public static $Code_Service_Token_Overtime_Ready_Msg = "Token已过期";
    //业务处理：Token已无效
    public static $Code_Service_Token_Invalid = "40105";
    public static $Code_Service_Token_Invalid_Msg = "Token已无效";
    //业务处理：Token账号不存在
    public static $Code_Service_Token_User_Invalid = "40106";
    public static $Code_Service_Token_User_Invalid_Msg = "Token账号不存在";
    
    //业务处理：数据表已存在
    public static $Code_Service_Table_Already_Exist = "40201";
    public static $Code_Service_Table_Already_Exist_Msg = "数据表已存在，无法进行数据表创建";
    
    //系统码
    public function FlySystemCode(){
        
        $vJson  = "";
        
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Run_Success, self::$Code_Run_Success_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Run_Fail, self::$Code_Run_Fail_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Run_Exception, self::$Code_Run_Exception_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Run_Debug, self::$Code_Run_Debug_Msg)) . ",";
        
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Insert_Success, self::$Code_Insert_Success_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Insert_Fail, self::$Code_Insert_Fail_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Insert_Exception, self::$Code_Insert_Exception_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Insert_Already_Exist, self::$Code_Insert_Already_Exist_Msg)) . ",";
        
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Update_Success, self::$Code_Update_Success_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Update_Fail, self::$Code_Update_Fail_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Update_Exception, self::$Code_Update_Exception_Msg)) . ",";
        
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Delete_Success, self::$Code_Delete_Success_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Delete_Fail, self::$Code_Delete_Fail_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Delete_Exception, self::$Code_Delete_Exception_Msg)) . ",";
        
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Select_Success_Have, self::$Code_Select_Success_Have_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Select_Success_Null, self::$Code_Select_Success_Null_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Select_Exception, self::$Code_Select_Exception_Msg)) . ",";
        
        $vJson .= JsonObj(JsonKeyValue(self::$Code_SQL_Exception, self::$Code_SQL_Exception_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_SQL_Stmt_Check, self::$Code_SQL_Stmt_Check_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_SQL_Where_Is_Null, self::$Code_SQL_Where_Is_Null_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_SQL_Recode_Zero, self::$Code_SQL_Recode_Zero_Msg)) . ",";
        
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Function_Success, self::$Code_Function_Success_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Function_Exception, self::$Code_Function_Exception_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Function_Array_Size_Different, self::$Code_Function_Array_Size_Different_Msg)) . ",";
        
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Parameter_Null, self::$Code_Parameter_Null_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Parameter_Regular, self::$Code_Parameter_Regular_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Parameter_Debug, self::$Code_Parameter_Debug_Msg)) . ",";
        
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Service_Token_Null, self::$Code_Service_Token_Null_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Service_Token_Format, self::$Code_Service_Token_Format_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Service_Token_Overtime_Will, self::$Code_Service_Token_Overtime_Will_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Service_Token_Overtime_Ready, self::$Code_Service_Token_Overtime_Ready_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Service_Token_Invalid, self::$Code_Service_Token_Invalid_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Service_Token_User_Invalid, self::$Code_Service_Token_User_Invalid_Msg)) . ",";
        $vJson .= JsonObj(JsonKeyValue(self::$Code_Service_Table_Already_Exist, self::$Code_Service_Table_Already_Exist_Msg)) . ",";
        
        $vJson = HandleStringDeleteLast($vJson);
        
        return JsonArray($vJson);
        
    } 
    
    
}

