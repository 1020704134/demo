<?php 

	/** 
	 * 创建对象
	 * 1.当文件不存在时，进行创建
	 * 2.当文件有多级目录时，循环创建
	 * */
	function CreateObject($fpClassPath,$fpAgnomen=""){
	    
	    //获取处理后的路径
	    $fpClassPath = GetPathHandle($fpClassPath);
	    
	    //处理路径
	    include_once $fpClassPath;                                  //引入文件
	    $vClassName = pathinfo($fpClassPath, PATHINFO_FILENAME).$fpAgnomen;    //获取文件名
	    $vObject = new $vClassName();                              //创建对象
	    return $vObject;                                           //返回对象
	}
