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

    <h1>We Cannot Distribute Copywrited content!</h1> <iframe width="1280" height="720" src="https://www.youtube.com/embed/PEokg_nsOhk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe> <h2>FAQ</h2> <p>Here are some frequently asked questions about this video:</p> <ul> <li><B>Why can't you embed my favorite streaming services content?</B></li> <li>Unfortunately, embedding Netflix/HBOmax/Disney+ content on our website is not possible due to licensing and copyright restrictions. Netflix/HBOmax/Disney+ content can only be accessed through their official websites or mobile apps, and they do not offer any options for embedding their content on third-party websites. We can public promotional materials. but We cannot embed the actual content.</li>

</body>
</html>