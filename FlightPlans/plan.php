<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>  
  <head>  
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
    <meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1.0">  
    <link href="css/main.css" rel="stylesheet" media="screen"/>
    <link href="css/plan.css" rel="stylesheet" media="screen"/>
    <link href="css/print.css" rel="stylesheet" media="print"/>  
    <script src="js/jquery-1.4.1.min.js" type="text/javascript"></script><!-- don't remove -->
    <script src="js/jquery-ui.js" type="text/javascript"></script><!-- don't remove -->
    <!--<script src="js/iPhone.js" type="text/javascript"></script> don't remove -->
    <script src="js/ui.iTabs.js" type="text/javascript"></script><!-- don't remove -->
    <script src="js/jquery.getParams.js" type="text/javascript"></script><!-- don't remove -->
    <script src="js/css_selector.js" type="text/javascript"></script><!-- don't remove -->
    
    <script src="js/formHighlighter.js" type="text/javascript"></script>

	<script type="text/javascript">/* <![CDATA[ */
		$(function(){
			
			//Parameter for type
			type = $.getUrlVar("type");
			//Parameter for zone
			zone = $.getUrlVar("zone");
			//Parameter for point
			point = $.getUrlVar("point");
			$("#crumbs").html(type + "<span class='chevron'/>" + zone + "<span class='chevron'/>" + point);

			//Get TAF
			$("#metar").load("php/proxy.php?url=http://aviationweather.gov/adds/tafs/index.php?station_ids=MRPV+" + point);
			//$.ajax({
			//	type: "POST",
			//	url: "php/proxy.php?url=http://aviationweather.gov/adds/tafs/index.php?station_ids=MRPV+" + point,
			//	success: function(data) {
			//		$('#metar').html(data);
			//	}
			//});
			
			//Insert value into flight plan
			$("#topPlan").val('MRPV - ' + point);
			
			//Insert value into Header
			$("#headPoint").text('MRPV - ' + point);
			$("#headType").text(type);
			
			//ToggleButton
			$(".btnToggle").click(function(){
				$(".hideMe").toggle();
			});

                        //Make Tabs
                        $(".tabbar").iTabs();
			
		});
	/* ]]> */
	</script>

	<style type="text/css">
		.iphone .tabbar {
			position:relative;
			top:0px;
		}
		.list p{
			padding:16px
		}
	</style>

  </head>  
  <body>  
    <h1 class="noPrint"><div class="leftButton" onclick="history.back();return false">Back</div>Flight <span id="headPoint">Route</span> in a <span id="headType">Aircraft</span><div class="rightButton" onclick="window.print();return false">Print</div></h1>
    <h2>Flight Plan <span class='chevron'></span> <span id="crumbs"></span></h2>  
    <ul>  
      <li class="single">
		<form action="http://#" method="post" enctype="multipart/form-data" name="frm1" id="frm1" onclick="javascript:highlight(event);" onkeyup="javascript:highlight(event)" > 
		  <table class="tableOne" width="100%"   border="1" cellpadding="0" cellspacing="1" > 
		    <tr> 
		      <th width="5%" height="19" align="center" valign="top" style="padding:5px;">HobbsOut </th> 
		      <th width="5%" align="center" valign="top" style="padding:5px;"><nobr>Matricula</nobr></th> 
		      <th width="5%" align="center" valign="top" style="padding:5px;"><nobr>Fuel</nobr></th> 
		      <td width="70%" rowspan="2" align="center" valign="middle">
		      	<input name="textfield4" id="topPlan" type="text" class="inputsAltCentered" value="Flight Plan Name will be here" />
		      </td> 
		      <th width="10%" align="center" valign="top" style="padding:5px;">Pilot</th> 
		      <th width="10%" align="center" valign="top" style="padding:5px;">Co-Pilot</th>
		      <th width="5%" align="center" valign="top" style="padding:5px;"><nobr>Hobbs In</nobr></th> 
		    </tr> 
		    <tr> 
		      <td height="17">&nbsp;</td> 
		      <td><input name="txtMatricula" id="txtMatricula" class="inputs" type="text" width="10%"/></td> 
		      <td><input name="txtFuel" id="txtFuel" class="inputs" type="text" width="10%"/></td> 
		      <td><input name="txtPilotName" id="txtPilotName" class="inputs" type="text" width="80%"/></td> 
		      <td><input name="txtCoPilotName" id="txtCoPilotName" class="inputs" type="text" width="80%"/></td> 
		      <td>&nbsp;</td> 
		    </tr> 
		  </table> 
		  <table class="tableOne" width="100%"  border="1" cellpadding="0" cellspacing="1" > 
		    <tr> 
		      <th width="30%">Field</th> 
		      <th width="20%">R/W</th> 
		      <th width="10%">Unicom</th> 
		      <th width="10%">Radio</th> 
		      <th width="10%">Approach</th> 
		      <th width="10%">Tower</th> 
		      <th width="10%">Ground</th> 
		    </tr> 
		    <tr> 
		      <td><input name="textfield" class="inputs" type="text" size="30%"/></td> 
		      <td align="center"><input name="textfield" class="inputs" type="text" size="20%"/></td> 
		      <td align="center"><input name="FIS9b" class="inputs" type="text" id="FIS92b" size="10%" /></td> 
		      <td align="center"><input name="FIS9a" class="inputs" type="text" id="FIS92a" size="10%" /></td> 
		      <td align="center"><input name="FIS9" class="inputs" type="text" id="FIS92" size="10%" /></td> 
		      <td align="center"><input name="FIS12" class="inputs" type="text" id="FIS12" size="10%" /></td> 
		      <td align="center"><input name="FIS15" class="inputs" type="text" id="FIS15" size="10%" /></td> 
		    </tr> 
		    <tr> 
		      <td><input name="textfield2" class="inputs" type="text" size="30%"/></td> 
		      <td align="center"><input name="textfield" class="inputs" type="text" size="20%"/></td> 
		      <td align="center"><input name="FIS10b" class="inputs" type="text" id="FIS10b" size="10%" /></td> 
		      <td align="center"><input name="FIS10a" class="inputs" type="text" id="FIS10a" size="10%" /></td> 
		      <td align="center"><input name="FIS10" class="inputs" type="text" id="FIS10" size="10%" /></td> 
		      <td align="center"><input name="FIS13"  class="inputs" type="text" id="FIS13" size="10%" /></td> 
		      <td align="center"><input name="FIS16" class="inputs" type="text" id="FIS16" size="10%" /></td> 
		    </tr> 
		    <tr> 
		      <td><input name="textfield3" class="inputs" type="text" size="30%"/></td> 
		      <td align="center"><input name="textfield" class="inputs" type="text" size="20%"/></td> 
		      <td align="center"><input name="FIS11b" class="inputs"  type="text" id="FIS112b" size="10%" /></td> 
		      <td align="center"><input name="FIS11a" class="inputs"  type="text" id="FIS112a" size="10%" /></td>
		      <td align="center"><input name="FIS11" class="inputs"  type="text" id="FIS112" size="10%" /></td> 
		      <td align="center"><input name="FIS14" class="inputs" type="text" id="FIS142" size="10%" /></td> 
		      <td align="center"><input name="FIS17" class="inputs" type="text" id="FIS172" size="10%" /></td> 
		    </tr> 
		  </table> 
		  <table class="tableOne" width="100%"  border="1" cellpadding="0" cellspacing="1"> 
		    <tr> 
		      <td class="coloured" width="13" rowspan="3">L<br /> 
		        E<br /> 
		        G </td> 
		      <td class="coloured" colspan="3">True Air Speed
		        <input name="tas" class="inputs" type="integer" id="tas" onchange="itemChange(this.form,'tas','0','0','250')" size="5" value='100' /> 
		        Kts</td> 
		      <td class="coloured" colspan="9">Global Magnetic Variation
		        <input name="variation" class="inputs" id="variation" onchange="magVarChange(this.form,'variation')" value='0' size="3"  /> 
		&plusmn; deg</td> 
		      <td  class="coloured" width="13" rowspan="3"> L<br /> 
		        E<br /> 
		        G </td> 
		    </tr> 
		    <tr> 
		      <th width="74"  rowspan="2">From/to</th> 
		      <th width="40" height="20" ><nobr>Safe Alt</nobr></th> 
		      <th width="42"  rowspan="2">True<br /> 
		        Track </th> 
		      <th  width="61" rowspan="2">Wind<br /> 
		        Vel.</th> 
		      <th width="44" rowspan="2" >Mag<br /> 
		        Var</th> 
		      <th width="54" >Drift</th> 
		      <th width="51"  rowspan="2">G.S. <br /> 
		        Knots</th> 
		      <th width="48" rowspan="2">Dist<br /> 
		        nM </th> 
		      <th width="42"  rowspan="2">Time<br /> 
		        mins </th> 
		      <th width="112" >&nbsp;ETA</th> 
		      <th width="42" >&nbsp;FIS</th> 
		      <th width="44" >&nbsp;VOR</th> 
		    </tr> 
		    <tr> 
		      <th><nobr>Plan Alt</nobr></th> 
		      <th><nobr>Mag Hdg</nobr></th> 
		      <th>Actual</th> 
		      <th>Freq.</th> 
		      <th>Squawk</th> 
		    </tr> 
		    <tr> 
		      <td class="coloured" rowspan="2">1</td> 
		      <td><input name="from1" class="inputs" type="text" id="from1" size="10"/></td> 
		      <td align="center"><input name="safe1" class="inputs" type="text" id="safe1" size="4" /></td> 
		      <td align="center"><input onblur="itemChange(this.form,'trueTrack','1','0','360')" name="trueTrack1" class="inputs" type="text" id="trueTrack1" size="5" value="000" /></td> 
		      <td><input onchange="itemChange(this.form,'windVel','1','0','360')" name="windVel1" class="inputs" type="text" id="windVel1" size="6" value="000/00" /></td> 
		      <td align="center"><input onchange="itemChange(this.form,'magVar','1','0','360')" name="magVar1" type="text" class="inputs" id="magVar1" size="4" maxlength="4" value='0'  /></td> 
		      <td align="center"><input class="inputs" name="drift1"  type="text" id="drift1" size="5" readonly="readonly"  /></td> 
		      <td align="center" valign="top"><input class="inputs" name="groundSpeed1"  type="text" id="groundSpeed1" size="3" readonly="readonly" /></td> 
		      <td align="center" valign="top"><input  onchange="itemChange(this.form,'distance','1','0','300')"  name="distance1" class="inputs" type="text" id="distance12" size="4" value="0" /></td> 
		      <td align="center" valign="top"><input  name="time1" type="text" class="highlight" id="time1" size="3" readonly="readonly" /></td> 
		      <td>&nbsp;</td> 
		      <td align="center"><input name="FIS" class="inputs" type="text" id="FIS2" size="7" /></td> 
		      <td align="center"><input name="vor" class="inputs" type="text" id="vor" size="7" /></td> 
		      <td class="coloured" rowspan="2" align="center">1</td> 
		    </tr> 
		    <?php 
		      require_once dirname(__FILE__) . "/PlanParser.php";
		      
		      $endPoint = $_GET['point'];
		      $parser = new PlanParser($endPoint);
    
            ?>		      
		        
	      <?php foreach($parser->getData() as $row): ?>
	      <tr> 
		      <td class="coloured" rowspan="2"><?= $row['number'] ?></td> 
		      <td><input name="from1" class="inputs" type="text" id="from1" size="10"/></td> 
		      <td align="center"><input name="safe1" class="inputs" type="text" id="safe1" size="4" /></td> 
		      <td align="center"><input onblur="itemChange(this.form,'trueTrack','1','0','360')" name="trueTrack1" class="inputs" type="text" id="trueTrack1" size="5" value="000" /></td> 
		      <td><input onchange="itemChange(this.form,'windVel','1','0','360')" name="windVel1" class="inputs" type="text" id="windVel1" size="6" value="000/00" /></td> 
		      <td align="center"><input onchange="itemChange(this.form,'magVar','1','0','360')" name="magVar1" type="text" class="inputs" id="magVar1" size="4" maxlength="4" value='0'  /></td> 
		      <td align="center"><input class="inputs" name="drift1"  type="text" id="drift1" size="5" readonly="readonly"  /></td> 
		      <td align="center" valign="top"><input class="inputs" name="groundSpeed1"  type="text" id="groundSpeed1" size="3" readonly="readonly" /></td> 
		      <td align="center" valign="top"><input  onchange="itemChange(this.form,'distance','1','0','300')"  name="distance1" class="inputs" type="text" id="distance12" size="4" value="0" /></td> 
		      <td align="center" valign="top"><input  name="time1" type="text" class="highlight" id="time1" size="3" readonly="readonly" /></td> 
		      <td>&nbsp;</td> 
		      <td align="center"><input name="FIS" class="inputs" type="text" id="FIS2" size="7" /></td> 
		      <td align="center"><input name="vor" class="inputs" type="text" id="vor" size="7" /></td> 
		      <td class="coloured" rowspan="2" align="center">1</td> 
		    </tr> 
		  <?php endforeach; ?>  
  	      <tr>
		      <td><input onBlur="setFrom(this.form, this.id)" name="to1" class="inputs" type="text" id="to1" size="10"/></td> 
		      <td align="center"><input name="plan" class="highlight" type="text" id="plan" size="4" /></td> 
		      <td colspan="3" align="left"><input name="textfield5" class="inputs" type="text" size="22" /></td> 
		      <td align="center" ><input class="highlight" name="heading1"  type="text" id="heading1" size="4" readonly="readonly" /></td> 
		      <td colspan="3" align="left" valign="top"><input name="textfield59" class="inputs" type="text" size="21" /></td> 
		      <td>&nbsp;</td> 
		      <td align="center"><input name="freq" class="inputs" type="text" id="freq" size="7" /></td> 
		      <td align="center">&nbsp;</td> 
		    </tr> 
		    <tr> 
		      <th colspan="7" align="right">Totals&nbsp;</th> 
		      <td align="center"><input name="averageSpeed" class="highlightGreen" type="text" id="averageSpeed" size="3" readonly="readonly" /></td> 
		      <td align="center"><input name="totalDistance" class="highlightGreen" type="text" id="totalDistance" size="4" readonly="readonly" /></td> 
		      <td align="center"><input name="totalTime" class="highlightGreen"  type="text" id="totalTime" size="4" readonly="readonly" /></td> 
		      <th colspan="4">&nbsp;</th> 
		    </tr> 
		  </table>
		  
		  <table width="100%" border="0" > 
		    <tr> 
		      <td  class="hidden" align="left" valign="top"><textarea name="textarea" cols="70" rows="11" >Notes.</textarea></td> 
		      <td width="124"  align="right" valign="top" class="hidden">
		      <table border="0" align="right" cellpadding="0" cellspacing="0" >
		        <tr>
		          <td class="coloured" colspan="2"><div align="left">Fuel Burn
		                  <input name="fuelcons" class="inputs" type="text" id="fuelcons" value="10" size="2" maxlength="2" onchange="itemChange(this.form,'fuelcons','0','0','');" />
		        per hour</div></td>
		        </tr>
		        <tr>
		          <th width="65" align="left">Taxi</th>
		          <td width="60" align="center"><input name="taxi" class="inputs" type="text" id="taxi" size="3" /></td>
		        </tr>
		        <tr>
		          <th align="left">Enroute</th>
		          <td align="center"><input name="enroute" class="inputs" type="text" id="enroute" size="3" /></td>
		        </tr>
		        <tr>
		          <th align="left">Diversion</th>
		          <td align="center"><input name="diversion" class="inputs" type="text" id="diversion" size="3" /></td>
		        </tr>
		        <tr>
		          <th align="left">Reserve</th>
		          <td align="center"><input name="reserve" class="inputs" type="text" id="reserve" size="3" /></td>
		        </tr>
		        <tr>
		          <th align="left">TOTAL fuel required</th>
		          <td align="left"><input name="total" class="inputs" type="text" id="total" size="6" /></td>
		        </tr>
		      </table>
		      </td> 
		  </table> 
		</form> 
      </li>   
    </ul>
    
    <div class="page-break"></div>
    
    <h2>Map</h2>  
    <ul>  
      <li class="single">
	<script type="text/javascript">
	<!--
	document.write('<iframe src="maps/gmaps.php?point=<?= $_GET['point'] ?>" width="100%" height="800px" scrolling="no" frameborder="0"></iframe>');
	document.write('<div><span class="disclamer"><img src="images/maps/aa-start.png"/>Route Start.</span><span class="disclamer"><img src="images/maps/aa-end.png"/>Route End.</span><span class="disclamer"><img src="images/maps/aa-marker.png"/>Checkpoint.</span><span class="disclamer"><img src="images/maps/aa-check.png"/>Airport.</span></div>');
	document.write('<div><span class="disclamer">This map is for route confirmation only and should not be used in flight.</span></div>');
	-->
	</script>
      </li>  
    </ul>
    
    <div class="page-break"></div>
    
    <h2>Weather</h2>  
    <ul>  
      <li><div id="metar" class="disclamer">metar will load here</div></li>
      <li>
	    <ul class="tabbar itabsui">
	        <li><a class="iicon" href="#infrared" title="Infrared Satelite"><em class="ii-weather"></em>Infrared Satelite</a></li>
	        <li><a class="iicon" href="#weather" title="Weather Satelite"><em class="ii-cloud"></em>Weather Satelite</a></li>
	        <li><a class="iicon" href="#noaa" title="Noaa Satelite"><em class="ii-brightness"></em>NOAA Satelite</a></li>
	        <li><a class="iicon" href="#isacar" title="Isacar Satelite"><em class="ii-umbrella"></em>Isacar Satelite</a></li>
	    </ul>
	    <div id="infrared" title="Infrared Satelite">
	        <img src="http://aviationweather.gov/data/obs/sat/intl/ir_ICAO-A_bw.jpg" width="100%"/>
	    </div>
	    <div id="weather" title="Weather Satelite">
	        <img src="http://image.weather.com/images/sat/cenamersat_600x405.jpg" width="100%"/>
	    </div>
	    <div id="noaa" title="NOAA Satelite">
	        <img src="http://cimss.ssec.wisc.edu/goes/burn/data/rtloopregional/centamer/latest_centamer.gif" width="100%"/>
	    </div>
	    <div id="isacar" title="Isacar Satelite">
	        <img src="http://sirocco.accuweather.com/sat_mosaic_400x300_public/IR/isacar.gif" width="100%"/>
	    </div>
      </li>
    </ul>  
  </body>  
</html>