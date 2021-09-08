<html>
    <head>
      <meta charset="utf-8">
	  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
      <link rel="stylesheet" href="https://cdn.rawgit.com/dmuy/MDTimePicker/7d5488f/mdtimepicker.min.css">
      <script src="https://cdn.rawgit.com/dmuy/MDTimePicker/7d5488f/mdtimepicker.min.js"></script>
	  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    </head>
    <script>
  
  $(function(){
			$( "#start_d" ).datepicker({
				changeMonth:true,
				changeYear:true,
				dateFormat:"yy-mm-dd",
						
			});
		
		$( "#end_d" ).datepicker({
				changeMonth:true,
				changeYear:true,
				dateFormat:"yy-mm-dd",
						
			});
		
		});
		
  </script>
<?php
//require_once("db.php");
require_once(__DIR__ . '/../../config.php');
defined('MOODLE_INTERNAL') || die();
global $CFG, $DB, $OUTPUT,$USER;
//require_once($CFG->dirroot.'/lib/moodlelib.php');

//$courseid = isset($_GET['id']);

if(isset($_GET['id']))$courseid =$_GET['id'] ;
if(isset($_GET['uid']))$userid =$_GET['uid'] ;
//echo $courseid ;
//echo $userid;
//require_login($courseid);
//$context = context_course::instance($courseid);
//require_capability('block/mygrade:viewpages', $context);
?>


    <body>
	 
        <font size="7"><center>教材觀看統計</font> </br> </br>
    <div class="container">
    <form id="form1" name="form1" method="post" action="view_statistic.php?id=<?=$courseid;?>"  class="form-inline">
    起始日：<input type = "text" class="form-control" name="start_d" id= "start_d" size = "20" value="<?php if(isset($_POST['start_d'])) echo $_POST['start_d'];?>">
    結束日： <input type = "text" class="form-control" name= "end_d" id= "end_d" size = "20"  value="<?php if(isset($_POST['end_d'])) echo $_POST['end_d'];?>">
    <input type="submit" class="btn btn-primary" value="送出"><br>
       </form>
   
<table class="table table-striped table-responsive-xl">
<tbody>	
<tr>				
<?php
			$i=0;
     // isset($_POST['start_d'])
if(isset($_POST['start_d'])){
 
   $start_d=$_POST['start_d'];
   $end_d=$_POST['end_d'];
  
	// echo $sub."的成績"; 
$grade= "['time','rawgrade'],";
$grade_barchart= "['time','rawgrade' ],";
 
$sql="SELECT * FROM mdl_course where id='$courseid'";
	$statement = $DB->get_records_sql($sql);//$db->query($sql);
		foreach($statement as $row){
			 
			  echo "<h3> 科目: ".$row->fullname."教材觀看統計</h3>"; 
		    
	 	}
/////
 

//////COUNT(A.userid) AS user_id
$sql="select COUNT(objecttable) AS c_objecttable ,objecttable
from mdl_logstore_standard_log 
where  courseid='$courseid' and action='viewed'and objecttable!='' and FROM_UNIXTIME(timecreated, '%Y-%m-%d') BETWEEN '$start_d' and '$end_d'
group by objecttable";
 
 
 $statement = $DB->get_records_sql($sql);//$db->query($sql);
 $sum=0;
 
 //echo "<tr><th>姓名</th><th>觀看次數</th></tr>";
foreach($statement as $row){
	 
  //  echo "<tr><td>".$row->c_objecttable .$row->objecttable."</td> </tr>";
     if($row->objecttable!='quiz_attempts'){
         	$g= $row->c_objecttable;//counting
            $h=$row->objecttable;
            
            $grade_barchart.="['$h', $g ],";
     }

   
}
 
 
/**/
 
 $grade_barchart=rtrim($grade_barchart,",");
 //echo $grade_barchart;
}
/*
$dev=0;
 for($j=0;$j<$i;$j++){
	 $dev+=pow($arrcounnt[$j]-$ave,2);
 }
 $dev=sqrt($dev/($i-1));
 */
?>

                                     
	</tr> 
	</tbody>
	</table>
    <?php // echo "[$grade_barchart]"
    ?>
  
 
      <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
            <?=$grade_barchart;?>
        ]);

        var options = {
          title: '使用率',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>
			 
            <div id="donutchart" style="width: 900px; height: 500px;"></div>
			 
         </div> 
       
    </body>
</html>
