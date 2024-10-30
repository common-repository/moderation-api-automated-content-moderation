(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	
	$( window ).load(function() {
	 
		/**
		 * Shows the Enter API key form
		 */
		$( '.modapi-enter-api-key-box a' ).on( 'click', function ( e ) {

			e.preventDefault();

			var div = $( '.enter-api-key' );
			div.show( 500 );
			div.find( 'input[name=key]' ).focus();

			$( this ).hide();
		} );

	});
	
})( jQuery );
