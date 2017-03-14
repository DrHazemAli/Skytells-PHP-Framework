<?php
@session_start();
/**
 * Skytells PHP Framework --------------------------------------------------*
 * @category   Web Development ( Programming )
 * @package    Skytells PHP Framework
 * @version 1.4.0
 * @license Freeware
 * @copyright  2007-2017 Skytells, Inc. All rights reserved.
 * @license    https://www.skytells.net/us/terms  Freeware.
 * @author Dr. Hazem Ali ( fb.com/Haz4m )
 * @see The Framework's changelog to be always up to date.
 */
  define('BASEPATH', __DIR__);
  require("Application/Global.php");
  global $Core;
  if (USE_CACHE == TRUE) { $Core->Cache->Start(); }
  if (USE_ROUTER)
    {
      $router = new Router();
      $router->setBasePath(APPDIR.'/');
      require_once BASEPATH.'/Application/Misc/Config/Routes.php';
      defineRoutesConfig();
      $Boot = new Boot($router);
      $match = $router->match();
      if( $match && is_callable( $match['target'] ) ) {
      	call_user_func_array( $match['target'], $match['params'] );
      }
      else {
        header("HTTP/1.0 404 Not Found");
        require SYS_VIEWS.'/html/404.html';
      }
      /* Setup the URL routing. This is production ready. */
    }
if (USE_CACHE == TRUE) { $Core->Cache->End(); }
if (DEVELOPMENT_MODE == TRUE) { require_once(SYS_VIEWS."/php/DevTools.php"); }
@ob_end_flush();
