<?php 

    
    /**
     * 图片上传
     * 整理时间：2019-09-24 17:48:27
     * */
    function UploadImage(){
        
        //图片存储路径
        $FileSavePath = GetParameter("path");
        if(JudgeRegularUrl($FileSavePath)){ 
            $vSavePath = $_SERVER['DOCUMENT_ROOT'] . $FileSavePath;
            if(!is_dir($vSavePath)){$FileSavePath = "";}
        }else{
            $FileSavePath = "";
        }
        
        
        //判断上传的文件是否出错,是的话，返回错误
        if($_FILES["file"]["error"]){
            return JsonInforFalse($_FILES["file"]["error"], "上传文件异常");
        }else{  
            
            //--- 图片文件信息 ---
            $vFileType = $_FILES["file"]["type"];
            $vFileSize = $_FILES["file"]["size"];
            $vFieldName = $_FILES["file"]["name"];
            
            //--- 图片判断 ---
            //图片格式判断
            if(!($vFileType=="image/png"||$vFileType=="image/jpeg"||$vFileType=="image/jpg"||$vFileType=="image/gif")){
                return JsonInforFalse("图片格式错误", $vFileType);
            }
            //图片尺寸判断,2048000B
            if($vFileSize>2048000){
                return JsonInforFalse("图片尺寸不得大于2M", $vFileSize);
            }
            
            //--- 根变量 ---
            $vRoot = $_SERVER['DOCUMENT_ROOT'];     //项目根目录
            $vHost = $_SERVER['HTTP_HOST'];         //项目根目录
            
            //--- 图片变量 ---
            $vFieldNameSuffix = strrchr($vFieldName,'.');           //文件后缀
            $vFieldNewName = GetId("R").$vFieldNameSuffix;          //要保存的随机文件名
            
            $vImageSaveFile = "";
            $vImageSaveUrl = "";
            //当有传入的图片存储路径时
            if(!IsNull($FileSavePath)){
                $vImageSaveFile = $vRoot . "/" . $FileSavePath . "/" . $vFieldNewName;
                $vImageSaveUrl = "//" . $vHost . "/" . $FileSavePath . "/" . $vFieldNewName;
            }else{
                //--- 图片存储路径计算 ---
                //图片文件路径
                $vFileSavePath = $vRoot . PROJECT_CONFIG_IMAGE_PATH;
                $vImagePath = PROJECT_CONFIG_IMAGE_PATH;
                //图片目录最后一个子目录名称
                $vFolderLast = GetFolderLast($vFileSavePath);
                //图片存储路径
                $vImageFilePath = $vFileSavePath.$vFolderLast;
                if(IsNull($vFolderLast)){
                    $vFolderLast = "1000";
                    //组合图片存储路径，当该存储路径不存在时，创建路径
                    $vImageFilePath = $vFileSavePath.$vFolderLast;
                    if(!is_dir($vImageFilePath)){mkdir($vImageFilePath,0777,true);}
                }
                
                //获取最后文件夹文件数量
                $vFolderLastFileNumber = GetFolderFileNumber($vImageFilePath);
                //当图片文件数大等于1000时新建文件夹
                if($vFolderLastFileNumber >= 1000){
                    $vFolderLast = $vFolderLast + 1000;
                    //组合图片存储路径，当该存储路径不存在时，创建路径
                    $vImageFilePath = $vFileSavePath.$vFolderLast;
                    if(!is_dir($vImageFilePath)){mkdir($vImageFilePath,0777,true);}
                }
                //图片储路目录计算
                $vImageFolder = $vImageFilePath. "/";
                
                //图片文件存储路径
                $vImageSaveFile = $vImageFolder . $vFieldNewName;
                $vImageSaveUrl = "//" . $vHost . $vImagePath . $vFolderLast . "/" . $vFieldNewName;
            }
                       
            //将临时地址移动到指定地址
            if(move_uploaded_file($_FILES["file"]["tmp_name"],$vImageSaveFile)){
                $vImageUrlResult = $vImageSaveUrl;
                //当上传的图片为以下格式时，压缩图片
                if($vFieldNameSuffix=="jpg"||$vFieldNameSuffix=="jpeg"||$vFieldNameSuffix=="bmp"||$vFieldNameSuffix=="wbmp"){
                    //该部分代码可自定义进行实现，需拓展
                }
                //返回图片地址
                return JsonInforFalse($vImageUrlResult, "上传成功");
            }
            return JsonInforFalse($vFieldName, "上传失败");
           
        }
   
    }


    /**
     * 图片上传:layui
     * 整理时间：2019-09-24 17:48:46
     * */
    function UploadImageLayui(){
        
        //图片上传
        $vUploadImage = UploadImage();
        
        //判断上传结果
        if(JudgeJsonTrue($vUploadImage)){
            //上传成功
            $src = GetJsonValue($vUploadImage, "infor");
            $resultJson  = JsonKeyValue("code", "0").",";
            $resultJson .= JsonKeyValue("msg", "").",";
            $resultJson .= JsonKeyString("data", JsonObj(JsonKeyValue("src", $src).",".JsonKeyValue("title", "上传图片")));
            $resultJson  = JsonObj($resultJson);
            return $resultJson;
        }else{
            //上传失败
            $resultJson  = JsonKeyValue("code", "-1").",";
            $resultJson .= JsonKeyValue("msg", "上传失败").",";
            $resultJson .= JsonKeyString("data", "");
            $resultJson  = JsonObj($resultJson);
            return $resultJson;
        }
       
    }
    
    /**
     * 图片删除
     * TOBESORTEDOUT -- 待整理
     * */
    function DeleteImage(){
    	
    	//--- 参数获取区 ---
        //校验:文件名称
        $fileName = GetParameter('filename', "");
        if(IsNull($fileName)){return JsonModelParameterNull("filename");}
    	
    	//查询文件是否存在
    	$imagePath = $_SERVER['DOCUMENT_ROOT'] . PROJECT_CONFIG_IMAGE_PATH . $fileName;
    	//如果不存在则返回Json提示
    	if(!file_exists($imagePath)){
    	    return JsonInforFalse("文件不存在", "图片文件");
		}
		if(unlink($imagePath)){
		    return JsonInforTrue("删除成功","图片文件");
		}
  		return JsonInforFalse("删除失败","图片文件");
  
    }
    
