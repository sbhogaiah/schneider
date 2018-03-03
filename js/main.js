$(function() {

	// activate validator
	var validationMsgs = {
        errorTitle: 'Form submission failed!',
        requiredFields: 'Fill all required fields',
        badTime: 'Enter correct time',
        badEmail: 'Enter correct e-mail address',
        badSecurityAnswer: 'Enter correct answer to the security question',
        badDate: 'Enter correct date',
        lengthBadStart: 'The input value must be between ',
        lengthBadEnd: ' characters',
        lengthTooLongStart: 'Cannot exceed ',
        lengthTooShortStart: 'Enter more than ',
        notConfirmed: 'Input values could not be confirmed',
        badDomain: 'Incorrect domain value',
        badUrl: 'Enter proper URL',
        badCustomVal: 'The input value is incorrect',
        andSpaces: ' and spaces ',
        badInt: 'Enter correct integer number',
        badStrength: 'The password isn\'t strong enough',
        badAlphaNumeric: 'Enter only alphanumeric characters ',
        badAlphaNumericExtra: ' and ',
        min : 'min',
        max : 'max'
    };

	$.validate({ 
		language: validationMsgs,
		modules : 'security'
	});

	// global selectors
	var $body, $win;

	$body = $('body');
	$win = $(window);


	// dropdown
	var dropdown = $('.dropdown');
	dropdown.on('click', function(e) {
		e.stopPropagation();
		$(this).toggleClass('open');
	});
	$body.on('click', function() {
		dropdown.removeClass('open');
	});	

	// sidebar collapse
	var sidebarCollapseBtn;
	sidebarCollapseBtn = $('.sidebar--collapse-btn');
	sidebarCollapseBtn.on('click', function(e) {
		e.stopPropagation();
		var checkCollapse = $body.hasClass('sidebar--collapsed');
		if(!checkCollapse) {
			$body.addClass('sidebar--collapsed');
			Cookies.set('sidebarCollapse', 'true', { expires: 1 });
		} else {
			$body.removeClass('sidebar--collapsed');
			Cookies.set('sidebarCollapse', 'false', { expires: 1 });
		}
	});


});

