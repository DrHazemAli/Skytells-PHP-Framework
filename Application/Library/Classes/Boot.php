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

 /**
  * @param Boot Controller
  * @var This Class Handles the internal routes for the controllers, Functions, and args..
  * @var PLEASE DO NOT TOUCH THIS CLASS
  */
  Class Boot extends Controller
    {
      public $router;
      public function __construct($_ROUTER){
        //$this->Runtime = new Runtime();
        $this->router = $_ROUTER;
        $this->router->map('GET|POST', "[*:__MVCallback]",  function($__MVCallback){

          if (isset($__MVCallback) && !empty($__MVCallback) && $__MVCallback != ""){
            $_MVURI = $__MVCallback;
            $HomePage = getExplosion($_MVURI, 1);
            if (!isset($HomePage) || is_null($HomePage) || empty($HomePage)){
              if (file_exists(CONTROLLERS_DIR."Home.php") ){
                $Home = new Home();
                  $Home->index();
                  return false;
              }
              loadPage("index.php");
              return false;
            }



              if ( !empty($_ctrlName = getExplosion($_MVURI, 1) ) ){  /* Check if Exists */ if (isClassExist($_ctrlName)) {
                //  $this->Runtime->ReportController(CONTROLLERS_DIR.$_ctrlName.".php");
                $ParentClass = get_parent_class($_ctrlName);
                if ($ParentClass != "Controller")
                {
                  exit("<h1>Access Denied!</h1>You're not allowed to reach this area without third-party permission!");
                }
                $_CTR = new $_ctrlName(); // Create a new Object for the Controller.
                  if ($_funcName = getExplosion($_MVURI, 2)){ /* Check if Exists */  if ( isFunctionExist($_ctrlName, $_funcName ) ){
                              switch ($_MVURI) {
                                case getExplosion($_MVURI, 3) != false && getExplosion($_MVURI, 4) == false:
                                    $_CTR->$_funcName(getExplosion($_MVURI, 3));
                                  break;

                                case getExplosion($_MVURI, 4) != false:
                                      $_CTR->$_funcName(getExplosion($_MVURI, 3), getExplosion($_MVURI, 4));
                                  break;
                                default:
                                  $_CTR->$_funcName(); // If No Args..
                                  break;
                              }
                          }
                      }
                }
              }

          }});
      }
    }
