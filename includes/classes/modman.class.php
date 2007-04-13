<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/classes/modman.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
*******************************************************************************/

class modman {
	var $template_vars=array();
	var $errmessage='';
	var $dblink;
	var $manual_actions=array();
	var $mod_id='';
	var $fileop=null;

	function modman($modid='',$new_templates=array()) {
		$this->mod_id=$modid;
		$this->fileop=fileop::get_instance();
		if (defined('_BASEURL_')) {
			$this->template_vars['{$baseurl}']=_BASEURL_;
			$this->template_vars['{$tplurl}']=_BASEURL_.'/skins_site';
			$this->template_vars['{$imagesurl}']=_PHOTOURL_;
		}
		if (defined('_BASEPATH_')) {
			$this->template_vars['{$basepath}']=_BASEPATH_;
			$this->template_vars['{$tplpath}']=_BASEPATH_.'/skins_site/';
			$this->template_vars['{$imagespath}']=_PHOTOPATH_;
		}
		if (defined('_SITENAME_')) {
			$this->template_vars['{$sitename}']=_SITENAME_;
		}
		if (defined('_INTERNAL_VERSION_')) {
			$this->template_vars['{$internal_version}']=_INTERNAL_VERSION_;
		}
		if (!empty($new_templates)) {
			while (list($k,$v)=each($new_templates)) {
				$this->template_vars[$k]=$v;
			}
		}
	}


	function update($file='',$content='',$reverse=false,$new_version='') {
		require_once 'lib.xml.class.php';
		if (!empty($file)) {
			$modfile=$this->fileop->file_get_contents($file);
		} elseif (!empty($content)) {
			$modfile=$content;
		} else {
			trigger_error('No mod file available',E_USER_ERROR);
		}
		while (list($k,$v)=each($this->template_vars)) {
			$modfile=str_replace($k,$v,$modfile);
		}
		$mydoc=new XML_dsb();
		$mydoc->parseXML($modfile);
		$critical_error=false;
		if ($this->pretest($mydoc)) {
			$item=$mydoc->firstChild->firstChild;
			while ($item && !$critical_error) {
				if (strtolower($item->nodeName)=='if') {
					$attrs=$item->attributes;
					if (isset($attrs['cond']) && !empty($attrs['cond']) && function_exists($attrs['cond'])) {
						eval('$doparse='.$ifattrs['cond'].'();');
						if ($doparse) {
							$this->parse_block($item->firstChild);
						}
					}
				} else {
					$this->parse_block($item);
				}
				$item=$item->nextSibling;	// proceed to the next action (modify,sql,copy)
			}
			if (!empty($new_version) && !$critical_error) {
				$myfilename=$this->template_vars['{$basepath}'].'/includes/vars.inc.php';
				$this->fileop->backup_file($myfilename);
				$mydata=$this->fileop->file_get_contents($myfilename);
				$mydata=preg_replace("/define\('_INTERNAL_VERSION_',\d{3,}\);/","define('_INTERNAL_VERSION_',$new_version);",$mydata);
				if (!$this->fileop->file_put_contents($myfilename,$mydata)) {
					$masize=count($this->manual_actions);
					$this->manual_actions[$masize]['type']='copy_content';
					$this->manual_actions[$masize]['from']=$mydata;
					$this->manual_actions[$masize]['to']=$myfilename;
					$this->manual_actions[$masize]['error']="Unable to modify file '$myfilename'. Permission denied";
				}
			}
		}
		return count($this->manual_actions);
	}


