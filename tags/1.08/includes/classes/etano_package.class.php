<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/classes/etano_package.class.php
$Revision: 312 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
*******************************************************************************/

require_once dirname(__FILE__).'/lib.xml.class.php';
require_once dirname(__FILE__).'/fileop.class.php';

class etano_package {
	var $module_code=null;
	var $module_name=null;
	var $version=null;
	var $module_type=null;
	var $is_helper=false;
	var $install=array();
	var $error=true;
	var $error_text='';
	var $package_path='';
	var $manual_actions=array();
	var $ui='';	// user input content. It is set to some html content if a user input page must be displayed.

	function etano_package($manifest_file='') {
		if (!empty($manifest_file)) {
			$this->set_file($manifest_file);
		}
	}


	function set_file($manifest_file,$skip_input=-1) {
		$this->package_path=dirname($manifest_file);
		$this->_set_content(file_get_contents($manifest_file),$skip_input);
	}


	function _set_content($manifest_content,$skip_input=-1) {
		$this->ui='';
		$manifest=new XML_dsb();
		$manifest->parseXML($manifest_content);
		$item=$manifest->firstChild;
		if ($item->nodeName=='package') {
			$attrs=$item->attributes;
			$this->module_code=$attrs['id'];
			$this->module_name=$attrs['name'];
			$this->module_type=$attrs['type'];
			$this->version=$attrs['version'];
			$install=$item->firstChild;
			$install_counter=0;
			while ($install) {
				if ($install->nodeName=='install') {
					$attrs=$install->attributes;
					if (isset($attrs['type']) && $attrs['type']=='helper') {
						$this->is_helper=true;
					}
					$setting=$install->firstChild;
					while ($setting) {
						if ($setting->nodeName=='requires') {
							$attrs=$setting->attributes;
							if (!isset($this->install[$install_counter]['requires'])) {
								$this->install[$install_counter]['requires']=array();
							}
							$i=count($this->install[$install_counter]['requires']);
							$this->install[$install_counter]['requires'][$i]['id']=$attrs['id'];
							if (!empty($attrs['version'])) {
								$this->install[$install_counter]['requires'][$i]['version']=$attrs['version'];
							}
							if (isset($attrs['min-version'])) {
								$this->install[$install_counter]['requires'][$i]['min-version']=$attrs['min-version'];
							}
							if (isset($attrs['max-version'])) {
								$this->install[$install_counter]['requires'][$i]['max-version']=$attrs['max-version'];
							}
							if (isset($attrs['change-version'])) {
								$this->install[$install_counter]['requires'][$i]['change-version']=$attrs['change-version'];
							}
						} elseif ($setting->nodeName=='blockedby') {
							$attrs=$setting->attributes;
							if (!isset($this->install[$install_counter]['blockedby'])) {
								$this->install[$install_counter]['blockedby']=array();
							}
							$i=count($this->install[$install_counter]['blockedby']);
							$this->install[$install_counter]['blockedby'][$i]['id']=$attrs['id'];
							if (!empty($attrs['version'])) {
								$this->install[$install_counter]['blockedby'][$i]['version']=$attrs['version'];
							}
							if (isset($attrs['min-version'])) {
								$this->install[$install_counter]['blockedby'][$i]['min-version']=$attrs['min-version'];
							}
							if (isset($attrs['max-version'])) {
								$this->install[$install_counter]['blockedby'][$i]['max-version']=$attrs['max-version'];
							}
						} elseif ($setting->nodeName=='modfile') {
							$this->install[$install_counter]['file']=$setting->firstChild->nodeValue;
						} elseif ($setting->nodeName=='text') {
							$this->install[$install_counter]['text']=$setting->firstChild->nodeValue;
						} elseif ($setting->nodeName=='input' && $skip_input<$install_counter) {
							$this->install[$install_counter]['input']=$setting->firstChild->nodeValue;
						}
						$setting=$setting->nextSibling;	// go to the next install setting
					}
					++$install_counter;
				}
				$install=$install->nextSibling;	// go to the next install instruction
			}
		}
		$this->error=false;
	}


