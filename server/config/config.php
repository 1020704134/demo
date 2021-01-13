<?php

/**
 * 文件：配置文件，索引配置
 * 类型：流程文件
 * 说明：项目路径相关配置，统一存储在该配置文件中进行统一管理及设定
 * 时间：2019-09-24 11:11:17
 * 作者：第三云·shark
 * 文件说明：
 *   1.索引配置文件为系统底层执行提供标准支持，在使用类时需先引入该文件
 *   2.不需要其他文件支持的类索引放在索引靠前的位置，配置文件、系统码、常量文件等
 *   3.有依赖的类文件按照依赖关系依次由上至下的进行索引排序
 * */

    //配置:数据库业务函数索引
    require_once $_SERVER['DOCUMENT_ROOT'] . '/server/m/datamodel/dataindex.php';
    
    //索引:Fly工具类索引配置
    require_once $_SERVER['DOCUMENT_ROOT'] . '/server/fly/FlyImport.php';
    

