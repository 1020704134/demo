<?php 

    /**
     * 设置Session主键值
     * $fpKey：Session存储主键名
     * $fpValue：Session存储主键值
     * 整理时间：2019-09-24 18:01:20
     * */
    function SetSessionKey($fpKey,$fpValue){
        if(!isset($_SESSION)){session_start();}
        return $_SESSION[$fpKey]=$fpValue;
    }
    
    /**
     * 设置Session移除主键
     * $fpKey：移除Session主键
     * 整理时间：2019-09-24 18:01:25
     * */
    function SetSessionKeyRemove($fpKey){
        if(!isset($_SESSION)){session_start();}
        unset($_SESSION[$fpKey]);
    }
        
    /**
     * 清除Session会话全部主键
     * 整理时间：2019-09-24 18:01:30
     * */
    function SetSessionDestroy(){
        session_unset();//free all session variable
        session_destroy();//销毁一个会话中的全部数据
        setcookie(session_name(),'',time()-3600);//销毁与客户端的卡号
        return true;
    }
    




