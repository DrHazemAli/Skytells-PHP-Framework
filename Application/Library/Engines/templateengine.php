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
Class TemplateEngine
{
  const VERSION = "1.0.1";
  private $mTemplateText;
  public $mFilter = [];
  private $mConf = [
      "varStartTag" => "{=",
      "varEndTag" => "}",
      "comStartTag" => "{",
      "comEndTag" => "}"
                    ];
  public function __construct ($text="") {

      $this->mTemplateText = $text;
      $this->mFilter["_DEFAULT_"] = function ($input) { return htmlspecialchars($input); };
      // Raw is only a pseudo-filter. If it is not in the chain of filters, __DEFAULT__ will be appended to the filter
      $this->mFilter["html"] = function ($input) { return htmlspecialchars($input); };
      $this->mFilter["raw"] = function ($input) { return $input; };
      $this->mFilter["singleLine"] = function ($input) { return str_replace("\n", " ", $input); };
      $this->mFilter["inivalue"] = function ($input) { return addslashes(str_replace("\n", " ", $input)); };
      $this->mFilter["uppercase"] = function ($input) { return strtoupper($input); };
      $this->mFilter["lowercase"] = function ($input) { return strtolower($input); };
      $this->mFilter["ucfirst"] = function ($input) { return ucfirst($input); };
  }
  /**
   * Set the default Filter
   *
   * @param $filterName
   */
  public function setDefaultFilter ($filterName) {
      $this->mFilter["_DEFAULT_"] = $this->mFilter[$filterName];
  }
  /**
   * Add a user-defined filter function to the list of available filters.
   *
   * A filter function must accept at least one parameter: input and return the resulting
   * value.
   *
   * Example:
   *
   * addFilter("currency", function (input) {
   *      return number_format ($input, 2, ",", ".");
   * });
   *
   * @param $filterName
   * @param callable $filterFn
   * @return $this
   */
  public function addFilter ($filterName, callable $filterFn) {
    global $filterName;
      $this->mFilter[$filterName] = $filterFn;
      return $this;
  }
  /**
   * Tag-Nesting is done by initially adding an index to both the opening and the
   * closing tag. (By the way some cleanup is done)
   *
   * Example
   *
   * {if xyz}
   * {/if}
   *
   * Becomes:
   *
   * {if0 xyz}
   * {/if0}
   *
   * This trick makes it possible to add tag nesting functionality
   *
   *
   * @param $input
   * @return mixed
   * @throws \Exception
   */

  public function _runInclude($input) {
          $input =  preg_replace_callback( "~{= include (.+?)}~", function($m) { @ob_start(); include(VW_DIR.$m[1]); $output = ob_get_clean(); return $output; }, $input);
          $input =  preg_replace_callback( "~{= include_once (.+?)}~", function($m) { @ob_start(); include_once(VW_DIR.$m[1]); $output = ob_get_clean(); return $output; }, $input);
          $input =  preg_replace_callback( "~{= require (.+?)}~", function($m) { @ob_start(); require(VW_DIR.$m[1]); $output = ob_get_clean(); return $output; }, $input);
          $input =  preg_replace_callback( "~{= require_once (.+?)}~", function($m) { @ob_start(); require_once(VW_DIR.$m[1]); $output = ob_get_clean(); return $output; }, $input);
          $input =  preg_replace_callback( "~{= import (.+?)}~", function($m) { @ob_start(); file_get_contents(VW_DIR.$m[1]); $output = ob_get_clean(); return $output; }, $input);
          @ob_flush();
    return $input;
  }



  public function _replaceNestingLevels ($input) {
      $indexCounter = 0;
      $nestingIndex = [];
      $lines = explode("\n", $input);
      for ($li=0; $li < count ($lines); $li++) {
          $lines[$li] = preg_replace_callback('/\{(?!=)\s*(\/?)\s*([a-z]+)(.*?)\}/im',
              function ($matches) use (&$nestingIndex, &$indexCounter, &$li) {
                  $slash = $matches[1];
                  $tag = $matches[2];
                  $rest = $matches[3];
                  if ($slash == "") {
                      if ( ! isset ($nestingIndex[$tag]))
                          $nestingIndex[$tag] = [];
                      $nestingIndex[$tag][] = [$indexCounter, $li];
                      $out =  "{" . $tag . $indexCounter . rtrim($rest) . "}";
                      $indexCounter++;
                      return $out;
                  } else if ($slash == "/") {
                      if ( ! isset ($nestingIndex[$tag]))
                          throw new \Exception("Line {$li}: Opening tag not found for closing tag: '{$matches[0]}'");
                      if (count ($nestingIndex[$tag]) == 0)
                          throw new \Exception("Line {$li}: Nesting level does not match for closing tag: '{$matches[0]}'");
                      $curIndex = array_pop($nestingIndex[$tag]);
                      return "{/" . $tag . $curIndex[0] . "}";
                  } else {
                      throw new \Exception("Line {$li}: This exception should not appear!");
                  }
              },
              $lines[$li]
          );
      }
      foreach ($nestingIndex as $tag => $curNestingIndex) {
          if (count ($curNestingIndex) > 0)
              throw new \Exception("Unclosed tag '{$tag}' opened in line {$curNestingIndex[0][1]} ");
      }
      return implode ("\n", $lines);
  }
  private function _getValueByName ($context, $name, $softFail=TRUE) {
      $dd = explode (".", $name);
      $value = $context;
      $cur = "";
      foreach ($dd as $cur) {
          if (is_array($value)) {
              if ( ! isset ( $value[$cur] )) {
                  $value = NULL;
              } else {
                  $value = $value[$cur];
              }
          } else {
              if (is_object($value)) {
                  if ( ! isset ( $value->$cur )) {
                      $value = NULL;
                  } else {
                      $value = $value->$cur;
                  }
              } else {
                  if ( ! $softFail) {
                      throw new \Exception("ParsingError: Can't parse element: '{$name}' Error on subelement: '$cur'");
                  }
                  $value = NULL;
              }
          }
      }
      if (is_object($value) && ! method_exists($value, "__toString"))
          $value = "##ERR:OBJECT_IN_TEXT:[{$name}]ON[{$cur}]:" . gettype($value) . "###";
      return $value;
  }
  private function _parseValueOfTags ($context, $block, $softFail=TRUE) {
      $result = preg_replace_callback ("/\\{=(.+?)\\}/im",
          function ($_matches) use ($softFail, $context) {
              $match = $_matches[1];
              $chain = explode("|", $match);
              for ($i=0; $i<count ($chain); $i++)
                  $chain[$i] = trim ($chain[$i]);
              if ( ! in_array("raw", $chain))
                  $chain[] = "_DEFAULT_";
              $varName = trim (array_shift($chain));
              if ($varName === "__CONTEXT__") {
                  $value = "\n----- __CONTEXT__ -----\n" . var_export($context, true) . "\n----- / __CONTEXT__ -----\n";
              } else {
                  $value = $this->_getValueByName($context, $varName, $softFail);
              }

              foreach ($chain as $curName) {


                  if ( ! isset ($this->mFilter[$curName]))
                      //throw new \Exception("Filter '$curName' not defined");
                      global $curName;
                  $fn = $this->mFilter[$curName];
                  $value = $fn($value);
                }

              return $value;
          }, $block);
      return $result;
  }
  private function _runFor ($context, $content, $cmdParam, $softFail=TRUE) {
      if ( ! preg_match ('/([a-z0-9\.\_]+) in ([a-z0-9\.\_]+)/i', $cmdParam, $matches)) {
      }
      $iterateOverName = $matches[2];
      $localName = $matches[1];
      $repeatVal = $this->_getValueByName($context, $iterateOverName, $softFail);
      if ( ! is_array($repeatVal))
          return "";
      $index = 0;
      $result = "";
      foreach ($repeatVal as $key => $curVal) {
          $context[$localName] = $curVal;
          $context["@key"] = $key;
          $context["@index0"] = $index;
          $context["@index1"] = $index+1;
          $curContent = $this->_parseBlock($context, $content, $softFail);
          $curContent = $this->_parseValueOfTags($context, $curContent, $softFail);
          $result .= $curContent;
          $index++;
      }
      return $result;
  }
  private function _getItemValue ($compName, $context) {
      if (preg_match ('/^("|\')(.*?)\1$/i', $compName, $matches))
          return $matches[2]; // string Value
      if (is_numeric($compName))
          return ($compName);
      if (strtoupper($compName) == "FALSE")
          return FALSE;
      if (strtoupper($compName) == "TRUE")
          return TRUE;
      if (strtoupper($compName) == "NULL")
          return NULL;
      return $this->_getValueByName($context, $compName);
  }
  private function _runIf ($context, $content, $cmdParam, $softFail=TRUE) {
      //echo $cmdParam;
      if ( ! preg_match('/([\"\']?.*?[\"\']?)\s*(==|<|>|!=)\s*([\"\']?.*[\"\']?)/i', $cmdParam, $matches))
          return "!! Invalid command sequence: '$cmdParam' !!";
      //print_r ($matches);
      $comp1 = $this->_getItemValue(trim ($matches[1]), $context);
      $comp2 = $this->_getItemValue(trim ($matches[3]), $context);
      //decho $comp1 . $comp2;
      $doIf = FALSE;
      switch ($matches[2]) {
          case "==":
              $doIf = ($comp1 == $comp2);
              break;
          case "!=":
              $doIf = ($comp1 != $comp2);
              break;
          case "<":
              $doIf = ($comp1 < $comp2);
              break;
          case ">":
              $doIf = ($comp1 > $comp2);
              break;
      }
      if ( ! $doIf)
          return "";
      $content = $this->_parseBlock($context, $content, $softFail);
      $content = $this->_parseValueOfTags($context, $content, $softFail);
      return $content;
  }
  private function _parseBlock ($context, $block, $softFail=TRUE) {
      // (?!\{): Lookahead Regex: Don't touch double {{
      $result = preg_replace_callback('/\n?\{(?!=)(([a-z]+)[0-9]+)(.*?)\}(.*?)\n?\{\/\1\}/ism',
          function ($matches) use ($context, $softFail) {
              $command = $matches[2];
              $cmdParam = $matches[3];
              $content = $matches[4];
              switch ($command) {
                  case "for":
                      return $this->_runFor($context, $content, $cmdParam, $softFail);
                  case "if":
                      return $this->_runIf ($context, $content, $cmdParam, $softFail);
                  default:
                      return "!! Invalid command: '$command' !!";
              }
          }, $block);
      if ($result === NULL) {
          throw new \Exception("preg_replace_callback() returned NULL: preg_last_error() returns: " . preg_last_error() . " (error == 2: preg.backtracklimit to low)");
      }
      return $result;
  }
  /**
   * @param $template
   * @return $this
   */
  public function loadTemplate ($template) {
      $this->mTemplateText = $template;
      return $this;
  }
  /**
   * Parse Tokens in Text (Search for $(name.subname.subname)) of
   *
   *
   * @return string
   */
  public function apply ($params, $softFail=TRUE) {
      $text = $this->mTemplateText;
      $context = $params;
      $text = $this->_runInclude($text);
      $text = $this->_replaceNestingLevels($text);
      $text = $this->_parseBlock($context, $text, $softFail);
      $result = $this->_parseValueOfTags($context, $text, $softFail);
      return $result;
  }
}
