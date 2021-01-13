<?php 
	
    /** 
     * 获取目录文件数
     * $fpDir：项目目录路径
     * 整理时间：2019-09-24 19:20:21
     * */
    function GetFolderFileNumber($fpDir){
        return sizeof(scandir($fpDir)) - 2;
    }
    
    /** 
     * 获取目录文件数
     * $fpDir：项目目录路径
     * 整理时间：2019-09-24 19:20:37 
     * */
    function GetFolderList($fpDir){
        $vHandler = opendir($fpDir);
        $vFileList = array();
        while(($vFilename = readdir($vHandler))!=false){
            //略过linux目录的名字为'.'和‘..'的文件
            if($vFilename != "." && $vFilename != ".."){
                array_push($vFileList, $vFilename);
            }
        }
        return $vFileList;
    }
    
    
    /** 
     * 获取目录最后一个文件
     * $fpDir：项目目录路径
     * 整理时间：2019-09-24 19:20:58 
     * */
    function GetFolderLast($fpDir){
        $vHandler = opendir($fpDir);
        $vFileLast = "";
        while(($vFilename = readdir($vHandler))!=false){
            //略过linux目录的名字为'.'和‘..'的文件
            if($vFilename !== "." && $vFilename !== ".."){
                if(IsNull(strpos($vFilename,"."))){
                    $vFileLast = $vFilename;
                }
            }
        }
        return $vFileLast;
    }