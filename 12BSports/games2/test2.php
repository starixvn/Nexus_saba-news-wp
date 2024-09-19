<?php
//868206
$id = $_REQUEST['id'];
$host_url = "api-football-v1.p.rapidapi.com";
$api_key = "b93c579eb5msh3a862c325b248f4p197710jsn22158d3cbd15";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CUSTOM WIDGET</title>
    </head>
    <body>

				
				
				<div id="wg-api-football-game"
					data-host="<?php echo $host_url?>"
					data-key="<?php echo $api_key?>"
					data-id="<?php echo $id?>"
					data-theme=""
					data-refresh="90"
					data-show-errors="false"
					data-show-logos="true">
				</div>
				<script
					type="module"
					src="https://widgets.api-sports.io/2.0.3/widgets.js">
				</script>
    </body>
</html>