	function dry_run($install_index) {
		$files_to_change=array();	// keeps the files that will be changed to be listed before install
		$modfile=$this->package_path.'/'.$this->install[$install_index]['file'];
		if (is_file($modfile)) {
			$mod_content=file_get_contents($modfile);
			$mydoc=new XML_dsb();
			$mydoc->parseXML($mod_content);
			$mod_command=$mydoc->firstChild->firstChild;
			while ($mod_command) {
				if ($mod_command->nodeName=='php') {
					$mod_command->firstChild->nodeValue=str_replace(array('{package_path}','{basepath}'),array($this->package_path,_BASEPATH_),$mod_command->firstChild->nodeValue);
					if (!is_file($mod_command->firstChild->nodeValue)) {
						$this->error=true;
						$masize=count($this->manual_actions);
						$this->manual_actions[$masize]['type']='php';
						$this->manual_actions[$masize]['from']='';
						$this->manual_actions[$masize]['to']='';
						$this->manual_actions[$masize]['error']=sprintf('Couldn\'t find %1$s php file required by %2$s',$mod_command->firstChild->nodeValue,$modfile);
						break;
					}
				} elseif ($mod_command->nodeName=='copy') {
					$attrs=$mod_command->attributes;
					$attrs['from']=str_replace(array('{package_path}','{basepath}'),array($this->package_path,_BASEPATH_),$attrs['from']);
					if (!is_file($attrs['from']) && !is_dir($attrs['from'])) {
						$this->error=true;
						$masize=count($this->manual_actions);
						$this->manual_actions[$masize]['type']='copy';
						$this->manual_actions[$masize]['from']='';
						$this->manual_actions[$masize]['to']='';
						$this->manual_actions[$masize]['error']=sprintf('Couldn\'t find %1$s file required by %2$s',$attrs['from'],$modfile);
						break;
					} else {
						$files_to_change[]=str_replace(array('{package_path}','{basepath}'),array($this->package_path,_BASEPATH_),$attrs['to']);
					}
				} elseif ($mod_command->nodeName=='delete') {
				} elseif ($mod_command->nodeName=='mkdir') {
				} elseif ($mod_command->nodeName=='extract') {
					$attrs=$mod_command->attributes;
					$attrs['archive']=str_replace(array('{package_path}','{basepath}'),array($this->package_path,_BASEPATH_),$attrs['archive']);
					$attrs['to']=str_replace(array('{package_path}','{basepath}'),array($this->package_path,_BASEPATH_),$attrs['to']);
					if (!isset($attrs['type']) || $attrs['type']!='zip' || substr($attrs['archive'],-3)!='zip') {
						$this->error=true;
						$masize=count($this->manual_actions);
						$this->manual_actions[$masize]['type']='extract';
						$this->manual_actions[$masize]['from']='';
						$this->manual_actions[$masize]['to']='';
						$this->manual_actions[$masize]['error']=sprintf('Unknown archive type: %s',$attrs['archive']);
						break;
					}
					if (!is_file($attrs['archive'])) {
						$this->error=true;
						$masize=count($this->manual_actions);
						$this->manual_actions[$masize]['type']='extract';
						$this->manual_actions[$masize]['from']='';
						$this->manual_actions[$masize]['to']='';
						$this->manual_actions[$masize]['error']=sprintf('Couldn\'t find %1$s file required by %2$s',$attrs['archive'],$modfile);
						break;
					}
					if (!is_dir($attrs['to'])) {
						$this->error=true;
						$masize=count($this->manual_actions);
						$this->manual_actions[$masize]['type']='extract';
						$this->manual_actions[$masize]['from']='';
						$this->manual_actions[$masize]['to']='';
						$this->manual_actions[$masize]['error']=sprintf('Destination directory for archive extraction does not exist: %s',$attrs['to']);
						break;
					}
				} elseif ($mod_command->nodeName=='diff') {
					if (!is_file($this->package_path.'/'.$mod_command->firstChild->nodeValue)) {
						$this->error=true;
						$masize=count($this->manual_actions);
						$this->manual_actions[$masize]['type']='diff';
						$this->manual_actions[$masize]['from']='';
						$this->manual_actions[$masize]['to']='';
						$this->manual_actions[$masize]['error']=sprintf('Couldn\'t find %1$s diff file required by %2$s',$mod_command->firstChild->nodeValue,$modfile);
						break;
					}
					if (($diff_files=$this->_do_diff($this->package_path.'/'.$mod_command->firstChild->nodeValue,false,true))===false) {
						$this->error=true;
						$masize=count($this->manual_actions);
						$this->manual_actions[$masize]['type']='diff';
						$this->manual_actions[$masize]['from']=$this->package_path.'/'.$mod_command->firstChild->nodeValue;
						$this->manual_actions[$masize]['to']='';
						$this->manual_actions[$masize]['error']=$this->error_text;
						break;
					} else {
						$files_to_change=array_merge($files_to_change,$diff_files);
					}
				} elseif ($mod_command->nodeName=='sql') {
					if (isset($mod_command->attributes['type']) && $mod_command->attributes['type']=='file') {
						if (!is_file($this->package_path.'/'.$mod_command->firstChild->nodeValue)) {
							$this->error=true;
							$masize=count($this->manual_actions);
							$this->manual_actions[$masize]['type']='sql';
							$this->manual_actions[$masize]['from']='';
							$this->manual_actions[$masize]['to']='';
							$this->manual_actions[$masize]['error']=sprintf('Couldn\'t find %1$s sql file required by %2$s',$mod_command->firstChild->nodeValue,$modfile);
							break;
						}
					}
				}
				$mod_command=$mod_command->nextSibling;
			}
		} else {
			$this->error=true;
			$masize=count($this->manual_actions);
			$this->manual_actions[$masize]['type']='mod';
			$this->manual_actions[$masize]['from']='';
			$this->manual_actions[$masize]['to']='';
			$this->manual_actions[$masize]['error']=sprintf('Couldn\'t find %s mod file',$modfile);
		}
		if (!$this->error) {
			return $files_to_change;
		} else {
			return false;
		}
	}


