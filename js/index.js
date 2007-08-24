$(function() {
	window.setTimeout("refresh_testimonials();",30000);
});

function refresh_testimonials() {
	$.getJSON('ajax/get_testimonials.php',
				function(json) {
					if (json) {
						$('#testimonials').html(json.ttext+'<cite>'+json.tname+'</cite>');
					}
				}
	);
	window.setTimeout("refresh_testimonials();",30000);
}
