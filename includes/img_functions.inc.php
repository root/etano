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
//define('BICUBIC_RESAMPLE',1);

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
			ob_start();
			$myimg=@imagecreatefromjpeg($image);
			ob_end_flush();
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
			if (defined('BICUBIC_RESAMPLE')) {
				imagecopyresamplebicubic($mynewimg,$myimg,$x,$y,0,0,$new_size[0],$new_size[1],$orig_size[0],$orig_size[1]);
			} else {
				fastimagecopyresampled($mynewimg,$myimg,$x,$y,0,0,$new_size[0],$new_size[1],$orig_size[0],$orig_size[1]);
			}

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

			if (!empty($config['watermark_image']) && is_file($config['watermark_image'])) {
				$wm_image=@imagecreatefrompng($config['watermark_image']);
				$wm_image_width=imagesx($wm_image);
				$wm_image_height=imagesy($wm_image);
				$wm_image_x=(int)(($size[0]-$new_size[0])/2)+5;
				$wm_image_y=$new_size[1]+(int)(($size[1]-$new_size[1])/2)-$wm_image_height;
				if (defined('BICUBIC_RESAMPLE')) {
					imagecopyresamplebicubic($mynewimg,$wm_image,$wm_image_x,$wm_image_y,0,0,$wm_image_width,$wm_image_height,$wm_image_width,$wm_image_height);
				} else {
					fastimagecopyresampled($mynewimg,$wm_image,$wm_image_x,$wm_image_y,0,0,$wm_image_width,$wm_image_height,$wm_image_width,$wm_image_height);
				}
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
	if (!empty($myimg)) {
		imagedestroy($myimg);
	}
	if (!empty($mynewimg)) {
		imagedestroy($mynewimg);
	}
	return $myreturn;
}


function imagecopyresamplebicubic($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) {
	$scaleX=($src_w-1)/$dst_w;
	$scaleY=($src_h-1)/$dst_h;
	$scaleX2=$scaleX/2.0;
	$scaleY2=$scaleY/2.0;
	$tc=imageistruecolor($src_img);

	for ($y=$src_y;$y<$src_y+$dst_h;$y++) {
		$sY=$y*$scaleY;
		$siY=(int)$sY;
		$siY2=(int)$sY+$scaleY2;
		for ($x=$src_x;$x<$src_x+$dst_w;$x++) {
			$sX =$x*$scaleX;
			$siX =(int)$sX;
			$siX2=(int)$sX+$scaleX2;
			if ($tc) {
				$c1=imagecolorat($src_img, $siX, $siY2);
				$c2=imagecolorat($src_img, $siX, $siY);
				$c3=imagecolorat($src_img, $siX2, $siY2);
				$c4=imagecolorat($src_img, $siX2, $siY);
				$r=(($c1+$c2+$c3+$c4)>>2)&0xFF0000;
				$g=((($c1&0xFF00)+($c2&0xFF00)+($c3&0xFF00)+($c4&0xFF00))>>2)&0xFF00;
				$b=((($c1&0xFF)+($c2&0xFF)+($c3&0xFF)+($c4&0xFF))>>2);
				imagesetpixel($dst_img, $dst_x+$x-$src_x, $dst_y+$y-$src_y, $r+$g+$b);
			}  else {
				$c1=imagecolorsforindex($src_img, imagecolorat($src_img,$siX,$siY2));
				$c2=imagecolorsforindex($src_img, imagecolorat($src_img,$siX,$siY));
				$c3=imagecolorsforindex($src_img, imagecolorat($src_img,$siX2,$siY2));
				$c4=imagecolorsforindex($src_img, imagecolorat($src_img,$siX2,$siY));
				$r=($c1['red']+$c2['red'] +$c3['red'] +$c4['red']  ) << 14;
				$g=($c1['green']+$c2['green']+$c3['green']+$c4['green']) << 6;
				$b=($c1['blue'] +$c2['blue'] +$c3['blue'] +$c4['blue'] ) >> 2;
				imagesetpixel($dst_img, $dst_x+$x-$src_x, $dst_y+$y-$src_y, $r+$g+$b);
			}
		}
	}
}


function fastimagecopyresampled(&$dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h, $quality = 3) {
	// Plug-and-Play fastimagecopyresampled function replaces much slower imagecopyresampled.
	// Just include this function and change all "imagecopyresampled" references to "fastimagecopyresampled".
	// Typically from 30 to 60 times faster when reducing high resolution images down to thumbnail size using the default quality setting.
	// Author: Tim Eckel - Date: 09/07/07 - Version: 1.1 - Project: FreeRingers.net - Freely distributable - These comments must remain.
	//
	// Optional "quality" parameter (defaults is 3). Fractional values are allowed, for example 1.5. Must be greater than zero.
	// Between 0 and 1 = Fast, but mosaic results, closer to 0 increases the mosaic effect.
	// 1 = Up to 350 times faster. Poor results, looks very similar to imagecopyresized.
	// 2 = Up to 95 times faster.  Images appear a little sharp, some prefer this over a quality of 3.
	// 3 = Up to 60 times faster.  Will give high quality smooth results very close to imagecopyresampled, just faster.
	// 4 = Up to 25 times faster.  Almost identical to imagecopyresampled for most images.
	// 5 = No speedup. Just uses imagecopyresampled, no advantage over imagecopyresampled.
	if (empty($src_image) || empty($dst_image) || $quality <= 0) {
		return false;
	}
	if ($quality<5 && (($dst_w*$quality)<$src_w || ($dst_h*$quality)<$src_h)) {
		$temp=imagecreatetruecolor($dst_w*$quality+1,$dst_h*$quality+1);
		imagecopyresized($temp, $src_image, 0, 0, $src_x, $src_y, $dst_w * $quality + 1, $dst_h * $quality + 1, $src_w, $src_h);
		imagecopyresampled($dst_image, $temp, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $dst_w * $quality, $dst_h * $quality);
		imagedestroy($temp);
	} else {
		imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
	}
	return true;
}
