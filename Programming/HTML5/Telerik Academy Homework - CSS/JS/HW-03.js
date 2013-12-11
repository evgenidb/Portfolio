// Make the Tower (insert the divs for it)
jQuery(document).ready(function() {
	var inner = jQuery(".tower").html();
	for (var divCount = 0; divCount < 60; divCount++) {
		var inner = '<div>' + inner + '</div>';
	};
	jQuery(".tower").append(inner);
});