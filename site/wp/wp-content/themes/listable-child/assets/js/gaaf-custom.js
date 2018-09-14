

function wnw_set_google_autocomplete(){
	jQuery('#job_location,#_job_location').each(function(){
											  
		console.log((this));
		var autocomplete= new google.maps.places.Autocomplete(
		/** @type {HTMLInputElement} */(this),
		{ types: ['geocode'] });
		// When the user selects an address from the dropdown,
		// populate the address fields in the form.
                google.maps.event.addListener(autocomplete, 'place_changed', function() {
                        var place = autocomplete.getPlace();
                        alert('<div><strong>' + place.name + '</strong><br>' + "<br>" + place.geometry.location);
                        document.cookie = "job_location_lat="+place.geometry.location.lat();
                        document.cookie = "job_location_lon="+place.geometry.location.lng();
                });
	});
}
jQuery(window).load(function(){
	wnw_set_google_autocomplete();
});
