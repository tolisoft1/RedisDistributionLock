<?php

require_once __DIR__ . '/../vendor/autoload.php';
\fql\exception\Error::register();
$db = \fql\database\MyPDO::getInstance('mysql','172.17.20.37','root','123456','test','utf8');
//$db->dbh->beginTransaction();
$sql = "select * from stock";
$result = $db->query($sql);
foreach ($result as $k=>$v){
    if($v['num']>=1){
        $db->execSql("update stock set num = num-1 where  num=".$v['num']);

    }
}
//$db->dbh->commit();
//echo "<pre>";
//var_dump($result);
//do something...

$db->destruct();
