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
    <!--<script src="js/jquery.calculation.js" type="text/javascript"></script> don't remove -->
    <script src="js/css_selector.js" type="text/javascript"></script><!-- don't remove -->
    <script src="js/plan.js" type="text/javascript"></script><!-- don't remove -->
    
	<script type="text/javascript">/* <![CDATA[ */
	  $(function(){
		  
	      //Parameter for type
	      atype = $.getUrlVar("type");
	      //Parameter for zone
	      zone = $.getUrlVar("zone");
	      //Parameter for point
	      point = $.getUrlVar("point");
	      $("#crumbs").html(atype + "<span class='chevron'/>" + zone + "<span class='chevron'/>" + point);

	      //Get TAF
	      $("#metar").load("php/proxy.php?url=http://aviationweather.gov/adds/tafs/index.php?station_ids=MRPV+" + point);
	      
	      //Get MRPV NOTAM
			$.ajax({
				type: "POST",
				url: "php/proxyNotam.php?url=https://www.notams.jcs.mil/dinsQueryWeb/queryRetrievalMapAction.do",
				data: {'retrieveLocId': 'MRPV'},
				success: function(data) {
					$('#mrpvnotam').html(data);
				}
			});
			
	      //Get POINT NOTAM
			$.ajax({
				type: "POST",
				url: "php/proxyNotam.php?url=https://www.notams.jcs.mil/dinsQueryWeb/queryRetrievalMapAction.do",
				data: {'retrieveLocId': point},
				success: function(data) {
					$('#' + point + 'notam').html(data);
				}
			});
	      
	      //Insert value into flight plan
	      $("#topPlan").val('MRPV - ' + point);
	      
	      //Insert value into Header
	      $("#headPoint").text('MRPV - ' + point);
	      $("#headType").text(atype);
	      
	      //Make Tabs
	      $(".tabbar").iTabs();
	      
	      //Calculate Required Fuel
			calculateFuelBurn();
		  
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
    <h1 class="noPrint"><div class="leftButton" onclick="location.href='points.php?type=<?= $_GET['type']; ?>&zone=<?= $_GET['zone']; ?>'">Back</div>Flight <span id="headPoint">Route</span> in a <span id="headType">Aircraft</span><div class="rightButton" onclick="window.print();return false">Print</div></h1>
    <h2>Flight Plan <span class='chevron'></span> <span id="crumbs"></span></h2>  
    <ul>  
      <li class="single">
		<form action="http://#" method="post" enctype="multipart/form-data" name="frm1" id="frm1" > 
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
		        <input name="tas" class="inputs" type="integer" id="tas" onchange="itemChange(this.form,'tas','0','0','250')" size="3" value='110' /> 
		        Kts</td> 
		      <td class="coloured" colspan="9">Global Magnetic Variation
		        <input name="variation" class="inputs" id="magVar" onkeyup="magVarChange($(this));" value='0' size="3" maxlength="3"  /> 
		&plusmn; deg</td> 
		      <td  class="coloured" width="13" rowspan="3" align="center"> L<br /> 
		        E<br /> 
		        G </td> 
		    </tr> 
		    <tr> 
		      <th width="74" rowspan="2" align="left">&nbsp;From / To</th> 
		      <th width="40" height="20" ><nobr>Safe Alt</nobr></th> 
		      <th width="42"  rowspan="2">True<br /> Track </th> 
		      <th  width="61" rowspan="2">Wind<br /> Vel.</th> 
		      <th width="44" rowspan="2" >Mag<br /> Var</th> 
		      <th width="54" >Drift</th> 
		      <th width="51"  rowspan="2">G.S. <br /> Knots</th> 
		      <th width="48" rowspan="2">Dist<br /> nM </th> 
		      <th width="42"  rowspan="2">Time<br /> mins </th> 
		      <th width="112" >&nbsp;ETA</th> 
		      <th width="42" >&nbsp;Freq.</th>
		      <th width="44" >&nbsp;VOR</th>
		    </tr> 
		    <tr> 
		      <th><nobr>Plan Alt</nobr></th> 
		      <th><nobr>Mag Hdg</nobr></th> 
		      <th>Actual</th> 
		      <th>FIS</th>
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
					<td <?= $rowClass ?> align="left">&nbsp;<strong><?= $row['point'] ?></strong></td>
					<td <?= $rowClass ?> align="center"><strong class="red"><?= $row['altitude'] ?></strong></td>
					<td <?= $rowClass ?> align="center"><input class="inputs trueTrack" name="trueTrack"  type="text" size="5" value="<?= $row['course'] ?>" readonly="readonly"  /></td>
					<td <?= $rowClass ?> align="center"><input onkeyup="windDirVelocity($(this))" name="windVel" class="inputs windVel" type="text" id="windVel1" size="6" value="000/00" /></td>
					<td <?= $rowClass ?> align="center"><input name="magVar" type="text" class="inputs magVar" size="4" maxlength="4" value='0' readonly="readonly" /></td>
					<td <?= $rowClass ?> align="center"><input class="inputs drift" name="drift" type="text" size="5" readonly="readonly"  /></td>
					<td <?= $rowClass ?> align="center" valign="top"><input class="inputs gsKnots" name="avgSpeed"  type="text" size="3" readonly="readonly" /></td>
					<td <?= $rowClass ?> align="center"><?= $row['distance'] ?></td>
					<td <?= $rowClass ?> align="center" valign="top"><input  name="time" type="text" class="highlight totTime" size="3" readonly="readonly" /></td>
					<td <?= $rowClass ?> >&nbsp;</td>
					<td <?= $rowClass ?> align="center"><?= $row['frequency'] ?></td>
					<td <?= $rowClass ?> align="center">&nbsp;</td>
					<td <?= $alterRowClass ?> rowspan="2" align="center"><?= $rowNumber ?></td>
				</tr>
				<tr>
					<td <?= $rowClass ?> align="left">&nbsp;<?= $route[$rowNumber]['point'] ?></td>
					<td <?= $rowClass ?> align="center"></td>
					<td <?= $rowClass ?> colspan="3" align="left">&nbsp;</td>
					<td <?= $rowClass ?> align="center" ><input class="highlight heading" name="heading"  type="text" size="4" readonly="readonly" /></td>
					<td <?= $rowClass ?> colspan="3" align="left" valign="top">&nbsp;</td>
					<td <?= $rowClass ?> >&nbsp;</td>
					<td <?= $rowClass ?> align="center">&nbsp;</td>
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
                  <td align="left" valign="top"><textarea name="textarea" cols="70" rows="8" >Notes.</textarea></td>
                  <td width="150px"  align="right" valign="top">
                  <table border="0" align="right" cellpadding="0" cellspacing="0" width="100%" >
                    <tr>
                      <td class="coloured" colspan="2"><div align="left">&nbsp;Fuel Burn
                              <input name="fuelcons" class="inputs" type="text" id="fuelcons" value="14" size="2" maxlength="2" onkeyup="calculateFuelBurn($(this));" />
                    / hr</div></td>
                    </tr>
                    <tr>
                      <th align="left" width="70px">Taxi</th>
                      <td align="center"><input name="taxi" class="inputs fuelSum" type="text" id="taxi" size="3" value="10" readonly="readonly"/>min</td>
                    </tr>
                    <tr>
                      <th align="left">Enroute</th>
                      <td align="center"><input name="enroute" class="inputs fuelSum" type="text" id="enroute" size="3" readonly="readonly" value="<?= $parser->getTotalTime() ?>" />min</td>
                    </tr>
                    <tr>
                      <th align="left">Diversion</th>
                      <td align="center"><input name="diversion" class="inputs fuelSum" type="text" id="diversion" size="3" value="20" readonly="readonly"/>min</td>
                    </tr>
                    <tr>
                      <th align="left">Reserve</th>
                      <td align="center"><input name="reserve" class="inputs fuelSum" type="text" id="reserve" size="3" value="20" readonly="readonly"/>min</td>
                    </tr>
                    <tr>
                      <th align="left">TOTAL fuel required</th>
                      <td align="left"><input name="totalFuel" class="inputs strong larger" type="text" id="totalFuel" size="6" readonly="readonly" /></td>
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
	    	<li><a class="iicon" href="#mrpvnotams" title="MRPV NOTAMS"><em class="ii-radar"></em>MRPV NOTAMS</a></li>
			<li><a class="iicon" href="#tower" title="MRPV Tower"><em class="ii-flag"></em>MRPV Tower</a></li>
			<li><a class="iicon" href="#weather" title="Weather Satelite"><em class="ii-cloud"></em>Weather Satelite</a></li>
			<li><a class="iicon" href="#infrared" title="Infrared Satelite"><em class="ii-weather"></em>Infrared Satelite</a></li>
			<li><a class="iicon" href="#noaa" title="Noaa Satelite"><em class="ii-brightness"></em>NOAA Satelite</a></li>
			<li><a class="iicon" href="#<?= $_GET['point'] ?>notams" title="<?= $_GET['point'] ?> NOTAMS"><em class="ii-radar"></em><?= $_GET['point'] ?> NOTAMS</a></li>
	    </ul>
	    <div id="mrpvnotams" title="MRPV NOTAMS">
			<p id="mrpvnotam" class="disclamer" style="padding:10px;">MRPV notam will load here</p>
	    </div>
	    <div id="tower" title="MRPV Tower">
			<p style="text-align:center;">
				<!-- <img src="http://www.imn.ac.cr/especial/QNHPAVAS.png" width="360px"/> -->
				<img src="http://www.imn.ac.cr/especial/PavasRed.png" width="100%"/>
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
	    <div id="<?= $_GET['point'] ?>notams" title="<?= $_GET['point'] ?> NOTAMS">
			<p id="<?= $_GET['point'] ?>notam" class="disclamer" style="padding:10px;"><?= $_GET['point'] ?> notam will load here</p>
	    </div>
      </li>
    </ul>  
  </body>  
</html>