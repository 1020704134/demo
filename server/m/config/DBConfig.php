<?php 

//----- M层（数据模型层）数据库配置 -----

class DBConfig{
    
    //数据库配置
    public static $dbDescript = PROJECT_CONFIG_DB_DESCRIPT;        //数据库描述
    public static $dbType = PROJECT_CONFIG_DB_TYPE;                //数据库类型
    public static $dbHost = PROJECT_CONFIG_DB_HOST;                //数据库主机名
    public static $dbName = PROJECT_CONFIG_DB_NAME;                //使用的数据库
    public static $dbUser = PROJECT_CONFIG_DB_USER;                //数据库连接用户名
    public static $dbPassWord = PROJECT_CONFIG_DB_PASSWORD;        //数据库连接密码
    public static $dbPort = PROJECT_CONFIG_DB_PORT;                //数据库连接端口
    
    //创建数据库链接：获取DSN
    public static function GetDsn(){
        return self::$dbType.":host=".self::$dbHost.";port=".self::$dbPort.";dbname=".self::$dbName;
    }
            
}
    
