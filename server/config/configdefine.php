<?php 

/**
 * 系统配置文件
 * 配置数量：19
 * 上次修改时间：2020-03-23 08:08:24
 * */

//当前程序服务器版本
define("PROJECT_CONFIG_SERVER","PHP:1");

//接口传入参数校验：true（开启校验）、false（关闭校验）
define("PROJECT_CONFIG_PARAMETER_CHECK",true);

//接口角色权限RBAC校验：true（开启校验）、false（关闭校验）
define("PROJECT_CONFIG_RBAC_SWITCH",false);

//系统密码:用于检测：数据库连接、基础数据表检测
define("PROJECT_CONFIG_SYSTEM_PASSWORD","123456");

//系统密码权限：true（开启系统密码权限）、false（关闭系统密码权限）
define("PROJECT_CONFIG_SYSTEM_PASSWORD_SWITCH",true);

//用户（ajaxuser）Token加密解密Key
define("PROJECT_CONFIG_TOKEN_SECRET_USER","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");

//第三云（disanyun.com）应用Key
define("PROJECT_CONFIG_DISANYUN_APPID","21491584806601627DSAPP3486591720");

//Debug输出等级：INFOR（SQL信息、接口信息等） < WRONG（异常捕获信息、信息等） < ERROR（全部信息及异常）
define("PROJECT_CONFIG_DEBUG_LEVEL","INFOR");

//系统日志：true（开启系统日志）、false（关闭系统日志）
define("PROJECT_CONFIG_SYSTEM_RUN_LOG",true);

//图片存储路径
define("PROJECT_CONFIG_IMAGE_PATH","/server/static/image/");

//Fly限制传入参数定义
define("PROJECT_CONFIG_FLY_PARAMETER_LIMIT","table_name,insert_field,key_field,length_judge,only_key,array_bo,data_sub,sum_field,update_field,update_value,where_field,where_value,where_son,sql_debug");

//---------- 数据库配置 ----------
//数据库描述
define("PROJECT_CONFIG_DB_DESCRIPT","第三云-系统");
//数据库类型
define("PROJECT_CONFIG_DB_TYPE","mysql");
//数据库IP
define("PROJECT_CONFIG_DB_HOST","47.97.162.154");
//数据库名称
define("PROJECT_CONFIG_DB_NAME","test_project");
//数据库用户名
define("PROJECT_CONFIG_DB_USER","test_project");
//数据库密码
define("PROJECT_CONFIG_DB_PASSWORD","tKTefedsXfMAnnsY");
//数据库端口
define("PROJECT_CONFIG_DB_PORT","3306");

//---------- 系统运行 ----------
//配置文件位置
define("FS_CONFIG_PATH",__FILE__);
//系统占位符:Fly system placeholder
define("FS_P","^R?V;");
//系统核心版本号:System Core
define("FS_CORE","20200328");
