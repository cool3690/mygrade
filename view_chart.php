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
	 
        <font size="7"><center>課程登入觀看次數</font> </br> </br>
    <div class="container">
    <form id="form1" name="form1" method="post" action="view_chart.php?id=<?=$courseid;?>"  class="form-inline">
    起始日：<input type = "text" class="form-control" name="start_d" id= "start_d" size = "20" value="<?php if(isset($_POST['start_d'])) echo $_POST['start_d'];?>">
    結束日： <input type = "text" class="form-control" name= "end_d" id= "end_d" size = "20"  value="<?php if(isset($_POST['end_d'])) echo $_POST['end_d'];?>">
    <input type="submit" class="btn btn-primary" value="送出"><br>
       </form>
   
<table class="table table-striped table-responsive-xl">
<tbody>	
<tr>				
<?php
			$i=0;
      $ave=0;
      $dev=0;
if(isset($_POST['start_d'])){
//	$sub=$_POST['emp2'];
  //$sub=$courseid;
  $start_d=$_POST['start_d'];
  $end_d=$_POST['end_d'];
	// echo $sub."的成績"; 
$grade= "['time','rawgrade'],";
$grade_barchart= "['time','rawgrade', { role: 'style' }],";
 
$sql="SELECT * FROM mdl_course where id='$courseid'";
	$statement = $DB->get_records_sql($sql);//$db->query($sql);
		foreach($statement as $row){
			 
			  echo "<h3> 科目: ".$row->fullname."的登入觀看次數</h3>"; 
		    
	 	}
/////
 

//////COUNT(A.userid) AS user_id
$sql="select COUNT(A.userid) AS user_id,A.action,A.userid,A.courseid, B.id,B.firstname,B.lastname 
from mdl_logstore_standard_log A, mdl_user B 
where A.action='viewed' and A.userid= B.id  and A.courseid='$courseid' and FROM_UNIXTIME(A.timecreated, '%Y-%m-%d') BETWEEN '$start_d' and '$end_d'
group by A.userid";
 //$sql="select userid,rawgrade,rawgrademax,timecreated from mdl_grade_grades where userid='$sub' and timecreated!='NULL'"
$statement = $DB->get_records_sql($sql);//$db->query($sql);
 $sum=0;
 
 echo "<tr><th>姓名</th><th>觀看次數</th></tr>";
foreach($statement as $row){
	$arrcounnt[$i]=$row->user_id;
	$i++;
	
    echo "<tr><td>".$row->lastname .$row->firstname."</td><td>".$row->user_id ."</td></tr>";
    /* */
	$g= $row->user_id;//counting
	$h=$row->lastname .$row->firstname ;
	$sum+=$g;
	//$grade.="['$i',$g],";
	$grade_barchart.="['$h', $g ,'gold'],";
   
}
$ave=$sum/$i;
/**/
//$grade=rtrim($grade,",");
$grade_barchart=rtrim($grade_barchart,",");
}
$dev=0;
 for($j=0;$j<$i;$j++){
	 $dev+=pow($arrcounnt[$j]-$ave,2);
 }
 $dev=sqrt($dev/($i-1));
 
?>

                                     
	</tr> 
	</tbody>
	</table>
<?php
    if($ave==""){$ave=0;}
    if($dev==""){$dev=0;}
?>
   <center><h3>每人平均觀看次數:  <?=round($ave,2);?></h3><br>
   <h3>標準差:  <?=round($dev,2);?></h3></center>
	 

<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php  echo "[$grade_barchart]"?>); 
        var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);
 
      var options = {
        title: "觀看次數",
        width: 600,
        height: 400,
        bar: {groupWidth: "20%"},
        legend: { position: "none" },
      };
        var chart = new google.visualization.BarChart(document.getElementById('chart_div2'));
        chart.draw(data, options);
      }
	  google.setOnLoadCallback(drawChart1);
	  
    </script>
			<div id="chart_div2" style="width: 900px; height: 200px;"></div>
			 
         </div> 
       
    </body>
</html>
