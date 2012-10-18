<?php

class Elevation
{
	private static $_api_base = 'http://dev.elevationfit.dev/api/';
	
	static public function test()
	{
		echo "ASDFASDFASDF";
	}
	
	// ------------------ Exercises -------------------------- //

	//
	// Exercises - get
	//
	static public function exercises_get()
	{
		$url = self::$_api_base . 'exercises/get/format/json/limit/3';
		echo $url;
		
/*
		if($rt = file_get_contents($url))
		{
			$data = json_decode($rt, TRUE);
			return $data['data'];
		}
*/
		
		return array();
	} 
}

/* End File */