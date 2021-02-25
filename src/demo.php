<?php


require_once __DIR__ . '/../vendor/autoload.php';

$config = [
    'host' => 'redis',
    'name' => 'default',    //Redis key名称
    'ttl' => 60,            //Redis Key的ttl
    'interval' => 5,        //子进程续期的时间间隔
];

try{
    $res = \fql\SequenceTask\SequenceTask::execute($config,function (){
        //fake long time logic...
        sleep(20);
//        throw new Exception('业务逻辑出错');
        return 'job execute success';
    });
}catch (Exception $exception){
    //redis-lock 内部异常
    $res = $exception->getMessage();
}catch (Throwable $exception){
    //业务逻辑代码异常,看情况处理
    $res = $exception->getMessage();
}

var_dump($res);