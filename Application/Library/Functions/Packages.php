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

  function unzipPackage($Name, $Path)
    {
      $zip = new ZipArchive;
      if ($zip->open($Name) === TRUE) {
          $zip->extractTo($Path);
          $zip->close();
          return true;
      } else {
          return false;
      }
    }