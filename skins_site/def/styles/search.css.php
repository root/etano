<?php
require_once '../../../includes/general_functions.inc.php';
require_once '../../../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);

header('Content-Type: text/css');
$t1width=get_site_option('t1_width','core_photo');
?>
	ul.list_view li {
		margin-left: 10px;
		width: 100%;
		float: none;
	}


ul.list_view .result_user {
	padding-bottom: 5px;
	margin-bottom: 10px;
	border-bottom: 1px dashed #ccc;
}

ul.list_view .result_user .user_photo {
	float: left;
	margin: 0 10px 10px 0;
}

ul.gallery_view {
	margin: 10px 0px;
	padding-bottom: 15px;
	border-bottom: 1px dashed #ccc;
}

	ul.gallery_view li {
		margin-left: 20px;
		width: <?php echo ($t1width+20);?>px;
		border-right: 1px dashed #ccc;
	}

	ul.gallery_view li.first {
		margin-top: 0;
		margin-left: 0;
	}

	ul.gallery_view li.last {
		border: none;
	}

ul.gallery_view p {
	display: none;
}

/* LEFT MENU */

.content-link.gview {
	background-image: url('../images/menu_gallery_view.gif');
}

.content-link.lview {
	background-image: url('../images/menu_list_view.gif');
}

#my_searches ul.menu li {
	border: none;
	padding: 0;
}

#my_searches ul.menu li.controls {
	border-top: 1px dashed #ccc;
	padding-top: 10px;
	margin-top: 5px;
}

