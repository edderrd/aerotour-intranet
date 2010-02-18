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
	  var formData = $(this).serializeArray();

	  // do something with json
	  $.ajax({
	    type: "POST",
	    url: "../php/addFlight.php",
	    //data: dataString,
	    data: formData,
	    success: function(returnValue) {
	      $('#btnSubmit').append(returnValue);
	    }
	  });


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
			
    });


    //do action on select change
    function dropdown(sel){ 
      if(sel.options.selectedIndex == 0){ 
	  alert('Please choose an option'); 
	  return false; 
      } 
      else{ 
	  c = confirm('You chose ' + sel.options[sel.selectedIndex].value + '\nDo you want to continue?'); 
	  if(c){ sel.form.submit(); } else{ sel.selectedIndex = 0; } 
      } 
    };


    </script>
    <style>
    div label {
      width: 100px !important;
      display: inline-block !important;
    }
    </style>
  </head>  
  <body>  
    <h1><div class="leftButton" onclick="location.href='index.php'">Back</div>AEROTOUR INTRANET</h1>  
    <h2>Add a flight to White Board</h2>  
    <ul>
      <li class="single">
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
	    <div><label for="fdate">Date:</label> <input type="text" class="iinputui" name="fdate" id="datepicker"></div>
	    <div><label for="route">Route:</label> <input type="text" class="iinputui" id="route" name="route"/></div>
	    <div><label for="pax">Pax:</label> <input type="text" class="iinputui" id="pax" name="pax" /></div>
	    <div>
	      <label for="tail">Tail:</label>
	      <select name="tail">
				<option value="TI-BBU">TI-BBU</option>
				<option value="TI-BAZ">TI-BAZ</option>
				<option value="TI-BAR">TI-BAR</option>
				<option value="TI-BCA">TI-BCA</option>
				<option value="TI-AZZ">TI-AZZ</option>
				<option value="TI-BBM">TI-BBM</option>
				<option value="TI-BBP">TI-BBP</option>
				<option value="TI-BCC">TI-BCC</option>
				<option value="TI-BBA">TI-BBA</option>
				<option value="TI-BDI">TI-BDI</option>
				<option value="TI-BCZ">TI-BCZ</option>
				<option value="TI-AZZ">TI-AZZ</option>
				<option value="TI-AZA">TI-AZA</option>
				<option value="TI-ATP">TI-ATP</option>
				<option value="TI-AVO">TI-AVO</option>
	      </select>
	    </div>
	    <div>
	      <label for="pilot">Pilot:</label>
	      <select name="pilot">
				<option value="Federico Laurencich">Federico Laurencich</option>
				<option value="Jose Leiva">Jose Leiva</option>
				<option value="Adam Swafford">Adam Swafford</option>
				<option value="Dennis Iotti">Denis Iotti</option>
				<option value="Luis Paulino Guzman">Luis Paulino Guzman</option>
				<option value="Rogelio Navas">Rogelio Navas</option>
				<option value="Jose Romero">Jose Romero</option>
				<option value="Alekse Kuzenkov">Alekse Kuzenkov</option>
	      </select>
	    </div>
	    <div><label for="client">Client:</label> <input type="text" class="iinputui" name="client" id="client" /></div>
	    <div><label for="leaves">Leaves:</label> <input  type="text" class="iinputui" name="leaves" id="leave" /></div>
	    <div><label for="returns">Return:</label> <input type="text" class="iinputui" name="returns" id="returns" /></div>
	    <div><label for="fuel">Fuel:</label> <input type="text" class="iinputui" name="fuel" id="fuel" /></div>
	    <div><label for="notes">Notes:</label> <textarea id="notes" name="notes" rows="5" cols="50"></textarea></div>
	    <p id="btnSubmit"><input type="submit" name="submit" class="button" id="submit_btn" value="Add new Flight" /></p>
	  </fieldset>
	</form>
      </li>
     </ul>
     
     <h2>Manage Flights</h2>
     
     <ul>
      <li class="single">
		<div id="curData">
		
			<table cellpadding="0" cellspacing="1" border="0" id="tblFlights" width="100%"> 
				<thead> 
					<tr> 
						<th>X</th>
						<th>Status</th> 
						<th>Date</th> 
						<th>Route</th> 
						<th>Pax</th> 
						<th>Tail</th>
						<th>Pilot</th>
						<th>Client</th>
						<th>Leave</th>
						<th>Return</th>
						<th>Fuel</th>
						<th>Notes</th>
					</tr> 
				</thead> 
				<tbody> 
					<?php
									
						$dbname='../data/flights.sqlite';
						$mytable ="schedule";
						
						$base=new SQLiteDatabase($dbname, 0666, $err);
						if ($err)  exit($err);		
										
						//read data from database
						$query = "SELECT id, status, date, route, pax, tail, pilot, client, leaves, returns, fuel, notes FROM $mytable ORDER BY date";
						$results = $base->arrayQuery($query, SQLITE_ASSOC);
						$size = count($results);
						
						for($i = 0; i < $size; $i++)
						{
						  $arr = $results[$i];
						  if(count($arr) == 0) break;
  							
  							$id = $arr['id'];
							$status = $arr['status'];
							$date = $arr['date']; 
							$route = $arr['route'];
							$pax = $arr['pax'];
							$tail = $arr['tail'];
							$pilot = $arr['pilot'];
							$client = $arr['client']; 
							$leaves = $arr['leaves'];
							$returns = $arr['returns'];
							$fuel = $arr['fuel'];
							$notes = $arr['notes'];

						echo "<form name='statusForm' action='status.php' id='formStatus'>";
						echo "<input type='hidden' name='id' value='$id' />";
						echo "<tr>";
						echo "<td style='text-align:center'><a href='delete.php?id=$id&'>delete</a></td>";   
						echo "<td><select name='status' onchange='return dropdown(this)'>
							<option value=''>Change Status</option>
							<option value='ACTIVE'>ACTIVE</option>
							<option value='STANDBY'>STANDBY</option>
							<option value='CANCELED'>CANCELED</option>
							</select> | $status</td>";
						echo "<td> $date </td>";
						echo "<td> $route </td>";
						echo "<td> $pax </td>";
						echo "<td> $tail </td>";
						echo "<td> $pilot </td>";
						echo "<td> $client </td>";
						echo "<td> $leaves </td>";
						echo "<td> $returns </td>";
						echo "<td> $fuel </td>";
						echo "<td> $notes </td>";
						echo "</tr>";
						echo "</form>";
						}
						
						//echo "$size records in database\n";
											
						
						
					?>
				</tbody> 
			</table>
		</div>
		<p class="disclamer"><a href="clear.php">Delete all flights before today.</a></p>
      </li>
    </ul>  
  </body>  
</html>
