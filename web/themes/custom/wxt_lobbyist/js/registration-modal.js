
var waitForEl = function(selector, callback) {
  if (jQuery(selector).length) {
    callback();
  } else {
    setTimeout(function() {
      waitForEl(selector, callback);
    }, 100);
  }
};

jQuery(document).ready(function($){
	var selector = 'div:contains("You do not appear to be a lobbyist"):last';
	var trigger_selector = '.ui-front.ui-dialog-content.ui-widget-content';
	
	waitForEl(trigger_selector, function() {
		$(trigger_selector).on('click', 'input', function() {
			waitForEl(selector, function() {
				setTimeout(function() {
				    if(($(selector).parent().attr('style') == "display: none;") ) {
					  $('.webform-button--next').show();
				  	} else {
					  $('.webform-button--next').hide();
				  	}
				}, 180);
			});
		});
	});
});