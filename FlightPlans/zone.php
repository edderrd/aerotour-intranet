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
			type = $.getUrlVar("type");
			$("#crumbs").html(type);
		});
	/* ]]> */
	</script>
    
  </head>  
  <body>  
    <h1><div class="leftButton" onclick="history.back();return false">Back</div>AEROTOUR INTRANET</h1>  
    <h2>Select zone of operation <span class='chevron'></span> <span id="crumbs"></span></h2>  
    <ul class="menu">  
      <li><a href="points.php?type=<?= $_GET['type'] ?>&zone=Guanacaste" class="arrow icon iicon"><em class="ii-radar"></em> Guanacaste</a></li>
      <li><a href="points.php?type=<?= $_GET['type'] ?>&zone=Puntarenas" class="arrow icon iicon"><em class="ii-radar"></em> Puntarenas</a></li>
      <li><a href="points.php?type=<?= $_GET['type'] ?>&zone=Heredia" class="arrow icon iicon"><em class="ii-radar"></em> Heredia</a></li>
      <li><a href="points.php?type=<?= $_GET['type'] ?>&zone=Alajuela" class="arrow icon iicon"><em class="ii-radar"></em> Alajuela</a></li>
      <li><a href="points.php?type=<?= $_GET['type'] ?>&zone=SanJose" class="arrow icon iicon"><em class="ii-radar"></em> San Jose</a></li>
      <li><a href="points.php?type=<?= $_GET['type'] ?>&zone=Cartago" class="arrow icon iicon"><em class="ii-radar"></em> Cartago</a></li>
      <li><a href="points.php?type=<?= $_GET['type'] ?>&zone=Limon" class="arrow icon iicon"><em class="ii-radar"></em> Limon</a></li>
    </ul>
  </body>  
</html>