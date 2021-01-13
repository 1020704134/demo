//配置:HTTP协议类型
var urlHttpType = window.location.protocol;

//配置:主机地址
var urlHost = urlHttpType + "//" + document.domain;

//配置:图片地址
var urlImage = urlHttpType + "//" + document.domain + "/server/c/static/image/";

//配置:管理员请求地址
var urlAjax = urlHttpType + "//" + document.domain + "/server/c/access/ajaxuser.php?";
//配置:用户请求地址
var urlAjaxUser = urlHttpType + "//" + document.domain + "/server/c/access/ajaxuser.php?";
//配置:超级管理员请求地址
var urlAjaxSuper = urlHttpType + "//" + document.domain + "/server/c/access/ajaxsuper.php?";

//配置:品牌管理员请求地址
var urlAjaxBrand = urlHttpType + "//" + document.domain + "/server/c/access/ajaxbrand.php?";
//配置:品牌管理员请求地址
var urlAjaxBrandUser = urlHttpType + "//" + document.domain + "/server/c/access/ajaxbranduser.php?";