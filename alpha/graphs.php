<?php
$graph = new Graph();

//

$graph->values = array(2, 3,6);
$graph->keys = array(3000000, 40000, 2000);
$graph->drawGraph();

putenv('GDFONTPATH=' . realpath('.'));

class Graph
{
	const LAST_24_HOURS = 1;
	const CUSTOM = 2;
	const ALL = 3;
	const AVERAGE = 4;

	private $start, $stop;
	private $interval;
	private $isUnique;

	public $values,$keys;
	public $border = true;
	public $transparent = false;
	
	private $columns,$colWidth,$colHeight;
	private $x1,$x2,$y1,$y2;
	private $im;
	private $gType, $unique;
	private $font = 'Arial';
	private $c = array();

	private $title;

	function __construct(){
	
		define(DAY,86400);
		define(HOUR , 3600);
		define(WEEK , 604800);
		define(HEIGHT , 400);
		define(WIDTH , 440);
		define(G_WIDTH , 300);
		define(G_HEIGHT , 200);
		include_once("misc.php");

	}



	function loadColors()
	{
		$this->c['gray']      = imagecolorallocate ($this->im,0xcc,0xcc,0xcc);
		$this->c['gray_lite'] = imagecolorallocate ($this->im,0xee,0xee,0xee);
		$this->c['gray_dark'] = imagecolorallocate ($this->im,0x7f,0x7f,0x7f);
		$this->c['white']     = imagecolorallocate ($this->im,0xff,0xff,0xff);
		$this->c['black']    = imagecolorallocate ($this->im,0x00,0x00,0x00);
	}

	// Fill in the background of the image
	function createPic()
	{
		$this->columns = sizeof($this->keys);
		$this->colWidth = G_WIDTH / ($this->columns - 1) ;
		
		$this->im = imagecreatetruecolor(WIDTH,HEIGHT);
		$this->loadColors();
		imagesetthickness($this->im, 2);
		imageantialias($this->im, true);
		if($this->transparent){
			imagecolortransparent ($this->im , $this->c['white'] );
		}
		if($this->border){
			$ourterBorderColor = $this->c['black'];
		} else {
			$ourterBorderColor = $this->c['white'];
		}

		imagefilledrectangle($this->im,0,0,WIDTH,HEIGHT ,$ourterBorderColor);
		
		imagefilledrectangle($this->im,1,1,WIDTH - 2,HEIGHT - 2,$this->c['white']);
		$this->x1 = 2*(WIDTH-G_WIDTH)/4 + 5;
		$this->x2 = WIDTH - 2*(WIDTH-G_WIDTH)/4 + 5;
		$this->y1 = 60;
		$this->y2 = G_HEIGHT + $this->y1;

		imagefilledrectangle($this->im,$this->x1,$this->y1,$this->x2,$this->y2,$this->c['black']);
		imagefilledrectangle($this->im,$this->x1 + 1,$this->y1 + 1,$this->x2 - 1,$this->y2 - 1,$this->c['white']);
		$this->CenterImageString($this->im, WIDTH, $this->title, 15, 30, $this->c['black']);
		$s = showtime(time());
		imagettftext($this->im, 7, 0, WIDTH - 5*strlen($s) + 2, HEIGHT - 3, $this->c['black'], $this->font, $s);
		imagettftext($this->im, 7, 0,  5, HEIGHT - 3, $this->c['black'], $this->font, "Created by Rasmus Prentow 2010");
	}


