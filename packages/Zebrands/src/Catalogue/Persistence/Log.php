<?php 
declare( strict_types = 1 );

namespace Zebrands\Catalogue\Persistence;

use Base\Log AS BaseLog;

/**
 * @method static void info(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 *
 * @method static void emergency(string $message, array $context = [])
 * @method static void alert(string $message, array $context = [])
 * @method static void critical(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 * @method static void notice(string $message, array $context = [])
 * @method static void debug(string $message, array $context = [])
 * @method static void log($level, string $message, array $context = [])
 * @method static mixed channel(string $channel = null)
 * @method static \Psr\Log\LoggerInterface stack(array $channels, string $channel = null)
 *
 * @see \Illuminate\Log\Logger
 */
class Log extends BaseLog
{
	public function info(string $message, $context = [])
	{
		$context = $context??[]; 
		\Base\Log::info($message, (array) $context);
	}
	public function error(string $message, $context = [])
	{
		\Base\Log::error($message, (array) $context);
	}

	public function stack(array $channels, $channel = null)
    {
    	return \Base\Log::stack($channels, $channel);
        return new Logger(
            $this->createStackDriver(compact('channels', 'channel')),
            $this->app['events']
        );
    }
}