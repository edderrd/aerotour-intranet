<html>  
  <head>  
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
    <meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1.0">  
    <link href="../css/main.css" rel="stylesheet" media="screen"/> 
    <link href="../css/plan.css" rel="stylesheet" media="screen"/> 
    <link href="http://jqueryui.com/themes/base/ui.all.css" rel="stylesheet" media="screen"/>
    <script src="../js/jquery-1.4.1.min.js" type="text/javascript"></script><!-- don't remove -->
    <script src="http://jqueryui.com/ui/ui.core.js" type="text/javascript"></script><!-- don't remove -->
    <script src="http://jqueryui.com/ui/ui.datepicker.js" type="text/javascript"></script><!-- don't remove -->
    <script src="../js/css_selector.js" type="text/javascript"></script><!-- don't remove -->

     <script> 
     	$(document).ready(function(){
			//Attach datepicker
			$("#datepicker").datepicker();
			
			$("#addFlight").submit(function(){
			    var arr = $(this).serializeArray();
			    var json = "";
			    jQuery.each(arr, function(){
			        jQuery.each(this, function(i, val){
			                if (i=="name") {
			                        json += '"' + val + '":';
			                } else if (i=="value") {
			                        json += '"' + val.replace(/"/g, '\\"') + '",';
			                }
			        });
			    });
			    json = "{" + json.substring(0, json.length - 1) + "}";
			    // do something with json
				  $.ajax({
				    type: "POST",
				    url: "../php/addFlight.php",
				    //data: dataString,
				    data: {json: json},
				    success: function(returnValue) {
				      $('#btnSubmit').append(returnValue);
				    }
				  });
			    
			    //write out for refference into div
			    $("#curData").append(json + "<br/>");
			    
			    //clear all fields
			    $("#addFlight").find(':input').each(function() {
        			switch(this.type) {
            		case 'text':
            		case 'textarea':
               		 $(this).val('');
                		break;
            		case 'radio':
                		this.checked = false;
        			}
    			});
			    return false;
			});
			
			//success: function() {
			//  $('#btnSubmit').append("<div id='message' class='ui-state-highlight' style='padding:10px;text-align:center;'>Flight Added!></div>");
			//}

			
		});
     </script>
  <style>
    div label {
      width: 100px !important;
      display: inline-block !important;
    }
  </style>
  </head>  
  <body>  
    <h1><div class="leftButton" onclick="history.back();return false">Back</div>AEROTOUR INTRANET</h1>  
    <h2>Add a flight to White Board</h2>  
    <ul>
      <li>
	<form action="" method="post" name="addFlight" id="addFlight">
	  <fieldset>
	    <div>
	      <label for="fstatus">Status:</label> 
	      <select name="fstatus">
		<option value="ACTIVE">ACTIVE</option>
		<option value="STANDBY">STANDBY</option>
		<option value="CANCELED">CANCELED</option>
	      </select>
	    </div>
	    <div><label for="date">Date:</label> <input type="text" class="iinputui" name="date" id="datepicker"></div>
	    <div><label for="route">Route:</label> <input type="text" class="iinputui" id="route" name="route"/></div>
	    <div><label for="pax">Pax:</label> <input type="text" class="iinputui" id="pax" name="pax" /></div>
	    <div>
	      <label for="tail">Tail:</label>
	      <select name="tail">
		<option value="TI-BBU">TI-BBU</option>
		<option value="TI-BAZ">TI-BAZ</option>
		<option value="TI-AZZ">TI-AZZ</option>
		<option value="TI-BBM">TI-BBM</option>
		<option value="TI-BBC">TI-BBC</option>
		<option value="TI-BBA">TI-BBA</option>
		<option value="TI-BCZ">TI-BCZ</option>
		<option value="TI-AZA">TI-AZA</option>
	      </select>
	    </div>
	    <div>
	      <label for="pilot">Pilot:</label>
	      <select name="pilot">
		<option value="Federico Laurench">Federico Laurench</option>
		<option value="Jose Leiva">Jose Leiva</option>
		<option value="Adam Swafford">Adam Swafford</option>
		<option value="Dennis Rodman">Dennis Rodman</option>
		<option value="Alekse Kuzenkov">Alekse Kuzenkov</option>
	      </select>
	    </div>
	    <div><label for="client">Client:</label> <input type="text" class="iinputui" name="client" id="client" /></div>
	    <div><label for="leave">Leaves:</label> <input  type="text" class="iinputui" name="leave" id="leave" /></div>
	    <div><label for="returns">Return:</label> <input type="text" class="iinputui" name="returns" id="returns" /></div>
	    <div><label for="fuel">Fuel:</label> <input type="text" class="iinputui" name="fuel" id="fuel" /></div>
	    <div><label for="notes">Notes:</label> <textarea id="notes" name="notes" rows="5" cols="50"></textarea></div>
	    <p id="btnSubmit"><input type="submit" name="submit" class="button" id="submit_btn" value="Add new Flight" /></p>
	  </fieldset>
	</form>
      </li>
      <li>
		<h2>Added Flights</h2>
		<div id="curData"></div>
      </li>
    </ul>  
  </body>  
</html>
