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
    <script src="js/ui.iTabs.js" type="text/javascript"></script><!-- don't remove -->
    <script src="js/jquery.getParams.js" type="text/javascript"></script><!-- don't remove -->
    <script src="js/css_selector.js" type="text/javascript"></script><!-- don't remove -->
    
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
	      
	      //Insert value into flight plan
	      $("#topPlan").val('MRPV - ' + point);
	      
	      //Insert value into Header
	      $("#headPoint").text('MRPV - ' + point);
	      $("#headType").text(type);

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
    <h1 class="noPrint"><div class="leftButton" onclick="location.href='points.php'">Back</div>Flight <span id="headPoint">Route</span> in a <span id="headType">Aircraft</span><div class="rightButton" onclick="window.print();return false">Print</div></h1>
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
		  
			<?php
			   require_once dirname(__FILE__) . "/PlanParser.php";
			
			   $endPoint = $_GET['point'];
			   $parser = new PlanParser($endPoint);
			   $route = $parser->getRoute();
			?>
				
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
			<?php foreach( $parser->getAirports() as $number => $row): ?>
		    <tr> 
		      <td>&nbsp;<?= $row['point'] ?></td> 
		      <td align="center" height="20px"><?= $row['runway'] ?>, <?= $row['length'] ?>m, <?= $row['ground'] ?></td> 
		      <td align="center" height="20px"><?= $row['unicom'] ?></td> 
		      <td align="center" height="20px"><?= $row['radio'] ?></td> 
		      <td align="center" height="20px"><?= $row['approach'] ?></td> 
		      <td align="center" height="20px"><?= $row['tower'] ?></td> 
		      <td align="center" height="20px"><?= $row['twrground'] ?></td> 
		    </tr> 
			<?php endforeach; ?>
		  </table> 
		  <table class="tableOne" width="100%"  border="1" cellpadding="0" cellspacing="1"> 
		    <tr> 
		      <td class="coloured" width="13" rowspan="3" align="center">L<br /> 
		        E<br /> 
		        G </td> 
		      <td class="coloured" colspan="3">True Air Speed
		        <input name="tas" class="inputs" type="integer" id="tas" onchange="itemChange(this.form,'tas','0','0','250')" size="3" value='100' /> 
		        Kts</td> 
		      <td class="coloured" colspan="9">Global Magnetic Variation
		        <input name="variation" class="inputs" id="variation" onchange="magVarChange(this.form,'variation')" value='0' size="3"  /> 
		&plusmn; deg</td> 
		      <td  class="coloured" width="13" rowspan="3" align="center"> L<br /> 
		        E<br /> 
		        G </td> 
		    </tr> 
		    <tr> 
		      <th width="74" rowspan="2" align="center">From / To</th> 
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

				<?php foreach( $route as $number => $row): ?>
				<?php $rowNumber = $number + 1; ?>
				<?php
					$rowClass = ($number %  2) == 0 ? 'class="coloured"' : "";
					$alterRowClass = ($number %  2) == 0 ? "" : 'class="coloured"';
				?>
				<tr>
					<td <?= $alterRowClass ?> rowspan="2" align="center"><?= $rowNumber ?></td>
					<td <?= $rowClass ?> align="center"><strong><?= $row['point'] ?></strong></td>
					<td <?= $rowClass ?> align="center"><strong class="red"><?= $row['altitude'] ?></strong></td>
					<td <?= $rowClass ?> align="center"><?= $row['course'] ?></td>
					<td <?= $rowClass ?> align="center"><input onchange="itemChange(this.form,'windVel','1','0','360')" name="windVel1" class="inputs" type="text" id="windVel1" size="6" value="000/00" /></td>
					<td <?= $rowClass ?> align="center"><input onchange="itemChange(this.form,'magVar','1','0','360')" name="magVar1" type="text" class="inputs" id="magVar1" size="4" maxlength="4" value='0'  /></td>
					<td <?= $rowClass ?> align="center"><input class="inputs" name="drift1"  type="text" id="drift1" size="5" readonly="readonly"  /></td>
					<td <?= $rowClass ?> align="center" valign="top"><input class="inputs" name="groundSpeed1"  type="text" id="groundSpeed1" size="3" readonly="readonly" /></td>
					<td <?= $rowClass ?> align="center"><?= $row['distance'] ?></td>
					<td <?= $rowClass ?> align="center" valign="top"><input  name="time1" type="text" class="highlight" id="time1" size="3" readonly="readonly" /></td>
					<td <?= $rowClass ?> >&nbsp;</td>
					<td <?= $rowClass ?> align="center"><?= $row['frequency'] ?></td>
					<td <?= $rowClass ?> align="center">&nbsp;</td>
					<td <?= $alterRowClass ?> rowspan="2" align="center"><?= $rowNumber ?></td>
				</tr>
				<tr>
					<td <?= $rowClass ?> align="center"><?= $route[$rowNumber]['point'] ?></td>
					<td <?= $rowClass ?> align="center"></td>
					<td <?= $rowClass ?> colspan="3" align="left">&nbsp;</td>
					<td <?= $rowClass ?> align="center" ><input class="highlight" name="heading1"  type="text" id="heading1" size="4" readonly="readonly" /></td>
					<td <?= $rowClass ?> colspan="3" align="left" valign="top">&nbsp;</td>
					<td <?= $rowClass ?> >&nbsp;</td>
					<td <?= $rowClass ?> align="center"><input name="freq" class="inputs" type="text" id="freq" size="7" /></td>
					<td <?= $rowClass ?> align="center">&nbsp;</td>
				</tr>
             <?php endforeach; ?>
                <tr>
                  <th colspan="7" align="right">Totals&nbsp;</th>
                  <td align="center"><input name="averageSpeed" class="highlightGreen" type="text" id="averageSpeed" size="3" readonly="readonly" value="<?= $parser->getAverageSpeed() ?>" /></td>
                  <td align="center"><input name="totalDistance" class="highlightGreen" type="text" id="totalDistance" size="4" readonly="readonly" value="<?= $parser->getTotalDistance() ?>" /></td>
                  <td align="center"><input name="totalTime" class="highlightGreen"  type="text" id="totalTime" size="4" readonly="readonly" value="<?= $parser->getTotalTime() ?>" /></td>
                  <th colspan="4">&nbsp;</th>
                </tr>
              </table>
              
              <table width="100%" border="0">
                <tr>
                  <td align="left" valign="top"><textarea name="textarea" cols="70" rows="11" >Notes.</textarea></td>
                  <td width="124"  align="right" valign="top">
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
			<li><a class="iicon" href="#tower" title="MRPV Tower"><em class="ii-weather"></em>MRPV Tower</a></li>
			<li><a class="iicon" href="#weather" title="Weather Satelite"><em class="ii-cloud"></em>Weather Satelite</a></li>
			<li><a class="iicon" href="#infrared" title="Infrared Satelite"><em class="ii-weather"></em>Infrared Satelite</a></li>
			<li><a class="iicon" href="#noaa" title="Noaa Satelite"><em class="ii-brightness"></em>NOAA Satelite</a></li>
			<li><a class="iicon" href="#isacar" title="Isacar Satelite"><em class="ii-umbrella"></em>Isacar Satelite</a></li>
	    </ul>
	    <div id="tower" title="MRPV Tower">
			<p style="text-align:center;">
				<img src="http://www.imn.ac.cr/especial/QNHPAVAS.png" width="360px"/>
				<img src="http://www.imn.ac.cr/especial/PavasRed.png" width="600px"/>
			</p>
	    </div>
	    <div id="weather" title="Weather Satelite">
	        <img src="http://www.imn.ac.cr/especial/SATVIS.GIF" width="100%"/>
	    </div>
	    <div id="infrared" title="Infrared Satelite">
	        <img src="http://www.imn.ac.cr/especial/SATELITE.GIF" width="100%"/>
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
