<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/site_news_delete.php
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
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/site_news.php';
$news_id=isset($_GET['news_id']) ? (int)$_GET['news_id'] : 0;
if (!empty($_GET['return'])) {
	$nextpage='admin/'.sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
}

$query="DELETE FROM `{$dbtable_prefix}site_news` WHERE `news_id`=$news_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='News post deleted.';

require_once _BASEPATH_.'/includes/classes/rss_writer.class.php';
$rss_writer_object=new rss_writer_class();
$rss_writer_object->specification='1.0';
$rss_writer_object->about=_BASEURL_.'/rss/site_news.xml';
$rss_writer_object->rssnamespaces['dc']='http://purl.org/dc/elements/1.1/';
$properties=array();
$properties['description']='Site news';
$properties['link']=_BASEURL_;
$properties['title']='Site news';
$properties['dc:date']=mktime(gmdate('H'),gmdate('i'),gmdate('s'),gmdate('m'),gmdate('d'),gmdate('Y'));
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

$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);
