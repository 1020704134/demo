<?php 
    
    //引用类
    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/m/dao/DBHelper.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/m/datamodel/service/mysql/DBMySQLDelete.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/m/datamodel/service/mysql/DBMySQLInsert.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/m/datamodel/service/mysql/DBMySQLJudge.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/m/datamodel/service/mysql/DBMySQLSelect.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/m/datamodel/service/mysql/DBMySQLUpdate.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/m/datamodel/service/mysql/DBMySQLDelete.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/m/datamodel/service/mysql/DBMySQLWhere.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/m/datamodel/service/mysql/json/DBMySQLServiceJson.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/m/datamodel/service/mysql/service/DBMySQLService.php";
    

    //========== M索引类:方法名索引流程 ==========
    
    //--- Data --- 
    function MIndexDataPaging($json){return DBMySQLServiceJson::GetDataPaging($json);}                  //Data - Paging
    function MIndexDataInsert($json){return DBMySQLServiceJson::OperationDataInsert($json);}            //Data - Insert
    function MIndexDataUpdate($json){return DBMySQLServiceJson::OperationDataUpdate($json);}            //Data - Update
    function MIndexDataDelete($json){return DBMySQLServiceJson::OperationDataDelete($json);}            //Data - Delete
    function MIndexDataTruncate($json){return DBMySQLServiceJson::OperationDataTruncate($json);}        //Data - Truncate
    function MIndexDataIndexNumberReorder($json){return DBMySQLServiceJson::OperationDataIndexNumberReorder($json);}        //Data - Truncate

    
    //--- Data Base ---
    function MIndexDBName(){return DBMySQLServiceJson::GetDBName();}                                    //DB - Name
    function MIndexDBVersion(){return DBMySQLServiceJson::GetDBVersion();}                              //DB - Version
    function MIndexDBInfor(){return DBMySQLServiceJson::GetDBInfor();}                         //DB - Infor
    function MIndexDBVariable(){return DBMySQLServiceJson::GetDBVariable();}                         //DB - Infor ALL
    
    function MIndexDBTables($json){return DBMySQLServiceJson::GetDBTables($json);}                      //DB - Tables
    
    function MIndexDBTableCheck($json){return DBMySQLServiceJson::GetDBTableCheck($json);}              //DB - Table Check
    function MIndexDBTableNameSet($json){return DBMySQLServiceJson::OperationDBTableNameSet($json);}    //DB - Table Name Set
    function MIndexDBTableDelete($json){return DBMySQLServiceJson::OperationDBTableDelete($json);}      //DB - Table Delete
    function MIndexDBTableAutoIncrementSet($json){return DBMySQLServiceJson::OperationDBTableAutoIncrementSet($json);}      //DB - Table Auto Increment Set
    function MIndexDBTableAutoIncrement($json){return DBMySQLServiceJson::GetDBTableAutoIncrement($json);}      //DB - Table Auto Increment Get
    function MIndexDBTableCreateSql($json){return DBMySQLServiceJson::GetDBTableCreateSql($json);}      //DB - Table Create Sql
    
    function MIndexDBTableFields($json){return DBMySQLServiceJson::GetDBTableFields($json);}            //DB - Table Fields
    function MIndexDBTableFieldAdd($json){return DBMySQLServiceJson::OperationDBTableFieldAdd($json);}  //DB - Table Field Add
    function MIndexDBTableFieldSet($json){return DBMySQLServiceJson::OperationDBTableFieldSet($json);}  //DB - Table Field Set
    function MIndexDBTableFieldDelete($json){return DBMySQLServiceJson::OperationDBTableFieldDelete($json);}    //DB - Table Field Delete
    function MIndexDBTableFieldBaseCheck($json){return DBMySQLServiceJson::OprationFieldBaseCheck($json);}    //DB - Table Field Base Check

    //--- Table Base ---
    function MIndexTBLoad($json){return DBMySQLServiceJson::OperationTBLoad($json);}                    //TB - Table Load
    function MIndexTBCopy($json){return DBMySQLServiceJson::OperationTBCopy($json);}                    //TB - Table Copy
    function MIndexTBNumber($json){return DBMySQLServiceJson::GetTBNumber($json);}                      //TB - Table Number
    function MIndexTBNames($json){return DBMySQLServiceJson::GetTBNames($json);}                        //TB - Table Names