	function parse_block(&$item) {
		if (strtolower($item->nodeName)=='modify') {	// pick a file and do some changes
			$attrs=$item->attributes;
			$myfilename=$attrs['file'];
			$do_backup=true;
			if (isset($attrs['backup']) && $attrs['backup']=='false') {
				$do_backup=false;
			}
			$mydata=$this->fileop->file_get_contents($myfilename);
			$action=$item->firstChild;
			$find='';
			$regexp=false;
			while ($action) {
				if (strtolower($action->nodeName)=='find') {
					$find=$action->firstChild->nodeValue;
					$findattrs=$action->attributes;
					$regexp=false;
					if (isset($findattrs['regexp']) && !empty($findattrs['regexp'])) {
						$regexp=true;
					}
				} elseif (strtolower($action->nodeName)=='addbefore' && !empty($find)) {		//depends on <find>
					if ($regexp) {
						$mydata=preg_replace("/$find/",$action->firstChild->nodeValue.'$0',$mydata);
					} else {
						$mydata=str_replace($find,$action->firstChild->nodeValue.$find,$mydata);
					}
				} elseif (strtolower($action->nodeName)=='addafter' && !empty($find)) {			//depends on <find>
					if ($regexp) {
						$mydata=preg_replace("/$find/",'$0'.$action->firstChild->nodeValue,$mydata);
					} else {
						$mydata=str_replace($find,$find.$action->firstChild->nodeValue,$mydata);
					}
				} elseif (strtolower($action->nodeName)=='replace' && !empty($find)) {			//depends on <find>
					if ($regexp) {
						$mydata=preg_replace("/$find/",$action->firstChild->nodeValue,$mydata);
					} else {
						$mydata=str_replace($find,$action->firstChild->nodeValue,$mydata);
					}
				}
				$action=$action->nextSibling;	// go to the next action to perform on the current file
			}
			if ($do_backup) {
				$this->fileop->backup_file($myfilename);
			}
			if (!$this->fileop->file_put_contents($myfilename,$mydata)) {
				$masize=count($this->manual_actions);
				$this->manual_actions[$masize]['type']='copy_content';
				$this->manual_actions[$masize]['from']=$mydata;
				$this->manual_actions[$masize]['to']=$myfilename;
				$this->manual_actions[$masize]['error']="Unable to modify file '$myfilename'. Permission denied";
			}
		} elseif (strtolower($item->nodeName)=='copy') {	// copy a file or dir from one place to another
			$attrs=$item->attributes;
			if (!$this->fileop->copy($attrs['file'],$attrs['destination'])) {
				$masize=count($this->manual_actions);
				$this->manual_actions[$masize]['type']='copy';
				$this->manual_actions[$masize]['from']=$attrs['file'];
				$this->manual_actions[$masize]['to']=$attrs['destination'];
				$this->manual_actions[$masize]['error']="Unable to copy '".$attrs['file']."' to '".$attrs['destination']."'";
			}
		} elseif (strtolower($item->nodeName)=='delete') {	// delete a file
			$attrs=$item->attributes;
			if (!$this->fileop->delete($attrs['file'])) {
				$masize=count($this->manual_actions);
				$this->manual_actions[$masize]['type']='delete';
				$this->manual_actions[$masize]['from']=$attrs['file'];
				$this->manual_actions[$masize]['to']='';
				$this->manual_actions[$masize]['error']="Unable to delete '".$attrs['file']."'. Permission denied.";
			}
		} elseif (strtolower($item->nodeName)=='include-php') {		// execute php scripts
			{														// artificially create a block
			// inside the included file you're still in the class!!
			// this php file can generate errors of type critical which halt the execution of installer
			require_once _BASEPATH_.'/patches/'.$this->mod_id.'/'.$item->firstChild->nodeValue;
			}														// end block
		} elseif (strtolower($item->nodeName)=='sql') {		// sql fun
			$sqlattrs=$item->attributes;
			if ($sqlattrs['type']=='inline') {				// simple queries
				if (!$this->db_query($item->firstChild->nodeValue)) {
					$masize=count($this->manual_actions);
					$this->manual_actions[$masize]['type']='sql';
					$this->manual_actions[$masize]['from']=$item->firstChild->nodeValue;
					$this->manual_actions[$masize]['error']='Unable to run query: '.$this->errmessage;
				}
			} elseif ($sqlattrs['type']=='disk') {			// entire files to be inserted
				if (!$this->db_insert_file($item->firstChild->nodeValue)) {
					$masize=count($this->manual_actions);
					$this->manual_actions[$masize]['type']='sqlfile';
					$this->manual_actions[$masize]['from']=$item->firstChild->nodeValue;
					$this->manual_actions[$masize]['error']='Unable to insert the file '.$item->firstChild->nodeValue.' or parts of it in database: '.$this->errmessage;
				}
			}
		}
	}


