<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/classes/package_downloader.class.php
$Revision: 312 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
*******************************************************************************/

require_once dirname(__FILE__).'/fileop.class.php';

class package_downloader {
	var $remote_file='';
	var $file_name='';
	var $error=false;
	var $error_text='';

	function package_downloader($remote_file) {
		if (substr($remote_file,0,23)==substr($tplvars['remote_site'],0,23)) {
			$this->remote_file=$remote_file;
		}
		$this->file_name='';
	}


	function download() {
		$this->error=false;
		define('HTTP_EOL',"\r\n");
		if (!empty($this->remote_file)) {
			$this->file_name='';
			$info=parse_url($this->remote_file);
			$fileop=new fileop();

			$header='GET '.$info['path'];
			if (isset($info['query'])) {
				$header.='?'.$info['query'];
			}
			$header.=' HTTP/1.0'.HTTP_EOL;
			$header.='Host: '.$info['host'].HTTP_EOL;
			$header.='Connection: close'.HTTP_EOL.HTTP_EOL;
			$socket=fsockopen($info['host'],80,$errno,$errstr,30);
			if ($socket) {
				fputs($socket,$header);
			}
			$reply='';
			$headerdone=false;
			while(!feof($socket)) {
				$line=fgets($socket);
				if (strcmp($line,HTTP_EOL)==0) {
					// read the header
					$headerdone=true;
				} elseif (!$headerdone) {
					if (empty($this->file_name)) {
						if (preg_match('/Content\-Disposition: attachment; filename="(.+)"/',$line,$m)) {
							$this->file_name=$m[1];
						} elseif (preg_match('/Content\-Type: application\/octet\-stream; name="(.+)"/',$line,$m)) {
							$this->file_name=$m[1];
						} elseif (preg_match('/Content\-Type: application\/octetstream; name="(.+)"/',$line,$m)) {
							$this->file_name=$m[1];
						}
					}
				} elseif ($headerdone) {
					// header has been read. now read the contents
					$reply.=$line;
				}
			}
			fclose ($socket);
			if (!empty($reply) && !empty($this->file_name)) {
				$fileop->file_put_contents(_BASEPATH_.'/tmp/'.$this->file_name,$reply);
				if ($this->verify()) {
					$fileop->rename(_BASEPATH_.'/tmp/'.$this->file_name,_BASEPATH_.'/tmp/packages/'.$this->file_name);
				}
			} else {
				$this->error=true;
				$this->error_text='Unable to download package.';
			}
		} else {
			$this->error=true;
			$this->error_text='Invalid package selected for download.';
		}
		return !$this->error;
	}

	// TODO: this function should connect to datemill fileserver to confirm that the downloaded file is a legitimate file
	// for now we rely on the simple verification in the constructor of the class
	function verify() {
		return true;
	}
}
