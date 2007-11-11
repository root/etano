$(function() {
	$('a.group_name').attr('href','#').toggle(
		function() {
			$(this).parent('.year').toggleClass('current');
		},
		function() {
			$(this).parent('.year').toggleClass('current');
		}
  	);
});
