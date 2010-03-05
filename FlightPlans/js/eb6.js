// CED subroutine for cleaning up JavaScript rounding errors 
// to any reasonable number of decimal places 5/5/1997 last mod 2/19/2004
// round for decimal of (value of precision) places, default is 3
// This routine can be used to pass a number and a number for precision
// or just a number only, that is to be rounded to a set number of decimal
// places. This routine supports leading and training zeros, leading and
// trailing spaces, and padding. To prevent errors, pass variables as a string.


function perRound(num, precision) {
	var precision = 3; //default value if not passed from caller, change if desired
	// remark if passed from caller
	precision = parseInt(precision); // make certain the decimal precision is an integer
    var result1 = num * Math.pow(10, precision);
    var result2 = Math.round(result1);
    var result3 = result2 / Math.pow(10, precision);
    return zerosPad(result3, precision);
}


function zerosPad(rndVal, decPlaces) {
    var valStrg = rndVal.toString(); // Convert the number to a string
    var decLoc = valStrg.indexOf("."); // Locate the decimal point
    // check for a decimal 
    if (decLoc == -1) {
        decPartLen = 0; // If no decimal, then all decimal places will be padded with 0s
        // If decPlaces is greater than zero, add a decimal point
        valStrg += decPlaces > 0 ? "." : "";
    }
    else {
        decPartLen = valStrg.length - decLoc - 1; // If there is a decimal already, only the needed decimal places will be padded with 0s
    }
     var totalPad = decPlaces - decPartLen;    // Calculate the number of decimal places that need to be padded with 0s
    if (totalPad > 0) {
        // Pad the string with 0s
        for (var cntrVal = 1; cntrVal <= totalPad; cntrVal++) 
            valStrg += "0";
        }
    return valStrg;
}

// send the value in as "num" in a variable


function deg2rad(cr, hd) {
//
//Convert degrees to radians
//
  crs = (Math.PI/180)* cr;
  head = (Math.PI/180)* hd;
  return (crs, head);
}

function floatFix (Val, Places) {
//
//round decimal places to the right
//
  var Res = "" + Math.round(Val * Math.pow(10, Places));
	 var Dec = Res.length - Places;
	 if (Places != 0){
		  OutString = Res.substring(0, Dec) + "." + Res.substring(Dec, Res.length);
  }
  else {
    OutString = Res;
  }
	 return (OutString);
}


function windSpeed(calcWindSpeed) {
//
//Calculate the wind speed 
//TAS = true airspeed 
//GS = ground speed
//head = heading (in radians)
//crs = course (in radians)
//
  deg2rad(calcWindSpeed.course.value, calcWindSpeed.heading.value);
  ws = Math.sqrt(Math.pow(calcWindSpeed.TAS.value-calcWindSpeed.GS.value, 2)
       + 4*calcWindSpeed.TAS.value*calcWindSpeed.GS.value*
       Math.pow(Math.sin((head-crs)/2), 2));
  calcWindSpeed.windspd.value = Math.round(ws);
}

function windDirection(calcWindDirection) {
//
//Calculate the wind direction
//TAS = true airspeed 
//GS = ground speed
//head = heading (in radians)
//crs = course (in radians)
//
  deg2rad(calcWindDirection.course.value, calcWindDirection.heading.value);
  wd = crs + Math.atan2(calcWindDirection.TAS.value * Math.sin(head-crs), calcWindDirection.TAS.value *
       Math.cos(head-crs) - calcWindDirection.GS.value);
  if (wd<0) {
    wd=wd+2*Math.PI;
  }
  if (wd>2*Math.PI) {
    wd=wd-2*Math.PI;
  }
calcWindDirection.windDir.value = Math.round((180/Math.PI) * wd);
} 

//
//Calculate heading
//
function Heading(calcHeading) {
  crs = (Math.PI/180) * calcHeading.course.value;
  wd = (Math.PI/180) * calcHeading.windDir.value;
  swc = (calcHeading.windSpd.value/calcHeading.TAS.value) * 
        Math.sin(wd - crs);
  if (Math.abs(swc) > 1){
    alert("Danger!... Course should not be flown... Wind is too strong");
    return;
    }
  hd = crs + Math.asin(swc);
  if (hd < 0) {
    hd = hd + 2 * Math.PI;
    }
  if (hd > 2*Math.PI) {
    hd = hd - 2 * Math.PI;
    }
  calcHeading.heading.value = Math.round((180/Math.PI) * hd);
  calcHeading.GS.value = Math.round(calcHeading.TAS.value * Math.sqrt(1 - Math.pow(swc, 2)) - 
         (calcHeading.windSpd.value * Math.cos(wd - crs)));
  wca = Math.atan2(calcHeading.windSpd.value * Math.sin(hd-wd),
                               calcHeading.TAS.value-calcHeading.windSpd.value * 
                               Math.cos(hd-wd));
  calcHeading.WCA.value = Math.round((180/Math.PI) * (wca * -1)); // 6/2/02 CED sign correction
}

