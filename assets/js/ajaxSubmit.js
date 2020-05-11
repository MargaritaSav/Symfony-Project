let $ = require('jquery');
module.exports = function(formSelector, url, flashMessageSelector, mailFieldSelector){

	let form = $(formSelector);
	$(this).hide()
	let formSerialize = form.serialize();
	let email = form.find(mailFieldSelector).val();
	
	$.post(url, formSerialize, function(response){
		if(response){
			$(formSelector).hide(500);
			$(flashMessageSelector).append(" " + email + ".");
			$(flashMessageSelector).show(600);
		}
	})
}