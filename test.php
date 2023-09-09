<!DOCTYPE html>
<html>
<head>
  <title>Comment Box</title>

  <style>
    form {
      width: 400px;
      margin: 0 auto;
      background-color: #f5f5f5;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    label {
      font-weight: bold;
      margin-bottom: 5px;
      text-align: left;
      font-family: Arial;
    }

    input[type='text'],
    textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 3px;
      border: 1px solid #ccc;
      min-height: 50px;
      resize: none;
      box-sizing: border-box;
      overflow: hidden;
    }

    input[type='submit'] {
      width: 100%;
      padding: 10px;
      border: none;
      cursor: pointer;
      background-color: #194360;
      border-radius: 10px;
      color: #E7F5FE;
      background: linear-gradient(180deg, #32546D 0%, #194360 100%);
      font-family: Arial;
    }

    input[type='submit']:hover {
      background: linear-gradient(180deg, #32546D 0%, #194360 100%);
      border-radius: 10px;
      transform: translateY(-1px);
    }

    input[type='submit']:active {
      background: linear-gradient(180deg, #194360 0%, #32546D 100%);
      border-radius: 10px;
      transform: translateY(0);
    }
  </style>

</head>

  
  
  
<?php
  echo <<<EOT


  <body>
  <form id='commentForm'>
    <label for='name'>Name:</label>
    <input type='text' id='name' required><br><br>
    <label for='message'>Message:</label>
    <textarea id='message' required></textarea><br><br>
    <input type='submit' value='Submit'>
  </form>


  <script>
    // JavaScript code for handling form submission and updating comments section
    document.getElementById("message").addEventListener("input", function() {
      this.style.height = "auto"; // Reset the height to auto
      this.style.height = this.scrollHeight + "px"; // Set the height to fit the content
    });


    document.getElementById('commentForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Prevent the default form submission behavior

      // Get the values entered by the user
      var name = document.getElementById('name').value;
      var message = document.getElementById('message').value;

      // Clear the form fields
      document.getElementById('name').value = '';
      document.getElementById('message').value = '';

      // Create a new XMLHttpRequest object
      var xhr = new XMLHttpRequest();

      // Prepare the request
      xhr.open('POST', 'save_comment.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      // Define the data to be sent
      var data = 'name=' + encodeURIComponent(name) + '&message=' + encodeURIComponent(message);

      // Handle the request response
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            console.log('Comment saved successfully.');
          } else {
            console.error('Error saving comment:', xhr.status);
          }
        }
      };

      // Send the request
      xhr.send(data);
    });
  </script>

  EOT;
?>



<?php
// Define the path to the comments folder
$folder = "blog/data/2023/7/9/comments/";

// Get an array of comment file names in ascending order
$commentFiles = glob($folder . "*.txt");
sort($commentFiles);

// Display the comments
foreach ($commentFiles as $commentFile) {
  // Read the comment file content
  $commentContent = file_get_contents($commentFile);

  // Split the comment content into name and message
  [$name, $message] = explode(PHP_EOL, $commentContent, 2);

  // Display the comment
  echo "<strong>Name:</strong> $name<br>";
  echo "<strong>Message:</strong> $message<br><br>";
}
?>





</body>
</html>
