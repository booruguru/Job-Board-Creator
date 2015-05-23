jQuery(function($){

	//Click on reply button
	$('.comment-reply-link').click(function(e){
  		e.preventDefault();
	  	var args = $(this).data('onclick');
	  	args = args.replace(/.*\(|\)/gi, '').replace(/\"|\s+/g, '');
	  	args = args.split(',');
	  	tinymce.EditorManager.execCommand('mceRemoveEditor', true, 'comment');
	  	addComment.moveForm.apply( addComment, args );
	  	tinymce.EditorManager.execCommand('mceAddEditor', true, 'comment');
	  	$('#wp-link .howto, #search-panel').remove();
    });

    //Build replies button for each comments
	$('div.comment').each(function(){
		var id = $(this).attr('id');
		id = id.split('comment-');
		id = id[1];
		//Has children
		var nb_children = $('.comment.li-parent-'+id).length;
		var text = nb_children > 1 ? 'replies' : 'reply';
		if(nb_children) {
			$(this).find('.reply').after('<div class="right button tiny secondary children"><a class="show toggle_children_comments" data-id="'+id+'" href="javascript:void(0)">'+nb_children+' '+text+' <span class="sign">+</span></a></div>');
		}
	});

	//Toggle displaying of children comments
	$(document).on('click', '.toggle_children_comments', function(){
		var id = $(this).attr('data-id');
		if($(this).hasClass('show')) {
			//Show all
			$('.li-parent-'+id).slideDown('fast');
			$('div#comment-'+id).find('span.sign').html('-')
		} else {
			//Hide all children
			$('.li-parent-'+id).parent().find('div[class*="li-comment"]').slideUp('fast').find('span.sign').html('+');
			$('div#comment-'+id).find('span.sign').html('+');
		}
		$(this).toggleClass('show');
	});

	//User is directly arriving on comment on page load
	if(window.location.hash.indexOf('#comment-') !=-1) {
		var id = window.location.hash.split('-');
		id = id[1];
		$('.li-comment-'+id).show().find('.toggle_children_comments').removeClass('show').find('span.sign').html('-');
		//And show all siblings and parents
		$('.li-comment-'+id).siblings('div.comment').show().find('.toggle_children_comments').removeClass('show').find('span.sign').html('-');
		$('.li-comment-'+id).parents('ol').prev('div.comment').show().find('.toggle_children_comments').removeClass('show').find('span.sign').html('-');
		//Very first parent

	}

});