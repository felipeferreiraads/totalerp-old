<?php 
class HttpRequest {
	
	function post($url, $params=array(), $returnType = 'xml'){
		$postData = http_build_query($params); 
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POST, count($postData));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		$output=curl_exec($ch);
		curl_close($ch);
		$xml = simplexml_load_string($output, "SimpleXMLElement", LIBXML_NOCDATA);
		$json = json_encode($xml);
		$array = json_decode($json,TRUE);
		switch ($returnType){
			case 'json':
				return $json;
			case 'array':
				return $array;
			default:
				return $output;
		}
	}
}