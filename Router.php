<?
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


 /**
  * Map a route to a target
  *
  * @param string $method One of 5 HTTP Methods, or a pipe-separated list of multiple HTTP Methods (GET|POST|PATCH|PUT|DELETE)
  * @param string $route The route regex, custom regex must start with an @. You can use multiple pre-set regex filters, like [i:id]
  * @param mixed $target The target where this route should point to. Can be anything.
  * @param string $name Optional name of this route. Supply if you want to reverse route this url in your application.
  */
  /**  (*)             // Match all request URIs
  [i]                  // Match an integer
  [i:id]               // Match an integer as 'id'
  [a:action]           // Match alphanumeric characters as 'action'
  [h:key]              // Match hexadecimal characters as 'key'
  [:action]            // Match anything up to the next / or end of the URI as 'action'
  [create|edit:action] // Match either 'create' or 'edit' as 'action'
  [*]                  // Catch all (lazy, stops at the next trailing slash)
  [*:trailing]         // Catch all as 'trailing' (lazy)
  [**:trailing]        // Catch all (possessive - will match the rest of the URI)
  .[:format]?          // Match an optional parameter 'format' - a / or . before the block is also optional
  */



  // map homepage ( / ) Without parameters
  //  $router->map('GET|POST', "/",  function(){
  //    loadPage('Home/index.php');

    // });



  // Adding another route for /Home
    $router->map('GET|POST', "/home", function() { loadPage('Home/index.php'); });
