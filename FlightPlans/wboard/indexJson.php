<html>  
  <head>  
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
    <meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1.0">  
    <link href="../css/main.css" rel="stylesheet" media="screen"/> 
    <link href="../css/flights.css" rel="stylesheet" media="screen"/>
    <script src="../js/jquery-1.4.1.min.js" type="text/javascript"></script><!-- don't remove -->
    <script src="../js/jquery.dataTables.min.js" type="text/javascript"></script><!-- don't remove -->
    <script src="../js/css_selector.js" type="text/javascript"></script><!-- don't remove -->

	<script> 
	$(document).ready(function(){
	
		var currentTime = new Date();
		var month = currentTime.getMonth() + 1;
		var day = currentTime.getDate();
		var year = currentTime.getFullYear();
		var fulldate = month + "/" + day + "/" + year;
		$('.dateHere').text(fulldate);
	
		$('#tblFlights').dataTable( {
			"bProcessing": true,
			"sAjaxSource": '../data/flights.txt',
		//	"sAjaxSource": '../data/' + fulldate + '.txt',
			"bPaginate": false,
			"bLengthChange": false,
			"bFilter": true,
			"bSort": false,
			"bInfo": false,
			"bAutoWidth": false
		});
				      		
	});
	</script>

  </head>  
  <body>  
    <h1><div class="leftButton" onclick="history.back();return false">Back</div>AEROTOUR INTRANET</h1>  
    <h2>Flights Board, today is <span class="dateHere"></span></h2>  
    <ul>
      <li class="single">
			<table cellpadding="0" cellspacing="1" border="0" id="tblFlights" width="100%"> 
				<thead> 
					<tr> 
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
					
				</tbody> 
			</table>
      </li>
    </ul>  
  </body>  
</html>