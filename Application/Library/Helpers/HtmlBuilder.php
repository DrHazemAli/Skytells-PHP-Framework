<?php
/**
 * Skytells PHP Framework --------------------------------------------------*
 * @category   Web Development ( Programming )
 * @package    Skytells PHP Framework
 * @version 2.0.0
 * @license Freeware
 * @copyright  2007-2017 Skytells, Inc. All rights reserved.
 * @license    https://www.skytells.net/us/terms  Freeware.
 * @author Dr. Hazem Ali ( fb.com/Haz4m )
 * @see The Framework's changelog to be always up to date.
 */
require __DIR__."/HtmlBuilder/HtmlBuilder.forms.php";
require __DIR__."/HtmlBuilder/HtmlBuilder.inputs.php";

  Class HtmlBuilder
    {
      public $forms;
      function __construct()
      {
        try {
          $this->forms = new HB_Forms();
          $this->inputs = new HB_Inputs();
        } catch (Exception $e) {
          throw new Exception($e->getMessage(), 100);

        }

      }



    }
