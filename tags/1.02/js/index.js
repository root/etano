function req_update_location(str_field,val) {
	$.post(baseurl+'/ajax/location.php',
			{'field':str_field,'val':val},
			function(data) {
				if (data!=null && data!='') {
					allopts=data.split("\n");
					str_field=allopts[0];
					toshow=allopts[1].split('|');
					$('#row_'+str_field+'state').removeClass('visible').addClass('invisible');
					$('#row_'+str_field+'city').removeClass('visible').addClass('invisible');
					$('#row_'+str_field+'zip').removeClass('visible').addClass('invisible');
					for (i=0;i<toshow.length;i++) {
						$('#row_'+toshow[i]).removeClass('invisible').addClass('visible');
					}
					if (allopts.length>3) {
						to_update=$('#'+str_field+allopts[2])[0];
						to_update.options.length=0;
						for (i=3;i<allopts.length;i++) {
							oneopt=allopts[i].split('|');
							opt=new Option(oneopt[1],oneopt[0]);
							to_update.options.add(opt,to_update.length);
						}
					}
				}
			}
	);
}

