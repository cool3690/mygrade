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
<?php
//require_once("db.php");
require_once(__DIR__ . '/../../config.php');
defined('MOODLE_INTERNAL') || die();
global $CFG, $DB, $OUTPUT,$USER;
 
if(isset($_GET['id']))$courseid =$_GET['id'] ;
if(isset($_GET['uid']))$userid =$_GET['uid'] ;
 $pass=0;$fail=0;
?>


    <body>
    <div class="container"> 
    <center><h1>個人成績平均表</h1> 
      <?php 
      $grade_barchart= "['quiz','小考平均','小考成績預測','小考成績'],";
         
           
           ?>

                
               <?php
               
             $sql_quiz="select* from mdl_quiz where course='$courseid'";
             $sta_quiz =$DB->get_records_sql($sql_quiz);// $db->query($sql_user);
             $i=0;
             foreach($sta_quiz as $row){
                $id= $row->id;
                $arrname[$i]= $row->name;
                $sql_ave="select* from mdl_quiz_grades where quiz='$id'and userid='$userid'";
                $sta_ave =$DB->get_records_sql($sql_ave);
                foreach($sta_ave as $row){
                  $arr_ave[$i]=$row->grade ;
                 
                }
                  $i++;
            } 
            $sum=0;
            for($j=0;$j<$i;$j++){
              $sum+=$arr_ave[$j];
              if($arr_ave[$j]==''){$arr_ave[$j]=0;}
              if($j==0){
                $grade_barchart.="['$arrname[$j]', $arr_ave[$j] ,$arr_ave[$j] ,$arr_ave[$j]],";
              }
              else{
                $tmp=($arr_ave[$j]+$arr_ave[$j-1])/2;
                 $ave=$sum/($j+1);
                $grade_barchart.="['$arrname[$j]' ,$ave,$tmp, $arr_ave[$j]],";
              }
            }
            $grade_barchart=rtrim($grade_barchart,",");
          //echo  $grade_barchart;
            ?>
             
           
            <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([<?=$grade_barchart;?>]);

        var options = {
          title : '小考分數及預測',
          vAxis: {title: '分數'},
          hAxis: {title: '小考'},
          seriesType: 'line',
          series: {2: {type: 'bars'}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
			<div id="chart_div" style="width: 900px; height: 500px;"></div>    
         </div>
      
     </div>
    </body>
</html>