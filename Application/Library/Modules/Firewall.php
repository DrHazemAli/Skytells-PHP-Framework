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
Class Firewall extends Controller
  {
    public $DDOS_EXP = 1;
    public function __construct()
      {
        if (MD_Firewall == TRUE)
          {
            $this->SecureUris();
            if (FIREWALL_ANTI_DDOS)
              {
                $this->AntiDDoS();
                $this->DDOS_EXP = FIREWALL_ANTI_DDOS_INTERVAL;
              }
              if (FCHECK_BROWSER && FCHECK_BROWSER == true) {
                $this->CheckComputer();
              }
          }
      }

    public function CheckComputer()
      {

        global $_COOKIE;
        if (isset($_COOKIE["cn_cls"]) && $_COOKIE["cn_cls"] == md5("1")){
          @header("Connection: close\r\n");
          exit("Banned!");
        }
        if (!isset($_COOKIE["fw_bw"]) || empty($_COOKIE["fw_bw"]) || $_COOKIE["fw_bw"] != md5(STRONG_ENC_KEY))
          {
            $_RED_URI = getUrl();
            include(SYS_VIEWS."/php/scanner.php");
            if (checkBrowser() == true) {
           @setcookie("fw_bw", md5(STRONG_ENC_KEY), 7000+time());
         }else {
             @setcookie("cn_cls", md5("1"), 200+time());
         }


            exit;
          }
      }
    public function SecureUris()
      {
          // get the current url
          $inurl = $_SERVER['REQUEST_URI'];
          if (preg_match("#select|update|delete|concat|create|table|union|length|show_table|mysql_list_tables|mysql_list_fields|mysql_list_dbs#i", $inurl))
          {
            exit(include(SYS_VIEWS."/html/security_warning.html"));
          }
           $securityUlrs_url = $_SERVER['QUERY_STRING'];
           if ($securityUlrs_url != '' AND !preg_match("/^[_a-zA-Z0-9-=&]+$/", $securityUlrs_url))
           {
            exit(include(SYS_VIEWS."/html/security_warning.html"));
           }
           return true;
      }

    public function getClean($txt){
        $txt = htmlspecialchars($txt);
        $txt = str_replace("select","5ev1ect",$txt);
        $txt = str_replace("update","upd4tee",$txt);
        $txt = str_replace("insert","1dn5yert",$txt);
        $txt = str_replace("where","w6eere",$txt);
        $txt = str_replace("like","1insk",$txt);
        $txt = str_replace("or","08r",$txt);
        $txt = str_replace("and","4nd",$txt);
        $txt = str_replace("set","5eut",$txt);
        $txt = str_replace("into","1n8t0",$txt);
        $txt = str_replace("'", "", $txt);
        $txt = str_replace(";", "", $txt);
        $txt = str_replace(">", "", $txt);
        $txt = str_replace("<", "", $txt);
        $txt = strip_tags($txt);
        return $txt;
    }

    public function get_ip()
     {
           if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                 $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
           }
           elseif(isset($_SERVER['HTTP_CLIENT_IP'])) {
                 $ip = $_SERVER['HTTP_CLIENT_IP'];
           }
           else {
                 $ip = $_SERVER['REMOTE_ADDR'];
           }
           return $ip;
     }

    public function AntiDDoS()
      {
        try {
          // Assuming session is already started
            global $_SESSION; global $_SERVER;
            $uri = md5($_SERVER['REQUEST_URI']);
            $expireIn = time()+$this->DDOS_EXP;
            $hash = $uri .'|'. $expireIn;
            if (isset($_SESSION["Firewall_AntiDDoS"]) && !empty($_SESSION["Firewall_AntiDDoS"])) {


            $FDcheck = explode('|', $_SESSION["Firewall_AntiDDoS"]);
            $tmcheck = $FDcheck[1];
            //exit(time() . "<br>". $tmcheck);
            if ($FDcheck[0] == $uri && time() < $tmcheck) {
                header('HTTP/1.1 503 Service Unavailable');

                 die("<title>Security Warning</title><h1>DDoS Attack Detected!</h1>
                 <hr>
                      <p>Our System observed illegal requests during connecting our server!</p>
                      <p>You have been blocked from accessing our server for 1 minute due to illegal activity.</p>
                      ");
                die;
            }
          }
            // Save last request

            $_SESSION["Firewall_AntiDDoS"] = $hash;

        }
        catch(Exception $e)
          {
            $this->Debugger->ShowError($e);
          }
      }
  }

 ?>
