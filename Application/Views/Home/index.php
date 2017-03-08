<!DOCTYPE html>
<!-- {#CACHE:FLUSH!} || {#CACHE:EXCLUDE!}
	* You can use these tags to disable this page from caching.
	* Remove (#) from {#CACHE:FLUSH!} to flush cached files if this page requires cache to be cleared.
	* Remove (#) from {#CACHE:EXCLUDE!} to EXCLUDE this page from caching.
	* To remove cached files please pass this parameter to any page ( GET: ?Action=FlushCache )
	-->

<head>
	<title><?=l('PAGE_TITLE');?></title>
	<link rel="icon" href="<?= SITEBASE; ?>/favicon.png">
	<style>
		body {
			background-color: #2D2D2D;
		}

		h1 {
			color: #C26356;
			font-size: 30px;
			font-family: Menlo, Monaco, fixed-width;
		}

		p {
			color: white;
			font-family: "Source Code Pro", Menlo, Monaco, fixed-width;
		}
    small {
      color: white;
			font-family: "Source Code Pro", Menlo, Monaco, fixed-width;
      font-size: 12px;
    }
	</style>
</head>
<body>
	<div align="center"><br><h1>Hello World!</h1>

    <br>

	<?
	
	// This function associated to the child controller (Views/Home/Controllers/HomeChildController.php);
	t( $this->HomeChildController->SayHello() );

	// This function associated to the controller (Controllers/HomeController.php);
	//  p_array( $this->HomeController->PerformSQLTest() );
	?>

  <div align="center"><small><p><?=	t( Request("testParameter") ); ?></p>© 2017 Dr. Hazem Ali, All rights reserved.</small></div>
</body>
</html>
