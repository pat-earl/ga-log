$(document).ready(function() {
    initButtons();
    $('input#short-data, textarea#comment-data').characterCounter();
    M.AutoInit();
    fetchComments();
    
});

function initButtons() {
	$('#submit-comment').click(function(){
        var ajaxurl = './actions/submit_comment.php',
        data =  $('form').serialize() + "&ajax=" + true;
        $.post(ajaxurl, data, function (response) {
            if(response.indexOf("success")>=0){
                M.toast({html: 'Ding!'});
                fetchComments();
        	} else {
        		M.toast({html: ("Error in init: " + response)});
        	}
        });
	});
}

function fetchComments() {
    var ajaxurl = './actions/fetch_comments.php',
    data =  "&ajax=" + true;
    $.post(ajaxurl, data, function (response) {
        if(response.indexOf("success")>=0){
            printComments();
        } else {
            M.toast({html: ("Error in fetch: " + response)});
        }
    });
}

function printComments() {
    var ajaxurl = './actions/print_comments.php',
    data =  "&ajax=" + true;
    $.post(ajaxurl, data, function (response) {
        $('#comments_go_here').html(response);
    });
}