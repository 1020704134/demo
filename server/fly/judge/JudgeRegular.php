<?php 
    
    //--- 正则判断方法 --- 

    /** 判断字符串是否符合正则*/
    function JudgeRegularString($string,$regular){
        if(IsNull($string)){
        	return false;
        }
        $pat_array = array();
        preg_match_all($regular,$string,$pat_array);
        $matchString = "";
        if(isset($pat_array[0][0])){
            $matchString = $pat_array[0][0];
        }
        if(IsNull($matchString)){
            return false;
        }
        return true;
    }

    
    //================================= 正则判断 ================================= 

    //--- 数字 ---

    //id
    function JudgeRegularId($string){return preg_match('/^[0-9]+$/',$string);}
    //number
    function JudgeRegularNumber($string){return preg_match('/^[0-9]+$/',$string);}
    //number letter
    function JudgeRegularNumberLetter($string){return preg_match('/^[a-zA-Z0-9]+$/',$string);}
    //数字正则:整数
    function JudgeRegularInt($string){return preg_match('/^(\-|\+?)[0-9]{1,11}$/',$string);}
    //数字正则:正整数
    function JudgeRegularIntRight($string){return preg_match('/^[1-9][0-9]{0,8}$/',$string);}
    //数字正则:非负数
    function JudgeRegularIntRightZero($string){return preg_match('/^(\+?)[0-9]{1,9}$/',$string);}
    //数字正则:金额
    function JudgeRegularMoney($string){return preg_match('/^\d+(\.\d{1,2})?$/',$string);}
    //数字正则:小数
    function JudgeRegularDouble($string){return preg_match('/^\d+(\.\d+)?$/',$string);}

    
    //--- 字母 ---
    
    //字母
    function JudgeRegularLetter($string){return preg_match('/^[a-zA-Z]+$/',$string);}
    //字母数字
    function JudgeRegularLetterNumber($string){return preg_match('/^[a-zA-Z0-9]+$/',$string);}
    
    
    //--- 值类型判断 ---

    //title
    function JudgeRegularTitle($fpString){return preg_match('/^[\x{4e00}-\x{9fa5}A-Za-z0-9,._:：，、。；！？%“”‘’（）【】]+$/u',$fpString);}
    //tag
    function JudgeRegularTag($fpString){return preg_match('/^[\x{4e00}-\x{9fa5}A-Za-z0-9:：、]+$/u',$fpString);}
    //selector
    function JudgeRegularSelector($fpString){return preg_match('/^[A-Za-z0-9:#=\*\s\'\.\(\)\[\]]+$/u',$fpString);}
    
    
    
    //--- 表单 ---
    
    //手机号
    function JudgeRegularPhone($string){return preg_match('/^1[3456789][0-9]{9}$/',$string);}
    //验证码:短信验证码
    function JudgeRegularPhoneCode($fpString){ return preg_match('/^[a-zA-Z0-9]{4,8}$/',$fpString); }
    //身份证校验
    function JudgeRegularIdcard($string){
        $id = strtoupper($string);
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
        $arr_split = array();
        if(!preg_match($regx, $id)){ return false; }
        if(15==strlen($id)){
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
            @preg_match($regx, $id, $arr_split);
            //检查生日日期是否正确
            $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth)){
                return false;
            } else {
                return true;
            }
        }else{
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
            @preg_match($regx, $id, $arr_split);
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth)){
                return FALSE;
            }else{
                //检验18位身份证的校验码是否正确。
                //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
                $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $sign = 0;
                for ( $i = 0; $i < 17; $i++ ){
                    $b = (int) $id{$i};
                    $w = $arr_int[$i];
                    $sign += $b * $w;
                }
                $n = $sign % 11;
                $val_num = $arr_ch[$n];
                if ($val_num != substr($id,17, 1)){
                    return false;
                }else{
                    return true;
                }
            }
        }
    }
    
    

    //--- 数据表 ---
    
    //参数判断
    function JudgeRegularParameter($string){
        $string = HandleStringReplace($string,',','');
        return preg_match('/^[0-9a-zA-Z_-]+$/',$string); 
    }
    //数据表判断
    function JudgeRegularTable($string){return preg_match('/^[0-9a-zA-Z_-]+$/',$string);}
    //字段值判断
    function JudgeRegularField($string){return preg_match('/^[0-9a-zA-Z]+$/',$string);}
    //字段值判断
    function JudgeRegularFieldString($string){return preg_match('/^[0-9a-zA-Z_,|]+$/',$string);}
    
    
    //--- 状态 ---
    //值为状态值判断
    function JudgeRegularState($string){return preg_match('/^[0-9a-zA-Z_]+$/',$string);}
    

    //--- 密码 ---
    
    //密码8~16位
    function JudgeRegularPassword($string){ return preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,16}$/',$string); }
    
    
    //--- 订单 ---
    
    //订单格式
    function JudgeRegularOrder($fpString){ return preg_match('/[A-Za-z\d]+/',$fpString); }
    //Key:Order、Token、Key、Secret
    function JudgeRegularKey($fpString){ return preg_match('/[A-Za-z_\d\-]+/',$fpString); }

    
    //--- 微信相关 ---
    
    //微信OpenID
    function JudgeRegularWechatOpenId($string){return preg_match('/[A-Za-z_\d\-]{28}/',$string);}
    

    //--- 日期时间 ---
    
    //月格式
    function JudgeRegularDateMonth($string){return preg_match('/^\d\d\d\d-\d\d$|^\d\d\d\d-\d$/',$string);}
    
    //日期格式 dddd-dd-dd dd:dd:dd
    function JudgeRegularDateTime($string){return preg_match('/^\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d$/',$string);}
    
    //日期时间格式
    function JudgeRegularDate($string){
        $vDateMonthBool = preg_match('/^\d\d\d\d-\d\d$/',$string);
        $vDateBool = preg_match('/^\d\d\d\d-\d\d-\d\d$/',$string);
        $vDateTimeBool = preg_match('/^\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d$/',$string);
        $vTimeBool = preg_match('/^\d\d:\d\d:\d\d$/',$string);
        $vTimeSHBool = preg_match('/^\d\d:\d\d$/',$string);
        return $vDateBool||$vDateTimeBool||$vTimeBool||$vTimeSHBool||$vDateMonthBool;
    }
    
    //日期时间格式组合
    function JudgeRegularDateGroup($string){
        $vDateGroupOne = preg_match('/^\d\d\d\d-\d\d-\d\d--\d\d\d\d-\d\d-\d\d$/',$string);
        $vDateGroupTwo = preg_match('/^\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d--\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d$/',$string);
        $vTimeGroupThree = preg_match('/^\d\d:\d\d:\d\d--\d\d:\d\d:\d\d$/',$string);
        $vTimeGroupFour = preg_match('/^\d\d:\d\d--\d\d:\d\d$/',$string);
        return $vDateGroupOne||$vDateGroupTwo||$vTimeGroupThree||$vTimeGroupFour;
    }

    
    
    //--- 合法值判断（防注入校验） ---
    
    //图片网址判断
    function JudgeRegularImage($string){
        return preg_match('/^((http:\/\/)?|(https:\/\/)?)[a-zA-Z0-9,.:=_\/\?\-]+$/ui', $string);
    }
    
    //网址判断
    function JudgeRegularUrl($string,$fpSlashBo=false){
        if($fpSlashBo){ $string = str_replace("\\","/",$string); }
        return preg_match('/^((http:\/\/)?|(https:\/\/)?)[a-zA-Z0-9,.:;=_{}\/\?\-]+$/ui', $string);
    }
    
    //图标判断
    function JudgeRegularIcon($string){
        return preg_match('/[&#;a-z0-9]+/ui', $string);
    }
    
    //值判断：数字（Number）、字母（Letter）、文字（Word）
    function JudgeRegularFont($fpString,$fpLengthOne="",$fpLengthTwo=""){
        //获取字符串的值
        $vStringLength = mb_strlen($fpString);
        //判断字符长度为0
        if($vStringLength == 0){ return false; }
        //判断第一个长度
        if(!IsNull($fpLengthOne)){
            if($vStringLength<$fpLengthOne){ return false; }
        }
        //判断第二个长度
        if(!IsNull($fpLengthTwo)){
            if($vStringLength>$fpLengthTwo){ return false; }
        }
        //判断字符串正则
        return preg_match('/^[\x{4e00}-\x{9fa5}A-Za-z0-9<>,·._:;：，、。；！？●%“”‘’（）\^\s\-\/\*]+$/u',$fpString);
    }    
    
    //判断是否有逗号
    function JudgeRegularComma($string){
        return preg_match('/"/ui', $string);
    }