	function install($install_index,$skip_input=-1) {
		$this->ui='';
		if (isset($this->install[$install_index]['input']) && $skip_input!=$install_index) {
			$this->ui=require_once $this->package_path.'/'.$this->install[$install_index]['input'];
			$this->error=false;
		} else {
			$modfile=$this->package_path.'/'.$this->install[$install_index]['file'];
			$mod_content=file_get_contents($modfile);
			$fileop=new fileop();
			$mydoc=new XML_dsb();
			$mydoc->parseXML($mod_content);
			$mod_command=$mydoc->firstChild->firstChild;
			while ($mod_command) {
				if ($mod_command->nodeName=='php') {
					{	// artificially create a block
						// inside the included file we're still in this class!!
						// this php file can generate errors of type critical which halt the execution of installer
						$mod_command->firstChild->nodeValue=str_replace(array('{package_path}','{basepath}'),array($this->package_path,_BASEPATH_),$mod_command->firstChild->nodeValue);
						require_once $mod_command->firstChild->nodeValue;
					}	// end block
				} elseif ($mod_command->nodeName=='diff') {
					if (isset($mod_command->attributes['force_revision'])) {
						$force_revision=true;
					} else {
						$force_revision=false;
					}
					if (!$this->_do_diff($this->package_path.'/'.$mod_command->firstChild->nodeValue,$force_revision)) {
						$this->error=true;
						$masize=count($this->manual_actions);
						$this->manual_actions[$masize]['type']='diff';
						$this->manual_actions[$masize]['from']=$this->package_path.'/'.$mod_command->firstChild->nodeValue;
						$this->manual_actions[$masize]['to']='';
						$this->manual_actions[$masize]['error']=$this->error_text;
					}
				} elseif ($mod_command->nodeName=='copy') {
					$attrs=$mod_command->attributes;
					$attrs['from']=str_replace(array('{package_path}','{basepath}'),array($this->package_path,_BASEPATH_),$attrs['from']);
					$attrs['to']=str_replace(array('{package_path}','{basepath}'),array($this->package_path,_BASEPATH_),$attrs['to']);
					if (isset($attrs['type']) && $attrs['type']=='skin') {
						foreach ($GLOBALS['skins'] as $mc=>$mdir) {
							$temp=str_replace('{skin}',$mdir,$attrs['from']);
							$temp1=str_replace('{skin}',$mdir,$attrs['to']);
							if (!$fileop->copy($temp,$temp1)) {
								$this->error=true;
								$masize=count($this->manual_actions);
								$this->manual_actions[$masize]['type']='copy';
								$this->manual_actions[$masize]['from']=$temp;
								$this->manual_actions[$masize]['to']=$temp1;
								$this->manual_actions[$masize]['error']=sprintf('Unable to copy file %1$s to %2$s',$temp,$temp1);
							}
						}
					} else {
						if (!$fileop->copy($attrs['from'],$attrs['to'])) {
							$this->error=true;
							$masize=count($this->manual_actions);
							$this->manual_actions[$masize]['type']='copy';
							$this->manual_actions[$masize]['from']=$attrs['from'];
							$this->manual_actions[$masize]['to']=$attrs['to'];
							$this->manual_actions[$masize]['error']=sprintf('Unable to copy file %1$s to %2$s',$attrs['from'],$attrs['to']);
						}
					}
				} elseif ($mod_command->nodeName=='delete') {
					$attrs=$mod_command->attributes;
					$attrs['file']=str_replace(array('{package_path}','{basepath}'),array($this->package_path,_BASEPATH_),$attrs['file']);
					if (isset($attrs['type']) && $attrs['type']=='skin') {
						foreach ($GLOBALS['skins'] as $mc=>$mdir) {
							$temp=str_replace('{skin}',$mdir,$attrs['file']);
							if (!$fileop->delete($temp)) {
								$this->error=true;
								$masize=count($this->manual_actions);
								$this->manual_actions[$masize]['type']='delete';
								$this->manual_actions[$masize]['from']=$temp;
								$this->manual_actions[$masize]['to']='';
								$this->manual_actions[$masize]['error']='Unable to automatically delete file.';
							}
						}
					} else {
						if (!$fileop->delete($attrs['file'])) {
							$this->error=true;
							$masize=count($this->manual_actions);
							$this->manual_actions[$masize]['type']='delete';
							$this->manual_actions[$masize]['from']=$attrs['file'];
							$this->manual_actions[$masize]['to']='';
							$this->manual_actions[$masize]['error']="Unable to automatically delete file.";
						}
					}
				} elseif ($mod_command->nodeName=='extract') {
					$attrs=$mod_command->attributes;
					$attrs['archive']=str_replace(array('{package_path}','{basepath}'),array($this->package_path,_BASEPATH_),$attrs['archive']);
					$attrs['to']=str_replace(array('{package_path}','{basepath}'),array($this->package_path,_BASEPATH_),$attrs['to']);
					if (isset($attrs['type']) && $attrs['type']=='zip') {
						if (!$fileop->extract_zip($attrs['archive'],$attrs['to'])) {
							$this->error=true;
							$masize=count($this->manual_actions);
							$this->manual_actions[$masize]['type']='extract';
							$this->manual_actions[$masize]['from']=$attrs['archive'];
							$this->manual_actions[$masize]['to']=$attrs['to'];
							$this->manual_actions[$masize]['error']="Unable to extract archive.";
						}
					}
				} elseif ($mod_command->nodeName=='mkdir') {
					$attrs=$mod_command->attributes;
					$attrs['path']=str_replace(array('{package_path}','{basepath}'),array($this->package_path,_BASEPATH_),$attrs['path']);
					if (isset($attrs['type']) && $attrs['type']=='skin') {
						for ($i=0;isset($GLOBALS['skins'][$i]);++$i) {
							$temp1=str_replace('{skin}',$GLOBALS['skins'][$i],$attrs['path']);
							$path='';
							$temp=explode('/',$temp1);
							for ($i=0;isset($temp[$i]);++$i) {
								if (!empty($temp[$i])) {
									$path.='/'.$temp[$i];
									if (!is_dir($path) && !$fileop->mkdir($path)) {
										$this->error=true;
										$masize=count($this->manual_actions);
										$this->manual_actions[$masize]['type']='mkdir';
										$this->manual_actions[$masize]['from']=$temp1;
										$this->manual_actions[$masize]['to']='';
										$this->manual_actions[$masize]['error']='Unable to automatically create directory.';
										break;
									}
								}
							}
						}
					} else {
						$path='';
						$temp=explode('/',$attrs['path']);
						for ($i=0;isset($temp[$i]);++$i) {
							if (!empty($temp[$i])) {
								$path.='/'.$temp[$i];
								if (!is_dir($path) && !$fileop->mkdir($path)) {
									$this->error=true;
									$masize=count($this->manual_actions);
									$this->manual_actions[$masize]['type']='mkdir';
									$this->manual_actions[$masize]['from']=$attrs['path'];
									$this->manual_actions[$masize]['to']='';
									$this->manual_actions[$masize]['error']='Unable to automatically create directory.';
									break;
								}
							}
						}
					}
				} elseif ($mod_command->nodeName=='sql') {
					$attrs=$mod_command->attributes;
					if (!isset($attrs['type'])) {
						$attrs['type']='inline';
					}
					if ($attrs['type']=='inline') {
						$query=$mod_command->firstChild->nodeValue;
						$query=trim($query," \n\t;\r\0");
						if (!@mysql_query($query)) {
							$this->error=true;
							$masize=count($this->manual_actions);
							$this->manual_actions[$masize]['type']='sql';
							$this->manual_actions[$masize]['from']=$mod_command->firstChild->nodeValue;
							$this->manual_actions[$masize]['to']='';
							$this->manual_actions[$masize]['error']=mysql_error();
						}
					} else {
						if (!$this->db_insert_file($this->package_path.'/'.$mod_command->firstChild->nodeValue)) {
							$this->error=true;
							$masize=count($this->manual_actions);
							$this->manual_actions[$masize]['type']='sqlfile';
							$this->manual_actions[$masize]['from']=$this->package_path.'/'.$mod_command->firstChild->nodeValue;
							$this->manual_actions[$masize]['to']='';
							$this->manual_actions[$masize]['error']=mysql_error();
						}
					}
				}
				$mod_command=$mod_command->nextSibling;
			}
			if (!$this->error) {
				$this->post_install($install_index);
			}
		}
		return !$this->error;
	}


