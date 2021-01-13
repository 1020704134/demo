<?php 

    // ---- Plug ins ----
    
    /**插件支持检测配置相关文件*/
    function PlugInCheck(){
        
        //--- 文件判断 ---
        
        $vProjectRoot = $_SERVER['DOCUMENT_ROOT']; 
        
        //判断插件目录是否存在
        if(!is_dir($vProjectRoot."/server/flyclass/plugins")){
            return JsonInforFalse("插件目录不存在", $vProjectRoot."/server/flyclass/plugins");
        }
        
        //判断插件显示目录是否存在
        if(!is_dir($vProjectRoot."/view/page/admin/plugins")){
            return JsonInforFalse("插件显示界面目录不存在", $vProjectRoot."/view/page/admin/plugins");
        }
        
        //判断对象配置文件是否存在
        if(!is_file($vProjectRoot."/server/config/configclass.php")){
            return JsonInforFalse("对象注册文件不存在", $vProjectRoot."/server/config/configclass.php");
        }
        
        //判断数据库是否可连接
        $vDBCheck = ObjSystem() -> CheckDataBaseLink();
        if(JudgeJsonFalse($vDBCheck)){
            return $vDBCheck;
        }
        
        return JsonInforTrue("插件环境检测成功", "插件环境检测");
        
    }
        
    /**插件列表*/
    function PlugInList(){
        
        //插件环境检测
        $vCheck = PlugInCheck();
        if(JudgeJsonFalse($vCheck)){ return $vCheck; }
        
        $url = "https://service.disanyun.com/c/access/ajax.php?line=plugin&method=list";
        return GetHttp($url);
        
    }

    /**插件加载*/
    function PlugInLoad(){
        
        //插件环境检测
        $vCheck = PlugInCheck();
        if(JudgeJsonFalse($vCheck)){ return $vCheck; }
        
        
        //--- 参数获取 ---
        
        $json = "";
        
        //获取插件Key
        $pPlugInKey = GetParameter("plugin_key",$json);
        if(IsNull($pPlugInKey)){return JsonModelParameterNull("plugin_key");}
        
        //获取插件版本
        $pPlugInVersion = GetParameter("plugin_version",$json);
        if(IsNull($pPlugInVersion)){return JsonModelParameterNull("plugin_version");}
        
        //获取插件配置信息
        $url = "https://service.disanyun.com/c/access/ajax.php?line=plugin&method=load&plugin_key={$pPlugInKey}&plugin_version={$pPlugInVersion}";
        return GetHttp($url);
    
    }