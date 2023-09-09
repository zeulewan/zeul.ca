<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<script 
		src="../../script.js" defer>

	</script>
	<link href="../../styles.css" rel="stylesheet">
 	<title>Zeul's Website!</title>

</head>
<body>

    <?php
	if (isset($_GET['day']) && isset($_GET['month']) && isset($_GET['year']) && isset($_GET['title'])) {
		// Sanitize the 'month' parameter
		$month = filter_input(INPUT_GET, 'month', FILTER_SANITIZE_STRING);
		if ($month === false || $month === null) {
			// Handle the case when 'month' is missing or invalid
			$month = '1'; // Provide a default value or perform appropriate error handling
		}
	
		// Sanitize the 'year' parameter as an integer
		$year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT);
		if ($year === false || $year === null || $year <= 0) {
			// Handle the case when 'year' is missing, invalid, or non-positive
			$year = '2025'; // Provide a default value or perform appropriate error handling
		}
	
		$day = filter_input(INPUT_GET, 'day', FILTER_VALIDATE_INT);
		if ($day === false || $day === null || $day <= 0) {
			// Handle the case when 'day' is missing, invalid, or non-positive
			$day = '1'; // Provide a default value or perform appropriate error handling
		}
	
		$title = filter_input(INPUT_GET, 'title', FILTER_SANITIZE_STRING);
		if ($title === false || $title === null) {
			// Handle the case when 'title' is missing or invalid
			$title = "Not working"; // Provide a default value or perform appropriate error handling
		}
	
	} else {
		// Handle the case when either 'day', 'month', 'year', or 'title' parameters are not provided
		$day = '1';
		$month = '1';
		$year = '2022';
		$title = "Variables not passed properly";
	}
	


/*
        $year = basename(dirname(__FILE__ , 3));
        $month = basename(dirname(__FILE__ , 2));   
        $day  = basename(dirname(__FILE__ , 1)); 
        $title = basename(__FILE__, ".php");
*/

        $f = fopen("../data/$year/$month/$day/$title.txt", 'r');
        $line = fgets($f);
        fclose($f);
        


    ?>


	<div class="banner">
	<br>
          <h1><?php echo $line?></h1>
		  <br>
	</div>
	
   
	<div class="center">

	<button class='button-10' role="button" onclick="location.href='../../'">Go Back Home</button>
	</div>
	<br><br>
    <div class="tab-content">
    		
		
            <font face="Arial" color="#E7F5FE">

<?php

									echo ("<div id='blog'> <p>");

									$date = date_create("$year-$month-$day");
									$test= date_format($date,"F jS Y");
									echo "<b>$test</b>";

									echo ("</p> <p>");
									
									$pagecontents = file_get_contents("../data/$year/$month/$day/$title.txt");
									$newpc = str_replace("$line", "", $pagecontents);	
                                    //echos the blog post with applied formatting
									echo replaceImageKeywords($pagecontents, $year, $month, $day);
		
									echo ("</p> </div> <br>");

									function replaceImageKeywords($text, $year, $month, $day) {
									// Replace any occurrences of [word].[extension] with the image URL format
									$text = preg_replace('/([a-zA-Z0-9]+)\.(jpg|jpeg|png|gif)/i', "</p><img src='../data/$year/$month/$day/$1.$2'><p>", $text);
								
									// Return the updated text
									return $text;
							 		 }			
	
?>


</div>
			</font>

</body>
</html>
