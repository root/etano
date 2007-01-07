<?php
/******************************************************************************
File:                       includes/sco_captcha.class.php
Info:   					class for generating captcha images
File version:				1.2006101601
Created by:                 Dan Caragea (http://www.sco.ro - dan@rdsct.ro)
******************************************************************************/

/*
v 1.2006091401
 - Initial release
*/

class sco_captcha {
	var $ttf_folder;
	var $ttf_name=array('arial.ttf','georgia.ttf','times.ttf','verdana.ttf');
	var $chars;
	var $minsize;
	var $maxsize;
	var $maxrotation;
	var $noise;
	var $websafecolors;

	var $lx;				// width of picture
	var $ly;				// height of picture
	var $jpegquality=80;	// image quality
	var $noisefactor=9;		// this will be multiplied with number of chars
	var $nb_noise;			// number of background-noise-characters
	var $ttf_file;			// holds the current selected TrueTypeFont
	var $gd_version;		// holds the Version Number of GD-Library
	var $r;
	var $g;
	var $b;


	function sco_captcha($ttf_folder,$chars=6,$noise=false,$minsize=13,$maxsize=14,$maxrotation=10,$websafecolors=false) {
		$this->ttf_folder=$ttf_folder;
		$this->chars=$chars;
		$this->noise=$noise;
		$this->minsize=$minsize;
		$this->maxsize=$maxsize;
		$this->maxrotation=$maxrotation;
		$this->websafecolors=$websafecolors;
		$this->gd_version=$this->get_gd_version();
		if($this->gd_version==0) {
			trigger_error('There is no GD Library available. The Captcha class cannot be used!',E_USER_ERROR);
		}
		$this->change_ttf();
		$this->nb_noise=$this->noise ? ($this->chars*$this->noisefactor) : 0;
		$this->lx=($this->chars+1)*(int)(($this->maxsize+$this->minsize)/1.5);
		$this->ly=(int)(2.4*$this->maxsize);
	}


	function gen_rnd_string() {
		$alphabet='ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
		$myreturn='';
		mt_srand(make_seed());
		for ($i=0;$i<$this->chars;++$i) {
			$myreturn.=$alphabet{mt_rand(0,33)};
		}
		return $myreturn;
	}


	function get_gd_version() {
		static $gd_version_number=null;
		if($gd_version_number===null) {
			ob_start();
			phpinfo(8);
			$module_info = ob_get_contents();
			ob_end_clean();
			if(preg_match('/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i',$module_info,$matches)) {
				$gd_version_number=$matches[1];
			} else {
				$gd_version_number=0;
			}
		}
		return $gd_version_number;
	}


	function change_ttf() {
		if(is_array($this->ttf_name)) {
			mt_srand((float)microtime()*10000000);
			$key=array_rand($this->ttf_name);
			$this->ttf_file=$this->ttf_folder.'/'.$this->ttf_name[$key];
		} else {
			$this->ttf_file=$this->ttf_folder.'/'.$this->ttf_name;
		}
		return $this->ttf_file;
	}


	function makeWebsafeColors(&$image) {
		//$a = array();
		for($r = 0; $r <= 255; $r += 51) {
			for($g = 0; $g <= 255; $g += 51) {
				for($b = 0; $b <= 255; $b += 51) {
					$color = imagecolorallocate($image, $r, $g, $b);
					//$a[$color] = array('r'=>$r,'g'=>$g,'b'=>$b);
				}
			}
		}
		//return $a;
	}


	function make_captcha($captcha_text) {
		// create Image and set the apropriate function depending on GD-Version & websafecolor-value
		if($this->gd_version >= 2 && !$this->websafecolors) {
			$func1='imagecreatetruecolor';
			$func2='imagecolorallocate';
		} else {
			$func1='imageCreate';
			$func2='imagecolorclosest';
		}
		$image = $func1($this->lx,$this->ly);

		// Set background color
		$this->random_color(224,255);
		$back=@imagecolorallocate($image,$this->r,$this->g,$this->b);
		@ImageFilledRectangle($image,0,0,$this->lx,$this->ly,$back);

		// allocates the 216 websafe color palette to the image
		if($this->gd_version < 2 || $this->websafecolors) $this->makeWebsafeColors($image);

		// fill with noise or grid
		if($this->nb_noise > 0) {
			// random characters in background with random position, angle, color
			for($i=0; $i < $this->nb_noise; ++$i) {
				mt_srand((double)microtime()*1000000);
				$size	= mt_rand((int)($this->minsize / 2.3), (int)($this->maxsize / 1.7));
				mt_srand((double)microtime()*1000000);
				$angle	= mt_rand(0, 360);
				mt_srand((double)microtime()*1000000);
				$x		= mt_rand(0, $this->lx);
				mt_srand((double)microtime()*1000000);
				$y		= mt_rand(0, (int)($this->ly - ($size / 5)));
				$this->random_color(160, 224);
				$color	= $func2($image, $this->r, $this->g, $this->b);
				mt_srand((double)microtime()*1000000);
				$text	= chr(mt_rand(45,250));
				@ImageTTFText($image, $size, $angle, $x, $y, $color, $this->change_ttf(), $text);
			}
		} else {
			// generate grid
			for($i=0; $i < $this->lx; $i += (int)($this->minsize)) {
				$this->random_color(160,224);
				$color=$func2($image, $this->r, $this->g, $this->b);
				@imageline($image,$i,0,$i,$this->ly,$color);
			}
			for($i=0;$i<$this->ly;$i+=(int)($this->minsize)) {
				$this->random_color(160,224);
				$color=$func2($image, $this->r, $this->g, $this->b);
				@imageline($image,0,$i,$this->lx,$i,$color);
			}
		}

		// generate Text
		$x=(int)($this->lx/($this->chars+10));
		$y=(int)($this->maxsize*1.5);
		for($i=0;isset($captcha_text[$i]);++$i) {
			$text=strtoupper(substr($captcha_text,$i,1));
			mt_srand((double)microtime()*1000000);
			$angle=mt_rand(-$this->maxrotation,$this->maxrotation);
//			$angle=0;
			$size=mt_rand($this->minsize,$this->maxsize);
			$this->random_color(0,127);
			$color=$func2($image,$this->r,$this->g,$this->b);
			$this->random_color(0,127);
			$shadow=$func2($image,$this->r+127,$this->g+127,$this->b+127);
			@ImageTTFText($image,$size,$angle,$x+(int)($size / 15), $y, $shadow, $this->change_ttf(), $text);
			@ImageTTFText($image,$size,$angle,$x, $y - (int)($size / 15), $color, $this->ttf_file, $text);
			$x+=(int)($size+($this->minsize/5));
		}
		@imagejpeg($image,'', $this->jpegquality);
		@imagedestroy($image);
	}


	function random_color($min,$max) {
		mt_srand((double)microtime()*1000000);
		$this->r=mt_rand($min,$max);
		$this->g=mt_rand($min,$max);
		$this->b=mt_rand($min,$max);
	}
}
