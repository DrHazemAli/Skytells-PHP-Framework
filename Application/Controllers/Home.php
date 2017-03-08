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
  Class Home extends Controller
    {

      public function __construct($ref = "Core")
        {
          // This is important to include in __construct function for each controller you create.
          // To get the required method, functions, engines and etc from the base controller.
          parent::__construct();

          // Log some text in the Console.
          //$this->console->log("HomeController has been called from ". $ref);

          // --------- TemplateEngine Library ( Optional )
          /* Please refere to the Docs. to learn how to use it.
           * $this->load->engine("TemplateEngine");


          // --------- Sessions Handling.
          /*
          Session::set("testSessionKey", "Value");
          $Val = Session::get("testSessionKey");
          if ($Val != false)
          {
            // If Session Key is exist.
            t($Val);
          }
          */

          // --------- Reporting Controller.
          /* $this->Runtime = new Runtime();
          $this->Runtime->ReportController(__FILE__); */



          // -------- Displaying Index when Controller being called.

        }

      /**
       * @method Index function.
       * This function cannot be deleted.
       */
      public function index($arg1 = "", $arg2 = "")
        {
          // Access this function from ( http://www.domain.com/{Framework_FOLDER}/HomeController/index/ )

        //  $this->load->helper("HtmlParser");
        //  $html = file_get_html("http://localhost/Framework");
        //  echo $html;
      
        $this->view->HelloWorld = $this->SayHello();
        $this->view->render("index.php");

        }


      protected function SayHello()
        {
          return "<p>Thanks for using Skytells Framework for PHP.</p>
            <p>This is a Hello World Example!</p>
            <p>You can change this Page by going to : Application/Views/Home/Index.php</p>
            <p>You're free to customize your Web-Application As ( MVC or MVHC ) or Even ( Static ) Method.</p>
            <p>You're free to Add your Classes, Controllers, Functions, Modules into (Views) Folder.</p>
            <p> This text comes from your default controller (Application/Controllers/Home.php)</p></div><br>";
        }
    }