//
//Calculate Ground Speed, Course & Wind Correction Angle
//
function GndSpdCrsWca(calcGndSpdCrsWca) {
  wd = (Math.PI/180)*calcGndSpdCrsWca.windDir.value
  hd = (Math.PI/180)*calcGndSpdCrsWca.heading.value
  calcGndSpdCrsWca.GS.value = Math.round(Math.sqrt(Math.pow(calcGndSpdCrsWca.windSpd.value, 2) + 
                              Math.pow(calcGndSpdCrsWca.TAS.value, 2)- 2 * calcGndSpdCrsWca.windSpd.value *
                              calcGndSpdCrsWca.TAS.value * Math.cos(hd-wd)));
  wca = Math.atan2(calcGndSpdCrsWca.windSpd.value * Math.sin(hd-wd),
                               calcGndSpdCrsWca.TAS.value-calcGndSpdCrsWca.windSpd.value * 
                               Math.cos(hd-wd));
  calcGndSpdCrsWca.WCA.value = Math.round((180/Math.PI) * wca);
  crs = (hd + wca) % (2 * Math.PI);
  calcGndSpdCrsWca.course.value = Math.round((180/Math.PI) * crs);
}

//
//Calculate Magnetic variation in different regions
//
function magVar(magneticvar) {
//Magnetic variation for the continental USA only. 
    lat = parseInt(magneticvar.latDegrees.value) + (parseInt(magneticvar.latMinutes.value)/60) + 
        (parseInt(magneticvar.latSeconds.value)/3600);
  lon = parseInt(magneticvar.lonDegrees.value) + (parseInt(magneticvar.lonMinutes.value)/60) + 
        (parseInt(magneticvar.lonSeconds.value)/3600);
  v = -65.6811 + .99 * lat + .0128899 * Math.pow(lat, 2) - .0000905928 *
     Math.pow(lat, 3) + 2.87622 * lon - .0116268 * lat * lon - .00000603925 *
     Math.pow(lat, 2) * lon - .0389806 * Math.pow(lon, 2) - .0000403488 *
     lat * Math.pow(lon, 2) + .000168556 * Math.pow(lon, 3);
  magneticvar.variation.value = floatFix(v,1);
}



//
//Calculate Density Altitude 
//
function densityAltitude(denAlt) {
  if (denAlt.Radio2[0].checked == true) {
	   temp = (denAlt.Temp.value - 32) *5/9;
  }else {
   temp = parseInt(denAlt.Temp.value);
  }
  T_k = 273.15 + temp;
  T_s = 273.15 + (15 - (.0019812 * parseInt(denAlt.PA.value)));
  D_Alt = parseInt(denAlt.PA.value) + (T_s/.0019812) * (1 - (Math.pow(T_s/T_k, .2349690)));
  denAlt.DA.value = Math.round(D_Alt);
}


//
// Calculate F from C
//
function ConvertTempToF(TempConversionF) {
		var temperature = TempConversionF.C.value; 
  if (temperature.length > 0) {
		TempConversionF.TempF.value = Math.round((temperature * 9/5) + 32);
  }
}



//
// Calculate C from F
//
function ConvertTempToC(TempConversionC) {
  var temperature = TempConversionC.F.value;
  if (temperature.length > 0) {
		TempConversionC.TempC.value = Math.round(5/9 * (temperature - 32));
  }
}



//
// Calculate the wind chill
//
function WindChill (calcWindChill) {
  var n, temperature, ws, wc
  var temp = calcWindChill.T.value;
  var winds = calcWindChill.windspd.value;
  if (calcWindChill.corf[1].checked) {
      n = (temp * 9/5) + 32;
	   temperature = n.toString();
  }else{
				  temperature = temp;
  }
  if (calcWindChill.wind[1].checked) {
      n = 	winds/.868391;
      ws = n.toString();
  }else {
		ws = winds;
  }
  if (temperature.length > 0 && ws.length > 0) {
      wc = (.0817*(3.71*(Math.pow(ws, .5))+
      5.81-.25*ws)*(temperature-91.4)+91.4);
      calcWindChill.WindChillTempF.value = Math.round(wc);
      calcWindChill.WindChillTempC.value = Math.round(5/9 * (wc - 32));
  }
 }

 

 //
 // Calculate the heat index
 //
function HeatIndex(calcHeatIndex) {
  var n, F, hi;
  var temp = calcHeatIndex.temp.value;
  var rh = calcHeatIndex.humidity.value;
  if (calcHeatIndex.corf[1].checked) {
      n = (temp * 9/5) + 32;
	   F = n.toString();
  }else{
	   F = temp;
  }
  if (F.length > 0 && rh.length > 0) { 
      hi = -42.379 + 2.04901523*F + 10.14333127*rh - 0.22475541*F*rh -6.83783e-03*Math.pow(F,2) - 5.481717e-02*Math.pow(rh, 2) + 1.22874e-03*Math.pow(F,2)*rh + 8.5282e-04*F*Math.pow(rh, 2)- 1.99e-06*Math.pow(F,2)*Math.pow(rh, 2);
      calcHeatIndex.HeatIndexF.value = Math.round(hi);
      calcHeatIndex.HeatIndexC.value = Math.round(5/9 * (hi - 32));
  }
}



