<?php
if (!defined('_LICENSE_KEY_')) {
	die('Hacking attempt');
}
require_once _BASEPATH_.'/includes/interfaces/iprofile_field.class.php';
require_once _BASEPATH_.'/includes/classes/fields/field_textfield.class.php';
require_once _BASEPATH_.'/includes/classes/fields/field_textarea.class.php';
require_once _BASEPATH_.'/includes/classes/fields/field_select.class.php';
require_once _BASEPATH_.'/includes/classes/fields/field_mchecks.class.php';
require_once _BASEPATH_.'/includes/classes/fields/field_birthdate.class.php';
require_once _BASEPATH_.'/includes/classes/fields/field_location.class.php';
require_once _BASEPATH_.'/includes/classes/fields/field_range.class.php';
require_once _BASEPATH_.'/includes/classes/fields/field_age_range.class.php';
require_once _BASEPATH_.'/includes/classes/fields/field_zip_distance.class.php';

$GLOBALS['_pfields'][1]=new field_select(
								array('label'=>&$GLOBALS['_lang'][2282],
									'searchable'=>true,
									'search_type'=>'field_mchecks',
									'search_label'=>&$GLOBALS['_lang'][2283],
									'reg_page'=>1,
									'required'=>true,
									'editable'=>true,
									'visible'=>true,
									'dbfield'=>'f1',
									'fk_pcat_id'=>6,
									'accepted_values'=>array('',&$GLOBALS['_lang'][2280],&$GLOBALS['_lang'][2281]),
									'default_value'=>'1',
									'search_default'=>'|2|',
									'help_text'=>&$GLOBALS['_lang'][2284])
							);

$GLOBALS['_pfields'][2]=new field_mchecks(
								array('label'=>&$GLOBALS['_lang'][2287],
									'searchable'=>true,
									'search_type'=>'field_mchecks',
									'search_label'=>&$GLOBALS['_lang'][2288],
									'reg_page'=>1,
									'required'=>true,
									'editable'=>true,
									'visible'=>true,
									'dbfield'=>'f2',
									'fk_pcat_id'=>6,
									'accepted_values'=>array('',&$GLOBALS['_lang'][2285],&$GLOBALS['_lang'][2286]),
									'default_value'=>'|2|',
									'search_default'=>'|1|',
									'help_text'=>&$GLOBALS['_lang'][2289])
							);

$GLOBALS['_pfields'][3]=new field_birthdate(
								array('label'=>&$GLOBALS['_lang'][2290],
									'searchable'=>true,
									'search_type'=>'field_age_range',
									'search_label'=>&$GLOBALS['_lang'][2291],
									'reg_page'=>1,
									'required'=>true,
									'editable'=>true,
									'visible'=>true,
									'dbfield'=>'f3',
									'fk_pcat_id'=>6,
									'accepted_values'=>array('min'=>18,'max'=>100),
									'search_default'=>array('min'=>18,'max'=>75),
									'help_text'=>&$GLOBALS['_lang'][2292])
							);

$GLOBALS['_pfields'][4]=new field_location(
								array('label'=>&$GLOBALS['_lang'][2293],
									'searchable'=>true,
									'search_type'=>'field_location',
									'search_label'=>&$GLOBALS['_lang'][2294],
									'reg_page'=>1,
									'required'=>true,
									'editable'=>true,
									'visible'=>true,
									'dbfield'=>'f4',
									'fk_pcat_id'=>6,
									'fn_on_change'=>'update_location',
									'default_value'=>218,
									'search_default'=>218,
//									'search_default'=>array('dist'=>5),
									'help_text'=>&$GLOBALS['_lang'][2295])
							);

$GLOBALS['_pfields'][5]=new field_textarea(
								array('label'=>&$GLOBALS['_lang'][2296],
									'search_label'=>&$GLOBALS['_lang'][2297],
									'reg_page'=>2,
									'editable'=>true,
									'use_bbcode'=>true,
									'use_smilies'=>true,
									'required'=>true,
									'ta_len'=>1000,
									'visible'=>true,
									'dbfield'=>'f5',
									'fk_pcat_id'=>6,
									'help_text'=>&$GLOBALS['_lang'][2298])
							);