	function _do_diff($diff_file,$force_revision=false,$test_only=false) {
		$files_to_change=array();	// keeps the files that are/will be changed in the diff
		$fileop=new fileop();
		$diff_array=file($diff_file);
		$cur_file='';
		$file_content=array();
		$src_size=-1;
		$src_start=0;
		$dst_size=-1;
		$dest_start=0;
		$new_revision=0;
		$this->error=false;
		$first_chunk=true;
		for ($i=0;isset($diff_array[$i]);++$i) {
			if (substr($diff_array[$i],0,7)=='Index: ') {	// a new file
				if (!$first_chunk && !$test_only) {
					if (empty($file_content)) {
						$fileop->delete($cur_file);
					} else {
						$file_content=join('',$file_content);
						if ($force_revision) {
							$file_content=preg_replace('/\$Revision: \d+ \$/','$Revision: '.$new_revision.' $',$file_content);
						}
						$fileop->file_put_contents($cur_file,$file_content);
					}
				}
				$cur_file=_BASEPATH_.'/'.trim(substr($diff_array[$i],7));
				if (is_file($cur_file)) {
					$file_content=file($cur_file);
				} else {
					$file_content=array();
				}
				$files_to_change[]=$cur_file;
				$last_change_on_line=-1;
			} elseif (substr($diff_array[$i],0,3)=='===') {
			} elseif (substr($diff_array[$i],0,3)=='---') {
			} elseif (substr($diff_array[$i],0,3)=='+++') {
				if (preg_match('/\(revision (\d+)\)/',$diff_array[$i],$m)) {
					$new_revision=$m[1];
				}
			} elseif (substr($diff_array[$i],0,2)=='@@') {
				$m=array();
				if (preg_match('/@@ -(\\d+)(,(\\d+))?\\s+\\+(\\d+)(,(\\d+))?\\s+@@/',$diff_array[$i],$m)) {
					$src_start=(int)$m[1]-1;	// -1 because our arrays are 0 based
					$dest_start=(int)$m[4]-1;	// -1 because our arrays are 0 based
					if ($m[3]==='') {
						$src_size=1;
					} else {
						$src_size=(int)$m[3];
					}
					if ($m[6]==='') {
						$dst_size=1;
					} else {
						$dst_size=(int)$m[6];
					}
				} else {
					$this->error=true;
					$this->error_text=sprintf('Invalid diff file: %s. Line %s in diff file',$diff_file,$i);
					break;
				}
			} elseif ($diff_array[$i]{0}==' ' || $diff_array[$i]{0}=='-' || $diff_array[$i]{0}=='+' || $diff_array[$i]{0}=='\\') {
				$source=array();
				$dest=array();
				while ($src_size>0 || $dst_size>0) {
					if (isset($diff_array[$i])) {	// make sure we haven't reached the end of the diff array
						$type=$diff_array[$i]{0};
						$diff_line=substr($diff_array[$i],1);
					} else {
						$this->error=true;
						$this->error_text=sprintf('Invalid diff file: %s. Unexpected end of file',$diff_file);
						break 2;
					}
					if ($type==' ') {
						$source[]=$diff_line;
						$dest[]=$diff_line;
						--$src_size;
						--$dst_size;
					} elseif ($type=='-') {
						$source[]=$diff_line;
						--$src_size;
					} elseif ($type=='+') {
						$dest[]=$diff_line;
						--$dst_size;
					} else {
						$this->error=true;
						$this->error_text=sprintf('Invalid diff file: %s. Line %s in diff file. Unknown diff marker.',$diff_file,$i);
						break 2;
					}
					++$i;
				}
				--$i;	// the outer for() would increment it again and we don't want this.
				if (!empty($src_size) || !empty($dst_size) || (empty($source) && empty($dest))) {
					$this->error=true;
					$this->error_text=sprintf('Invalid diff file: %s. Unexpected end of block at line %s',$diff_file,$i);
					break;
				}
				if (!empty($source)) {
					// where could our block be? We don't want to rely on the $dest_start read from the diff file
					$possible_locations=array_keys($file_content,$source[0]);
					for ($j=1;isset($source[$j]);++$j) {
						for ($k=0;isset($possible_locations[$k]);++$k) {
							if ($possible_locations[$k]<=$last_change_on_line || !isset($file_content[$possible_locations[$k]+$j]) || $source[$j]!=$file_content[$possible_locations[$k]+$j]) {
								unset($possible_locations[$k]);
							}
						}
					}
					if (empty($possible_locations)) {
						$this->error=true;
						$this->error_text=sprintf('Cannot apply patch because the source file (%s) is changed.',$cur_file);
						break;
					} elseif (count($possible_locations)>1) {
						if (!in_array($dest_start,$possible_locations)) {
							$this->error=true;
							$this->error_text=sprintf('Cannot apply patch because the source file (%s) is changed',$cur_file);
							break;
						}
					} elseif (count($possible_locations)==1) {
						reset($possible_locations);
						$dest_start=current($possible_locations);
					}
				}

				// if we are here then there was no error and we can apply the diff!!!
				array_splice($file_content,$dest_start,count($source),$dest);
				$last_change_on_line=$dest_start+count($source);
				$first_chunk=false;
			}
		}
		if (!$this->error && !$first_chunk && !$test_only) {
			if (empty($file_content)) {
				$fileop->delete($cur_file);
			} else {
				$file_content=join('',$file_content);
				if ($force_revision) {
					$file_content=preg_replace('/\$Revision: \d+ \$/','$Revision: '.$new_revision.' $',$file_content);
				}
				$fileop->file_put_contents($cur_file,$file_content);
			}
		}
		if (!$this->error) {
			return $files_to_change;
		} else {
			return false;
		}
	}	// _do_diff()


