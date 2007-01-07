<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/img_functions.inc.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

function save_thumbnail($image,$size,$save_path,$save_name) {
	$myreturn=false;
	if ($imginfo=getimagesize($image)) {
		$orig_size=array($imginfo[0],$imginfo[1]);
		$relevant_length=0;
		if ($orig_size[0]<$orig_size[1]) {
			$relevant_length=1;
		}
		if ($imginfo[2]==1 && function_exists('imagecreatefromgif')) {			//gif
			$myimg=@imagecreatefromgif($image);
		} elseif ($imginfo[2]==2 && function_exists('imagecreatefromjpeg')) {	//jpg
			$myimg=@imagecreatefromjpeg($image);
		} elseif ($imginfo[2]==3 && function_exists('imagecreatefrompng')) {	//png
			$myimg=@imagecreatefrompng($image);
		}

		$new_size=array();
		$mynewimg='';
		if ($orig_size[$relevant_length]>$size) {	// scale down
			$new_size[$relevant_length]=$size;
			$new_size[1-$relevant_length]=(int)($orig_size[1-$relevant_length]*($size/$orig_size[$relevant_length]));
			if (isset($myimg) && !empty($myimg)) {
				$mynewimg=@imagecreatetruecolor($size,$size);
				imagefilledrectangle($mynewimg,0,0,$size,$size,0xFFFFFF);
				$x=(int)(($size-$new_size[0])/2);
				$y=(int)(($size-$new_size[1])/2);
				imagecopyresampled($mynewimg,$myimg,$x,$y,0,0,$new_size[0],$new_size[1],$orig_size[0],$orig_size[1]);
			}
		} else {									// just white padding here. picture is smaller than the needed size
			if (isset($myimg) && !empty($myimg)) {
				$size=$orig_size[$relevant_length];
				$new_size=$orig_size;
				$mynewimg=@imagecreatetruecolor($size,$size);
				imagefilledrectangle($mynewimg,0,0,$size,$size,0xFFFFFF);
				$x=(int)(($size-$orig_size[0])/2);
				$y=(int)(($size-$orig_size[1])/2);
				imagecopy($mynewimg,$myimg,$x,$y,0,0,$orig_size[0],$orig_size[1]);
			}
		}

		$watermark=get_site_option(array('watermark_text','watermark_text_color'),'core_photo');
		if (!empty($watermark['watermark_text'])) {
			$watermark['watermark_text_color']=str_pad($watermark['watermark_text_color'],6,'0',STR_PAD_RIGHT);
			$text_color=imagecolorallocate($mynewimg,hexdec(substr($watermark['watermark_text_color'],0,2)),hexdec(substr($watermark['watermark_text_color'],2,2)),hexdec(substr($watermark['watermark_text_color'],4,2)));
			$text_color2=imagecolorallocate($mynewimg,255-hexdec(substr($watermark['watermark_text_color'],0,2)),255-hexdec(substr($watermark['watermark_text_color'],2,2)),255-hexdec(substr($watermark['watermark_text_color'],4,2)));
			$font_size=15;
			do {
				--$font_size;
				$text_box=imagettfbbox($font_size,0,_BASEPATH_.'/includes/fonts/verdana.ttf',$watermark['watermark_text']);
				$textlen=$text_box[2]-$text_box[0]+5;
			} while ($textlen>$size);
			$watermark_x=(int)(($size-$new_size[0])/2)+5;
			$watermark_y=$new_size[1]+(int)(($size-$new_size[1])/2)-20;
			//shadow first
			ImageTTFText($mynewimg,$font_size,0,$watermark_x,$watermark_y,$text_color2,_BASEPATH_.'/includes/fonts/verdana.ttf',$watermark['watermark_text']);
			//text second
			ImageTTFText($mynewimg,$font_size,0,$watermark_x+1,$watermark_y+1,$text_color,_BASEPATH_.'/includes/fonts/verdana.ttf',$watermark['watermark_text']);
		}
		imagejpeg($mynewimg,$save_path.'/'.$save_name.'.jpg',100);
		$myreturn=true;
	}
	return $myreturn;
}