	function pretest(&$mydoc) {
		$myreturn=true;
		$item=$mydoc->firstChild->firstChild;
		while ($item) {
			if (strtolower($item->nodeName)=='modify') {	// pick a file and do some changes
				$attrs=$item->attributes;
				$myfilename=$attrs['file'];
				$mydata=$this->fileop->file_get_contents($myfilename);
				$action=$item->firstChild;
				$find='';
				while ($action) {
					if (strtolower($action->nodeName)=='find') {
						$find=$action->firstChild->nodeValue;
						$findattrs=$action->attributes;
						if (isset($findattrs['mandatory']) && $findattrs['mandatory']==true) {
							$regexp=false;
							if (isset($findattrs['regexp']) && !empty($findattrs['regexp'])) {
								$pos=true;
								if (!preg_match("/$find/",$mydata)) {
									$pos=false;
								}
							} else {
								$pos=strpos($mydata,$find);
							}
							if ($pos===false) {
								$myreturn=false;
								$masize=count($this->manual_actions);
								$this->manual_actions[$masize]['type']='critical';
								$this->manual_actions[$masize]['from']='Aborting...';
								$this->manual_actions[$masize]['to']='';
								$this->manual_actions[$masize]['error']="This mod cannot be installed because file <b>$myfilename</b> is changed.";
								if (isset($findattrs['info']) && !empty($findattrs['info'])) {
									$this->manual_actions[$masize]['error'].='<br>Error info: '.$findattrs['info'];
								}
								break 2;
							}
						}
					}
					$action=$action->nextSibling;	// go to the next action to perform on the current file
				}
			} elseif (strtolower($item->nodeName)=='copy') {	// copy a file from one place to another
				$attrs=$item->attributes;
				if (!is_file($attrs['file'])) {
					$myreturn=false;
					$masize=count($this->manual_actions);
					$this->manual_actions[$masize]['type']='critical';
					$this->manual_actions[$masize]['from']='File <b>'.$attrs['file'].'</b> could not be found. Mod not applied. Aborting...';
					$this->manual_actions[$masize]['to']='';
					$this->manual_actions[$masize]['error']='';
					break;
				}
			}
			$item=$item->nextSibling;	// proceed to the next action (modify,sql,copy)
		}
		return $myreturn;
	}


	// we don't want to consume too much memory with huge sql files. This function is fast and has very low memory requirements
	// ----------------------------------------------------DON'T ALLOW COMMENTS IN THE SQL FILE OR THIS FUNCTION WILL NOT WORK!!!!
	function db_insert_file($sqlfile) {
		$myreturn=false;
		if ($this->connect2db()) {
			$fp=fopen($sqlfile,'rb');
			$reminder='';
			while ($buffer=fread($fp,4096)) {
	//			$buffer=$reminder.preg_replace("/^#.*/m","",$buffer); // remove comments and new lines in the file
				$buffer=$reminder.$buffer;
				$buffer=preg_replace("/\n/",'',$buffer);
				$buffer=preg_replace("/\r/",'',$buffer);
				$pos=0;
				$reminder='';
				$replace_back=array();
				while (true) {
					$endpos=strpos($buffer,';',$pos);
					if ($endpos!==false) {
						$query=substr($buffer,$pos,$endpos-$pos);
						$query_type=substr($query,0,strpos($query,' '));
						if ($query_type=='INSERT' || $query_type=='UPDATE' || $query_type=='DELETE' || $query_type=='SELECT') {
							if ($query{strlen($query)-1}!=')') {	// yuck, ugly workaround for ';' inside strings
								$buffer{$endpos}='.';				// replace ';' with '.' so the parser can get by
								$replace_back[]=$endpos-$pos;
								continue;
							}
							foreach ($replace_back as $rpos) {
								$query{$rpos}=';';					// set the '.' back to ';' once we got the whole query
							}
							$query=preg_replace("/(\w)''(\w)/","\\1&#039;\\2",$query);	// single quotes to html entities
						}
						$myreturn=@mysql_query($query);
						$replace_back=array();
						if (!$myreturn) {
							$this->errmessage='Error executing query: '.mysql_error();
							break 2;
						}
						$pos=$endpos+1;
					} else {
						$reminder=substr($buffer,$pos);
						break;
					}
				}
			}
			fclose($fp);
		}
		return $myreturn;
	}


