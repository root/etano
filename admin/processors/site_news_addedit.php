<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/site_news_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/site_news.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/site_news.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($site_news_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$site_news_default['defaults'][$k]);
	}
	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage='admin/'.$input['return'];
	}

// check for input errors
	if (empty($input['news_body'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the news content';
		$input['error_news_body']='red_border';
	}

	if (!$error) {
		unset($input['date_posted']);
		if (!empty($input['news_id'])) {
			$query="UPDATE `{$dbtable_prefix}site_news` SET ";
			foreach ($site_news_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `news_id`=".$input['news_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='News changed.';
		} else {
			$input['date_posted']=gmdate('YmdHis');
			$query="INSERT INTO `{$dbtable_prefix}site_news` SET ";
			foreach ($site_news_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='News added.';
		}

		require_once _BASEPATH_.'/includes/classes/rss_writer.class.php';
		$rss_writer_object=&new rss_writer_class();
		$rss_writer_object->specification='1.0';
		$rss_writer_object->about=_BASEURL_.'/rss/site_news.xml';
		$rss_writer_object->rssnamespaces['dc']='http://purl.org/dc/elements/1.1/';
		$properties=array();
		$properties['description']='Site news';
		$properties['link']=_BASEURL_;
		$properties['title']='Site news';
		$properties['dc:date']=gmmktime();
		$rss_writer_object->addchannel($properties);

		$query="SELECT `news_title`,`news_body`,UNIX_TIMESTAMP(`date_posted`) as `date_posted` FROM `{$dbtable_prefix}site_news` ORDER BY `news_id` DESC";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($rsrow=mysql_fetch_assoc($res)) {
			$properties=array();
			$properties['description']=$rsrow['news_body'];
			$properties['link']='';
			$properties['title']=$rsrow['news_title'];
			$properties['dc:date']=$rsrow['date_posted'];
			$rss_writer_object->additem($properties);
		}
		if ($rss_writer_object->writerss($towrite)) {
			require_once _BASEPATH_.'/includes/classes/fileop.class.php';
			$fileop=new fileop();
			$fileop->file_put_contents(_BASEPATH_.'/rss/site_news.xml',$towrite);
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']=$rss_writer_object->error;
		}
	} else {
		$nextpage='admin/site_news_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['news_body']=addslashes_mq($_POST['news_body']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);