//
// Calculate the dewpoint
//
function Dewpoint(calcDewpoint) {
  var t, f, dewpoint;
  if (calcDewpoint.Radio1[0].checked == true) {
	   t = convert2C(calcDewpoint.T.value);
  }else {
      t = parseInt(calcDewpoint.T.value);
  }
  f = parseInt(calcDewpoint.RH.value)/100;
  dewpoint = 237.3/(1/(Math.log(f)/17.27+t/(t+237.3))-1);
  calcDewpoint.DPC.value = (Math.round(dewpoint));
  calcDewpoint.DPF.value = Math.round((dewpoint * 9/5) + 32);
}


//
// Calculate the relative humidity
//
function RelHumidity(calcRelHumidity) {
  var t, dew, humidity;
  if (calcRelHumidity.Radio1[0]) {
      t = convert2C(calcRelHumidity.T.value);
  }else {
      t = parseInt(calcRelHumidity.T.value);
  }
  if (calcRelHumidity.Radio2[0]) {
      dew = convert2C(calcRelHumidity.DP.value);
  }else {
      dew = (calcRelHumidity.DP.value);
  }
  dew = parseInt(dew);
  humidity = Math.exp(17.27*(dew/(dew+237.3)-t/(t+237.3)))*100;
  calcRelHumidity.RH.value = (Math.round(humidity));
}


function Pressure(calcPressure) {
  var pressure = calcPressure.P.value;
  if (calcPressure.Radio1[0].checked) {
      // Convert Millibars to inches Mercury Hg
	   var inHg = floatFix(pressure * .02953, 2);
	   calcPressure.results.value = inHg + " in Hg";
  }else {
      // Convert inches Mercury Hg to Millibars
	  var MB = floatFix(pressure * 33.8639, 2);
	  calcPressure.results.value = MB + " Millibars";
  }
}



function convert2C(temp) {
  var t;
  t = Math.round(5/9 * (temp - 32));
  return(t);
}


//
// Crosswind functions CED (special input)
//
function compute(Rway) {
           if (Rway.runwayd.value == "" || Rway.wdirection.value == "" || Rway.wspeed.value == ""){ 
      alert("Runway heading, Wind direction and Speed are required") 
    } else 
      {     	
    			d = "";
    			g = "";
    			l = Rway.runwayd.value;
				n = Rway.wdirection.value;
				k = Rway.wspeed.value;
				o = Math.abs(n - l);
				oo = (n - l); //determine left or right relative wind
				p = .0174*o;
				q = Math.abs(k*(Math.sin(p)));
				m = Math.abs(k*(Math.cos(p)));
				Rway.cwresult.value=eval(Math.round(q));
							
				Rway.cwresultloss.value=eval(Math.round(m));

				if (oo < 0){
				d = "LEFT";
				Rway.d.value=d;
				}
				if (oo > 0){
				d = "RIGHT";
				Rway.d.value=d;
				}
				
				if (oo == 0){
				d = "NONE";
				Rway.d.value=d;
				}
				
				if (oo == 360){
				d = "NONE";
				Rway.d.value=d;
				}
				
				if (oo == 180){
				d = "NONE";
				Rway.d.value=d;
				}
				
				//gain wind is behind and helping
				//loss wind is from ahead and hurting
				if (o > 90){
				g = "GAIN";
				Rway.g.value=g;
				}
				
				if (o < 90.0000000000000001){
				g = "LOSS";
				Rway.g.value=g;
				}

				if (o > 270){
				g = "LOSS";
				Rway.g.value=g;
				}

                    
        }    

}


//
// Calculate K from MPH
//
function ConvertMilesToK(SpeedConversionK) {
		var Speed = SpeedConversionK.mph.value; 
  if (Speed.length > 0) {
		SpeedConversionK.knot.value = eval(Speed * 0.86897624);
  }
}

//
// Calculate MPK from K
//
function ConvertKnotsToM(SpeedConversionM) {
  var Speed = SpeedConversionM.knot.value;
  if (Speed.length > 0) {
		SpeedConversionM.mph.value = eval(Speed * 1.15077945);
  }
}

function tairSpeed(calcTAS) {
//
//Calculate the TAS = true airspeed 
//ias = indicated airspeed
//msla = mean sea level altitude
//
    ias1 = eval(calcTAS.ias.value);
	msl1 = eval(calcTAS.msla.value);
	ias2 = (ias1) * .02;
	msl2 = Math.floor(msl1 / 1000);

	calcTAS.tas.value = perRound((ias2 * msl2) + (ias1));
}
