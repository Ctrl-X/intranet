<?php
	function in_array_r($needle, $haystack, $strict = true)
	{
		foreach($haystack as $item)
			if(($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict)))
				return true;
				
		return false;
	}
	
	function _exist ($champList, $method = 'both')
	{
		switch(strtolower($method))
		{
			case 'post'	: $global = $_POST; break; 
			case 'get'	: $global = $_GET; break; 
			default		: $global = $_REQUEST; break; 
		}
		
		for($i = 0; $i < count($champList); $i++)
		{
			$key = $champList[$i]; 
			if(!isset($global[$key]) || empty($global[$key]))
				return false; 
		}
		
		return true; 
	}
	
	function _echo ($str, $end = null, $start = 0)
	{
		return Brain::_echo($str, $end, $start); 
	}
	
	function _text ($text, $end = null, $start = 0)
	{
		return Brain::_text($text, $end, $start); 
	}
	
	function _mysql_query  ($sql, $secure = true)
	{
		return Brain::mysqlQuery($sql, $secure); 
	}
?>