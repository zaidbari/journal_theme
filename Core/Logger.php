<?php namespace Core;

use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;


class Logger extends \Monolog\Logger
{
	private static array $loggers = [];

	/**
	 * Logger constructor.
	 *
	 * @param string $key
	 * @param null   $config
	 */
	public function __construct( $key = "app", $config = null )
	{
		parent::__construct($key);
		if ( empty($config) ) {
			$LOG_PATH = './logs';
			$config = [
				'logFile' => "{$LOG_PATH}/{$key}.log",
				'logLevel' => \Monolog\Logger::DEBUG
			];
		}
		try {
			$this->pushHandler(new StreamHandler($config['logFile'], $config['logLevel']));
		} catch (\Exception $e) {
			echo $e;
		}
	}

	/**
	 * @param string $key
	 * @param null   $config
	 *
	 * @return Logger
	 */
	public static function getInstance( $key = "app", $config = null ) : Logger
	{
		if ( empty(self::$loggers[ $key ]) ) self::$loggers[ $key ] = new Logger($key, $config);
		return self::$loggers[ $key ];
	}

	public static function enableSystemLogs()
	{

		$LOG_PATH = './logs';

		// Error Log
		self::$loggers['error'] = new Logger('errors');
		self::$loggers['error']->pushHandler(new StreamHandler("{$LOG_PATH}/errors.log"));
		ErrorHandler::register(self::$loggers['error']);

		// Request Log
		$data = [
			$_SERVER,
			$_REQUEST,
			trim(file_get_contents("php://input"))
		];
		self::$loggers['request'] = new Logger('request');
		self::$loggers['request']->pushHandler(new StreamHandler("{$LOG_PATH}/request.log"));
		self::$loggers['request']->info("REQUEST", $data);
	}

	public static function crit(string $msg,array $data = []) {
		self::getInstance()->critical($msg, $data);
	}
}
