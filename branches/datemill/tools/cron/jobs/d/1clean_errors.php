<?php
$jobs[]='clean_errors';

function clean_errors() {
	global $dbtable_prefix;

	if (function_exists('curl_init')) {
		$config=get_site_option(array('collect_errors'),'adv_features');
		if (!empty($config['collect_errors'])) {
			$query="SELECT `log_id`,`module`,`error` FROM `{$dbtable_prefix}error_log` ORDER BY `log_id`";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$num_rows=mysql_num_rows($res);
			if ($num_rows) {
				$ch=curl_init('http://www.datemill.com/remote/ident.php?lk='.md5(_LICENSE_KEY_).'&bu='.rawurlencode(base64_encode(_BASEURL_)).'&v='.rawurlencode(_INTERNAL_VERSION_));
				curl_setopt($ch,CURLOPT_HEADER, false);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				$given_id=curl_exec($ch);

				$given_id=trim($given_id);
				$given_id=(int)$given_id;
				if (!empty($given_id)) {
					while ($rsrow=mysql_fetch_assoc($res)) {
						$rsrow['error']=rawurlencode($rsrow['error']);
						curl_setopt($ch,CURLOPT_URL,'http://www.datemill.com/remote/collect_errors.php');
						curl_setopt($ch,CURLOPT_POST,true);
						curl_setopt($ch,CURLOPT_HEADER, false);
						curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
						curl_setopt($ch,CURLOPT_POSTFIELDS,'id='.$given_id.'&m='.$rsrow['module'].'&e='.$rsrow['error']);
						curl_exec($ch);
					}
				}
				curl_close($ch);
			}
			if ($num_rows) {
				$query="TRUNCATE TABLE `{$dbtable_prefix}error_log`";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}
		}
	}
	return true;
}
