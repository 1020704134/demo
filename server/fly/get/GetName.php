<?php 

    /**根据传入的Key获取中文名称*/
    function GetName($string){
        $vNameArray = [
            "user" => "用户",
            "token" => "令牌权限",
            "admin" => "管理员",
            "visitor" => "游客",
            "image" => "图片",  
            "system" => "系统",
            "test" => "测试",
            "text" => "文本",
            "rbac" => "RBAC权限",
            "database" => "数据库",
            "interface" => "接口",
            "worktask" => "工作目标",
            "workorder" => "工单",
            "log" => "日志",
            "systemp" => "系统密码权限",
            "login" => "登陆",
            "register" => "注册",
        ];
        
        foreach($vNameArray as $key=>$value){
            if($key==$string){
                return $value;
            }
        }
        return "none";
    }
    
    
    
    
    