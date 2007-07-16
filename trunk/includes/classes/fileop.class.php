<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/classes/fileop.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
*******************************************************************************/

class fileop {

	var $op_mode='disk';	// 'disk' or 'ftp'
	var $ftp_id=null;

	function fileop() {
		$ftp_error=false;
		if (defined('_FILEOP_MODE_') && _FILEOP_MODE_=='ftp' && defined('_FTPHOST_') && defined('_FTPUSER_') && defined('_FTPPASS_') && function_exists('ftp_connect')) {
			$this->ftp_id=ftp_connect(_FTPHOST_);
			if ($this->ftp_id) {
				if (@ftp_login($this->ftp_id,_FTPUSER_,_FTPPASS_)) {
					ftp_pasv( $this->ftp_id,true);
					$this->op_mode='ftp';
				} else {
					$ftp_error=true;
				}
			}
		} else {
			$ftp_error=true;
		}
		if ($ftp_error) {
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
			if (is_dir($source) && substr($source,-1)!='/') {
				$source.='/';
			}
			$source=str_replace(_BASEPATH_.'/',_FTPPATH_,$source);
			$myreturn=$this->_ftp_delete($source);
		}
		return $myreturn;
	}


	function file_put_contents($myfilename,$mydata) {
		$myreturn=false;
		if ($this->op_mode=='disk') {
			if (is_file($myfilename) && !is_writable($myfilename)) {
				@chmod($myfilename,0644);
				if (!is_writable($myfilename)) {
					@chmod($myfilename,0666);
				}
			}
			if ((is_file($myfilename) && is_readable($myfilename) && is_writable($myfilename)) || !is_file($myfilename)) {
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
		if (substr($source,-1)=='/') {
			@ftp_chdir($this->ftp_id,$source);
			$files=ftp_nlist($this->ftp_id,'-aF .');	// array or false on error. -F will append / to dirs
			if (empty($files)) {
				$temp=ftp_rawlist($this->ftp_id,'-aF .');	// array or false on error. -F will append / to dirs
				if (!empty($temp)) {
					for ($i=0;isset($temp[$i]);++$i) {
						$files[]=preg_replace('/.*:\d\d /','',$temp[$i]);
					}
				}
			}
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
