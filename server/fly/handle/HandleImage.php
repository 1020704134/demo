<?php 


	/** 
	 * 处理图片：图片转base64编码
	 * $fpImageUrl：图片地址
	 * $fpBase64Header：图片Base64数据头信息
	 * 整理时间：2019-09-25 19:07:58
	 * */
	function HandleImageUrlToBase64($fpImageUrl,$fpBase64Header=true){
        $img = $fpImageUrl;
        //不组合任何前缀进行请求
        $imageInfo = @getimagesize($img);
        //组合Http协议前缀
        if(!$imageInfo){
        	$img = "http:".$fpImageUrl;
        	$imageInfo = @getimagesize($img);
        }
        //组合Https协议前缀
        if(!$imageInfo){
        	$img = "https:".$fpImageUrl;
        	$imageInfo = @getimagesize($img);
        }
        //请求图片
        $base64 = "" . chunk_split(base64_encode(file_get_contents($img)));
        if($fpBase64Header){
            return 'data:' . $imageInfo['mime'] . ';base64,' . chunk_split(base64_encode(file_get_contents($img)));
        }else{
            return chunk_split(base64_encode(file_get_contents($img)));
        }
	}
	
	/** 
	 * 处理图片：base64编码转图片文件
	 * $fpFilePath：处理后的图片文件存储路径
	 * $fpImageBase64：图片Base64数据
	 * 整理时间：2019-09-25 19:08:48
	 * */
	function HandleImageBase64ToFile($fpFilePath,$fpImageBase64){
	    //截取Base64编码字符串
	    $vHandleString = GetStringRightFindEnd($fpImageBase64, "base64,");
	    //为空判断
	    if(IsNull($vHandleString)){return false;}
	    //写到文件
	    return file_put_contents($fpFilePath, base64_decode($vHandleString));
	}
	
	