	function connect2db() {
		$myreturn=false;
		if (empty($this->dblink)) {
			if (defined('_DBHOSTNAME_') || defined('_DBUSERNAME_') || defined('_DBPASSWORD_') || defined('_DBNAME_')) {
				$this->dblink=@mysql_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_);
				if (empty($this->dblink)) {
					$myreturn=false;
					$this->errmessage='Wrong database credentials or host name';
				} else {
					$myreturn=mysql_select_db(_DBNAME_);
					if (!$myreturn) {
						$this->errmessage='Error - wrong database name or permission denied to access the specified database';
					}
				}
			} else {
				$myreturn=false;
				$this->errmessage='Database connection info not specified';
			}
		} else {
			$myreturn=true;
		}
		return $myreturn;
	}


	function db_query($query) {
		$myreturn=false;
		if ($this->connect2db()) {
			$myreturn=@mysql_query($query);
			$this->errmessage='Error executing query: '.mysql_error();
		}
		return $myreturn;
	}


	function mods_list() {
		require_once 'lib.xml.class.php';
		$myreturn=array();
		if ($dh=opendir(_BASEPATH_.'/patches')) {
			$i=0;
			while (($file=readdir($dh))!==false) {
				if (strpos($file,'.')!==0 && is_file(_BASEPATH_."/patches/$file/manifest.xml")) {
					$modfile=$this->fileop->file_get_contents(_BASEPATH_."/patches/$file/manifest.xml");
					$manifest=new XML_dsb();
					$manifest->parseXML($modfile);
					$item=$manifest->firstChild;
					if ($item->nodeName=='package') {
						$attrs=$item->attributes;
						$myreturn[$i]['modid']=$attrs['id'];
						$myreturn[$i]['modname']=$attrs['name'];
						$myreturn[$i]['modversion']=$attrs['version'];
						$setting=$item->firstChild;
						while ($setting) {
							if ($setting->nodeName=='applies-to') {
								$attrs=$setting->attributes;
								$myreturn[$i]['4product']=$attrs['product'];
								$myreturn[$i]['4version']=isset($attrs['version']) ? $attrs['version'] : '';
								if (isset($attrs['change-version'])) {
									$myreturn[$i]['upgrade2']=$attrs['change-version'];
								}
							} elseif ($setting->nodeName=='install') {
								$attrs=$setting->attributes;
								if (isset($attrs['reverse'])) {
									$myreturn[$i]['reverse']=$attrs['reverse'];
								}
								$install=$setting->firstChild;
								while ($install) {
									if ($install->nodeName=='text') {
										$attrs=$install->attributes;
										if ($attrs['type']=='inline') {
											$myreturn[$i]['installtext']=$install->firstChild->nodeValue;
										} elseif ($attrs['type']=='file') {
											$myreturn[$i]['installtext']=$this->fileop->file_get_contents($install->firstChild->nodeValue);
										}
									} elseif ($install->nodeName=='modfile') {
										$myreturn[$i]['modfile']=$install->firstChild->nodeValue;
									}
									$install=$install->nextSibling;
								}
							}
							$setting=$setting->nextSibling;	// go to the next setting
						}
						$myreturn[$i]['is_installed']=0;
						if ($this->is_mod_installed($myreturn[$i]['modid'],$myreturn[$i]['modversion'])) {
							$myreturn[$i]['is_installed']=1;
						}
					}
					++$i;
				}
			}
			closedir($dh);
		}
		return $myreturn;
	}


	function is_mod_installed($mod_id,$mod_version) {
		$myreturn=0;
		$query="SELECT is_installed FROM modmanager3 WHERE mod_id='$mod_id' AND mod_version='$mod_version'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$myreturn=mysql_result($res,0,0);
		}
		return $myreturn;
	}
}


