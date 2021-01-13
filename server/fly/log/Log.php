<?php 

    //操作日志添加
    function OpreationLog($fpUserType,$fpUserId,$fpODescript,$fpOFile,$fpOFunction,$fpOLine,$fpOResult,$fpORInfor){
        ObjFlyDBSQLite() -> OpreationLog($fpUserType,$fpUserId, $fpODescript, $fpOFile, $fpOFunction, $fpOLine, $fpOResult, $fpORInfor);
    }
