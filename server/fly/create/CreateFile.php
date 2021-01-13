<?php 

	/** 
	 * 创建文件
	 * 1.当文件不存在时，进行创建
	 * 2.当文件有多级目录时，循环创建
	 * */
	function CreatePath($fpFilePath, $mode=0777){
	    
	    //获取处理后的路径
	    $fpFilePath = GetPathHandle($fpFilePath);
	    
	    //处理路径
	    $vFilePath = pathinfo($fpFilePath, PATHINFO_DIRNAME);      //获取文件名
	    $vFileName = pathinfo($fpFilePath, PATHINFO_BASENAME);     //获取文件名
	    
	    //判断目录是否存在
	    $vCreateDirInfor = "文件路径中目录结构不存在";
	    if(!IsNull($vFilePath)){
	        if(is_dir($vFilePath)){
	            $vCreateDirInfor = '目录已存在';
	        }else{
	            if(mkdir($vFilePath, $mode, true)) {
	                $vCreateDirInfor = '目录创建成功';
	            }else{
	                $vCreateDirInfor = '目录创建失败';
	            }
	        }
	    }
	    
	    //判断文件名是否存在
	    $vCreateFileInfor = "文件路径中文件名不存在";
	    if(!IsNull($vFileName)){
	        if(is_file($fpFilePath)){
	            $vCreateFileInfor = '文件已存在';
	        }else{
	            if(fopen($fpFilePath, "w")) {
	                $vCreateFileInfor = '文件创建成功';
	            }else{
	                $vCreateFileInfor = '文件创建失败';
	            }
	        }
	    }
	    
	    //返回执行结果
	    return JsonInforTrue("{$vCreateDirInfor},{$vCreateFileInfor}", "路径文件创建");
	}
