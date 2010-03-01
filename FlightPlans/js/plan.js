//Sets the value for all magnetic variation text fields
function magVarChange(parentElement) {
	$(".magVar").each(function(index, element){
		element.value = parentElement.val();
	});
}

//Sets the value of all wind direction and velocity
function setWindDirVelocity() {

}

//Calculates Drift
function calculateDrift() {

}

//Calculates Magnetic Heading
function calculateMagHdn() {

}

//Calculates Ground Speed
function calculateGroundSpeed() {

}

//Calculates travel Time for each point in min
function calculateTime() {

}

//Calculates Total Ground Speed, displayed as average.
function calculateTotalGs() {
   var calculatedAvgSpeed = 0;
   var objRegExp = '\s+';
   $("input[name^='avgSpeed']").each ( function() {
    calculatedAvgSpeed += parseFloat ( $(this).val().replace(/\s/g,'').replace(',','.'));
   });
   $("#averageSpeed").val(calculatedAvgSpeed + " gs");
}

//Calculates Total Time to trave, displayed in minutes.
function calculateTotalTime() {
   var calculatedTotalTime = 0;
   var objRegExp = '\s+';
   $(".totTime").each ( function() {
    calculatedTotalTime += parseFloat ( $(this).val().replace(/\s/g,'').replace(',','.'));
   });
   $("#totalTime").val(calculatedTotalTime);
   $("#enroute").val(calculatedTotalTime);
calculateFuelBurn();
}

//Calculates Fuel Required.
function calculateFuelBurn() {
   var calculatedFuelSum = 14;
   var objRegExp = '\s+';
   $(".fuelSum").each ( function() {
    calculatedFuelSum += parseFloat ( $(this).val().replace(/\s/g,'').replace(',','.'));
   });
   calculatedFuel = Math.round(calculatedFuelSum * parseFloat($("#fuelcons").val()) / 60);
   $("#totalFuel").val(calculatedFuel + " gal");
   $("#txtFuel").val(calculatedFuel + " gal");
}


//by setting windDirVelocity calculate GS, Correstion Angle,  Magnetic Heading
function windDirVelocity() {
  wd = (Math.PI/180)*$(".windVal").val();
  hd = (Math.PI/180)*$(".trueTrack").val();
//  $(".gsKnots").val() = Math.round(Math.sqrt(Math.pow(calcGndSpdCrsWca.windSpd.value, 2) + 
//                              Math.pow($("#tas").val(), 2)- 2 * calcGndSpdCrsWca.windSpd.value *
  $(".gsKnots").val() = Math.round(Math.sqrt(Math.pow(20, 2) + 
                              Math.pow($("#tas").val(), 2)- 2 * 20 *
                              $("#tas").val() * Math.cos(hd-wd)));
//  wca = Math.atan2(calcGndSpdCrsWca.windSpd.value * Math.sin(hd-wd),
//                               $("#tas").val()-calcGndSpdCrsWca.windSpd.value *
  wca = Math.atan2(20 * Math.sin(hd-wd),
                               $("#tas").val()-20 * 
                               Math.cos(hd-wd));
  $(".drift").val() = Math.round((180/Math.PI) * wca);
  crs = (hd + wca) % (2 * Math.PI);
  $(".heading").val() = Math.round((180/Math.PI) * crs);
}

