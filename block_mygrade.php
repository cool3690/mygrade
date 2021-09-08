<?php
 
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/filelib.php');

class block_mygrade extends block_base {
    public function init() {
        $this->title = get_string('mygrade', 'block_mygrade');
    }
   
   public function get_content() {
	global $CFG, $DB, $OUTPUT,$USER;
		
 
	 $course = $this->page->course;
	// $user=$this->page->user;
 
 
	if ($this->content !== null) {
		return $this->content;
	}

 

	$this->content = new stdClass;//get_string('chart', 'block_mygrade')
	$this->content->text = "測試" ;
	 
	  $this->content->text .= "<li> <a href={$CFG->wwwroot}/blocks/mygrade/view_statistic.php?id={$course->id}&uid={$USER->id}
				              target=_blank>教材觀看統計</a>";
	  $this->content->text .= "<li> <a href={$CFG->wwwroot}/blocks/mygrade/evaluation.php?id={$course->id}&uid={$USER->id}
				               target=_blank> emoji統計 </a>";
							   
	  $this->content->text .= "<li> <a href={$CFG->wwwroot}/blocks/mygrade/view_chart.php?id={$course->id}&uid={$USER->id}
				               target=_blank> 課程觀看次數查詢  </a>";
	  $this->content->text .= "<li> <a href={$CFG->wwwroot}/blocks/mygrade/grade_personal.php?id={$course->id}&uid={$USER->id}
				               target=_blank> 學生個人成績統計</a>";
	  $this->content->text .= "<li> <a href={$CFG->wwwroot}/blocks/mygrade/grade_ave_pridict.php?id={$course->id}&uid={$USER->id}
				               target=_blank> 全班成績平均表</a>";
	  $this->content->text .= "<li> <a href={$CFG->wwwroot}/blocks/mygrade/gradeboard.php?id={$course->id}
				               target=_blank> 班級小考成績</a>";
	$this->content->footer = '';
	return $this->content;
	}
  
 
}