<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="description" content="This is Zeul's website.">
	
	<meta name="author" content="Zeulewan Mordasiewicz">

	<script 
		src="script.js" defer>

	</script>
	<link href="styles.css" rel="stylesheet">
 	<title>Zeul's Website!</title>

</head>
<body>

<div class="banner">
<h1><br>Blog Archive Menu<br><br></h1>
</div>
<div class="tab-content">

<div class="center">
<button class="button-10" role="button" onclick="location.href='../../../'"> go back home </button>

</div>
                        
                    <?php 	
                        for ($year=2022; $year<=date("Y"); $year++) 
                        {	
                            if (is_dir("blog/data/$year"))
                            {
                                echo ("<br><div id='blog'> <br>");
                                echo "<b>$year</b> <br><br>";
                                for ($month=1; $month<=12; $month++) 
                                {
                                    if (is_dir("blog/data/$year/$month"))
                                        {
                                            $date = date_create("$year-$month-1");
								            $monthtxt = date_format($date,"F");
                                            echo("<button class='button-10' onclick=\"location.href='blog/archive/template.php?month=$month&year=$year'\">$monthtxt</button> ");
                                        }
                                }
                                echo("<br> <br></div>");
                            }
                                
                        }
                        ?>
 </div>

</body>
</html>
