<?php
@session_start();
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
  define('BASEPATH', __DIR__);
  require("Application/Global.php");

  if (USE_ROUTER)
    {
      global $Core;
      $router = new Router();
      $router->setBasePath(APPDIR.'/');
      require_once BASEPATH.'/Router.php';



      $Boot = new Boot($router);

      /*$router->map('GET', '/home', function() {
          $Core->loadPage('Home/index.php');
      });
      /* Match the current request */
      $match = $router->match();
      if( $match && is_callable( $match['target'] ) ) {
      	call_user_func_array( $match['target'], $match['params'] );

      }
      else {

        header("HTTP/1.0 404 Not Found");
        require MAINDIR.'Library/System/html/404.html';
      }
      /* Setup the URL routing. This is production ready. */
    }
@ob_end_flush();
