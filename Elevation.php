<?php
//
// By: Elevation Fitness
// Web: http://elevationfit.com
// Date: 10/28/2012
//

class Elevation
{
	private static $_apihost = 'http://dev.elevationfit.dev/api/';
	private static $_request_url = '';
	private static $_response = '';
	private static $_raw_response = '';
	private static $_request_data = array();
	private static $_access_token = '';
	private static $_account_id = '';
	private static $_error = array();

	// ----------------------------- Setters ----------------------------- //

	//
	// Set the access token.
	//
	public static function set_access_token($token)
	{
		self::$_access_token = trim($token);
	}
	
	//
	// Set the account id.
	//
	public static function set_account_id($id)
	{
		self::$_account_id = trim($id);
	}

	//
	// Set request data.
	//
	public static function set_data($key, $value)
	{
		self::$_request_data[$key] = $value;
	}
	
	//
	// Set order.
	//
	public static function set_order($order, $sort = 'desc')
	{
		self::set_data('order', $order);
		self::set_data('sort', $sort);
	}
	
	//
	// Set limit.
	//
	public static function set_limit($limit)
	{
		self::set_data('limit', $limit);		
	}
	
	//
	// Set API host.
	//
	public static function set_api_host($host)
	{
		self::$_apihost = $host;
	}
	
	//
	// Set which. Which datasets do you want to include in the return.
	//
	public static function set_which($which)
	{
		self::set_data('which', $which . ',');
	}
	
	// ----------------------- Non-API Getters --------------------------- //
	
	//
	// Return the raw response.
	//
	public static function get_raw_response()
	{
		return self::$_raw_response;
	}
	
	//
	// Return the error messages.
	//
	public static function get_error()
	{
		return self::$_error;
	}
	
	//
	// Get API host.
	//
	public static function get_api_host()
	{
		return self::$_apihost;
	}
	
	// ------------------ Exercises -------------------------- //

	//
	// Exercises - get
	//
	static public function exercises_get()
	{
		self::$_request_url = self::$_apihost . 'exercises/get';
		return self::_request('get');	
	} 
	
	// ----------------- Private Functions -------------------- //

	//
	// Make request to server.
	//
	private static function _request($type)
	{
		// Reset error.
		self::$_error = array();

		// Set post / get requests we have to send with every request.
		self::$_request_data['access_token'] = self::$_access_token;
		self::$_request_data['account_id'] = self::$_account_id;
		self::$_request_data['format'] = 'json';

		// Is this a get request? If so tack on the params.
		if($type == 'get')
		{
			self::$_request_url = self::$_request_url . '?' . http_build_query(self::$_request_data);
		}

		// Setup request.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$_request_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
		
		// Is this a post requests?
		if($type == 'post')
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, self::$_request_data);
		}
		
		// Send and decode the request.
		self::$_raw_response = curl_exec($ch);
		self::$_response = json_decode(self::$_raw_response, TRUE);
		self::$_request_data = array();
		self::$_request_url = '';
		curl_close($ch);
		
		// Make sure status was returned
		if(! isset(self::$_response['status']))
		{
			self::$_error[] = array('error' => 'Request failed', 'field' => 'N/A');
			return 0;
		}
		
		// Check for any errors.
		if(self::$_response['status'] == 0)
		{
			self::$_error = self::$_response['errors'];
			return false;
		}

		return self::$_response;
	}
}

/* End File */