$GLOBALS['_pfields'][6]=new field_select(
								array('label'=>&$GLOBALS['_lang'][2308],
									'searchable'=>true,
									'search_type'=>'field_mchecks',
									'search_label'=>&$GLOBALS['_lang'][2309],
									'reg_page'=>2,
									'required'=>true,
									'editable'=>true,
									'visible'=>true,
									'dbfield'=>'f6',
									'fk_pcat_id'=>7,
									'accepted_values'=>array('',&$GLOBALS['_lang'][2299],&$GLOBALS['_lang'][2300],&$GLOBALS['_lang'][2301],&$GLOBALS['_lang'][2302],&$GLOBALS['_lang'][2303],&$GLOBALS['_lang'][2304],&$GLOBALS['_lang'][2305],&$GLOBALS['_lang'][2306],&$GLOBALS['_lang'][2307]),
									'default_value'=>array(),
									'search_default'=>'',
									'help_text'=>&$GLOBALS['_lang'][2310])
							);

$GLOBALS['_pfields'][7]=new field_select(
								array('label'=>&$GLOBALS['_lang'][2323],
									'searchable'=>true,
									'search_type'=>'field_range',
									'search_label'=>&$GLOBALS['_lang'][2324],
									'reg_page'=>2,
									'editable'=>true,
									'visible'=>true,
									'dbfield'=>'f7',
									'fk_pcat_id'=>7,
									'accepted_values'=>array('',&$GLOBALS['_lang'][2311],&$GLOBALS['_lang'][2312],&$GLOBALS['_lang'][2313],&$GLOBALS['_lang'][2314],&$GLOBALS['_lang'][2315],&$GLOBALS['_lang'][2316],&$GLOBALS['_lang'][2317],&$GLOBALS['_lang'][2318],&$GLOBALS['_lang'][2319],&$GLOBALS['_lang'][2320],&$GLOBALS['_lang'][2321],&$GLOBALS['_lang'][2322],&$GLOBALS['_lang'][2326],&$GLOBALS['_lang'][2327]),
									'default_value'=>array(),
									'search_default'=>array('min'=>1,'max'=>14),
									'help_text'=>&$GLOBALS['_lang'][2325])
							);

$GLOBALS['_pfields'][8]=new field_select(
								array('label'=>&$GLOBALS['_lang'][2337],
									'searchable'=>true,
									'search_type'=>'field_mchecks',
									'search_label'=>&$GLOBALS['_lang'][2338],
									'reg_page'=>2,
									'editable'=>true,
									'visible'=>true,
									'dbfield'=>'f8',
									'fk_pcat_id'=>7,
									'accepted_values'=>array('',&$GLOBALS['_lang'][2328],&$GLOBALS['_lang'][2329],&$GLOBALS['_lang'][2330],&$GLOBALS['_lang'][2331],&$GLOBALS['_lang'][2332],&$GLOBALS['_lang'][2333],&$GLOBALS['_lang'][2334],&$GLOBALS['_lang'][2335],&$GLOBALS['_lang'][2336]),
									'default_value'=>array(),
									'search_default'=>'',
									'help_text'=>&$GLOBALS['_lang'][2339])
							);

$GLOBALS['_pfields'][9]=new field_select(
								array('label'=>&$GLOBALS['_lang'][2346],
									'searchable'=>true,
									'search_type'=>'field_select',
									'search_label'=>&$GLOBALS['_lang'][2347],
									'reg_page'=>2,
									'editable'=>true,
									'visible'=>true,
									'dbfield'=>'f9',
									'fk_pcat_id'=>7,
									'accepted_values'=>array('',&$GLOBALS['_lang'][2340],&$GLOBALS['_lang'][2341],&$GLOBALS['_lang'][2342],&$GLOBALS['_lang'][2343],&$GLOBALS['_lang'][2344],&$GLOBALS['_lang'][2345]),
									'default_value'=>array(),
									'search_default'=>array(),
									'help_text'=>&$GLOBALS['_lang'][2348])
							);

$GLOBALS['_pfields'][10]=new field_select(
								array('label'=>&$GLOBALS['_lang'][2358],
									'searchable'=>true,
									'search_type'=>'field_mchecks',
									'search_label'=>&$GLOBALS['_lang'][2359],
									'reg_page'=>2,
									'editable'=>true,
									'visible'=>true,
									'dbfield'=>'f10',
									'fk_pcat_id'=>7,
									'accepted_values'=>array('',&$GLOBALS['_lang'][2349],&$GLOBALS['_lang'][2350],&$GLOBALS['_lang'][2351],&$GLOBALS['_lang'][2352],&$GLOBALS['_lang'][2353],&$GLOBALS['_lang'][2354],&$GLOBALS['_lang'][2355],&$GLOBALS['_lang'][2356],&$GLOBALS['_lang'][2357]),
									'default_value'=>array(),
									'search_default'=>'',
									'help_text'=>&$GLOBALS['_lang'][2360])
							);

