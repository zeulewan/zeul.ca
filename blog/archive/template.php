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
	if (isset($_GET['month']) && isset($_GET['year'])) {
		// Sanitize the 'month' parameter
		$month = filter_input(INPUT_GET, 'month', FILTER_SANITIZE_STRING);
		if ($month === false || $month === null) {
			// Handle the case when 'month' is missing or invalid
			$month = '1'; // Provide a default value or an appropriate error handling
		}

		// Sanitize the 'year' parameter as an integer
		$year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT);
		if ($year === false || $year === null || $year <= 0) {
			// Handle the case when 'year' is missing, invalid, or non-positive
			$year = 2022; // Provide the current year as a default value or perform appropriate error handling
		}
	} else {
		// Handle the case when either 'month' or 'year' parameters are not provided
		$month = '1';
		$year = 2022; 
	}
	$date = date_create("$year-$month-1");
	$monthtxt = date_format($date,"F");
	?>



	<div class="banner">
	<br>
  		<h1><?php echo "$monthtxt $year"; ?></h1>
		  <br>
	</div>
	<br>
	<div class="center">
    <button class="button-10" onclick="location.href='../../blogarchive.php'"     id="home"  >   Archive Menu    </button>
	</div>

  	<div class="tab-content">

				<br>
				<?php 
							for ($day=1; $day<= 31; $day++) 
							{		
								$pagecontents = file_get_contents("../data/$year/$month/$day/$day.txt");
								if ($pagecontents!=null)
								{
									echo ("<div id='box'> <p>");

									$date = date_create("$year-$month-$day");
									$test= date_format($date,"F jS Y");
									echo "<b>$test</b>";
									echo "<br><br>";
									//echos the blog post with applied formatting
									echo replaceImageKeywords($pagecontents, $year, $month, $day);

									

									/*$folderPath = "../data/$year/$month/$day/comments/"; 
									$files = array_diff(scandir($folderPath), array('.', '..'));

									if (count($files) > 0) {
										echo '<hr>';
									}*/

									

										$commentspath = "../data/$year/$month/$day/comments/";

										if (file_exists($commentspath) && count(glob("$commentspath/*")) > 0) {
											echo "
												<hr>
												<div class='commenttext'>
													<b>Comments:</b><br>
												</div>
											";
										}	

										for ($cmnt=1; $cmnt<=10; $cmnt++)
										{
											if (file_exists("$commentspath/$cmnt.txt"))
											{
												
											$file = fopen("$commentspath/$cmnt.txt", 'rb');

											// Read and print the first line
											echo "<p class='commenttext'>";

											$line1 = fgets($file);
											
											echo "<b>";
											echo $line1;
											echo "</b>";
											echo "<br>";
										
											// Read and print the second line
											$line2 = fgets($file);
											echo $line2;
											echo "</p>";

											// Close the file
											fclose($file);

											}
										}

										echo ("</div><br>");
									
								}
							}
				
							function replaceImageKeywords($text, $year, $month, $day) {
								// Replace any occurrences of [word].[extension] with the image URL format
								$text = preg_replace('/([a-zA-Z0-9]+)\.(jpg|jpeg|png|gif)/i', "</p><img src='../data/$year/$month/$day/$1.$2'><p>", $text);
								
								// Return the updated text
								return $text;
							  }	
				?>
			</div>
			</div>
  	</div>
</body>
</html>