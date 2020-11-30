<?php
namespace App\Fundations\Logger;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

/**
 * @method static void alert(string $message, array $context = [])
 * @method static void critical(string $message, array $context = [])
 * @method static void debug(string $message, array $context = [])
 * @method static void emergency(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 * @method static void info(string $message, array $context = [])
 * @method static void log($level, string $message, array $context = [])
 * @method static void notice(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 */
class Log
{
    protected static $instance;

    public static function instance(): Logger
    {
        if (is_null(static::$instance)) {
            static::$instance = new Logger('App\Log');
        }

        return static::$instance;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method Support methods: debug|info|notice|warning|error|critical|alert|emergency
     * @param array $args
     * @return mixed
     * @author Sphenginx
     */
    public static function __callStatic($method, $args)
    {
        $message = $args[0];

        $context = isset($args[1]) ? $args[1] : [];

        $path = isset($args[2]) ? $args[2] : 'logs/' . $method . '/';
        $path = storage_path($path);

        is_dir($path) || mkdir($path, 0777, true);

        $handler = (new StreamHandler(
            $path . date('Y-m-d') . '.log', Logger::toMonologLevel($method),
            true,
            0777
        ))->setFormatter(
            new LineFormatter(null, null, true, true)
        );

        static::instance()->setHandlers([$handler])->$method($message, $context);
    }
}
