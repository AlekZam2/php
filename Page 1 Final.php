<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- This connects the CSS file with the PHP file so that when we make changes in the CSS we can see them in the php -->
    <link rel="stylesheet" href="style.css">    
    <title>Assigment 3</title>
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
        <!-- When we press the button we are loading the first page -->
        <li><span><a href="Page 1 Final.php" class="button">Refresh Top 10 List</a></span></li>
      </nav>
    </div>
  </header>      
    <?php
      // My personal connection details
       $db_host = "localhost";
       $db_user = "u2180833";
       $db_pass = "AZ11nov91az";
       $db_name = "u2180833";
       // Connect to the database
       $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
       // Check for connection errors. This way if we have an error we will get explanation on what the error is instead just the message printed
       if(mysqli_connect_error()) {
        die("Connection failed: " . mysqli_connect_error());
      }
        // Select the number of owners, dogs, and events
        $sql = "SELECT 
            (SELECT COUNT(id) FROM owners) AS num_owners, 
            (SELECT COUNT(id) FROM dogs) AS num_dogs, 
            (SELECT COUNT(id) FROM events) AS num_events";
        
        // Execute the query
        $result = $conn->query($sql);

        // Check for errors when executing the query
        if (!$result){
            die("Error: " . $conn->error);
        }
      
        // Initialize variables to store data from the database
        $numOwners = 0;
        $numDogs = 0;
        $numEvents = 0;
        
        // Fetch data from the database
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Store data in variables
                $numOwners = $row["num_owners"];
                $numDogs = $row["num_dogs"];
                $numEvents = $row["num_events"];
            }
        } else {
            // If there are no results, display a message
            echo "0 results";
        }
        // Welcome message with the number of owners, dogs, and events
        echo "<h1>Welcome to Poppleton Dog Show!</h1>";
        echo "<h2>This year $numOwners owners entered $numDogs dogs in $numEvents events.</h2>";
        // Display the top 10 dogs heading
        echo "<h3> Top 10 contestants in the shows</h3>";

        //Build the query
        $sql = "SELECT d.name AS dog_name, b.name AS breed_name, AVG(e.score) AS avg_score, o.name AS owner_name, o.email AS owner_email
        FROM dogs d
        
        INNER JOIN breeds b ON d.breed_id = b.id #Join tables using INNER JOIN
        INNER JOIN entries e ON d.id = e.dog_id
        INNER JOIN owners o ON d.owner_id = o.id 

        GROUP BY d.name, b.name, o.name, o.email #Group by multiple columns
        
        HAVING COUNT(e.id) >= 2 # Only include groups with at least 2 entries
        ORDER BY avg_score DESC # Order by average score in descending order
        
        LIMIT 10"; # Limit the number of results to 10

        // Execute the query
        $result = $conn->query($sql);

        // Check if there are any results
        if ($result->num_rows > 0) {
          // Create the table
          echo "<table class='table'>";
            // Create the table headers
        $headers = ["Dog Name", "Breed", "Average score", "Owners Name", "Email"];
        echo "<tr>";
        // Output each header in a table cell
        foreach ($headers as $header) {
          echo "<th>$header</th>";
        }
        echo "</tr>";
        // Output data of each row
        while($row = $result->fetch_assoc()) {
          // Retrieve data for each column
          $dogName = $row['dog_name'];
          $breedName = $row['breed_name'];
          $avgScore = $row['avg_score'];
          $ownerName = $row['owner_name'];
          $ownerEmail = $row['owner_email'];
          echo "<tr>";
          // Output data for each column in a table cell
          echo "<td>$dogName</td>";
          echo "<td>$breedName</td>";
          echo "<td>$avgScore</td>";
          // Create a link to the owner's name, passing the owner's name as a URL parameter
          echo "<td><a href='Page 2 Final.php?owner_name=$ownerName'>$ownerName</a></td>";
          // Create a link to the owner's email address, which will open the user's default email client with the email address pre-populated in the "To" field
          echo "<td><a href='mailto:$ownerEmail'>$ownerEmail</a></td>";
          echo "</tr>";
        }
        // Close the table
        echo "</table>";
      }
      // If there are no results, output "0 results"
      else {
        echo "0 results";
      }
      // Close the database connection
      $conn->close();
  ?>
</body>
</html>
