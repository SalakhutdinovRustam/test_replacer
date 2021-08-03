jQuery( function( $ ){ 

	$('#replacer_input, #replacer_output').on('focus', function(){
		$(this).parents('#replace_option').find('#submit').attr('disabled', true);
	})

	$('#replacer_input, #replacer_output').on('blur', function(){

		let replacer_values = $(this).val();

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: 'action=replacer_valid&replacer_values=' + replacer_values,
			beforeSend: () => {
				$(this).attr('readonly', true);
			},
			success: (result) => {
				if(result.result_check === true ) {
					$(this).parents('#replace_option').find('#submit').attr('disabled', false);
				} else {
					alert('You have Cyrillic characters or punctuation marks');
				}

				$(this).attr('readonly', false);
			}
		}); 
		
	
	});
});