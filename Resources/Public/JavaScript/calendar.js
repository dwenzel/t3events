/**
 * calendar.js
 * custom scripts for calendar widget (Ajax)
 */

var t3events = {};

t3events.ajax = function (url, parentObject) {
	jQuery('#loader').addClass('loading');
	jQuery.ajax({
		url: url,
		context: parentObject
	})
		.done(function (data) {
			this.html(data);
			jQuery('#loader').removeClass('loading');
		})
};
jQuery('.tx-t3events ').on('click', '.navigation a', function (e) {
	e.preventDefault();
	element = jQuery(this);
	calendarId = element.data('calendarid');
	parentObject = jQuery('#' + calendarId);
	t3events.ajax(element.attr('href'), parentObject);
});

