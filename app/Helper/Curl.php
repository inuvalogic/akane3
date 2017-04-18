<?php

namespace Akane\Helper;

class Curl
{
	private $httpStatus, $result, $requestHeaders, $responseHeaders;

	public function request($method='GET', $url = '', $data = array(), $headers = array())
	{
		if ($method=='GET'){
			if (is_array($data) && count($data)>0){
				$params = http_build_query($data);
				$url = $url.'?'.$params;
			}
		}

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		switch ($method) {
		    case 'POST':
		        curl_setopt($ch, CURLOPT_POST, true);
		        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		        break;
		    case 'PUT':
		    case 'PATCH':
		    case 'DELETE':
		        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		        break;
		}

		if ($method != NULL) {
		    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		}

		$result = curl_exec($ch);
		$this->httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$this->requestHeaders = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		$responseHeaderSize = strlen($result) - curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
		$this->responseHeaders = substr($result, 0, $responseHeaderSize);
		$this->result = substr($result, $responseHeaderSize);

		curl_close($ch);		
	}

	public function getStatusCode()
	{
		return $this->httpStatus;
	}

	public function getBody()
	{
		return $this->result;
	}
}