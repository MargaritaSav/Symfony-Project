import '../css/blog.sass';
import $ from 'jquery';


 $(document).ajaxStart(function(){
    $("#preloader").show();
    $('#articles-list').css("opacity", "0");
  });
  $(document).ajaxComplete(function(){
    $("#preloader").hide();
    $('#articles-list').css("opacity", "1");
  });

$('#articles-list').on('click', '.page-link', function(event){
	changeContent(event,"#articles-list");
    $('html, body').animate({ scrollTop: 200 }, '300');
	
})
$('#categories li a').click((event)=>{
	changeContent(event, "#articles-list")
});

function changeContent(event, selector){
	event.preventDefault();
	$.ajax({url: event.target.href, success: function(result){
    $(selector).html(result);
    }});
}

