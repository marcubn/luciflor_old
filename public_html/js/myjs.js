$(document).ready(function() {
	$( ".modal" ).dialog();

	$( "#contact_form" ).validate({
		rules: {
			nume: {
				required: true,
				minlength: 3
			},
			email: {
				required: true,
				email: true
			},
			telefon: {
				required: true,
				digits: true
			},
			/*captcha: {
				required: true,
				maxlength: 2,
				customrule: $("#sum").val()
			},*/
			mesaj: {
				required: true
			}
		},
		submitHandler: function(form) {
			ga('send', 'event', 'Contact', 'send', 'Message');
			form.submit();
			/*var googleResponse = jQuery('#g-recaptcha-response').val();
			if (!googleResponse) {
				$('<p style="color:red !important" class=error-captcha"><span></span> Please fill up the captcha.</p>" ').insertAfter(".g-recaptcha");
				return false;
			} else {
				_gaq.push(['_trackEvent', 'Contact', 'Formular trimis']);
				form.submit();
			}*/
		}
	});
});