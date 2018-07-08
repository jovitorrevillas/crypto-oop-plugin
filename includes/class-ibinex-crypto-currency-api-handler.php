<?php
/**
 * The class responsible for handling all the actions of the
 * Cryptocompare API
 *
 * @package crypto_handler
 * @since   1.0.0
 */
/**
 * Provides attributes and functionality responsible for handling
 * the cryptocompare API.
 *
 * @package crypto_handler
 * @since   1.0.0
 */
class API_Hander_Exception extends ErrorException {};

class API_Handler
{
	protected $url;     // API base URL
	protected $version; // API version
	protected $request;
	protected $queries;
    protected $curl;    // curl handle

	/**
	 * Constructor for KrakenAPI
	 *
	 * @param string $key API key
	 * @param string $secret API secret
	 * @param string $url base URL for Kraken API
	 * @param string $version API version
	 * @param bool $sslverify enable/disable SSL peer verification.  disable if using beta.api.kraken.com
	 */
	function __construct($url='https://min-api.cryptocompare.com', $version='0', $sslverify=true)
	{
		$this->queries = array();
		$this->url = $url;
		$this->version = $version;
		$this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_SSL_VERIFYPEER => $sslverify,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT => 'Kraken PHP API Agent',
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true)
        );
	}

	/**
	 * Query public methods
	 *
	 * @param string $method method name
	 * @param array $request request parameters
	 * @return array request result on success
	 * @throws API_Hander_Exception
	 */
	function add_queries(array $requests = array())
	{
		if(!is_array($requests))
			throw new API_Hander_Exception('Not an array');

		foreach($requests as $request)
			if(array_key_exists($request, $this->queries))
				throw new API_Hander_Exception('Key already exist in the array');
		
		$this->queries = array_merge($this->queries, $requests);
	}

	/**
	 * Query public methods.
	 * 
	 * List of methods you can use:
	 * 
	 * all/coinlist - Returns all the coins that CryptoCompare has added to the website.

	 * price - Get the current price of any cryptocurrency in any other currency that you need.
	 * e.g: price?fsym=BTC&tsyms=USD,JPY,EUR
	 * 
	 * pricemulti - Same as single API path but with multiple from symbols.
	 * e.g: pricemulti?fsyms=BTC,ETH&tsyms=USD,EUR
	 * 
	 * pricemultifull - Get all the current trading info (price, vol, open, high, low etc)
	 * of any list of cryptocurrencies in any other currency that you need.
	 * e.g: pricemultifull?fsyms=BTC&tsyms=USD,EUR
	 *
	 * @param string $method method name
	 * @throws API_Hander_Exception
	 */
	function run($method)
	{
		// build the POST data string
		$postdata = http_build_query($this->queries);

		
		// make request
		curl_setopt($this->curl, CURLOPT_URL, $this->url . '/data/' . $method . '?' . $postdata);
		// curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt($this->curl, CURLOPT_HTTPHEADER, array());
		$result = curl_exec($this->curl);
		if($result===false)
			throw new API_Hander_Exception('CURL error: ' . curl_error($this->curl));

		// decode results
		$result = json_decode($result, true);
		if(!is_array($result))
			throw new API_Hander_Exception('JSON decode error');

		return $result;

	}
}
