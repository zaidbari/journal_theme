<?php


namespace App\Lib;


class HTTPRequester
{
	/**
	 * @description Make HTTP-GET call
	 *
	 * @param       $url
	 * @param array $params
	 * @param int|array $headers
	 *
	 * @return array|false|string HTTP-Response body or an empty string if the request fails or is empty
	 */
	public static function HTTPGet( $url, array $params = [], $headers = [] )
	{
		$query = http_build_query($params);
		$ch = curl_init($url . '?' . $query);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$response = curl_exec($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$body = substr($response, $header_size);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);
		return ["header" => $header, "body" => json_decode($body), "code" => $code];
	}

	/**
	 * @description Make HTTP-POST call
	 *
	 * @param       $url
	 * @param array $params
	 *
	 * @return array|false|string HTTP-Response body or an empty string if the request fails or is empty
	 */
	public static function HTTPPost( $url, array $params )
	{
		$query = http_build_query($params);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
		$response = curl_exec($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$body = substr($response, $header_size);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);
		return json_encode(["header" => $header, "body" => $body, "code" => $code]);
	}
}