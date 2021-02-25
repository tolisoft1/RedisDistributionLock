<?php

namespace fql\exception;
use Exception;
use fql\log\Log;


class Error
{
    /**
     * 配置参数
     * @var array
     */
    protected static $exceptionHandler;

    /**
     * 注册异常处理
     * @access public
     * @return void
     */
    public static function register()
    {

        ini_set("display_errors", "On");
        error_reporting(E_ALL | E_STRICT);
        set_error_handler([__CLASS__, 'error']);
        set_exception_handler([__CLASS__, 'exception']);
        register_shutdown_function([__CLASS__, 'shutdown']);
    }

    /**
     * Exception Handler
     * @access public
     * @param  \Exception|\Throwable $e
     */

    public static function exception($e)
    {
        $data = [
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'message' => $e->getMessage(),
            'code'    => $e->getCode(),
        ];
        Log::writeLog($data);
    }

    /**
     * Error Handler
     * @access public
     * @param  integer $errno   错误编号
     * @param  integer $errstr  详细错误信息
     * @param  string  $errfile 出错的文件
     * @param  integer $errline 出错行号
     * @throws ErrorException
     */
    public static function error($errno, $errstr, $errfile = '', $errline = 0): void
    {
        $data = [
            'file'    => $errfile,
            'line'    =>$errline,
            'message' => $errstr,
            'code'    => $errno,
        ];
        Log::writeLog($data);
//        \think\facade\Log::error('错误信息',$data);
    }

    /**
     * Shutdown Handler
     * @access public
     */
    public static function shutdown()
    {

        if (!is_null($error = error_get_last()) && self::isFatal($error['type'])) {
            self::error('','',$error['file'],$error['line']);
        }
    }

    /**
     * 确定错误类型是否致命
     *
     * @access protected
     * @param  int $type
     * @return bool
     */
    protected static function isFatal($type)
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }
}