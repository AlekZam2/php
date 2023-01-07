<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link to the stylesheet for the header -->
    <link rel="stylesheet" href="style.css">
    <title>Assigment 3 page 2</title>
</head>
<body>
  <header>
    <div class="head">
      <!-- Page emblem -->
      <div class="emblem">
        <div>
          <!-- Image for the emblem -->
          <img src="1.png">
        </div>
      </div>
      <nav>
        <!-- When you press the button you are navigated back to the first page -->
        <li><span><a href="Page 1 Final.php" class="button">Back to Top 10</a></span></li>
      </nav>
    </div>
  </header>
           
    <?php
    // Connection details
    $db_host = "localhost";
    $db_user = "u2180833";
    $db_pass = "AZ11nov91az";
    $db_name = "u2180833";
    // Connect to the database
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    // Check for connection errors. And this way if we have an error we will get explanation on what the error is instead just the message printed
    if(mysqli_connect_error()) {
     die("Connection failed: " . mysqli_connect_error());
    }

      // Select the owner's name, email, address, and phone number from the 'owners' table
      $sql = "SELECT o.name AS 'name', o.email AS 'owner_email', o.address AS 'owner_address', o.phone AS 'owner_phone'
      FROM owners o
      WHERE o.name = '" . $_GET['owner_name'] . "'";
      // Execute the quer
      $result = $conn->query($sql);
      // If the owner's name was not transferred or is empty, display an error message and terminate the scrip
      if (!isset($_GET['owner_name']) || empty($_GET['owner_name'])) {
        die("No information");
      }
      // If the query returned at least one row
      if ($result->num_rows > 0) {
        // Create an array to store the owner's information
        $owner_info = array();
        //Fetch the owner's information for each row in the result set
        while($row = $result->fetch_assoc()){
          $owner_info[] = array(
            "name" => $row['name'],
            "email" => $row['owner_email'],
            "address" => $row['owner_address'],
            "phone" => $row['owner_phone']
        );
      }
      echo "<h4>Owner Information </h4>";
      //Create a table to display the owner's personal information
      echo "<table class='table'>";
      echo "<tr>";
      echo "<th>Name</th>";
      echo "<th>Email</th>";
      echo "<th>Address</th>";
      echo "<th>Phone Number</th>";
      echo "</tr>";
      // Output the owner's information for each element in the array
      foreach ($owner_info as $info) {
        echo "<tr>";
        echo "<td>" . $info['name'] . "</td>";
        // Create a link to the owner's email address, which will open the user's default email client with the email address pre-populated in the "To" field
        echo "<td><a href='mailto:" . $info['email'] . "'>" . $info['email'] . "</a></td>";
        // Create a link to Google Maps with the owner's address as the search query
        echo "<td><a href='http://maps.google.co.uk/?q=" . $info['address'] . "'>" . $info['address'] . "</a></td>";
        echo "<td>" . $info['phone'] . "</td>";
        echo "</tr>";
      }
      // Close the table
      echo "</table>";
    } else {
      // If no rows were returned, display a message indicating that the owner was not found
      echo "<p>Not found.</p>";
    }
    // Close the database connection
    $conn->close();
    ?>
       
  </body>
</html>
