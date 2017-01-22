<?php
if (!defined("IOHOST")) require "config.inc.php";
class ControlIO {


    function __construct() {}
    function changeRelayState($relaisID,$onoff) {
        $onoff=($onoff)? "1":"0";
        $params=array("value"=>$onoff);
        $answer=$this->httpRequest("POST","/rest/relay/".$relaisID,$params);
    }
    function getSensor($sensorID) {
        $params=array();
        $res=$this->httpRequest("GET","/rest/sensor/".$sensorID."/value",$params);
        $value=explode('"value": ',$res);
        if (isset($value[1])) return substr($value[1],0,-1);
        else return 0;
    }
    function getAnalogInput($analogID) {
        $params=array();
        $res=$this->httpRequest("GET","/rest/ai/".$analogID."/value",$params);
        $value=explode('"value": ',$res);
        $value=substr($value[1],0,-1);
        $value=(3.04-$value)*100;
        return $value;
    }
    function httpRequest($method, $path, $params) {
        $host=IOHOST;
        $port=IOPORT;
      // Params are a map from names to values
      $paramStr = "";
      foreach ($params as $name=>$val) {
        $paramStr .= $name . "=";
        $paramStr .= urlencode($val);
        $paramStr .= "&";
      }

      // Assign defaults to $method and $port, if needed
      if (empty($method)) {
        $method = 'GET';
      }
      $method = strtoupper($method);
      if (empty($port)) {
        $port = 80; // Default HTTP port
      }

      // Create the connection
      $sock = fsockopen($host, $port);
      if ($method == "GET") {
        $path .= "?" . $paramStr;
      }
      fputs($sock, "$method $path HTTP/1.1\r\n");
      fputs($sock, "Host: $host\r\n");
      fputs($sock, "Content-type: " .
                   "application/x-www-form-urlencoded\r\n");
      if ($method == "POST") {
        fputs($sock, "Content-length: " .
                     strlen($paramStr) . "\r\n");
      }
      fputs($sock, "Connection: close\r\n\r\n");
      if ($method == "POST") {
        fputs($sock, $paramStr);
      }
      // Buffer the result
      $result = "";
      while (!feof($sock)) {
        $result .= fgets($sock,1024);
      }

      fclose($sock);
      return $result;
    }

}

?>
