<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>  
  <head>  
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
    <meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1.0">  
    <link href="css/main.css" rel="stylesheet" media="screen"/>  
    <script src="js/jquery-1.4.1.min.js" type="text/javascript"></script><!-- don't remove --> 
    <script src="js/jquery.getParams.js" type="text/javascript"></script><!-- don't remove -->
    <script src="js/css_selector.js" type="text/javascript"></script><!-- don't remove -->
    
    <script type="text/javascript">/* <![CDATA[ */
      $(function(){
	//Parameter for type
	htype = $.getUrlVar("type");
	//Parameter for zone
	zone = $.getUrlVar("zone");
	$("#crumbs").html(htype + "<span class='chevron'/>" + zone);

	//show hide li's
	if (zone && htype==="Helicopter") {
	  $('li.' + zone).removeClass('hidden');
	};

      });
    /* ]]> */
    </script>
     
  </head>  
  <body>  
    <h1><div class="leftButton" onclick="location.href='zone.php?type=<?= $_GET['type'] ?>'">Back</div>AEROTOUR INTRANET</h1>  
    <h2><span id="crumbs"></span> <span class='chevron'></span> Select destination</h2>  
    <ul class="menu">
      <li class="Puntarenas hidden"><a href="plan.php?type=<?= $_GET['type'] ?>&zone=<?= $_GET['zone'] ?>&point=MRQP" class="arrow icon iicon"><em class="ii-flag"></em> Quepos</a></li>
      <li class="Puntarenas hidden"><a href="plan.php?type=<?= $_GET['type'] ?>&zone=<?= $_GET['zone'] ?>&point=MRRM" class="arrow icon iicon"><em class="ii-flag"></em> Los Suenos</a></li>
      <li class="Guanacaste hidden"><a href="plan.php?type=<?= $_GET['type'] ?>&zone=<?= $_GET['zone'] ?>&point=MRLB" class="arrow icon iicon"><em class="ii-flag"></em> Liberia</a></li>
      <li class="Guanacaste hidden"><a href="plan.php?type=<?= $_GET['type'] ?>&zone=<?= $_GET['zone'] ?>&point=MRCR" class="arrow icon iicon"><em class="ii-flag"></em> Carillo</a></li>
    </ul>  
  </body>  
</html>
