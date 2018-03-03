// modal
var modal = {

	'init': function () {
		this.openEvent('[data-modal-open]');
		this.closeEvent('[data-modal-close]');
		this.overlayEvent();
	},

	'openEvent': function (el) {
		$this = this;
		$(el).on('click', function(e) {
			e.preventDefault();
			var id = $(this).data('modal-open');
			$this.openModal(id);
			console.log('modal');
			//activate close btn
			$this.closeEvent($(id).find('[data-modal-close]'));
			$this.overlayEvent();

			if($(id).find('form').length !== 0) {
				$(id).find('form').get()[0].reset();
			}
		}); 
	},

	'closeEvent': function (el) {
		$this = this;
		$(el).on('click', function(e) {
			e.preventDefault();
			var id = $(this).data('modal-close');
			$this.closeModal(id);
		});
	},

	'overlayEvent': function () {
		$('.modal').on('click', function (e) {
			if(e.target.className == "modal") {
				$(this).fadeOut(300);
			}
		});
	},
 
	'openModal': function (el, callback) {
		$this = this;
		$(el).fadeIn(300, callback);
		
		$this.confirm(el);
		//activate close btn
		$this.closeEvent($(el).find('[data-modal-close]'));
		$this.overlayEvent();
	},

	'closeModal': function (el, callback) {
		$(el).fadeOut(300, callback);
	},

	'confirm': function (el) {
		$this = this;
		var confirmButtons = $(el).find('[data-modal-confirm]');

		if(confirmButtons.is('.button')) {

			confirmButtons.click(function() {
				var check = $(this).data('modal-confirm');
				if(check) {
					return true;
				} else {
					$this.closeModal(el);
				}
			});
			
		}
	}
};

// to add 0 to single digit nummber
var numHelpers = {
	'leftPad': function (number) {  
	    return ((number < 10 && number >= 0) ? '0' : '') + number;
	},

	'rightPad': function (number) {  
	    return number + ((number < 10 && number >= 0) ? '0' : '');
	}
};

// message box
var page = {

	'element' : {
		'show' : function (el) {
			$(el).removeClass('hidden');
		},
		'hide' : function (el) {
			$(el).addClass('hidden');
		}
	},

	'loadAnim' : {
		'start' : function (el) { 
			$(el).addClass('loading-animation'); 
		},

		'stop' : function (el) { 
			$(el).removeClass('loading-animation'); 
		}
	},

	'createMsg' : function(msg, type) {

		if($('#message-box')) {
			$('#message-box').remove();
		}

		var msgBox = '<div id="message-box"><div class="message-box--contents msg-error">' + msg + '</div></div>';

		//add message box to page
		$('body').append(msgBox);

		//check type of message
		type = typeof type !== 'undefined' ? type : 'default';

		if(type == 'error') { $('#message-box .message-box--contents').addClass('error-message'); }

		$('#message-box .message-box--contents').fadeIn(200);
		
		//erase after some time
		setTimeout(function() {
			$('#message-box .message-box--contents').fadeOut(400, function() {
				$('#message-box').remove();
			});
		}, 2500);
	}
};