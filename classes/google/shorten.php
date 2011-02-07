<?php defined('SYSPATH') or die('No direct script access.');

class Google_Shorten
{
	public static function shorten($url = '', $key = FALSE)
	{
		$results = '{"error" : "invalid url"}';

		// check if url is valid
		if ( ! shorten::url_valid($url))
		{
			return $results;
		}

		$key = $key ? '?key='.$key : '';

		try {

			$results = remote::get('https://www.googleapis.com/urlshortener/v1/url'.$key, array(
							CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
							CURLOPT_POSTFIELDS     => json_encode(array('longUrl' => $url)),
							CURLOPT_SSL_VERIFYPEER => FALSE
						));

		} catch(ErrorException $e) {

			// do nothing

		}

		return $results;
	}

	public static function expand($url = '', $key = FALSE, $analytics = FALSE)
	{
		$results = '{"error" : "invalid url"}';

		preg_match('@^(?:http://)?([^/]+)@i', $url, $matches);
		$host = $matches[1];

		// check if url is valid or hostname is not goo.gl
		if ( ! shorten::url_valid($url) OR $host != 'goo.gl')
		{
			return $results;
		}

		$key = $key ? '&key='.$key : '';
		$analytics = $analytics ? '&projection=FULL' : '';

		try {

			$results = remote::get('https://www.googleapis.com/urlshortener/v1/url?shortUrl=' . $url . $key . $analytics, array(
							CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
							CURLOPT_SSL_VERIFYPEER => FALSE
						));

		} catch(ErrorException $e) {

			// do nothing

		}

		return $results;
	}

	// Copied from Validate Library to remove dependency
	public static function url_valid($url)
	{
		// Based on http://www.apps.ietf.org/rfc/rfc1738.html#sec-5
		if ( ! preg_match(
			'~^

			# scheme
			[-a-z0-9+.]++://

			# username:password (optional)
			(?:
				    [-a-z0-9$_.+!*\'(),;?&=%]++   # username
				(?::[-a-z0-9$_.+!*\'(),;?&=%]++)? # password (optional)
				@
			)?

			(?:
				# ip address
				\d{1,3}+(?:\.\d{1,3}+){3}+

				| # or

				# hostname (captured)
				(
					     (?!-)[-a-z0-9]{1,63}+(?<!-)
					(?:\.(?!-)[-a-z0-9]{1,63}+(?<!-)){0,126}+
				)
			)

			# port (optional)
			(?::\d{1,5}+)?

			# path (optional)
			(?:/.*)?

			$~iDx', $url, $matches))
			return FALSE;

		// We matched an IP address
		if ( ! isset($matches[1]))
			return TRUE;

		// Check maximum length of the whole hostname
		// http://en.wikipedia.org/wiki/Domain_name#cite_note-0
		if (strlen($matches[1]) > 253)
			return FALSE;

		// An extra check for the top level domain
		// It must start with a letter
		$tld = ltrim(substr($matches[1], (int) strrpos($matches[1], '.')), '.');
		return ctype_alpha($tld[0]);
	}
}