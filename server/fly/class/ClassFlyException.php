<?php

class ClassFlyException extends Exception{
    
    //构造方法
    public function __construct($fpMessage){
        //错误信息
        $errorMsg = $this->getFile().":".$this->getLine().":'".$fpMessage."'";
        header('Content-Type:text/html;charset=utf-8');
        echo JsonInforFalse("执行异常", $errorMsg);
    }
        

}
 