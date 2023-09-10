<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="description" content="This is Zeul's website.">
	<meta name="author" content="Zeulewan Mordasiewicz">

	<!-- JavaScript file -->
	<script src="script.js" defer></script>

	<!-- Stylesheet -->
	<link href="styles.css" rel="stylesheet">
	<title>Zeul's Website!</title>
</head>
<body>
	
	<!-- Banner with Navigation Menu -->
	<div class="banner">
		<br>
		<h1>zeulewan.com</h1>

		<?php 
			// Checks if the request method is POST and sets a variable to indicate the default tab
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$c = '1'; // Set default tab to "Blog"
			} else {
				$c = '0'; // Set default tab to "Home"
			}
		?>
	
		<!-- Navigation Menu -->
		<ul class="header"> 
			<li data-tab-target="#something" class="<?php if($c == 0){ echo "active"; } ?> tab"><font color="FFFFFF">Home</font></li>
			<li data-tab-target="#projects" class="tab"><font color ="FFFFFF">Projects</font></li>
			<li data-tab-target="#home" class="<?php if($c == 1){ echo "active"; } ?> tab"><font color ="FFFFFF">Blog</font></li>
			<li data-tab-target="#links" class="tab"><font color="FFFFFF">Links</font></li>	
  		</ul>
	</div>

	<!-- Content Sections -->
  	<div class="tab-content">

	 	<!-- About Me Section -->
	 	<div id="something" data-tab-content class=<?php if($c == 0){ echo "active"; } ?> >	
      		<h1>About me</h1>

			<div id="otherformat">
     			Hi, my name is Zeul, and this is my website.
				I am an aerospace engineering student at TMU. This
				website is a place to put random things that I do.
				<br><br>
				<img src="media/portrait.jpg" style="max-width:600px;width:100%" alt="image not found">
			</div>	
		</div>

		<!-- Blog Section -->
    	<div id="home" data-tab-content class=<?php if($c == 1){ echo "active"; } ?>>
		
			<font face='Arial' color="#E7F5FE">
			<h1>My Blog!</h1>
				
			<!--<h4>This is my blog</h4>-->
				
			<!-- Button to view blog archives -->
			<div class="center">
				<button class="button-10" role="button" onclick="location.href='../../../blogarchive.php'" id="home"> blog archive</button>
			</div>
				
			<?php 
				// Loop through blog posts dynamically
				for ($year=date("Y"); $year>=2022; $year--) {
					for ($month=12; $month>=1; $month--) {
						for ($day=31; $day>= 1; $day--) {		
							$pagecontents = file_get_contents("blog/data/$year/$month/$day/$day.txt");
							if ($pagecontents!=null) {
								echo ("<br><div id='blog'> <p>"); 
								$date=date_create("$year-$month-$day");
								$test= date_format($date,"F jS Y");
								echo "<b>$test</b>";
								echo ("</p> <p>");
								
								// Function to replace image keywords with actual image tags
								echo replaceImageKeywords($pagecontents, $year, $month, $day);
								echo '<hr>';

								// Check and create comments directory if not exists
								if (!is_dir("blog/data/$year/$month/$day/comments")) {
									mkdir("blog/data/$year/$month/$day/comments");
								}

								// Check if the maximum number of comments is reached, show comment form accordingly
								$directory = "blog/data/$year/$month/$day/comments";
								$files = scandir($directory);
								$fileCount = 0;
								foreach ($files as $file) {
									$filePath = $directory . '/' . $file;
									if (is_file($filePath)) {
										$fileCount++;
									}
								}
								
								if ($fileCount >= 10) {
									echo "</p>Max number of comments reached. <p>";
								} else {
									// Display the comment form
									echo "<div class='comment'>
										<form method=post>
											Comment: <br>
											<input type=text name='name' placeholder='Name' required><br>
											<input type=text name='message' placeholder='Message' required><br>
											<input type=hidden name=day value=${day}>
											<input type=hidden name=month value=${month}>
											<input type=hidden name=year value=${year}>
											<input type=hidden name=time value=${time}>
											<input type=submit value=Submit>
										</form>		
									</div>";	
									
								}

								// Loop through and display comments
								for ($cmnt=1; $cmnt<=10; $cmnt++) {
									if (!file_exists("blog/data/$year/$month/$day/comments/$cmnt.txt")) {
										break;
									} 
									$comment = file_get_contents("blog/data/$year/$month/$day/comments/$cmnt.txt");
									echo "</p>";
									$fh = fopen("blog/data/$year/$month/$day/comments/$cmnt.txt", 'r');
									$pageText = fread($fh, 25000);
									echo nl2br($pageText);
									echo "<br>";
									echo date ("F jS Y g:i A", filemtime("blog/data/$year/$month/$day/comments/$cmnt.txt"));
									echo "<p>";
								}

								// Save new comments when submitted 
								$mess = $_POST['message'];
								$pattern = "/http|www/i";
								if (preg_match($pattern, $mess)==0) {
									$d = htmlspecialchars($_POST['day']);
									$m = htmlspecialchars($_POST['month']);
									$y = htmlspecialchars($_POST['year']);
									if ($d==$day && $m==$month && $y==$year) {
										$texts = array(htmlspecialchars($_POST['name']), htmlspecialchars($_POST['message']));
										echo '<p>';
										echo implode('<br>', $texts);
										file_put_contents("blog/data/$year/$month/$day/comments/$cmnt.txt", implode(PHP_EOL, $texts));
										echo '<br>';
										echo date ("F jS Y g:i A", filemtime("blog/data/$year/$month/$day/comments/$cmnt.txt"));
									}	
								}	
								echo ("</p> </div> ");
								$post_counter++;
								if ($post_counter>=10) {
									break 3;
								}
							}
						}
					}
				}

				// Function to replace image keywords with actual image tags
				function replaceImageKeywords($text, $year, $month, $day) {
					// Replace any occurrences of [word].[extension] with the image URL format
					$text = preg_replace('/([a-zA-Z0-9]+)\.(jpg|jpeg|png|gif)/i', "</p><img src='blog/data/$year/$month/$day/$1.$2'><p>", $text);
					
					// Return the updated text
					return $text;
				}
			?>

		</div>

		<!-- Projects Section -->
		<div id="projects" data-tab-content >	
      		<h1>Projects</h1>
			<?php 
				// Loop through projects dynamically
				for ($year=date("Y"); $year>=2021; $year--) {
					if (is_dir("projects/data/$year")) {
						for ($month=12; $month>=1; $month--) {
							if (is_dir("projects/data/$year/$month")) {
								for ($day=31; $day>= 1; $day--) {
									if (is_dir("projects/data/$year/$month/$day")) {
										foreach(glob("projects/data/$year/$month/$day/*.txt") as $file) {	
											$bname = null;
											$bname = basename($file, ".txt");
										}

										$date = date_format(date_create("$year-$month-$day"), "F jS Y");
										
										$f = fopen("projects/data/$year/$month/$day/$bname.txt", 'r');
										$line1 = fgets($f);
										fclose($f);

										// Check if the thumbnail exists for the project, display accordingly
										if (file_exists("projects/data/$year/$month/$day/thumbnail.jpg")) {
											echo("<button class='button-10' onclick= \"location.href='projects/archive/template.php?day=$day&month=$month&year=$year&title=$bname'\">   
											<img src='projects/data/$year/$month/$day/thumbnail.jpg' class='thumbnail'><br>$line1 <br>$date</button> ");
										} else {
											echo("<button class='button-10' onclick= \"location.href='projects/archive/template.php?day=$day&month=$month&year=$year&title=$bname'\"> $line1 <br>$date</button> ");
										}
										echo("<br> <br>");
									}
								}
							}	
						}
					}				
				}
			?>
		</div>

   		<!-- Links Section -->
   		<div id="links" data-tab-content>
 			<h1>Links</h1>
			
			<div id="otherformat">
				<!-- Links to external resources -->
				<b>Stream my music:</b> <br>
				<a href="https://linktr.ee/zeul"><font color="#1187FC">Linktree</font></a>
				<br>	
				<br>		
			
				<b>Download my Music</b>
				<br>
				<a href="media/Zeul.zip" download>
   				<button class='button-10' type="button">320kbps MP3s</button></a> <br>
				<br>

				<b>Youtube Channel:</b> <br>
				<a href="https://www.youtube.com/zeulewan"><font color="#1187FC">youtube.com/zeulewan</font></a>
				<br>	
				<br>		

				<b>My LinkedIn:</b> <br>
				<a href="https://www.linkedin.com/in/zeul-mordasiewicz-8133561b9/"><font color="#1187FC">LinkedIn</font></a>
				<br>	
				<br>

				<b>My GitHub:</b> <br>
				<a href="https://github.com/Zeulewan"><font color="#1187FC">GitHub</font></a>
			</div>
    	</div>
		<br>
  	</div>
</body>
</html>