class fileop {

	var $op_mode='ftp';	// 'disk' or 'ftp'
	var $ftp_id=null;

	function get_instance() {
		static $instance;
		if (!isset($instance)) {
			$c=__CLASS__;
			$instance=new $c;
		}
		return $instance;
	}


	function fileop() {
		if (defined('_FTP_SERVER_') && defined('_FTP_USER_') && defined('_FTP_PASS_') && function_exists('ftp_connect')) {
			$this->ftp_id=ftp_connect(_FTP_SERVER_);
			if ($this->ftp_id) {
				if (@ftp_login($this->ftp_id,_FTP_USER_,_FTP_PASS_)) {
					$this->op_mode='ftp';
				}
			}
		}
		if (empty($this->op_mode)) {
			$this->op_mode='disk';
			if ($this->ftp_id) {
				// invalid credentials
				ftp_quit($this->ftp_id);
			}
		}
	}


// $file should have a full basepath (for 'disk' op mode). In case we're using ftp it will be converted to ftp path
	function set_permission($file,$mode) {
		$myreturn='';
		if ($this->op_mode=='disk') {
			$myreturn=@chmod($file,$mode);
		} elseif ($this->op_mode=='ftp') {
			$file=str_replace(_BASEPATH_,_FTPPATH_,$file);
			if (function_exists('ftp_chmod')) {
				$myreturn=@ftp_chmod($this->ftp_id,$mode,$file);
			} else {
				$myreturn=ftp_site($this->ftp_id,"CHMOD $mode $file");
			}
		}
	}


// both params should have a full basepath (for 'disk' op mode)
	function copy($source,$destination) {
		$myreturn=false;
		if ($this->op_mode=='disk') {
			$myreturn=$this->_disk_copy($source,$destination);
		} elseif ($this->op_mode=='ftp') {
			$destination=str_replace(_BASEPATH_,_FTPPATH_,$destination);
			$myreturn=$this->_ftp_copy($source,$destination);
		}
		return $myreturn;
	}


// $source should have a full basepath (for 'disk' op mode)
	function delete($source) {
		$myreturn=false;
		if ($this->op_mode=='disk') {
			$myreturn=$this->_disk_delete($source);
		} elseif ($this->op_mode=='ftp') {
			if (is_dir($source) && $source{strlen($source)-1}!='/') {
				$source.='/';
			}
			$source=str_replace(_BASEPATH_,_FTPPATH_,$source);
			$myreturn=$this->_ftp_delete($source);
		}
		return $myreturn;
	}


	function file_put_contents($myfilename,$mydata) {
		$myreturn=false;
		if ($this->op_mode=='disk') {
			if (!is_writable($myfilename)) {
				@chmod($myfilename,0644);
				if (!is_writable($myfilename)) {
					@chmod($myfilename,0666);
				}
			}
			if (is_readable($myfilename) && is_writable($myfilename)) {
				if ($handle=@fopen($myfilename,'wb')) {
					if (@fwrite($handle,$mydata)) {
						$myreturn=true;
					}
					@fclose($handle);
				}
			}
		} elseif ($this->op_mode=='ftp') {
			$myfilename=str_replace(_BASEPATH_,_FTPPATH_,$myfilename);
			$tmpfname=tempnam(_BASEPATH_.'/tmp','ftp');
			$temp=fopen($tmpfname,'wb+');
			fwrite($temp,$mydata);
			rewind($temp);
			$myreturn=ftp_fput($this->ftp_id,$myfilename,$temp,FTP_BINARY);
			fclose($temp);
			@unlink($tmpfname);
		}
		return $myreturn;
	}


