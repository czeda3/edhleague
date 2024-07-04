<?php
    echo "<h1>PHP test</h1>";
    $hostname = "localhost";
    $dbname = "nnvrbxyv_donblackeDB";
    $username = "nnvrbxyv_dbadmin";
    $password = "aQcluj6(^O(3";

    $conn = mysqli_connect($hostname, $username, $password, $dbname);

    if (!$conn) {
        echo "<h1>Not connected to DB</h1>";
        die("Connection failed". mysqli_connect_error());
    } else {
        echo "<h1>Connected to DB</h1>";
    }

    $sql = "SELECT id, first_name, last_name FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    echo "<table><tr><th>ID</th><th>Name</th></tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["first_name"]." ".$row["last_name"]."</td></tr>";
    }
        echo "</table>";
    } else {
        echo "0 results";
    }
    $conn->close();
    