<?php
/**
 * Author: Kyle Ossinger
 * Date: 2/1/14
 * Time: 10:36 PM
 * Based on the JSON API documentation on https://developers.shodan.io/shodan-rest.html
 * All credit for the Shodan WebAPI goes to Achillean.
 */

/**
 * Class shodan4php
 * Usage: include this file, then use "$shodan = new shodan4php(APIKEY)" with your API key.
 */
class shodan4php {
    private $apikey;
    private $shodan;

    function __construct($apikey)
    {
        $this->apikey = $apikey;
        $this->shodan = "http://www.shodanhq.com/api/";// or is it "https://api.shodan.io/";

    }

    /**
     * Base request method.  All other Shodan methods will go through this.
     *
     * @param $mode string : The ShodanHQ API method you wish to invoke
     * @param $params array : The associated parameters with that method.
     * @return array : key-value encoded array derived from the JSON output of ShodanHQ's API
     */
    function request($mode, $params)
    {
        //ini_set('max_execution_time', 1000);
        //set_time_limit(0);
        $qry = http_build_query($params + array('key'=>$this->apikey));
        $url =  $this->shodan . $mode . '?' . $qry;
        $r = curl_init($url);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($r);
        curl_close($r);
        return json_decode($content,true);
    }

    /**
     * @return array: key-value array containing what features a user has unlocked, and how many queries are left.
     */
    function info()
    {
        return $this->request("info",array());
    }

    /**
     * This method does not affect the user's query limit.  Use this to make sure you have enough results to warrant
     * a search.
     * @param $q string :  query to search for.
     * @return array : key-value encoded array containing the count of how many hosts match this query
     */
    function count($q)
    {
        return $this->request("count",array("q"=>$q));
    }

    /**
     * Finds all results pertaining to a certain IP address.
     * @param $ip string : IP address you would like ShodanHQ to find associated devices for.
     * @return array :  key-value encoded array containing the device information for specified host
     */
    function host($ip)
    {
        return $this->request("host",array("ip"=>$ip));
    }

    /**
     * Finds top 10,000 countries/cities that match a query.
     * @param $q string :  query to search for.
     * @return array : key-value encoded array containing the top 10,000 countries matching your query
     */
    function locations($q)
    {
        return $this->request("locations",array("q"=>$q));
    }

    /**
     * The classic search method.  Searches shodan for the specified query.
     * The following filters require 1 credit:
     *  *   city
     *  *   country
     *  *   net
     *  *   geo
     *  *   before
     *  *   after
     *  *   org
     *  *   isp
     *  *   title
     *  *   html
     * @param $q string : query to search for
     * @param $p int : page number (page > 1 requires 1 credit)
     * @return array : key-value encoded array containing the results of your search.
     */
    function search($q,$p=1)
    {
        return $this->request("search",array("q"=>$q,"p"=>$p));
    }

} 