	function file_get_contents($file) {
		$myreturn='';
		if (function_exists('file_get_contents')) {
			$myreturn=file_get_contents($file);
		} else {
			$myreturn=fread($fp=fopen($file,'rb'),filesize($file));
			fclose($fp);
		}
		return $myreturn;
	}


// a special way to mark the backup files. Why? because accessing file.php~ on the web would show the source code
// while file~.php wouldn't
// $myfilename should have a full basepath
	function backup_file($myfilename) {
		$ext=substr($myfilename,strrpos($myfilename,'.'));
		$basename=substr($myfilename,0,strlen($myfilename)-strlen($ext));
		$backupfile=$basename.'~'.$ext;
		if (is_file($backupfile)) {
			$this->delete($backupfile);
		}
		$this->copy($myfilename,$backupfile);
	}


	function mkdir($fullpath) {
		if (!is_dir($fullpath)) {
			if ($this->op_mode=='disk') {
				@mkdir($fullpath,0755);
			} elseif ($this->op_mode=='ftp') {
				$ftp_fullpath=str_replace(_BASEPATH_,_FTPPATH_,$fullpath);
				ftp_mkdir($this->ftp_id,$ftp_fullpath);
			}
		}
	}

// internal function, do not call from outside. Call fileop->copy() instead
// both params should have a full basepath
	function _disk_copy($source,$destination) {
		$myreturn=false;
		if (is_dir($source)) {
			if (!is_dir($destination)) {
				@mkdir($destination,0755);
			}
			$d=dir($source);
			while ($file=$d->read()) {
				if ($file!='.' && $file!='..') {
					$myreturn=$this->_disk_copy($source.'/'.$file, $destination.'/'.$file);
				}
			}
			$d->close();
		} else {
			// file to file or file to dir copy. If dir, $destination must exist
			$myreturn=@copy($source,$destination);
		}
		return $myreturn;
	}


// internal function, do not call from outside. Call fileop->copy() instead
// source must have a disk path and destination must have a ftp path
	function _ftp_copy($source,$destination) {
		$myreturn=false;
		if (is_dir($source)) {
			// dir to dir copy
			if (!@ftp_chdir($this->ftp_id,$destination)) {
				ftp_mkdir($this->ftp_id,$destination);
			}
			$d=dir($source);
			while ($file=$d->read()) {
				if ($file!='.' && $file!='..') {
					$myreturn=$this->_ftp_copy($source.'/'.$file, $destination.'/'.$file);
				}
			}
			$d->close();
		} else {
			// file to file or file to dir copy. If dir, $destination must exist
			$myreturn=ftp_put($this->ftp_id,$destination,$source,FTP_BINARY);
		}
		return $myreturn;
	}


// internal function, do not call from outside. Call fileop->delete() instead
// $source should have a full basepath
	function _disk_delete($source) {
		$myreturn=false;
		if (is_dir($source)) {
			$d=dir($source);
			while ($file=$d->read()) {
				if ($file!='.' && $file!='..') {
					$myreturn=$this->_disk_delete($source.'/'.$file);
				}
			}
			$d->close();
			$myreturn=@rmdir($source);
		} else {
			$myreturn=@unlink($source);
		}
		return $myreturn;
	}


// internal function, do not call from outside. Call fileop->delete() instead
// $source should have a full ftppath
	function _ftp_delete($source) {
		$myreturn=false;
		if ($source{strlen($source)-1}=='/') {
			@ftp_chdir($this->ftp_id,$source);
			$files=ftp_nlist($this->ftp_id,'-aF');	// array or false on error. -F will append / to dirs
			if ($files!==false) {
				for ($i=0;isset($files[$i]);++$i) {
					if ($files[$i]!='./' && $files[$i]!='../') {
						$myreturn=$this->_ftp_delete($source.'/'.$files[$i]);
					}
				}
				$myreturn=@ftp_rmdir($this->ftp_id,$source);
			} else {
				$myreturn=false;// not enough.Should also break out of the recurring function in the for() above if $myreturn==false
			}
		} else {
			$myreturn=@ftp_delete($this->ftp_id,$source);
		}
		return $myreturn;
	}


// must call this function to make sure we won't open several connections to the ftp server.
	function finish() {
		if ($this->op_mode=='ftp') {
			ftp_quit($this->ftp_id);
		}
	}
}
