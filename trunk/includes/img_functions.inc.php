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
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

define('PAD_2SIDES',1);
define('PAD_1SIDE',2);
define('PAD_NONE',3);
function save_thumbnail($image,$size,$save_path,$save_name,$config=array()) {
	$myreturn=false;
	$size=array($size,$size);
	if (empty($config['padding_type'])) {
		$config['padding_type']=PAD_1SIDE;
	}
	if (empty($config['quality'])) {
		$config['quality']=90;
	}
	if ($imginfo=getimagesize($image)) {
		$orig_size=array($imginfo[0],$imginfo[1]);
		if ($orig_size[0]/$size[0]<$orig_size[1]/$size[1]) {
			$relevant_length=1;
		} else {
			$relevant_length=0;
		}
		if ($imginfo[2]==IMAGETYPE_GIF && function_exists('imagecreatefromgif')) {			//gif
			$myimg=@imagecreatefromgif($image);
		} elseif ($imginfo[2]==IMAGETYPE_JPEG && function_exists('imagecreatefromjpeg')) {	//jpg
			$myimg=@imagecreatefromjpeg($image);
		} elseif ($imginfo[2]==IMAGETYPE_PNG && function_exists('imagecreatefrompng')) {	//png
			$myimg=@imagecreatefrompng($image);
		}

		if (!empty($myimg)) {
			$new_size=array();
			$mynewimg='';
			if ($orig_size[$relevant_length]>$size[$relevant_length]) {	// scale down
				$new_size[$relevant_length]=$size[$relevant_length];
				$new_size[1-$relevant_length]=(int)($orig_size[1-$relevant_length]*($size[$relevant_length]/$orig_size[$relevant_length]));
				if ($config['padding_type']==PAD_1SIDE || $config['padding_type']==PAD_2SIDES) {
//					$size=$size;	// this is actually just PAD_1SIDE and the photo will be square
				} else {
					$size=$new_size;	// no padding here, photo has original proportions
				}
			} else {	// picture is smaller than the needed size
				$new_size=$orig_size;
				if ($config['padding_type']==PAD_2SIDES) {	//pad in both directions. square and big
//					$size=array($size,$size);
				} elseif ($config['padding_type']==PAD_1SIDE) {	// padding in one direction only. square but smaller
					$size=$orig_size[$relevant_length];
					$size=array($size,$size);
				} else {	// no padding. original proportions
					$size=$orig_size;
				}
			}
			$mynewimg=@imagecreatetruecolor($size[0],$size[1]);
			imagefilledrectangle($mynewimg,0,0,$size[0],$size[1],0xFFFFFF);
			$x=(int)(($size[0]-$new_size[0])/2);
			$y=(int)(($size[1]-$new_size[1])/2);
			imagecopyresampled($mynewimg,$myimg,$x,$y,0,0,$new_size[0],$new_size[1],$orig_size[0],$orig_size[1]);

			if (!empty($config['watermark_text']) && function_exists('imagettftext')) {
				$config['watermark_text_color']=str_pad($config['watermark_text_color'],6,'0',STR_PAD_RIGHT);
				$text_color=imagecolorallocate($mynewimg,hexdec(substr($config['watermark_text_color'],0,2)),hexdec(substr($config['watermark_text_color'],2,2)),hexdec(substr($config['watermark_text_color'],4,2)));
				$text_color2=imagecolorallocate($mynewimg,255-hexdec(substr($config['watermark_text_color'],0,2)),255-hexdec(substr($config['watermark_text_color'],2,2)),255-hexdec(substr($config['watermark_text_color'],4,2)));
				$font_size=15;
				do {
					--$font_size;
					$text_box=imagettfbbox($font_size,0,_BASEPATH_.'/includes/fonts/arial.ttf',$config['watermark_text']);
					$textlen=$text_box[2]-$text_box[0]+5;
				} while ($textlen>$new_size[0]);
				$watermark_x=(int)(($size[0]-$new_size[0])/2)+5;
				$watermark_y=$new_size[1]+(int)(($size[1]-$new_size[1])/2)-20;
				//shadow first
				imagettftext($mynewimg,$font_size,0,$watermark_x,$watermark_y,$text_color2,_BASEPATH_.'/includes/fonts/arial.ttf',$config['watermark_text']);
				//text second
				imagettftext($mynewimg,$font_size,0,$watermark_x+1,$watermark_y+1,$text_color,_BASEPATH_.'/includes/fonts/arial.ttf',$config['watermark_text']);
			}

			if (!empty($config['round_corners'])) {
				$skin=get_my_skin();
				imagealphablending($mynewimg,true);
				// put the corners
				$corner=@imagecreatefrompng(_BASEPATH_.'/skins_site/'.$skin.'/images/corner_tl.png');
				imagecopy($mynewimg,$corner,0,0,0,0,7,7);
				$corner=@imagecreatefrompng(_BASEPATH_.'/skins_site/'.$skin.'/images/corner_tr.png');
				imagecopy($mynewimg,$corner,$size[0]-7,0,0,0,7,7);
				$corner=@imagecreatefrompng(_BASEPATH_.'/skins_site/'.$skin.'/images/corner_bl.png');
				imagecopy($mynewimg,$corner,0,$size[1]-7,0,0,7,7);
				$corner=@imagecreatefrompng(_BASEPATH_.'/skins_site/'.$skin.'/images/corner_br.png');
				imagecopy($mynewimg,$corner,$size[0]-7,$size[1]-7,0,0,7,7);
				// draw the border lines
				$border_color=imagecolorallocate($mynewimg,0xCC,0xCC,0xCC);
				imageline($mynewimg,7,0,$size[0]-8,0,$border_color);				//tl->tr
				imageline($mynewimg,$size[0]-1,7,$size[0]-1,$size[1]-8,$border_color);	//tr->br
				imageline($mynewimg,7,$size[1]-1,$size[0]-8,$size[1]-1,$border_color);	//bl->br
				imageline($mynewimg,0,7,0,$size[1]-8,$border_color);				//tl->bl
			}

			$myreturn=imagejpeg($mynewimg,$save_path.'/'.$save_name.'.jpg',$config['quality']);
		} else {
			$myreturn=false;
		}
	} else {
		$myreturn=false;
	}
	return $myreturn;
}
