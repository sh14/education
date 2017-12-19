
(function () {

	var your_message = document.getElementById("your_massage");
	var btn_message = document.querySelector('[data-event="btn_message"]');
	var results_message = document.getElementById("results_message");
	var p = document.createElement('p');
	var flag = 0;
	var now = new Date;

	btn_message.addEventListener("click", cl);


//ниже функция нажатия на клавишу и вызова функций
	function cl(event) {
		event.preventDefault();
		if (flag == 0) {
			flag = 1;
			show_message();
		}
		return flag = 0;
	}

//ниже функция отправки и получения сообщения в поле
	function show_message() {
		if (flag == 1 && your_message.value != "") {
			var vall = document.getElementById("your_massage").value;
			var template = tmpl( jQuery( '#message-template').html(), {
				'image':'1',
				'name':'2',
				'title':'3',
				'content':'4',
				'datetime':'5',
				'class':'6',
				'ID':'7',
				'id_message':'8',
			} );

			jQuery('#results_message').append(template);
		}
		your_message.value = "";


		var mes = $('#message').serialize();
		$.ajax({
			type: 'POST',
			url: 'index.php',
			data: mes && "event=mes_complited",
			success: function (data) {
				// $('#results_message').append(data);
				console.log(1);
			},
			error: function (xhr, str) {
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});
	}




	var cache = {};

	this.tmpl = function tmpl( str, data ) {
		// Figure out if we're getting a template, or if we need to
		// load the template - and be sure to cache the result.
		var fn = !/\W/.test( str ) ?
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
})();

/* //ниже ajax запрос на сообщения из сервера
	 function see_message() {
		 if(your_message.value != "") {
			 var mes = $('#message').serialize();
			 $.ajax({
				 type: 'POST',
				 url: 'index.php',
				 data: mes,
				 success: function (data) {
					 $('#results_message').append(data);
				 },
				 error: function (xhr, str) {
					 alert('Возникла ошибка: ' + xhr.responseCode);
				 }
			 });
		 }
	 }
 */