	function drawGraph()
	{
		$this->createPic();
		$x = array();	$y = array();

		// The maximum value
		$maxv = max($this->values);

		// The order of division.
		$divOrder = array(1,2,5,4,10,20,50,40,100,200,500,400,1000,2000,5000,4000, 10000,20000,50000,40000,100000,200000,500000,400000);
		//$divOrder = array(1,2,5,4);
		$hpl = 0;
		$i = 0;
		while($hpl == 0){
			foreach($divOrder as $ord){
				// Figure out how the grph should be divided.
				if(($maxv/$ord) > (6 - $i) && ($maxv/$ord) < (16 + $i)){
					$hpl = $ord;
					break;
				}
			}
			$i++;
		}




		// How many lines
		$multi = ceil($maxv/$hpl);
		if($multi == 0)	$multi = 1;

		// DRAW LINES
		for($i=0;$i<$multi + 1;$i++){
			if(($i == $multi) or $i == 0){
				$color = $this->c['black'];
			} else {
				$color = $this->c['gray'];
			}
			// HPLI is the height in pixels.
			// HPL is the height in real numbers.
			$hpli = (G_HEIGHT / 100) * (( ($i*$hpl) / ($multi*$hpl)) *100);
			imagettftext($this->im, 9, 0, $this->x1 - 8*strlen($i*$hpl) - 3, $this->y2 - $hpli + 5, $this->c['black'], $this->font,  $i*$hpl);
			imageline($this->im,$this->x1 + 1,$this->y2 - $hpli,$this->x2,$this->y2 - $hpli,$color);
		}

	//	$this->gType = self::AVERAGE;
		// DRAW DATA and KEYS.

		for($ii=0;$ii<$this->columns;$ii++)
		{
			
			
			if($this->gType == self::AVERAGE){
				$i = $ii%$this->columns;
			} else {
				$i = $ii;
			}
			
			// Calculates the column height	
			$colHeight = (G_HEIGHT / 100) * (( $this->values[$i] / ($multi*$hpl)) *100);


			$x[$i] = $i*($this->colWidth) + $this->x1;
			$y[$i] = G_HEIGHT-$colHeight + $this->y1;

			if($i > 0){
				imageline($this->im,$x[$i - 1],$y[$i - 1],$x[$i],$y[$i],$this->c['black']);
			}

			// Determines when to write the Keys. 
			$div = 1; //
/*
			if($this->gType === self::LAST_24_HOURS){
				$s = $today = date("H:i", $this->keys[$i] + $this->interval);
				$div = 2;
				if($this->interval == 60){
					$s = $today = date("H:i", $this->keys[$i] + $this->interval);
					$div = ceil($this->columns/13);
				}
			}elseif($this->gType === self::AVERAGE){
				$s = $today = date("H:i", $this->keys[$i] + HOUR);
				$div = 2;
			}elseif($this->interval == DAY){
				$s = date("j. F Y", $this->keys[$i]); // . "(". $this->values[$i] . ")"
				$div = ceil($this->columns/16);
			}elseif($this->interval == HOUR){
				$s = showtime($this->keys[$i] + $this->interval); // . "(". $this->values[$i] . ")"
				$div = ceil($this->columns/16);
			}
			 else {
				$s = showtime($this->keys[$i]); // . "(". $this->values[$i] . ")"
				$div = ceil($this->columns/16);
			}
			*/
				//$s = date("j. F Y", $this->keys[$ii]); // . "(". $this->values[$i] . ")"
				//$div = ceil($this->columns/16);
		//	$div = ceil($this->columns/16);
		
		
			if(($ii)%$div == 0){
				
				imagettftext($this->im, 9, 300, $x[$ii] - 6, $this->y2 + 8, $this->c['black'], $this->font, $this->keys[$ii]);
				imageline($this->im,$x[$ii],$this->y2 + 4,$x[$ii],$this->y2 - 4,$this->c['black']);
			} else {
				imageline($this->im,$x[$ii],$this->y2 + 2,$x[$ii],$this->y2 - 2,$this->c['black']);

			}
		}

				$this->sendHeader();


	}

	function sendHeader()
	{
		header("Content-type: image/png");
		imagepng($this->im);
		imagedestroy($this->im);
	}

	function ceiling($value, $precision = 0)
	{
		return ceil($value * pow(10, $precision)) / pow(10, $precision);
	}

	function flooring($value, $precision = 0)
	{
		return floor($value * pow(10, $precision)) / pow(10, $precision);
	}

	function CenterImageString($image, $image_width, $string, $font_size, $y, $color)
	{
		$text_width = imagefontwidth($font_size)*strlen($string);
		$center = ceil($image_width / 2);
		$x = $center - (ceil($text_width/2));
		//ImageTTFString($image, $font_size, $x, $y, $string, $color);
		imagettftext($this->im, $font_size, 0, $x, $y, $this->c['black'], $this->font, $string);
	}
}

new Graph();
?>