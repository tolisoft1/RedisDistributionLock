<?php


namespace fql\SequenceTask;

use fql\RedisLock;

class SequenceTask
{
    public static function execute(array $config, $callable)
    {
        $lock = new RedisLock($config);
        if ($lock->acquire() === false) {
            throw new \Exception('获取锁失败:'.$lock);
        }

        $pid = pcntl_fork();
        if ($pid === -1) {
            throw new Exception('子进程创建失败');
        } elseif ($pid === 0) {
            //子进程
            for ($i = 0; ; $i++) {
                $interval = $config['interval'] ?? $config['seconds'] ?? 40;
                sleep($interval);
                if ($lock->keepAlive() === false) {
                    exit(0);
                }
            }
        } else {
            //父进程
            try{
                $res = $callable();
            }catch (\Throwable $exception){
                $res = $exception;
            }finally{
                if ($lock->release() === false){
                    throw new \Exception('释放锁失败:'.$lock);
                }
            }
            pcntl_waitpid($pid,$status);
            if ($res instanceof \Throwable){
                throw $res;
            }
            return $res;
        }
    }
}