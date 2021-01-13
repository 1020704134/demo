<?php

/** 百度相关API */
class ThirdClassBaidu{
    
    //手机归属地查询
    public function MobileHome($fpPhonenumber){
        $query = file_get_contents("https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query={$fpPhonenumber}&resource_id=6004&ie=utf8&oe=utf8&format=json");
        $queryJson = json_decode($query);
        if($queryJson->status!="0"){
            return JsonInforFalse("数据获取失败", "百度手机号归属地查询");
        }
        $data = $queryJson->data;
        $data = $data[0];
        $prov = $data -> prov;
        $city = $data -> city;
        $type = $data -> type;
        return '{"result":"true","tips":"百度手机号归属地查询","prov":"'.$prov.'","city":"'.$city.'","type":"'.$type.'"}';
    }
    
}