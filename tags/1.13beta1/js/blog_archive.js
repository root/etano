$(function() {
	$('a.group_name').attr('href','javascript&#058;;').toggle(
		function() {
			$(this).parent('.year').toggleClass('current');
		},
		function() {
			$(this).parent('.year').toggleClass('current');
		}
  	);
});
