// Simple JavaScript Templating
// John Resig - http://ejohn.org/ - MIT Licensed
(function ( $ ) {
	"use strict";

	/*
	let cache = {};

	this.tmpl = function tmpl( str, data ) {
		// Figure out if we're getting a template, or if we need to
		// load the template - and be sure to cache the result.
		let fn = !/\W/.test( str ) ?
			cache[ str ] = cache[ str ] ||
				tmpl( document.getElementById( str ).innerHTML ) :

			// Generate a reusable function that will serve as a template
			// generator (and which will be cached).
			new Function( "obj",
				"var p=[],print=function(){p.push.apply(p,arguments);};" +

				// Introduce the data as local variables using with(){}
				"with(obj){p.push('" +

				// Convert the template into pure JavaScript
				str
					.replace( /[\r\t\n]/g, " " )
					.split( "<%" ).join( "\t" )
					.replace( /((^|%>)[^\t]*)'/g, "$1\r" )
					.replace( /\t=(.*?)%>/g, "',$1,'" )
					.split( "\t" ).join( "');" )
					.split( "%>" ).join( "p.push('" )
					.split( "\r" ).join( "\\'" )
				+ "');}return p.join('');" );
		// Provide some basic currying to the user
		return data ? fn( data ) : fn;
	};
*/
	function chat_auto_height() {
		let height = parseInt( $( window ).height() ) - 20;
		console.log(height);
		$( '.js-chat' ).height( height );
	}

	chat_auto_height();

	$( window ).on( 'resize', function () {
		chat_auto_height();
	} );


	/*Код жулинского для всплывающего окна*/


		function Show(){
			document.querySelector('.massive').classList.add("left");
			document.querySelector('#mex').classList.add("active");
		}
		function Hide(){
			document.querySelector('.massive').classList.remove("left");
			document.querySelector('#mex').classList.remove("active");
		}
		document.querySelector('#mex').onclick = function(){
			if(document.querySelector('.massive').classList.contains("left")){
				Hide();
			} else {
				Show();
			}
		}


	/*Конец кода Жулинского*/

})( jQuery );