	// we don't want to consume too much memory with huge sql files. This function is fast and has very low memory requirements
	// the only requirement is that we are already connected to the db before this function is run.
	function db_insert_file($sqlfile) {
		$myreturn=false;
		$fp=fopen($sqlfile,'rb');
		$query='';
		while (!feof($fp)) {
			$line=trim(fgets($fp));
			if (empty($line) || substr($line,0,2)=='--') {
				continue;
			} elseif (substr($line,-1)!=';') {
				$query.=$line;
			} elseif (substr($line,-1)==';') {
				$query.=substr($line,0,-1);
				$myreturn=@mysql_query($query);
				if (!$myreturn) {
					$masize=count($this->manual_actions);
					$this->manual_actions[$masize]['type']='sql';
					$this->manual_actions[$masize]['from']=$query;
					$this->manual_actions[$masize]['to']='';
					$this->manual_actions[$masize]['error']=mysql_error();
					break;
				}
				$query='';
			}
		}
		fclose($fp);
		return $myreturn;
	}


	function post_install($install_index) {
		global $dbtable_prefix;
		if (isset($_SESSION[_LICENSE_KEY_]['admin']['post_install'][$this->module_code][$install_index]) && is_file($this->package_path.'/'.$_SESSION[_LICENSE_KEY_]['admin']['post_install'][$this->module_code][$install_index])) {
			require_once $this->package_path.'/'.$_SESSION[_LICENSE_KEY_]['admin']['post_install'][$this->module_code][$install_index];
			unset($_SESSION[_LICENSE_KEY_]['admin']['post_install'][$this->module_code][$install_index]);
		}
		// update the version of all required packages with a change-version attribute
		for ($i=0;isset($this->install[$install_index]['requires'][$i]);++$i) {
			if (isset($this->install[$install_index]['requires'][$i]['change-version'])) {
				$query="UPDATE `{$dbtable_prefix}modules` SET `version`='".$this->install[$install_index]['requires'][$i]['change-version']."' WHERE `module_code`='".$this->install[$install_index]['requires'][$i]['id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}
		}
	}


	// all installable mods are installed successfully at this point so we should add ourselves to the list of installed modules
	function finish() {
		global $dbtable_prefix;
		$query="SELECT max(`sort`)+1 FROM `{$dbtable_prefix}modules`";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$sort=mysql_result($res,0,0);
		$query="INSERT IGNORE INTO `{$dbtable_prefix}modules` SET `module_code`='".$this->module_code."',`module_name`='".$this->module_name."',`module_type`='".$this->module_type."',`version`='".$this->version."',`sort`='$sort'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (!mysql_affected_rows()) {
// if the insert failed then this is was just an update and the new version should have been set with change-version in one
// of the requires
		}
		$fileop=new fileop();
		$fileop->delete($this->package_path);
	}
}	// class{}