$GLOBALS['_pfields'][11]=new field_select(
								array('label'=>&$GLOBALS['_lang'][2364],
									'searchable'=>true,
									'search_type'=>'field_mchecks',
									'search_label'=>&$GLOBALS['_lang'][2365],
									'reg_page'=>2,
									'editable'=>true,
									'visible'=>true,
									'dbfield'=>'f11',
									'fk_pcat_id'=>8,
									'accepted_values'=>array('',&$GLOBALS['_lang'][2361],&$GLOBALS['_lang'][2362],&$GLOBALS['_lang'][2363]),
									'default_value'=>array(),
									'search_default'=>'',
									'help_text'=>&$GLOBALS['_lang'][2366])
							);

$GLOBALS['_pfields'][12]=new field_select(
								array('label'=>&$GLOBALS['_lang'][2370],
									'searchable'=>true,
									'search_type'=>'field_mchecks',
									'search_label'=>&$GLOBALS['_lang'][2371],
									'reg_page'=>2,
									'editable'=>true,
									'visible'=>true,
									'dbfield'=>'f12',
									'fk_pcat_id'=>8,
									'accepted_values'=>array('',&$GLOBALS['_lang'][2367],&$GLOBALS['_lang'][2368],&$GLOBALS['_lang'][2369]),
									'default_value'=>array(),
									'search_default'=>'',
									'help_text'=>&$GLOBALS['_lang'][2372])
							);

$GLOBALS['_pfields'][13]=new field_select(
								array('label'=>&$GLOBALS['_lang'][2378],
									'searchable'=>true,
									'search_type'=>'field_select',
									'search_label'=>&$GLOBALS['_lang'][2379],
									'reg_page'=>2,
									'editable'=>true,
									'visible'=>true,
									'dbfield'=>'f13',
									'fk_pcat_id'=>8,
									'accepted_values'=>array('',&$GLOBALS['_lang'][2373],&$GLOBALS['_lang'][2374],&$GLOBALS['_lang'][2375],&$GLOBALS['_lang'][2376],&$GLOBALS['_lang'][2377]),
									'default_value'=>array(),
									'search_default'=>array(),
									'help_text'=>&$GLOBALS['_lang'][2380])
							);

$GLOBALS['_pfields'][14]=new field_select(
								array('label'=>&$GLOBALS['_lang'][2385],
									'searchable'=>true,
									'search_type'=>'field_select',
									'search_label'=>&$GLOBALS['_lang'][2386],
									'reg_page'=>2,
									'editable'=>true,
									'visible'=>true,
									'dbfield'=>'f14',
									'fk_pcat_id'=>9,
									'accepted_values'=>array('',&$GLOBALS['_lang'][2381],&$GLOBALS['_lang'][2382],&$GLOBALS['_lang'][2383],&$GLOBALS['_lang'][2384]),
									'default_value'=>array(),
									'search_default'=>array(),
									'help_text'=>&$GLOBALS['_lang'][2387])
							);

$GLOBALS['_pfields'][15]=new field_mchecks(
								array('label'=>&$GLOBALS['_lang'][2395],
									'searchable'=>true,
									'search_type'=>'field_mchecks',
									'search_label'=>&$GLOBALS['_lang'][2396],
									'reg_page'=>2,
									'editable'=>true,
									'visible'=>true,
									'dbfield'=>'f15',
									'fk_pcat_id'=>9,
									'accepted_values'=>array('',&$GLOBALS['_lang'][2388],&$GLOBALS['_lang'][2389],&$GLOBALS['_lang'][2390],&$GLOBALS['_lang'][2391],&$GLOBALS['_lang'][2392],&$GLOBALS['_lang'][2393],&$GLOBALS['_lang'][2394]),
									'default_value'=>array(),
									'search_default'=>'',
									'help_text'=>&$GLOBALS['_lang'][2397])
							);


$GLOBALS['_pcats'][6]['pcat_name']=&$GLOBALS['_lang'][2275];
$GLOBALS['_pcats'][6]['access_level']=7;
$GLOBALS['_pcats'][6]['fields']=array(1,2,3,4,5);
$GLOBALS['_pcats'][7]['pcat_name']=&$GLOBALS['_lang'][2276];
$GLOBALS['_pcats'][7]['access_level']=7;
$GLOBALS['_pcats'][7]['fields']=array(6,7,8,9,10);
$GLOBALS['_pcats'][8]['pcat_name']=&$GLOBALS['_lang'][2277];
$GLOBALS['_pcats'][8]['access_level']=7;
$GLOBALS['_pcats'][8]['fields']=array(11,12,13);
$GLOBALS['_pcats'][9]['pcat_name']=&$GLOBALS['_lang'][2278];
$GLOBALS['_pcats'][9]['access_level']=7;
$GLOBALS['_pcats'][9]['fields']=array(14,15);

$basic_search_fields=array(1,2,3,4);
