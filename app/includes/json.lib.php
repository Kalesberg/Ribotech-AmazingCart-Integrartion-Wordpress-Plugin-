<?php
	function json_custom_encode($array=false)
	{
		if(is_null($array)) return 'null';
		if($array === false) return 'false';
		if($array === true) return 'true';
		if(is_scalar($array))
		{
			if(is_float($array))
			{
				return floatval(str_replace(",", ".", strval($array)));
			}

			if(is_string($array))
			{
				static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
				return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $array) . '"';
			}
			else return $array;
		}

		$isList = true;
		for($i=0, reset($array); $i<count($array); $i++, next($array))
		{
			if(key($array) !== $i)
			{
				$isList = false;
				break;
			}
		}

		$result = array();
		if($isList)
		{
			foreach($array as $v) $result[] = json_encode($v);
			return '[' . join(',', $result) . ']';
		}
		else 
		{
			foreach ($array as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
			return '{' . join(',', $result) . '}';
		}
	}
	
	function json_response($state, $responsedata)
	{
		$response['status'] = $state;
		$response['results'] = $responsedata;
		return json_custom_encode($response);
	}
?>