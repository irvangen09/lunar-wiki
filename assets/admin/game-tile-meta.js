( function () {
	'use strict';

	var selectButton = document.getElementById( 'lunar-wiki-game-tile-select' );
	var removeButton = document.getElementById( 'lunar-wiki-game-tile-remove' );
	var preview      = document.getElementById( 'lunar-wiki-game-tile-preview' );
	var hiddenInput  = document.getElementById( 'lunar-wiki-game-tile-image-id' );

	if ( ! selectButton || ! hiddenInput || ! preview || typeof wp === 'undefined' || ! wp.media ) {
		return;
	}

	var frame = null;

	selectButton.addEventListener( 'click', function ( event ) {
		event.preventDefault();

		if ( frame ) {
			frame.open();
			return;
		}

		frame = wp.media( {
			title: 'Select Image',
			button: { text: 'Use This Image' },
			library: { type: 'image' },
			multiple: false,
		} );

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			var imageUrl   = attachment.sizes && attachment.sizes.medium
				? attachment.sizes.medium.url
				: attachment.url;

			hiddenInput.value = attachment.id;
			preview.src = imageUrl;
			preview.style.display = 'block';

			if ( removeButton ) {
				removeButton.style.display = '';
			}
		} );

		frame.open();
	} );

	if ( removeButton ) {
		removeButton.addEventListener( 'click', function ( event ) {
			event.preventDefault();

			hiddenInput.value = '';
			preview.src = '';
			preview.style.display = 'none';
			removeButton.style.display = 'none';
		} );
	}
} )();