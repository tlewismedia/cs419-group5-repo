<?php
/**
* Container class for dateTime intervals
*/
class Span {

public $start = null;
public $end = null;

   public function __construct($start, $end) {
		$this->start = $start;
		$this->end = $end; 
    }
	
	
	/*****************************************************************************
	* print the spans
	* parameters:  list of spans
	*/
	public function printSpans($spans){
		echo "<br>";
		foreach ($spans as $span) {
			echo "[ ".$span->start->format('Y-m-d H:i')." to ".$span->end->format('Y-m-d H:i')." ]<br>";
		}
	}

 
  public function isConflict($span1, $span2) {
  	/*****************************************************************************
	* checks for conflicting spans
	* parameters:  span, span
	*/
		if($span1->start <= $span2->start && $span1->end >= $span2->start){
			return true;
		}
		else if(($span2->start <= $span1->start && $span2->end >= $span1->start)){
			return true;
		} else {
			return false;
		}
  }
	


	public function sortSpans(&$spans)
	{
		/*****************************************************************************
		* parameters:  reference to list os spans
		*/
		if (!function_exists('compareSpans')){
			function compareSpans($s1, $s2) {
				if($s1->start == $s2->start){ return 0 ; }
				return ($s1->start < $s2->start) ? -1 : 1;
			}
		}

		usort($spans, 'compareSpans');

		// echo "in sort, post:<br>";
		// var_dump($spans);	
		  	
	}

	
	
	
  
}
