<?php 

	/**
	 * 数据静态化
	 * $fpFilePath：文件存储路径
	 * $fpData：文件数据
	 * 整理时间：2019-09-25 15:13:27
	 * */
	function HandleDataStatic($fpFilePath,$fpData){
	    $ctxtsubmit = $fpData;
        $f = fopen($fpFilePath, "wb");
        $text = utf8_encode($text);   //先用函数utf8_encode将所需写入的数据变成UTF编码格式。
        $text = "\xEF\xBB\xBF".$ctxtsubmit;   //"\xEF\xBB\xBF",这串字符不可缺少，生成的文件将成为UTF-8格式，否则依然是ANSI格式。
        fputs($f, $text);   //写入。
        fclose($f); //关闭文件
	}
	

