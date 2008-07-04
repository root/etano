$(function() {
	setTimeout('check_activity();',10000);
});

function check_activity() {
	$.getJSON('ajax/latest_logs.php',
				{'last_id':last_id},
				function(json) {
					towrite='';
					allids='';
					if (json.log) {
						for (i=0;i<json.log.length;i++) {
							log_id=json.log[i].log_id;
							if (i==0) {
								last_id=log_id;
							}
							allids+='#log_'+log_id+',';
							towrite+='<li id="log_'+log_id+'" style="display:none">';
							towrite+='<div class="user">'+json.log[i].user+'</div>';
							towrite+='<div class="ip">'+json.log[i].ip+'</div>';
							towrite+='<div class="level_code">'+json.log[i].level_code+'</div>';
							towrite+='<div class="time">'+json.log[i].time+'</div>';
							towrite+='</li>';
						}
						if (towrite!='') {
							$('#action_header').after(towrite);
							allids=allids.substr(0,allids.length-1);
							$(allids).show(1000,function() {
								$('#action li:gt(10)').hide(500);
							});
						}
					}
				}
	);
	setTimeout('check_activity();',10000);
}
