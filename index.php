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
	<title>zeul.ca</title>
</head>
<body>
	
	<!-- Banner with Navigation Menu -->
	<div class="banner">
		<br>
		<h1>ZEUL</h1>

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
			<li data-tab-target="#project" class="tab"><font color ="FFFFFF">Projects</font></li>
			<li data-tab-target="#home" class="<?php if($c == 1){ echo "active"; } ?> tab"><font color ="FFFFFF">Blog</font></li>
			<li data-tab-target="#links" class="tab"><font color="FFFFFF">Links</font></li>	
  		</ul>
	</div>

	 	<!-- About Me Section -->
	 	<div id="something" data-tab-content class=<?php if($c == 0){ echo "active"; } ?> >	

		<br>
			<div id="box">
			<br>
     			Hi, my name is Zeul, and this is my website. This
				website is a place to put random things that I do. 
				<a href="https://github.com/zeulewan/zeulewan.com"><font color="#1187FC">github.</font></a>
				I host this on a raspberry pi in my basmenet.
				<br><br>

				<!--<a href="blog/data/2022/8/27/1.jpeg"></a>-->
				<img src='media/portrait.jpg'>
				<br>

			</div>	
		</div>

		<!-- Blog Section -->
    	<div id="home" data-tab-content class=<?php if($c == 1){ echo "active"; } ?>>
		
			<h1></h1>
				
			<!-- Button to view blog archives -->
			<button class="button-10" role="button" onclick="location.href='blogarchive.php'" id="home">Archive</button>
			<br>

			<?php 
				// Loop through blog posts dynamically
				for ($year=date("Y"); $year>=2022; $year--) {
					for ($month=12; $month>=1; $month--) {
						for ($day=31; $day>= 1; $day--) {		
							$pagecontents = file_get_contents("blog/data/$year/$month/$day/$day.txt");
							if ($pagecontents!=null) {
								echo ("<br><div id='box'> <p>"); 
								
								//displays the date
								echo "<b>" . date_format(date_create("$year-$month-$day"), "F jS Y") . "</b>";
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
									echo "</p> Max number of comments reached on this post<p>";
								} else {
									// Display the comment form
									echo "
											
											<form method=post>

											<div class='commenttext'>
												<b>Comment:</b><br>
											</div>

											<div class='commentinput'>
												<input type=text name='name' placeholder='Name (max 30 chars)' required><br>
												<input type=text name='message' placeholder='Message (max 300 chars)' required><br>
												<input type=text name='human' placeholder='What is $month+$day?' required><br>

											</div>
									
												<input type=hidden name=day value=${day}>
												<input type=hidden name=month value=${month}>
												<input type=hidden name=year value=${year}>
												<input type=hidden name=time value=${time}>

												<input type=submit value=Submit>
												

											</form>		
										";	

								}
								

								// Loop through and display comments
								for ($cmnt=1; $cmnt<=10; $cmnt++) {
									if (!file_exists("blog/data/$year/$month/$day/comments/$cmnt.txt")) {
										break;
									} 

										$file = fopen("blog/data/$year/$month/$day/comments/$cmnt.txt", 'rb');

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

								if ($fileCount < 10) {
									if ($_SERVER['REQUEST_METHOD'] === 'POST') {
										// Save new comments when submitted 
										$mess = $_POST['message']; // These
										$pattern = "/http|www|@|buy|promote|promotion|.com/i"; 
										// lines get rid of scammers, blocks shit
										$name = $_POST['name'];
										$human = $_POST['human'];

										$max_message_length = 300;
										$max_name_length = 30;
										$expected_answer = $month+$day;


										if (
											preg_match($pattern, $mess) == 0 && // Check if $mess matches pattern
											strlen($mess) <= $max_message_length && // Check if message length is within limit
											strlen($name) <= $max_name_length && // Check if name length is within limit
											intval($human) === $expected_answer // Check if the captcha answer is correct
										) {
											$d = htmlspecialchars($_POST['day']);
											$m = htmlspecialchars($_POST['month']);
											$y = htmlspecialchars($_POST['year']);

											if ($d==$day && $m==$month && $y==$year) {

												$texts = array(htmlspecialchars($_POST['name']), htmlspecialchars($_POST['message']), date("Y-m-d H:i:s"));
												implode('<br>', $texts);
												file_put_contents("blog/data/$year/$month/$day/comments/$cmnt.txt", implode(PHP_EOL, $texts));
												$file = fopen("blog/data/$year/$month/$day/comments/$cmnt.txt", 'rb');
												
												echo "<p class='commenttext '>";
										
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

												shell_exec("chmod 770 $file");

											}	
										}	
									}
								}

								
								echo ("</div> ");
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
		<div id="project" data-tab-content >	
		
			
			<?php 
				// Loop through projects dynamically
				for ($year=date("Y"); $year>=2019; $year--) {
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
										$title_text = str_replace('_', ' ', $bname);

										// Check if the thumbnail exists for the project, display accordingly
										if (file_exists("projects/data/$year/$month/$day/thumbnail.jpg")) {
											echo("<button class='box' onclick= \"location.href='projects/archive/template.php?day=$day&month=$month&year=$year&title=$bname'\">   
											<img src='projects/data/$year/$month/$day/thumbnail.jpg' class='thumbnail'><br>$title_text<br>$date</button> ");
										} else {
											echo("<button class='box' onclick= \"location.href='projects/archive/template.php?day=$day&month=$month&year=$year&title=$bname'\">$title_text<br>$date</button> ");
										}
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
		   <br>
		   <div id="box">
		   		<br>
				<!-- Links to external resources -->
				<b>My music:</b> <br>
				<a href="https://linktr.ee/zeul"><font color="#1187FC">Linktree</font></a>
				<br>	
				<br>		
			
				<b>Download my music</b>
				<br>
				<a href="media/Zeul.zip" download>
   				<button class='button-10' type="button">320kbps MP3s</button></a> <br>
				<br>

				<b>Youtube</b> <br>
				<a href="https://www.youtube.com/zeulewan"><font color="#1187FC">youtube.com/zeulewan</font></a>
				<br>	
				<br>		

				<b>LinkedIn:</b> <br>
				<a href="https://www.linkedin.com/in/zeul-mordasiewicz-8133561b9/"><font color="#1187FC">LinkedIn</font></a>
				<br>	
				<br>

				<b>GitHub:</b> <br>
				<a href="https://github.com/zeulewan"><font color="#1187FC">GitHub</font></a>
				<br>
				<br>

				<a href="https://arrayinamatrix.xyz"><img src="https://arrayinamatrix.xyz/res/site/banners/custom/white_176x62.png"></a>

				<a href="https://zeul.ca"><img src="https://zeul.ca/media/zeul_88x31.png"></a>				
				<br>
			</div>
    	</div>
		<br>

</body>
</html>