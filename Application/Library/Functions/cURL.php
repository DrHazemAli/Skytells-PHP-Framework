<?php
/**
 * Skytells PHP Framework --------------------------------------------------*
 * @category   Web Development ( Programming )
 * @package    Skytells PHP Framework
 * @version 1.2.2
 * @license Freeware
 * @copyright  2007-2017 Skytells, Inc. All rights reserved.
 * @license    https://www.skytells.net/us/terms  Freeware.
 * @author Dr. Hazem Ali ( fb.com/Haz4m )
 * @see The Framework's changelog to be always up to date.
 */
  function HttpRequest($Uri, $Posts = "", $Method = "POST")
  {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Uri);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $Method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $Posts);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));


    $server_output = curl_exec ($ch);
    curl_close ($ch);
    return $server_output;
  }


 function getCurrentUrl($full = true) {
   $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
   return $url;

 }
