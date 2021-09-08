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
    <div class="container"><center><br><br>選擇考試項目:
    <form id="form1" name="form1" method="post" action="gradeboard.php?id=<?=$courseid?>"  class="form-inline">
    <select name="myquiz">
    <?PHP
        $sql="SELECT * FROM mdl_quiz where course='$courseid' order by timemodified ASC";
        $i=0;
		 $statement = $DB->get_records_sql($sql);
     
        //$statement = $db->query($sql);
		foreach($statement as $row){
            $quizarr[$i]=$row->id;
            $quizname[$i]=$row->name;
            $i++;
			?>
            <option value="<?=$row->id?>"><?=$row->name?></option>
            <?php
               }
            ?>
    </select>
    <input type="submit" class="btn btn-primary" value="送出"> 
   
</form>
      <?php 
      $grade_barchart= "['student','rawgrade', { role: 'style' }],";
        if(isset($_POST['myquiz'])){
            $quiz=$_POST['myquiz'];
            //echo $quizarr[0];
            $str="";
            $sum=0;
            $k=0;
            $count=0;
           
            for($i=0;$i<count($quizarr);$i++)
               {    if($quiz==$quizarr[$i] && $i==0){
                        $k=0;
                        break;
                      }
                    if($quiz==$quizarr[$i] && $i!=0){
                        $k=--$i;
                        break;
                    }
               }?>

               <table class="table table-striped table-responsive-xl">
               <tbody>
               <?php
               echo "<tr><th>姓名</th><th>上週 VS 本周考試成績</th><th>與上次成績比較</th></tr>";
             $sql_user="select* from mdl_user_lastaccess where courseid='$courseid'";
             $sta_user =$DB->get_records_sql($sql_user);// $db->query($sql_user);
             foreach($sta_user as $row){

                 $id=$row->userid;
                 $sql="select* from mdl_quiz_grades where quiz in ('$quiz') and userid='$id'";
                $sql_compare="select* from mdl_quiz_grades where quiz in ('$quizarr[$k]') and userid='$id' ";
                
                $statement = $DB->get_records_sql($sql);//$db->query($sql);
                $stat_compare=$DB->get_records_sql($sql_compare);//$db->query($sql_compare);
                $geade1=0;$grade2=0;
                 
                    foreach($statement as $row){
                        $geade1=$row->grade;   
                    } 
                
                //if(mysqli_num_rows($stat_compare)>0) {
                $geade2=0;
                    foreach($stat_compare as $row){
                        $geade2=$row->grade;   
                    } 
               // }
                $sql_username="select* from mdl_user where id='$id'" ;
                $stat_username=$DB->get_records_sql($sql_username);//$db->query($sql_username);
                foreach($stat_username as $row){ 
                    $myname=$row->lastname."".$row->firstname;
                    if( $geade2>$geade1){
                        if($geade2!=0){
                             $ans="退步 ". round(100-($geade1/$geade2*100),2) . "%";
                             if(round(100-($geade1/$geade2*100),2)>=30)
                             {
                                
                                 $str="<h3><font color='red'>".$myname." ".$ans;
                             }
                        }
                        else{//$count--;
                            $ans="成績有空缺，無法比較 "; 
                            break;
                        }
                    }
                    else{
                        if($geade1!=0){
                            $ans="進步 ".round((($geade1/$geade2)-1)*100,2) . "%";
                        }
                        else{//$count--;
                            $ans="成績有空缺，無法比較 "; 
                            break;
                        }
                    }
                    $arr[$count][0]=round($geade1,2);//grade
                    $arr[$count][1]=$myname;
                    $arr[$count][2]=$str;
                    $str="";
                    $count++;
                     $sum+=round($geade1,2);
                   // echo '<tr><td>'."sssss".'</td><td>'."sssss".'</td><td>'."sssss".'</td></tr>';
                    echo '<tr><td>'.$myname.'</td><td>'.round($geade2,2)."    /    ".round($geade1,2).'</td><td>'.$ans.'</td></tr>';
                    if($geade1>=60){$pass++;}
                    else{$fail++;}  
                    //$grade_barchart.="['$myname', round($geade1,2) ,'gold'],";
                     // echo round($geade1,2);
                     
                } 
                $grade_barchart.="['$myname',$geade1 , 'gray'],";
                 
                 
 
            } $grade_barchart=rtrim($grade_barchart,",");
          //  echo  $grade_barchart;
            ?>
            </tr> 
            </tbody>
            </table>
            <script type="text/javascript">
    
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php  echo "[$grade_barchart]"?>);
 
        var options = {
          chart: {
            title: '小考成績',
            subtitle: '班級小考成績 ',
          },
          bars: 'horizontal' // Required for Material Bar Charts.
        };

        var chart = new google.charts.Bar(document.getElementById('chart_div2'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    
	  
    </script>
    
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['grade', 'percent'],
          ['及格', <?=$pass?>],
          ['不及格', <?=$fail?>],
          
        ]);

        var options = {
          title: '及格率',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>
			<div id="chart_div2" style="width: 900px; height: 500px;"></div>
            <div id="donutchart" style="width: 900px; height: 500px;"></div>
         </div>
      <?php 
      $ave=round($sum/$count,2); 
      $deviation=0;
      for($j=0;$j<count($arr);$j++){
        $deviation+=pow($arr[$j][0]-$ave,2);
        if($arr[$j][0]<$ave){
            echo '<center>'. $arr[$j][2]."，且低於本次班級平均</font></h3>";
        }
      }
      $deviation=sqrt($deviation/count($arr));
      echo '<center><h3>本次成績平均:'.round($sum/$count,2)."   <br> <br>標準差: ".round($deviation,2). '</center></h3>';
      echo $str;  
        }
      ?>
 
 

     </div>
    </body>
</html>
