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
		      <td rowspan="2">2</td> 
		      <td class="coloured"><input name="from2" class="inputsAlt" type="text" id="from2" size="10"/></td> 
		      <td class="coloured" align="center"><input name="safe2" class="inputsAlt"  type="text" id="safe2" size="4" /></td> 
		      <td class="coloured" align="center"><input onchange="itemChange(this.form,'trueTrack','2','0','360')" name="trueTrack2" class="inputsAlt"  type="text" id="trueTrack2" size="5" value="000"/></td> 
		      <td class="coloured"><input  onchange="itemChange(this.form,'windVel','2','0','360')"  name="windVel2" class="inputsAlt"  type="text" id="windVel2" size="6" value="000/00"/></td> 
		      <td class="coloured" align="center"><input onchange="itemChange(this.form,'magVar','2','0','360')" name="magVar2" type="text" class="inputsAlt" id="magVar2" size="4" maxlength="4" value='0' /></td> 
		      <td class="coloured" align="center"><input class="inputsAlt" name="drift2"   type="text" id="drift2" size="5" readonly="READONLY" /></td> 
		      <td class="coloured" align="center" valign="top"><input class="inputsAlt" name="groundSpeed2"  type="text" id="groundSpeed2" size="3" readonly="READONLY" /></td> 
		      <td class="coloured" align="center" valign="top"><input onchange="itemChange(this.form,'distance','2','0','300')"  name="distance2" class="inputsAlt"  type="text" id="distance2" size="4" value="0"/></td> 
		      <td class="coloured" align="center" valign="top"><input class="highlightAlt" name="time2" type="text" id="time2" size="3" readonly="readonly" /></td> 
		      <td class="coloured">&nbsp;</td> 
		      <td class="coloured" align="center"><input name="FIS2" class="inputsAlt"  type="text" id="FIS22" size="7" /></td> 
		      <td class="coloured" align="center"><input name="vor2" class="inputsAlt"  type="text" id="vor2" size="7" /></td> 
		      <td rowspan="2" align="center">2</td> 
		    </tr> 
		    <tr> 
		      <td class="coloured"><input onBlur="setFrom(this.form, this.id)" name="to2" class="inputsAlt" type="text" id="to2" size="10"/></td> 
		      <td class="coloured" align="center"><input name="plan2" class="highlightAlt" type="text" id="plan2" size="4" /></td> 
		      <td class="coloured" colspan="3" align="left"><input name="textfield52" class="inputsAlt"  type="text" size="22" /></td> 
		      <td align="center"  class="coloured" ><input class="highlightAlt" name="heading2" type="text" id="heading122" size="4" readonly="readonly" /></td> 
		      <td class="coloured" colspan="3" align="left" valign="top"><input name="textfield592" class="inputsAlt"  type="text" size="21" /></td> 
		      <td class="coloured">&nbsp;</td> 
		      <td class="coloured" align="center"><input name="freq2" class="inputsAlt"  type="text" id="freq2" size="7" /></td> 
		      <td class="coloured" align="center">&nbsp;</td> 
		    </tr> 
		    <tr> 
		      <td class="coloured" rowspan="2">3</td> 
		      <td><input name="from3" class="inputs" type="text" id="from3" size="10"/></td> 
		      <td align="center"><input name="safe3" class="inputs" type="text" id="safe3" size="4" /></td> 
		      <td align="center"><input onchange="itemChange(this.form,'trueTrack','3','0','360')" name="trueTrack3" class="inputs"  type="text"  id="trueTrack3" size="5" value="000"/></td> 
		      <td><input  onchange="itemChange(this.form,'windVel','3','0','360')"  name="windVel3" class="inputs"  type="text" id="windVel3" size="6" value="000/00"/></td> 
		      <td align="center"><input onchange="itemChange(this.form,'magVar','3','0','360')" name="magVar3" type="text" class="inputs" id="magVar3" size="4" maxlength="4" value='0' /></td> 
		      <td align="center"><input class="inputs" name="drift3"  type="text" id="drift32" size="5" readonly="readonly" /></td> 
		      <td align="center" valign="top"><input class="inputs" name="groundSpeed3"  type="text" id="groundSpeed3" size="3" readonly="readonly" /></td> 
		      <td align="center" valign="top"><input onchange="itemChange(this.form,'distance','3','0','300')" name="distance3" class="inputs"  type="text" id="distance3" size="4" value="0"/></td> 
		      <td align="center" valign="top"><input class="highlight"  name="time3"  type="text" id="time3" size="3" readonly="readonly" /></td> 
		      <td>&nbsp;</td> 
		      <td align="center"><input name="FIS3" class="inputs"  type="text" id="FIS3" size="7" /></td> 
		      <td align="center"><input name="vor3" class="inputs" type="text" id="vor3" size="7" /></td> 
		      <td class="coloured" rowspan="2" align="center">3</td> 
		    </tr> 
		    <tr> 
		      <td><input onBlur="setFrom(this.form, this.id)" name="to3" class="inputs" type="text" id="to3" size="10"/></td> 
		      <td align="center"><input name="plan3" class="highlight"  type="text" id="plan3" size="4" /></td> 
		      <td colspan="3" align="left"><input name="textfield53" class="inputs" type="text" size="22" /></td> 
		      <td align="center"><input class="highlight"  name="heading3"  type="text" id="heading32" size="4" readonly="readonly" /></td> 
		      <td colspan="3" align="left" valign="top"><input name="textfield593" class="inputs"  type="text" size="21" /></td> 
		      <td>&nbsp;</td> 
		      <td align="center"><input name="freq3" class="inputs" type="text" id="freq3" size="7" /></td> 
		      <td align="center">&nbsp;</td> 
		    </tr> 
		    <tr> 
		      <td rowspan="2">4</td> 
		      <td class="coloured"><input name="from4" class="inputsAlt" type="text" id="from4" size="10"/></td> 
		      <td class="coloured" align="center"><input name="safe4" class="inputsAlt"  type="text" id="safe4" size="4" /></td> 
		      <td class="coloured" align="center"><input onchange="itemChange(this.form,'trueTrack','4','0','360')" name="trueTrack4" class="inputsAlt" type="text" id="trueTrack4" size="5" value="000"/></td> 
		      <td class="coloured"><input  onchange="itemChange(this.form,'windVel','4','0','360')" name="windVel4" class="inputsAlt"  type="text" id="windVel4" size="6" value="000/00"/></td> 
		      <td class="coloured" align="center"><input onchange="itemChange(this.form,'magVar','4','0','360')" name="magVar4" type="text" class="inputsAlt" id="magVar4" size="4" maxlength="4" value='0' /></td> 
		      <td class="coloured" align="center"><input name="drift4" class="inputsAlt"  type="text" id="drift4" size="5" readonly="readonly" /></td> 
		      <td class="coloured" align="center" valign="top"><input name="groundSpeed4" class="inputsAlt" type="text" id="groundSpeed4" size="3" readonly="readonly" /></td> 
		      <td class="coloured" align="center" valign="top"><input  name="distance4" class="inputsAlt"  type="text" id="distance4" onchange="itemChange(this.form,'distance','4','0','300')" value="0" size="4" maxlength="4"/></td> 
		      <td class="coloured" align="center" valign="top"><input name="time4" class="highlightAlt" type="text" id="time4" size="3" readonly="readonly" /></td> 
		      <td class="coloured" >&nbsp;</td> 
		      <td class="coloured" align="center"><input name="FIS4" class="inputsAlt"  type="text" id="FIS4" size="7" /></td> 
		      <td class="coloured" align="center"><input name="vor4" class="inputsAlt" type="text" id="vor4" size="7" /></td> 
		      <td rowspan="2" align="center">4</td> 
		    </tr> 
		    <tr> 
		      <td class="coloured"><input onBlur="setFrom(this.form, this.id)" name="to4" class="inputsAlt" type="text" id="to4" size="10"/></td> 
		      <td class="coloured" align="center"><input name="plan4" class="highlightAlt" type="text" id="plan4" size="4" /></td> 
		      <td class="coloured" colspan="3" align="left"><input name="textfield54" class="inputsAlt"  type="text" size="22" /></td> 
		      <td align="center" class="coloured"><input name="heading4" class="highlightAlt" type="text" id="heading4" size="4" readonly="readonly" /></td> 
		      <td class="coloured" colspan="3" align="left" valign="top"><input name="textfield594" class="inputsAlt"  type="text" size="21" /></td> 
		      <td class="coloured">&nbsp;</td> 
		      <td class="coloured" align="center"><input name="freq4" class="inputsAlt" type="text" id="freq4" size="7" /></td> 
		      <td class="coloured" align="center">&nbsp;</td> 
		    </tr> 
		    <tr> 
		      <td class="coloured" rowspan="2">5</td> 
		      <td><input name="from5" class="inputs" type="text" id="from5" size="10"/></td> 
		      <td align="center"><input name="safe5" class="inputs"  type="text" id="safe5" size="4" /></td> 
		      <td align="center"><input onchange="itemChange(this.form,'trueTrack','5','0','360')" name="trueTrack5" class="inputs"  type="text" id="trueTrack5" size="5" value="000"/></td> 
		      <td><input  onchange="itemChange(this.form,'windVel','5','0','360')"  name="windVel5" class="inputs"  type="text" id="windVel5" size="6" value="000/00"/></td> 
		      <td align="center"><input onchange="itemChange(this.form,'magVar','5','0','360')" name="magVar5" type="text" class="inputs" id="magVar5" size="4" maxlength="4" value='0' /></td> 
		      <td align="center"><input name="drift5" class="inputs"  type="text" id="drift5" size="5" readonly="readonly" /></td> 
		      <td align="center" valign="top"><input name="groundSpeed5" class="inputs"  type="text" id="groundSpeed5" size="3" readonly="readonly" /></td> 
		      <td align="center" valign="top"><input onchange="itemChange(this.form,'distance','5','0','300')"  name="distance5" class="inputs"  type="text" id="distance5" size="4" value="0"/></td> 
		      <td align="center" valign="top"><input name="time5" class="highlight"  type="text" id="time5" size="3" readonly="readonly" /></td> 
		      <td>&nbsp;</td> 
		      <td align="center"><input name="FIS5" class="inputs"  type="text" id="FIS5" size="7" /></td> 
		      <td align="center"><input name="vor5" class="inputs" type="text" id="vor5" size="7" /></td> 
		      <td class="coloured" rowspan="2" align="center">5</td> 
		    </tr> 
		    <tr> 
		      <td><input onBlur="setFrom(this.form, this.id)" name="to5" class="inputs" type="text" id="to5" size="10"/></td> 
		      <td align="center"><input name="plan5" class="highlight"  type="text" id="plan5" size="4" /></td> 
		      <td colspan="3" align="left"><input name="textfield55" class="inputs"  type="text" size="22" /></td> 
		      <td align="center"><input name="heading5" class="highlight"  type="text" id="heading53" size="4" readonly="readonly" /></td> 
		      <td colspan="3" align="left" valign="top"><input name="textfield595" class="inputs" type="text" size="21" /></td> 
		      <td>&nbsp;</td> 
		      <td align="center"><input name="freq5" class="inputs"  type="text" id="freq5" size="7" /></td> 
		      <td align="center">&nbsp;</td> 
		    </tr> 
		    <tr> 
		      <td rowspan="2" >6</td> 
		      <td class="coloured"><input name="from6" class="inputsAlt" type="text" id="from6" size="10"/></td> 
		      <td class="coloured" align="center"><input name="safe6" class="inputsAlt" type="text" id="safe6" size="4" /></td> 
		      <td class="coloured" align="center"><input onchange="itemChange(this.form,'trueTrack','6','0','360')" name="trueTrack6" class="inputsAlt" type="text" id="trueTrack6" size="5" value="000"/></td> 
		      <td class="coloured"><input  onchange="itemChange(this.form,'windVel','6','0','360')" name="windVel6" class="inputsAlt" type="text" id="windVel6" size="6" value="000/00"/></td> 
		      <td class="coloured" align="center"><input onchange="itemChange(this.form,'magVar','6','0','360')" name="magVar6" type="text" class="inputsAlt" id="magVar6" size="4" maxlength="4" value='0' /></td> 
		      <td class="coloured" align="center"><input name="drift6" class="inputsAlt" type="text" id="drift6" size="5" readonly="readonly" /></td> 
		      <td class="coloured" align="center" valign="top"><input name="groundSpeed6" class="inputsAlt" type="text" id="groundSpeed6" size="3" readonly="readonly"/></td> 
		      <td class="coloured" align="center" valign="top"><input onchange="itemChange(this.form,'distance','6','0','300')"  name="distance6" class="inputsAlt" type="text" id="distance6" size="4" value="0"/></td> 
		      <td class="coloured" align="center" valign="top"><input name="time6" class="highlightAlt" type="text" id="time6" size="3" readonly="readonly" /></td> 
		      <td class="coloured">&nbsp;</td> 
		      <td class="coloured" align="center"><input name="FIS6" class="inputsAlt" type="text" id="FIS6" size="7" /></td> 
		      <td class="coloured" align="center"><input name="vor6" class="inputsAlt" type="text" id="vor6" size="7" /></td> 
		      <td rowspan="2" align="center">6</td> 
		    </tr> 
		    <tr> 
		      <td class="coloured"><input onBlur="setFrom(this.form, this.id)" name="to6" class="inputsAlt" type="text" id="to6" size="10"/></td> 
		      <td class="coloured" align="center"><input name="plan6" class="highlightAlt" type="text" id="plan62" size="4" /></td> 
		      <td class="coloured" colspan="3" align="left"><input name="textfield56" class="inputsAlt"  type="text" size="22" /></td> 
		      <td align="center" class="coloured"><input name="heading6" class="highlightAlt" type="text" id="heading62" size="4" readonly="readonly" /></td> 
		      <td class="coloured" colspan="3" align="left" valign="top"><input name="textfield596" class="inputsAlt" type="text" size="21" /></td> 
		      <td class="coloured">&nbsp;</td> 
		      <td class="coloured" align="center"><input name="freq6" class="inputsAlt" type="text" id="freq6" size="7" /></td> 
		      <td class="coloured" align="center">&nbsp;</td> 
		    </tr> 
		    <tr> 
		      <td class="coloured" rowspan="2">7</td> 
		      <td><input name="from7" class="inputs"  type="text" id="from7" size="10"/></td> 
		      <td align="center"><input name="safe7" class="inputs" type="text" id="safe7" size="4" /></td> 
		      <td align="center"><input onchange="itemChange(this.form,'trueTrack','7','0','360')" name="trueTrack7" class="inputs"  type="text" id="trueTrack7" size="5" value="000"/></td> 
		      <td><input  onchange="itemChange(this.form,'windVel','7','0','360')"  name="windVel7" class="inputs"  type="text" id="windVel7" size="6" value="000/00"/></td> 
		      <td align="center"><input onchange="itemChange(this.form,'magVar','7','0','360')" name="magVar7" type="text" class="inputs" id="magVar7" size="4" maxlength="4" value='0' /></td> 
		      <td align="center"><input name="drift7" class="inputs" type="text" id="drift7" size="5" readonly="readonly" /></td> 
		      <td align="center" valign="top"><input name="groundSpeed7" class="inputs" type="text" id="groundSpeed7" size="3" readonly="readonly" /></td> 
		      <td align="center" valign="top"><input onchange="itemChange(this.form,'distance','7','0','300')" name="distance7" class="inputs"  type="text" id="distance7" size="4" value="0"/></td> 
		      <td align="center" valign="top"><input name="time7" class="highlight"   type="text" id="time7" size="3" readonly="readonly" /></td> 
		      <td>&nbsp;</td> 
		      <td align="center"><input name="FIS7" class="inputs"  type="text" id="FIS7" size="7" /></td> 
		      <td align="center"><input name="vor7" class="inputs" type="text" id="vor7" size="7" /></td> 
		      <td class="coloured" rowspan="2" align="center">7</td> 
		    </tr> 
		    <tr> 
		      <td><input onBlur="setFrom(this.form, this.id)" name="to7" class="inputs" type="text" id="to7" size="10"/></td> 
		      <td align="center"><input name="plan7" class="highlight"  type="text" id="plan7" size="4" /></td> 
		      <td colspan="3" align="left"><input name="textfield57" class="inputs" type="text" size="22" /></td> 
		      <td align="center"><input name="heading7" class="highlight"  type="text" id="heading72" size="4" readonly="readonly" /></td> 
		      <td colspan="3" align="left" valign="top"><input name="textfield597" class="inputs" type="text" size="21" /></td> 
		      <td>&nbsp;</td> 
		      <td align="center"><input name="freq7" class="inputs" type="text" id="freq7" size="7" /></td> 
		      <td align="center">&nbsp;</td> 
		    </tr> 
		    <tr> 
		      <td rowspan="2">8</td> 
		      <td class="coloured"><input name="from8" class="inputsAlt" type="text" id="from8" size="10"/></td> 
		      <td class="coloured" align="center"><input name="safe8" class="inputsAlt" type="text" id="safe8" size="4" /></td> 
		      <td class="coloured" align="center"><input onchange="itemChange(this.form,'trueTrack','8','0','360')" name="trueTrack8" class="inputsAlt" type="text" id="trueTrack8" size="5" value="000"/></td> 
		      <td class="coloured"><input  onchange="itemChange(this.form,'windVel','8','0','360')" name="windVel8" class="inputsAlt" type="text" id="windVel8" size="6" value="000/00" /></td> 
		      <td class="coloured" align="center"><input onchange="itemChange(this.form,'magVar','8','0','360')" name="magVar8" type="text" class="inputsAlt" id="magVar8" size="4" maxlength="4" value='0' /></td> 
		      <td class="coloured" align="center"><input name="drift8" class="inputsAlt" type="text" id="drift8" size="5" readonly="readonly" /></td> 
		      <td class="coloured" align="center" valign="top"> <input name="groundSpeed8" class="inputsAlt" type="text" id="groundSpeed8"  size="3" readonly="readonly" /></td> 
		      <td class="coloured" align="center" valign="top"><input onchange="itemChange(this.form,'distance','8','0','300')"  name="distance8" class="inputsAlt" type="text" id="distance8" size="4" value="0" /></td> 
		      <td class="coloured" align="center" valign="top"><input name="time8" class="highlightAlt" type="text" id="time8" size="3" readonly="readonly" /></td> 
		      <td class="coloured">&nbsp;</td> 
		      <td class="coloured" align="center"><input name="FIS8" class="inputsAlt" type="text" id="FIS8" size="7" /></td> 
		      <td class="coloured" align="center"><input name="vor8" class="inputsAlt" type="text" id="vor8" size="7" /></td> 
		      <td rowspan="2" align="center">8</td> 
		    </tr> 
		    <tr> 
		      <td class="coloured"><input name="to8" class="inputsAlt" type="text" id="to8" size="10"/></td> 
		      <td class="coloured" align="center"><input name="plan8" class="highlightAlt" type="text" id="plan8" size="4" /></td> 
		      <td class="coloured" colspan="3" align="left"><input name="textfield58" class="inputsAlt" type="text" size="22" /></td> 
		      <td align="center" class="coloured"><input name="heading8" class="highlightAlt" type="text" id="heading8" size="4" readonly="readonly" /></td> 
		      <td class="coloured" colspan="3" align="left" valign="top"><input name="textfield598" class="inputsAlt" type="text" size="21" /></td> 
		      <td class="coloured">&nbsp;</td> 
		      <td class="coloured" align="center"><input name="freq8" class="inputsAlt" type="text" id="freq8" size="7" /></td> 
		      <td class="coloured" align="center">&nbsp;</td> 
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
	        <li><a class="iicon" href="#tower" title="MRPV Tower"><em class="ii-weather"></em>MRPV Tower</a></li>
	        <li><a class="iicon" href="#infrared" title="Infrared Satelite"><em class="ii-weather"></em>Infrared Satelite</a></li>
	        <li><a class="iicon" href="#weather" title="Weather Satelite"><em class="ii-cloud"></em>Weather Satelite</a></li>
	        <li><a class="iicon" href="#noaa" title="Noaa Satelite"><em class="ii-brightness"></em>NOAA Satelite</a></li>
	        <li><a class="iicon" href="#isacar" title="Isacar Satelite"><em class="ii-umbrella"></em>Isacar Satelite</a></li>
	    </ul>
	    <div id="tower" title="MRPV Tower">
	        <p style="text-align:center;">
		  <img src="http://www.imn.ac.cr/especial/QNHPAVAS.png" width="360px"/>
		  <img src="http://www.imn.ac.cr/especial/PavasRed.png" width="600px"/>
		</p>
	    </div>
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
