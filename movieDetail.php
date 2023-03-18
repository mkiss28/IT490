<?php
require_once('rabbitFunctions.inc');

if (isset($_GET["id"])) {
    $movieID = $_GET["id"];
} else {
    die("Error: Movie ID not provided.");
}

$request = array("type" => "get_movie_details", "id" => $movieID);
$response = sendAPI($request);
$response = json_decode($response, true);

$displayBlock = "<table>";
$title = $response["title"];
$runtime = $response["runtime"];
$prodCompany = $response["production_companies"][0]["name"];
$overview = $response["overview"];
$releaseDate = $response["release_date"];

$genres = array();
foreach ($response["genres"] as $genre) {
    $genres[] = $genre["name"];
}
$genresString = implode(", ", $genres);

$streamServices = array();
foreach ($response["streaming_services"] as $service) {
    $streamServices[] = $service;
}
$streaming = implode("</td><td>", $streamServices);

$cast = "";
foreach ($response["cast"] as $role => $actor) {
    $cast .= "<tr><td>$role</td><td>$actor</td></tr>";
}

$director = $response["director"];
$producer = $response["producers"][0];
$writer = $response["writers"][0];

$displayBlock .= "
    <th>
        <strong>$title</strong>
    </th>
    <tr>
        <td rowspan='2' colspan='3'><strong>$overview</strong></td>
        <td><strong>$releaseDate</strong></td>
    </tr>
    <tr>
        <td><strong>$prodCompany</strong></td>
    </tr>
    <tr>
        <td><strong>Genres</strong></td>
        <td>$genresString</td>
        <td><strong>Runtime</strong></td>
        <td>$runtime minutes</td>
    </tr>
</table>
<br>
<table>
    <tr>
        <td>Director</td>
        <td>Producer</td>
        <td>Writer</td>
    </tr>
    <tr>
        <td><strong>$director</strong></td>
        <td><strong>$producer</strong></td>
        <td><strong>$writer</strong></td>
    </tr>
";
$displayBlock .= "</table><br>";
$displayBlock .= "<table><th><strong>Cast</strong></th>$cast</table><br>";
$displayBlock .= "<table><th><strong>Streaming on</strong></th><tr><td>$streaming</td></tr></table><br>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movie Details</title>
</head>
<body>
    <?php echo $displayBlock; ?>

    <button onclick="window.location.href='stream.php'">Stream Movie</button>
</body>
</html>

