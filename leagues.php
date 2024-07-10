<?php
$hostname = "localhost"; //localhost
$dbname = "nnvrbxyv_donblackeDB";
$username = "nnvrbxyv_dbadmin";
$password = "aQcluj6(^O(3";

$conn = mysqli_connect($hostname, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed". mysqli_connect_error());
}


function getSingleLeagueQueryString($leagueNumber) {
    $sql = "
    SELECT
        CONCAT(u.last_name, ' ', u.first_name) AS full_name,
        ld.commander AS commander,
        COALESCE(SUM(
            CASE
                WHEN m.first_player = u.id AND m.played = 1 THEN 
                    CASE
                        WHEN m.first_result IN (3, 4) THEN 3
                        WHEN m.first_result = 2 THEN 1
                        ELSE 0
                    END
                WHEN m.second_player = u.id AND m.played = 1 THEN 
                    CASE
                        WHEN m.second_result IN (3, 4) THEN 3
                        WHEN m.second_result = 2 THEN 1
                        ELSE 0
                    END
                ELSE 0
            END
        ), 0) AS points
    FROM 
        users u
    LEFT JOIN 
        matches m ON u.id IN (m.first_player, m.second_player)
    LEFT JOIN
        league_data ld ON u.id = ld.player
    WHERE
        ld.league = ".strval($leagueNumber)." AND
        m.league_id = ".strval($leagueNumber)."
    GROUP BY 
        u.id, u.first_name, u.last_name, ld.commander
    ORDER BY 
        points DESC;
    ";
    return $sql;
}


$leagueSQL = "SELECT id FROM `leagues` ORDER BY id DESC;";
$leagueSQLresult = $conn->query($leagueSQL);

$allLeagueStandingsArray = array();

while($row = $leagueSQLresult->fetch_assoc()) {                    
    $query = $conn->query(getSingleLeagueQueryString($row["id"]));
    $allLeagueStandingsArray[$row["id"]] = $query;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDH Liga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="./index.html">
                <span class="fw-bold text-light">MetaEDH</span>
            </a>
    
            <!-- toggle button -->
             <button class="navbar-toggler justify-content-end" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav"
             aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- links -->
            <div class="collapse navbar-collapse justify-content-end align-center" id="main-nav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="./leagues.php">Ligák</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Szabályok</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Statisztika</a>
                    </li>
                </ul>
            </div>
    
        </div>
    </nav>
    
    <!-- TABS -->
    <div class="container">SPACING</div>
    <div class="container my-5">
        <nav>
            <div class="nav nav-tabs text-secondary my-2" id="nav-tab" role="tablist">
                <?php
                //Generate every tab
                $leagueSQLresult->data_seek(0);
                while($row = $leagueSQLresult->fetch_assoc()) {                    
                    echo "
                        <button
                        class=\"nav-link fw-bold\" id=\"league-".$row["id"]."-tab\"
                        data-bs-toggle=\"tab\" data-bs-target=\"#league-".$row["id"]."\"
                        type=\"button\" role=\"tab\"
                        aria-controls=\"league-".$row["id"]."\" aria-selected=\"false\">
                        ".$row["id"].". Liga
                        </button>
                    ";    
                }
                ?>
            </div>
        </nav>
            <!-- League Tabs Content -->
            <div class="tab-content" id="nav-tabContent">

            <?php
            $leagueSQLresult->data_seek(0);
            while($tabRow = $leagueSQLresult->fetch_assoc()) {
                echo "<div class=\"tab-pane fade\" id=\"league-".$tabRow["id"]."\" role=\"tabpanel\" aria-labelledby=\"league-".$row["id"]."-tab\" tabindex=\"0\">";
            ?>
                    <table class="table table-dark table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Név</th>
                            <th scope="col">Commander</th>
                            <th scope="col">Pont</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $row_number = 1;
                        while($standingsRow = $allLeagueStandingsArray[$tabRow["id"]]->fetch_assoc()) {
                            echo "<tr>                    
                                    <th scope=\"row\">".$row_number."</th>
                                    <td>".$standingsRow["full_name"]."</td>
                                    <td>".$standingsRow["commander"]."</td>
                                    <td>".$standingsRow["points"]."</td>
                                </tr>";
                            $row_number++;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

</body>
</html>