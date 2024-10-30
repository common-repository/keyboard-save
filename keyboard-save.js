jQuery(window).bind('keydown', function(event) {
	if (event.ctrlKey || event.metaKey) {
		if(String.fromCharCode(event.which).toLowerCase() === 's' ) {
			event.preventDefault();

			if ( typeof wpkeysave_save_trigger !== 'undefined' ){
				// harsh way to save draft/publish/update
				jQuery( '#' + wpkeysave_save_trigger ).trigger( 'click' );
				console.log(jQuery( '#' + wpkeysave_save_trigger ));
			}

			// todo work on a sleepy way to autosave
			// if ( typeof wp !== 'undefined' && wp.autosave ) {
			//  wp.autosave.server.triggerSave();
			// }
		}
	}
});
