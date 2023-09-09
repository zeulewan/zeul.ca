<?php
// Get the submitted data
$name = sanitizeInput($_POST['name']);
$message = sanitizeInput($_POST['message']);


// Define the path to the comments folder
$folder = "blog/data/2023/7/9/comments/";


// Get the total number of existing comment files
$commentCount = count(glob($folder . "*.txt"));

// Check if the comment count is within the limit (e.g., 10 comments)
if ($commentCount < 10) {
  // Increment the comment count by 1 to get the new comment file name
  $newCommentCount = $commentCount + 1;

  // Create the comment file name
  $fileName = $folder . $newCommentCount . ".txt";

  // Prepare the comment content
  $commentContent = $name . PHP_EOL . $message;

  // Save the comment to a file
  file_put_contents($fileName, $commentContent);
}
?>
