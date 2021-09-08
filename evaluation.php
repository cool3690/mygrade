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
	<body>
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
	 
        <font size="7"><center>評價統計次數</font> </br> </br>
    <div class="container">
    
   
				<table class="table table-striped table-responsive-xl">
				<tbody>	
<tr>				
<?php
			$i=0;
 
$grade= "['time','rawgrade'],";
$grade_barchart= "['time','good', 'soso', 'bad'],";
//if(isset($_POST['start_d'])&& isset($_POST['end_d'])){
 //	$end_d=$_POST['end_d'];
   //  $start_d=$_POST['start_d'];//and FROM_UNIXTIME(`timecreated`, '%Y-%m-%d') BETWEEN '$start_d' and '$end_d'
 
   
//	$grade_barchart.="['$h', $g ,'gold'],";  
//$grade_barchart=rtrim($grade_barchart,",");

///////////////creat post/////
// SELECT A.cmid,A.vote,B.name FROM mdl_block_point_view A,mdl_modules B WHERE A.courseid='3' and A.cmid=B.id ORDER BY A.cmid // 計算每個物件有幾次讚
//count object
$sql_count="SELECT DISTINCT cmid FROM mdl_block_point_view WHERE courseid='$courseid' ORDER By `cmid`";
//$statement_count = $db->query($sql_count);
//$show_count = mysqli_num_rows($statement_count);
//echo "show=".$show_count."  </br>  ";
//count finish

//$DB->get_records_sql($sql_count);//

$tmp=0;
$i=0;$j=0;$k=0;
$sql_module="select* from mdl_course_modules  where course='$courseid' ";
$stat_module=$DB->get_records_sql($sql_module);
$t=1;
foreach($stat_module as $row){

  $id=$row->id;
  $module=$row->module;

  $sql_choose="select* from mdl_modules where id='$module'";
  $stat_choose=$DB->get_records_sql($sql_choose);
  foreach($stat_choose as $row){
    $name=$row->name;
  }
  $sql_fin="select* from mdl_block_point_view where courseid='$courseid' and cmid='$id'";
  $stat_fin=$DB->get_records_sql($sql_fin);
   $i=0;$j=0;$k=0;
  foreach($stat_fin as $row){
    
      
      
        if($row->vote==1){$i++;}
        else if($row->vote==2){$j++;}
        else if($row->vote==3){$k++;}
      
  
  }
  $grade_barchart.="['$name.$t', $i ,$j,$k],"; 
  
    $t++;
 //  echo $row->id.$row->name."<br>";
}

$grade_barchart=rtrim($grade_barchart,",");
//echo $grade_barchart;
/**/

/*

$sql_choose="select* from mdl_modules";
$i=0;
  $stat_choose=$DB->get_records_sql($sql_choose);
  foreach($stat_choose as $row){
    $namearr[$i]=$row->name;
    $i++;
  }
 //your vote
 $sql_post="select DISTINCT count(A.vote),A.vote,A.cmid,B.name 
 from mdl_block_point_view A,mdl_modules B  
 where A.courseid='$courseid' and A.cmid=B.id
 group by A.courseid,A.cmid,A.vote
order by A.courseid,A.cmid,A.vote";
$tmp=0;
$i=0;$j=0;$k=0;
$name="";
$statement_post = $db->query($sql_post);
foreach($statement_post as $row){
	 if($tmp==0){
    $name=$row['name'];
    if($row['vote']==1){$i+=$row['count(A.vote)'];}
    else if($row['vote']==2){$j+=$row['count(A.vote)'];}
    else if($row['vote']==3){$k+=$row['count(A.vote)'];}
   }
  else{
    if($name==$row['name']){
      if($row['vote']==1){$i+=$row['count(A.vote)'];}
      else if($row['vote']==2){$j+=$row['count(A.vote)'];}
      else if($row['vote']==3){$k+=$row['count(A.vote)'];}
    }
    else{
      $grade_barchart.="['$name', $i ,$j,$k],";  
      $i=0;$j=0;$k=0;
      $name=$row['name'];
      if($row['vote']==1){$i+=$row['count(A.vote)'];}
      else if($row['vote']==2){$j+=$row['count(A.vote)'];}
      else if($row['vote']==3){$k+=$row['count(A.vote)'];}
    }
  }
  $tmp++;
   // echo  $row['count(A.vote)']."  ".$row['cmid']."  ".$row['vote']."  ". $row['name']."<br>";
}
$grade_barchart.="['$name', $i ,$j,$k],"; 
$grade_barchart=rtrim($grade_barchart,",");
echo $grade_barchart;
*/

?>
<script type="text/javascript">
    
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([<?=$grade_barchart;?>]);

        var options = {
          chart: {
            title: '評價統計次數',
            subtitle: '即時了解同學滿意度 ',
          },
          bars: 'horizontal' // Required for Material Bar Charts.
        };

        var chart = new google.charts.Bar(document.getElementById('chart_div2'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    
	  
    </script>
			<div id="chart_div2" style="width: 900px; height: 500px;"></div>
			 
         </div>
                                     
	</tr> 
	</tbody>
	</table>
  
 
         <?php
//}
  
?>      
    </body>
</html>