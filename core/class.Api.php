<?php
/*
	Api
	Manage Api
	Dependencies : Loader, URL, Router
*/

class Api
{
	private static $instances = array();

	public static function get($name)
	{
		if (!isset(self::$instances[$name]))
		{
			self::$instances[$name] = new Api($name);
		}
	}

	public function __construct($className)
	{
		if (!($method=URL::getHash(0)) || empty($method))
			return false;


		$this->api = new $className();

		if (!method_exists($this->api, $method))
			return false;

		$refl = new ReflectionMethod($className, $method);

		if (!$refl->isPublic())
			return false;

		$this->api->$method();
	}
	
	public static function CORS($domains='*')
	{
		header('Access-Control-Allow-Origin: '.$domains);
	}
  
	public static function RESTMethods($methods='GET, POST, PUT, PATCH, DELETE')
	{
		header('Access-Control-Allow-Methods: '.$methods);
	}
	
	public static function getNextHash()
	{
		if (($hash1=URL::getHash(1)) && !empty($hash1))
			return $hash1;
	}
	
	public static function json($data)
	{
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);
	}

	public static function error($msg='', $errors=array())
	{
		self::json(array('code' => 400, 'message' => $msg, 'errors' => $errors));
		exit;
	}

	public static function error404()
	{
		self::json(array('code' => 404));
		exit;
	}

	public static function unauthorized()
	{
		self::json(array('code' => 401));
		exit;
	}

	public static function success()
	{
		self::json(array('code' => 200));
		exit;
	}

	public static function data($data=array())
	{
		self::json(array('code' => 200, 'data' => $data));
		exit;
	}


}
