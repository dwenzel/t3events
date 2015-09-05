jQuery(document).ready(function($) {

	jQuery(".performanceItem .performance.short").click(function() {
		jQuery(this).parent().toggleClass("open");
	});

});