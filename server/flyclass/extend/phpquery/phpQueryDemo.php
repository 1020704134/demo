<?php

require('phpQuery.php');

phpQuery::newDocumentFile('http://www.disanyun.com/');
echo pq(".top a")->attr('href');   //获取LOGO链接


