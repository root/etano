<?php
$_pfields[1]['label']=$_lang[8];
$_pfields[1]['html_type']=3;
$_pfields[1]['searchable']=true;
$_pfields[1]['search_type']=10;
$_pfields[1]['search_label']=$_lang[9];
$_pfields[1]['reg_page']=1;
$_pfields[1]['required']=true;
$_pfields[1]['editable']=true;
$_pfields[1]['visible']=true;
$_pfields[1]['dbfield']='f2';
$_pfields[1]['fk_pcat_id']=1;
$_pfields[1]['accepted_values']=array('-',$_lang[6],$_lang[7]);
$_pfields[1]['default_value']=array(1);
$_pfields[1]['default_search']=array(2);
$_pfields[1]['help_text']=$_lang[10];

$_pfields[2]['label']=$_lang[13];
$_pfields[2]['html_type']=10;
$_pfields[2]['searchable']=true;
$_pfields[2]['search_type']=10;
$_pfields[2]['search_label']=$_lang[14];
$_pfields[2]['reg_page']=1;
$_pfields[2]['required']=true;
$_pfields[2]['editable']=true;
$_pfields[2]['visible']=true;
$_pfields[2]['dbfield']='f3';
$_pfields[2]['fk_pcat_id']=1;
$_pfields[2]['accepted_values']=array('-',$_lang[11],$_lang[12]);
$_pfields[2]['default_value']=array(2);
$_pfields[2]['default_search']=array(1);
$_pfields[2]['help_text']=$_lang[15];

$_pfields[3]['label']=$_lang[3];
$_pfields[3]['html_type']=2;
$_pfields[3]['editable']=true;
$_pfields[3]['visible']=true;
$_pfields[3]['dbfield']='f1';
$_pfields[3]['fk_pcat_id']=1;
$_pfields[3]['help_text']=$_lang[5];

$_pfields[4]['label']=$_lang[22];
$_pfields[4]['html_type']=3;
$_pfields[4]['searchable']=true;
$_pfields[4]['search_type']=108;
$_pfields[4]['search_label']=$_lang[23];
$_pfields[4]['editable']=true;
$_pfields[4]['visible']=true;
$_pfields[4]['dbfield']='f4';
$_pfields[4]['fk_pcat_id']=2;
$_pfields[4]['accepted_values']=array('-',$_lang[16],$_lang[17],$_lang[18],$_lang[19],$_lang[20],$_lang[21]);
$_pfields[4]['default_value']=array(3);
$_pfields[4]['default_search']=array(1,6);
$_pfields[4]['help_text']=$_lang[24];

$_pfields[5]['label']=$_lang[25];
$_pfields[5]['html_type']=103;
$_pfields[5]['searchable']=true;
$_pfields[5]['search_type']=108;
$_pfields[5]['search_label']=$_lang[26];
$_pfields[5]['reg_page']=1;
$_pfields[5]['editable']=true;
$_pfields[5]['visible']=true;
$_pfields[5]['dbfield']='f5';
$_pfields[5]['fk_pcat_id']=1;
$_pfields[5]['accepted_values']=array('-','1930','1989');
$_pfields[5]['default_search']=array(18,75);
$_pfields[5]['help_text']=$_lang[27];


$_pcats[1]['pcat_name']=$_lang[1];
$_pcats[1]['access_level']=7;
$_pcats[1]['fields']=array(1,2,3,5);
$_pcats[2]['pcat_name']=$_lang[2];
$_pcats[2]['access_level']=7;
$_pcats[2]['fields']=array(4);

$basic_search_fields=array(1,2,4,5);
