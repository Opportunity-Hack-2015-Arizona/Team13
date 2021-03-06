$(function() {
	var access_token = $.cookie("token");

	getName();
    getEvents();

    $.validate({
        modules : 'location, date',
        onSuccess : function() {
          addEvent();
          return false; // Will stop the submission of the form
        }
    });

    $("#signOut").click(signOut);

    function getName() {
    	$.ajax({
	        type: 'GET',
	        url: '../server/index.php/host',
	        dataType: 'JSON',
	        headers: {
		    "Authorization": "Bearer " + access_token
		  	},
	        success: function(json) {
	            if(json.success != false) {
	            	console.log(json);

	            	$.each($('span#fullname'), function() {
					    $(this).html(json.host.firstname + ' ' + json.host.lastname);
					});

	            }
	        }
	    });
    }

    function getEvents() {
    	$.ajax({
	        type: 'GET',
	        url: '../server/index.php/event/host',
	        dataType: 'JSON',
	        headers: {
		    "Authorization": "Bearer " + access_token
		  	},
	        success: function(json) {
	            if(json.success != false) {
	            	console.log('success');
	            	var template = $('#eventtemplate').html();
			        Mustache.parse(template);   // optional, speeds up future uses
			        var rendered = Mustache.render(template, json);
			        $('#events').html(rendered);
	            }
	        }
	    });
    }

    function addEvent() {
    	console.log('adding');

    	var data = $('form').serializeArray();
    	console.log(data);

    	$.ajax({
	        type: 'POST',
	        url: '../server/index.php/event/',
	        data: data,
	        dataType: 'JSON',
	        headers: {
		    "Authorization": "Bearer " + access_token
		  	},
	        success: function(json) {
	            console.log(json);
	            if(json.success == true) {
	                $('#success').modal('show');
	                setTimeout(function(){
					   window.location.reload(1);
					}, 3000);
	            } else {
	                $('#fail').modal('show');
	            }
	                
	            
	        }
	    });
    }

    function signOut() {
    	window.location.href = "../";
    }
});