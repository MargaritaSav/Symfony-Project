
import $ from 'jquery';

const answerButton = $(".action-send_answer");
const validateButton = $(".validate-button");

answerButton.attr('data-toggle', 'modal');
answerButton.attr('data-target', '#questionModal');
let url = null;
answerButton.click((e)=>{
	e.preventDefault();
	url = e.target.href;
})
validateButton.click((e)=>{	
	let answer = $("#questions_answer").val();
	let answeredBy = $("#questions_answeredBy").val();
	$.post(url, 
	{
		answer: answer,
		answeredBy: answeredBy
	}, (response)=>{
		window.location.href = response;
	})
	
	validateButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')	
});
