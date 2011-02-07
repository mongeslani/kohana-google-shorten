KO3 Google URL Shortener API Wrapper
====================================

Just a simple [google URL shortener API](http://code.google.com/apis/urlshortener/) wrapper.

To Use
-------
1. Copy the module in your Modules directory
2. Include it in your application's bootstrap: 'kohana-google-shorten' => MODPATH.'kohana-google-shorten'
3. Checkout sample codes below:

Sample Code
------------




** Shorten URLs **

		$key = '<your API key>'; // optional, [read here](https://code.google.com/apis/console)
		$longUrl = 'http://www.mongeslani.com';
		$shortUrl = shorten::shorten($longUrl, $key);

** Expand shortened URLs **

		$key = '<your API key>'; // optional, [read here](https://code.google.com/apis/console)
		$shortUrl = 'http://goo.gl/N4mCb';
		$longUrl = shorten::expand($shortUrl, $key);

** Get Analytics for the shortened URLs **

		$key = '<your API key>'; // optional, [read here](https://code.google.com/apis/console)
		$shortUrl = 'http://goo.gl/N4mCb';
		$longUrl = shorten::expand($shortUrl, $key, TRUE); // same as expanding but with additional parameter

Issues
-------
Please report it to the [issues tracker](http://github.com/mongeslani/kohana-google-shorten/issues).