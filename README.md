# curl

A basic PHP Restful Client based on curl (see [http://php.net/curl](http://php.net/curl) for more information about the libcurl extension for PHP)


## Installation

	composer require emil/restful


## Usage

### Initialization

Simply require the composer autoloader

	require_once 'vendor/autoload.php';
	$response = Emil\Restful\Request::get('http://google.com')->send();


### Performing a Request

You can set different types of requests and params associated with the request. E.g:

	use Emil\Restful\Request;

	$res = Request::get('http://google.com', ['q' => 'testquery'])->send();
	
	$res = Request::post('http://google.com')
		->params(['q' => 'testquery'])
		->send();
	
	$res = Request::put('http://google.com')
		->param('q', 'testquery')
		->send();

To use a custom request methods, you can call the `custom` method:

	$res = Request::custom('YOUR_CUSTOM_REQUEST_TYPE', $url, $vars = array())->send();


Request parameters will get merge with any query strings:

	$response = $curl->get('google.com?q=test');

	# The Curl object will append '&some_variable=some_value' to the url
	$response = $curl->get('google.com?q=test', array('some_variable' => 'some_value'));
	
	$response = $curl->post('test.com/posts', array('title' => 'Test', 'body' => 'This is a test'));

All requests return a CurlResponse object (see below) or false if an error occurred. You can access the error string with the `$curl->error()` method.


### The CurlResponse Object

A normal CURL request will return the headers and the body in one response string. This class parses the two and places them into separate properties.

For example

	$response = $curl->get('google.com');
	echo $response->body; # A string containing everything in the response except for the headers
	print_r($response->headers); # An associative array containing the response headers

Which would display something like

	<html>
	<head>
	<title>Google.com</title>
	</head>
	<body>
	Some more html...
	</body>
	</html>

	Array
	(
	    [Http-Version] => 1.0
	    [Status-Code] => 200
	    [Status] => 200 OK
	    [Cache-Control] => private
	    [Content-Type] => text/html; charset=ISO-8859-1
	    [Date] => Wed, 07 May 2008 21:43:48 GMT
	    [Server] => gws
	    [Connection] => close
	)
	
The CurlResponse class defines the magic [__toString()](http://php.net/__toString) method which will return the response body, so `echo $response` is the same as `echo $response->body`


### Cookie Sessions

By default, cookies will be stored in a file located in the support directory.
This allows you to maintain a session across requests.


### Basic Configuration Options

You can easily set the referer and/or user-agent

	Request::get('http://example.com')
		->referer('http://google.com')
		->agent('some user agent string')
		->send();

You may even set these headers manually if you wish (see below)

### Setting Custom Headers

You can set custom headers to send with the request

	Request::get('http://example.com')
		->header('Host', '12.345.678.90');
		->header('Some-Custom-Header', 'Some Custom Value')
		->send();


### Setting Custom CURL request options

By default, the `Curl` object will follow redirects. You can disable this by setting:

	$curl->get('http://example.com')->nofollow()->send();

You can set/override many different options for CURL requests (see the [curl_setopt documentation](http://php.net/curl_setopt) for a list of them)

	# any of these will work
	Request::get('http://google.com')
		->option('AUTOREFERER', true)
		->option('autoreferer', true)
		->send();

	Request::get('http://google.com')
		->options(['CURLOPT_AUTOREFERER' => true, 'curlopt_autoreferer' => true])
		->send();

You can also retreive options in a similar fashion

	$req = Request::get('http://example.com')->option('my_option', true);
	$req->option('my_option'); // true
	$req->options(); // all options
	$req->send();

## Testing

Coming soon

