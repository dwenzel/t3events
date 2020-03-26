/**
 * calendar.js
 * custom scripts for calendar widget (Ajax)
 */
const t3events = {};

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
	let element = jQuery(this);
    const calendarId = element.data('calendarid');
    const parentObject = jQuery('#' + calendarId);
    t3events.ajax(element.attr('href'), parentObject);
});

