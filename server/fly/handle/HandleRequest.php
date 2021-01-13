<?php 


	/** 请求URL转发*/
	function HandleRequestForward($url){
	    header("HTTP/1.1 303 See Other");
	    header("Location: $url");
	    exit;
	}


