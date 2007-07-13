<?php
/*
 class feedReader
 reads a rss or xml feed
 version 2 for PHP 4
 autor: José Valente mailto:jcvalente@netvisao.pt
 2004 Portugal

	tweaked, tuned, translated & changed by Dan Caragea
*/


class feedReader {
	var $feedReader='';			// parser
	var $feedUrl='';			// url of the xml/rss file
	var $node=0;				// número de nós dos items
	var $channelFlag=false;		// flag
	var $currentTag='';			// actual tag
	var $outputData=array();	// results array
	var $itemFlag=false;		// flag
	var $imageFlag=false;		// flag
	var $feedVersion='';		// version of the rss file
	var $raw_xml='';			// the actual xml string to be parsed
	var $is_error=false;		//
	var $error_text='';			// last error encontered
//	var $url_is_path=false;		// the feedUrl points to a file on our server


	function feedReader($url='',$url_is_path=false) { //constructor
		$this->url_is_path=$url_is_path;
		if (!empty($url)) {
			$this->getFeed($url,$url_is_path);
		}
	}


	function setFeedUrl($url) { //set the url of the xml/rss feed
		$this->feedUrl=$url;
	}


	function getFeedOutputData() { // returns an array with all items.
		return $this->outputData;
	}


	function getFeedNumberOfNodes() { //get the number of items in the feed
		return $this->node;
	}


	function getRawXML() {
		return $this->raw_xml;
	}


	function setRawXML($xml) {
		$this->raw_xml=$xml;
	}


	function getFeed($url='',$url_is_path=false) {
		$myreturn=true;
		$this->url_is_path=$url_is_path;
		if (!empty($url)) {
			$this->setFeedUrl($url);
		}
		if ($this->url_is_path && is_file($this->feedUrl)) {
			if (!($this->raw_xml=file_get_contents($this->feedUrl))) {
				$myreturn=false;
				$this->is_error=true;
				$this->error_text='Unable to read the feed file';
			}
		} else {
			ini_set('auto_detect_line_endings','1');
			$url=parse_url($this->feedUrl);
			$header='GET '.$url['path'];
			if (!empty($url['query'])) {
				$header.='?'.$url['query'];
			}
			$header.=" HTTP/1.0\r\n";
			$header.='Host: '.$url['host']."\r\n";
			$header.="Connection: close\r\n\r\n";
			$socket=@fsockopen($url['host'],80,$errno,$errstr,10);
			if ($socket) {
				fwrite($socket,$header);
				$this->raw_xml='';
				$headerdone=false;
				while(!feof($socket)) {
					$line=fgets($socket,1024);
					if (strcmp($line,"\r\n")==0) {
						// read the header
						$headerdone=true;
					} elseif ($headerdone) {
						// header has been read. now read the contents
						$this->raw_xml.=$line;
					}
				}
				fclose ($socket);
				$this->raw_xml=trim($this->raw_xml);
			} else {
				$myreturn=false;
				$this->is_error=true;
				$this->error_text='Unable to connect to '.$url['host'];
			}
		}
		return $myreturn;
	}


	function parseFeed() {
		$myreturn=false;
		if (!empty($this->raw_xml)) {
			$this->feedReader=xml_parser_create();
			xml_set_object($this->feedReader,$this);
			xml_parser_set_option($this->feedReader,XML_OPTION_CASE_FOLDING,true);
			xml_set_element_handler($this->feedReader,'openTag','closeTag');
			xml_set_character_data_handler($this->feedReader,'dataHandling');
			if (@xml_parse($this->feedReader,$this->raw_xml,true)) {
				$myreturn=true;
				$this->is_error=false;
			} else {
				$this->is_error=true;
				$this->error_text=xml_error_string(xml_get_error_code($this->feedReader));
			}
			xml_parser_free($this->feedReader);
		}
		return $myreturn;
	}


	function openTag(&$parser,&$name,&$attribs) { //function startElement
		if ($name) {
			switch(strtolower($name)) {

				case 'channel':
					$this->channelFlag=true;
					break;

				case 'image':
					$this->channelFlag=false;
					$this->imageFlag=true;
					break;

				case 'item':
					$this->channelFlag=false;
					$this->imageFlag=false;
					$this->itemFlag=true;
					++$this->node;
					break;

				default:
					$this->currentTag=strtolower($name);
					break;

			}
		}
	}


	function closeTag(&$parser,&$name) { //function endElement
		$this->currentTag='';
	}


	function dataHandling(&$parser,&$data) { //function characterElement
		if (!empty($this->currentTag)) {
			$data=trim($data);
			if ($this->channelFlag) { //channel description
				if (isset($this->outputData['channel'][$this->currentTag])) {
					$this->outputData['channel'][$this->currentTag].=$data;
				} else {
					$this->outputData['channel'][$this->currentTag]=$data;
				}
			}
			if ($this->itemFlag) { //item description
				if (isset($this->outputData['item'][$this->node-1][$this->currentTag])) {
					$this->outputData['item'][$this->node-1][$this->currentTag].=$data;
				} else {
					$this->outputData['item'][$this->node-1][$this->currentTag]=$data;
				}
			}
			if ($this->imageFlag) { //image description
				if (isset($this->outputData['image'][$this->currentTag])) {
					$this->outputData['image'][$this->currentTag].=$data;
				} else {
					$this->outputData['image'][$this->currentTag]=$data;
				}
			}
		}
	}
}
