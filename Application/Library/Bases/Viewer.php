<?php
/**
 *
 */
Class Viewer extends Controller
{
  public $view;

  public function __construct()
    {
      $this->load = new Loader();
      $this->Runtime = new Runtime();
      // $this->getReady();
      $this->Cache = new Cache();
    }

  public function render($File, $Params = null, $SkipCaching = false, $Parses = null, $cFilters = null)
    {
      try
      {

        global $startScriptTime;
        if ($Params == true) { $SkipCaching = true; }
        if (is_array($SkipCaching)) { $Parses = $SkipCaching; $SkipCaching = false; }
        // This will set SkipCaching to True when passed as 2nd param.

        if (USE_CACHE && $SkipCaching == false) { $this->Cache->Start(); }
          $this->AnalyzeLangugage();

        $Path = explode('/', $File);
        if (is_dir(VW_DIR.$Path[0]."/Controllers/"))
        {

           $Controllers = scandir(VW_DIR.$Path[0]."/Controllers/");
          if (isset($Controllers) && is_array($Controllers))
            {
              foreach ($Controllers as $Controller)
                {
                  if (strpos($Controller, '.php') !== false) {


                   require_once(VW_DIR.$Path[0]."/Controllers/$Controller");
                    $this->RegController(VW_DIR.$Path[0]."/Controllers/$Controller");
                    $this->Runtime->ReportController(VW_DIR.$Path[0]."/Controllers/".$Controller);
                  }
                }
            }
        }

         if (!file_exists(VW_DIR.$File)){
           throw new Exception("Viewer : [ VW_DIR.$File ] Doesn't Exists in Views Folder!", 5);

         }

         if (is_array($Params) && !empty($Params) && $Params != null){
           global $_REQUEST;
           $_REQUEST = $Params;
          }


        if (is_array($Parses) && !empty($Parses) && $Parses != null){
           if (class_exists("TemplateEngine")) {
             $UI_Content = file_get_contents(VW_DIR.$File);
             $te = new TemplateEngine($UI_Content);

             if ($cFilters != null && is_array($cFilters) && !empty($cFilters)){
               foreach ($cFilters as $key => $value) {

                 if (!empty($key)){
                   $te->addFilter ($key, $value);
                 }
               }
             }

             global $lang;
             $TParses = array_merge($lang, $Parses);
             echo $te->apply ($TParses);

           }else { require_once(VW_DIR.$File); }
           }
           else {
          require_once(VW_DIR.$File);
          }
        $this->Runtime->ReportPage(VW_DIR.$File);

        if (USE_CACHE && $SkipCaching == false) { $this->Cache->End(); }
        if (DEVELOPMENT_MODE == TRUE)
          {
            require_once(SYS_VIEWS."/php/DevTools.php");
          }
      }
      catch(Exception $e)
      {
        throw new Exception($e->getMessage(), 1);

      }

    }




}
