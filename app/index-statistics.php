	
<div id="pagecontainer">

<iframe src="http://www.ribotech.net/zanabanda/stat" name="iframe_a" width="1200" height="500" scrolling="yes"></iframe>
<p><a href="http://www.ribotech.net" target="iframe_a">Ribotech.net</a></p>

</div>


<?php
exit;
$dashboard = new amazingcart_analytics;


if(!$_GET['byYear'] || empty($_GET['byYear']))
{
	$year = date('Y');	
}
else
{
	$year = $_GET['Year'];
}



if(!$_GET['byMonth'] || empty($_GET['byMonth']))
{
	$yearByDay = date('Y');	
	$monthByDay = date('n');	
}
else
{
	$da = explode(",", $_GET['byMonth']);
	$monthByDay = $da['0'];
	$yearByDay = $da['1'];	
	
}

function getMonthName($num){
$monthNum = $num;
$monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
return $monthName;	
}
?>
<?php $num = cal_days_in_month(CAL_GREGORIAN, $monthByDay, $yearByDay);?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Downloads'],
          ['January',  <?php echo $dashboard->getByMonth(1,$year); ?>],
          ['February',  <?php echo $dashboard->getByMonth(2,$year); ?>],
          ['March',  <?php echo $dashboard->getByMonth(3,$year); ?>],
          ['April',  <?php echo $dashboard->getByMonth(4,$year); ?>],
		  ['May',  <?php echo $dashboard->getByMonth(5,$year); ?>],
		  ['June',  <?php echo $dashboard->getByMonth(6,$year); ?>],
		  ['July',  <?php echo $dashboard->getByMonth(7,$year); ?>],
		  ['August',  <?php echo $dashboard->getByMonth(8,$year); ?>],
		  ['September',  <?php echo $dashboard->getByMonth(9,$year); ?>],
		  ['October',  <?php echo $dashboard->getByMonth(10,$year); ?>],
		  ['November',  <?php echo $dashboard->getByMonth(11,$year); ?>],
		  ['December',  <?php echo $dashboard->getByMonth(12,$year); ?>]
        ]);

        var options = {
          title: 'Downloaded App',
          hAxis: {title: 'Monthly Downloads',  titleTextStyle: {color: 'red'}}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
		
		
		
      }
	  
	  
	  
	  google.setOnLoadCallback(drawChart2);
      function drawChart2() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'Downloads'],<?php  for($i=1;$i<=$num;$i++){?> ['<?php echo $i; ?>',  <?php echo $dashboard->getByDay($i,$monthByDay,$yearByDay); ?>], <?php } ?>]);

        var options = {
          title: 'Downloaded App',
          hAxis: {title: 'Dailly Downloads',  titleTextStyle: {color: 'blue'}}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div2'));
        chart.draw(data, options);
		
		
		
		
      }
	  
    </script>

