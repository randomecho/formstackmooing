<!DOCTYPE html><html><head><meta charset="utf-8">
<title>Formstack Mooing</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Formstack to Moo cards">
<meta name="author" content="Soon Van - randomecho.com">
<style>
body, h1, h2, h3, p, ul, ol {
  margin: 0;
  padding: 0;
}

body {
  background: #fff;
  color: #000;
  font-size: 0.9em;
  font-family: sans-serif;
}

a {
  text-decoration: none;
}

h1, h2, h3, p {
  margin: 0 0 1em 0;
}

h1 {
  font-size: 1.5em;
}

h2 {
  font-size: 1.3em;
}

ul {
  margin: 0 0 1em 2em;
}

li {
  margin: 0 0 0.5em 0;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
}

.infobox {
  border: 1px solid #555;
  border-radius: 5px;
  margin: 1em auto;
  padding: 1em;
  width: 500px;
}

.ghost {
  display: none;
}
</style>
</head><body>

<div class="infobox">
<span id="people-count">0</span> people have registered
<button id="check-register">Check for registrations</button>
</div>

<div id="namebox" class="infobox ghost">
<button id="show-names">Show names</button>
</div>

<div id="imagebox" class="infobox">
<form action="./index.php" method="post" enctype="multipart/form-data">
<input type="file" >
<input type="submit" value="Upload image and send to Moo">
</form>
</div>

<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script>
$(function(){
  var moostack_uri = './formstackmoo.php';

  // Call Formstack API to show how many submissions have been captured
  $('#check-register').click(function(){
    $('#check-register').text('Checking...');
    $.get(moostack_uri, {get_count: 1}, function(data){
      var count_registered = parseInt(data);
      $('#check-register').text('Check for registrations');

      // If we have registrations, offer to show names of those attending
      if (count_registered > 0)
      {
        $('#people-count').text(count_registered);
        $('#check-register').remove();
        $('#namebox').fadeIn();
      }
    });
  });

  // Fetch and display list of attendees
  $('#show-names').click(function(){
    $('#namebox').text('Loading...');
    $.get(moostack_uri, {get_names: 1}, function(data){
      $('#namebox').html(data);
    });
  });

});
</script>
</body></html>