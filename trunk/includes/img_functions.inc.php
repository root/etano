<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/img_functions.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

function save_thumbnail($image,$size,$save_path,$save_name,$config=array()) {
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
			if (!empty($myimg)) {
				$mynewimg=@imagecreatetruecolor($size,$size);
				imagefilledrectangle($mynewimg,0,0,$size,$size,0xFFFFFF);
				$x=(int)(($size-$new_size[0])/2);
				$y=(int)(($size-$new_size[1])/2);
				imagecopyresampled($mynewimg,$myimg,$x,$y,0,0,$new_size[0],$new_size[1],$orig_size[0],$orig_size[1]);
			}
		} else {					// just white padding here. picture is smaller than the needed size
			if (!empty($myimg)) {
//				$size=$orig_size[$relevant_length];
				$new_size=$orig_size;
				$mynewimg=@imagecreatetruecolor($size,$size);
				imagefilledrectangle($mynewimg,0,0,$size,$size,0xFFFFFF);
				$x=(int)(($size-$orig_size[0])/2);
				$y=(int)(($size-$orig_size[1])/2);
				imagecopy($mynewimg,$myimg,$x,$y,0,0,$orig_size[0],$orig_size[1]);
			}
		}

		if (!empty($config['watermark_text'])) {
			$config['watermark_text_color']=str_pad($config['watermark_text_color'],6,'0',STR_PAD_RIGHT);
			$text_color=imagecolorallocate($mynewimg,hexdec(substr($config['watermark_text_color'],0,2)),hexdec(substr($config['watermark_text_color'],2,2)),hexdec(substr($config['watermark_text_color'],4,2)));
			$text_color2=imagecolorallocate($mynewimg,255-hexdec(substr($config['watermark_text_color'],0,2)),255-hexdec(substr($config['watermark_text_color'],2,2)),255-hexdec(substr($config['watermark_text_color'],4,2)));
			$font_size=15;
			do {
				--$font_size;
				$text_box=imagettfbbox($font_size,0,_BASEPATH_.'/includes/fonts/verdana.ttf',$config['watermark_text']);
				$textlen=$text_box[2]-$text_box[0]+5;
			} while ($textlen>$size);
			$watermark_x=(int)(($size-$new_size[0])/2)+5;
			$watermark_y=$new_size[1]+(int)(($size-$new_size[1])/2)-20;
			//shadow first
			ImageTTFText($mynewimg,$font_size,0,$watermark_x,$watermark_y,$text_color2,_BASEPATH_.'/includes/fonts/verdana.ttf',$config['watermark_text']);
			//text second
			ImageTTFText($mynewimg,$font_size,0,$watermark_x+1,$watermark_y+1,$text_color,_BASEPATH_.'/includes/fonts/verdana.ttf',$config['watermark_text']);
		}

		if (!empty($config['round_corners'])) {
			$skin=get_my_skin();
			imagealphablending($mynewimg,true);
			// put the corners
			$corner=@imagecreatefrompng(_BASEPATH_.'/skins_site/'.$skin.'/images/corner_tl.png');
			imagecopy($mynewimg,$corner,0,0,0,0,7,7);
			$corner=@imagecreatefrompng(_BASEPATH_.'/skins_site/'.$skin.'/images/corner_tr.png');
			imagecopy($mynewimg,$corner,$size-7,0,0,0,7,7);
			$corner=@imagecreatefrompng(_BASEPATH_.'/skins_site/'.$skin.'/images/corner_bl.png');
			imagecopy($mynewimg,$corner,0,$size-7,0,0,7,7);
			$corner=@imagecreatefrompng(_BASEPATH_.'/skins_site/'.$skin.'/images/corner_br.png');
			imagecopy($mynewimg,$corner,$size-7,$size-7,0,0,7,7);
			// draw the border lines
			$border_color=imagecolorallocate($mynewimg,0xCC,0xCC,0xCC);
			imageline($mynewimg,7,0,$size-8,0,$border_color);				//tl->tr
			imageline($mynewimg,$size-1,7,$size-1,$size-8,$border_color);	//tr->br
			imageline($mynewimg,7,$size-1,$size-8,$size-1,$border_color);	//bl->br
			imageline($mynewimg,0,7,0,$size-8,$border_color);				//tl->bl
		}

		imagejpeg($mynewimg,$save_path.'/'.$save_name.'.jpg',100);
		$myreturn=true;
	}
	return $myreturn;
}
