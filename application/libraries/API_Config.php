<?php defined('BASEPATH') OR exit('No direct script access allowed');

class API_Config 
{
	public static function parameter($name = '', $example = '1', $required = TRUE)
	{
		return array(
			'name' => $name,
			'example' => $example,
			'required' => $required
		);
	}

	public static function parameterMapping($methodConfig)
	{
		$validationConfig = API_Config::withName($methodConfig);

		return array_combine(
			array_keys($validationConfig),
			array_column($validationConfig, 'name')
		);
	}

	public static function requiredString($parameters, $separator = ', ')
	{
		return implode($separator, API_Config::requiredArray($parameters));
	}

	public static function requiredArray($parameters)
	{
		return array_map(
			function($long, $short) {
				if ($short === '') {
					$short = "/end_of_path";
				}
				return "$long ($short)";
			},
			API_Config::requiredLong($parameters),
			API_Config::requiredShort($parameters)
		);
	}

	public static function requiredLong($parameters)
	{
		return array_keys(API_Config::required($parameters));
	}

	public static function requiredShort($parameters)
	{
		return array_column(API_Config::required($parameters), 'name');
	}

	private static function required($parameters)
	{
		return array_filter($parameters, function($x) {
			return $x['required'];
		});
	}

	public static function exampleCall($apiConfig, $method)
	{
		$parameters = $apiConfig[$method];

		$pathPars = API_Config::withoutName($parameters);
		$getPars = API_Config::withName($parameters);

		$path = implode('/', array_column($pathPars, 'example'));

		$assignments = array_map(
			"API_Config::assignmentString",
			array_column($getPars, 'name'),
			array_column($getPars, 'example')
		);

		$getParsString = implode("&", $assignments);

		// append slash to the method name if the path is not empty
		$method .= (empty($path)? '' : '/');

		// append questionmark to the path if the GET parameter string is not empty
		$path .= (empty($getParsString)? '' : '?');

		return $method . $path . $getParsString;
	}

	public static function withName($parameters)
	{
		return array_filter($parameters, function($x) {
			return ($x['name'] !== '');
		});
	}

	public static function withoutName($parameters)
	{
		return array_filter($parameters, function($x) {
			return ($x['name'] === '');
		});
	}

	private static function assignmentString($a, $b)
	{
		return "$a=$b";
	}
}