<div style="padding-right:10px;">
	<h1>App Statistics Dashboard</h1>
	<div><hr /></div>
    <div>
   
    
     <div style="width:100px; background-color:#CCC; padding:10px; float:left;">
    		<div align="center" style="font-size:30px; font-weight:bold; margin-top:10px;"><?php echo $dashboard->getiPhoneTotalDownload(); ?></div>
        	<div align="center" style="margin-top:10px;" >Total iPhone </div>
        </div>
        
        <div style="width:100px; background-color:#CCC; padding:10px; float:left;">
    		<div align="center" style="font-size:30px; font-weight:bold; margin-top:10px;"><?php echo $dashboard->getAndroidTotalDownload(); ?></div>
        	<div align="center" style="margin-top:10px;" >Total Android </div>
        </div>
    
    
    	<div style="width:100px; background-color:#CCC; padding:10px; float:left;">
    		<div align="center" style="font-size:30px; font-weight:bold; margin-top:10px;"><?php echo $dashboard->getAllTimeTotalDownload(); ?></div>
        	<div align="center" style="margin-top:10px;" >Total Download</div>
        </div>    
        
        <div style="width:100px; background-color:#CCC; padding:10px; float:left; margin-left:10px;">
    		<div align="center" style="font-size:30px; font-weight:bold; margin-top:10px;"><?php echo $dashboard->getSubscriber(); ?></div>
        	<div align="center" style="margin-top:10px;" >Apns Subscriber</div>
        </div>
        
        <div style="width:100px; background-color:#CCC; padding:10px; float:left; margin-left:10px;">
    		<div align="center" style="font-size:30px; font-weight:bold; margin-top:10px;"><?php echo $dashboard->getTodays(); ?></div>
        	<div align="center" style="margin-top:10px;" >Today's</div>
        </div>
        
        <div style="width:100px; background-color:#CCC; padding:10px; float:left; margin-left:10px;">
    		<div align="center" style="font-size:30px; font-weight:bold; margin-top:10px;"><?php echo $dashboard->getMonths(); ?></div>
        	<div align="center" style="margin-top:10px;" >This Month</div>
        </div>
        
        <div style="width:100px; background-color:#CCC; padding:10px; float:left; margin-left:10px;">
    		<div align="center" style="font-size:30px; font-weight:bold; margin-top:10px;"><?php echo $dashboard->getYear(); ?></div>
        	<div align="center" style="margin-top:10px;" >This Year</div>
        </div>
        
        
        
        <div style="clear:both;"></div>
    </div>
    <div style="margin-top:30px;">
        <div><div style="float:left; width:60%;"><h1>Monthly Graph <?php echo $year; ?></h1></div><div style="float:left; margin-top:13px; width:39%;"><div align="right"><select id="yearSort1"><option value="0">Select Year</option> <?php for($i=2013;$i<=date('Y');$i++){ ?> <option value="<?php echo $i; ?>"><?php echo $i; ?></option> <?php } ?></select><button class="button-primary" onclick="sort1()">Sort</button></div></div><div style="clear:both;"></div></div>
        <div id="chart_div" style="width: 100%; height:300px;"></div>
    </div>
    
    <div style="margin-top:30px;">
         <div><div style="float:left; width:60%;"><h1>Dailly Graph <?php echo getMonthName($monthByDay); ?> <?php echo $yearByDay; ?></h1></div><div style="float:left; margin-top:13px; width:39%;"><div align="right">
<select id="monthSort2">
<option value="0">Select Month</option> 
<?php for($i=1;$i<=12;$i++){ ?>
<option value="<?php echo $i; ?>"><?php echo getMonthName($i); ?></option>
<?php } ?>


 </select> 
 
 <select id="yearSort2">
 <option value="0">Select Year</option> 
<?php for($i=2013;$i<=date('Y');$i++){ ?> <option value="<?php echo $i; ?>"><?php echo $i; ?></option> <?php } ?>
 </select>
 <button class="button-primary" onclick="sort2()" >Sort</button>
 </div></div><div style="clear:both;"></div></div>
        <div id="chart_div2" style="width: 100%; height:300px;"></div>
    </div>
</div>

<script>
	function sort1(){
		
		var e = document.getElementById("yearSort1");
		var strUser = e.options[e.selectedIndex].value;
		if(strUser == "0")
		{
		alert('Please select your year');	
		}
		else
		{
		window.location = "?page=AmazingCartDashboard&byYear="+strUser+"";
		}
	}
	
	function sort2(){
		
		var e1 = document.getElementById("monthSort2");
		var strUser1 = e1.options[e1.selectedIndex].value;
		
		var e2 = document.getElementById("yearSort2");
		var strUser2 = e2.options[e2.selectedIndex].value;
		
		if(strUser1 == "0")
		{
		alert('Please select your month');	
		}
		else if(strUser2 == "0")
		{
		alert('Please select your year');	
		}
		else
		{
		window.location = "?page=AmazingAmazingCartDashboard&byMonth="+strUser1+","+strUser2+"";
		}
	}

</script>