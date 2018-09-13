if(document.querySelector('#_job_location') != null) {
  $('#_job_location').attr("autocomplete", "true");
  var placesAutocomplete = places({
    container: document.querySelector('#_job_location'),
    language: 'en_US'
  });
}
if(document.querySelector('#job_location') != null) {
  var placesAutocomplete = places({
    container: document.querySelector('#job_location'),
    language: 'en_US'
  });
}
