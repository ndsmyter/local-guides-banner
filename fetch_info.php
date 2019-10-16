<?php /** @noinspection HtmlUnknownAttribute */

$id = isset($_GET['id']) ? $_GET['id'] : "";
$name = "";
$level = "";
$points = "";
$image = "";
$values = [];
$options = array();

if ($id == "" || is_nan($id) || $id == "null" || is_null($id)) {
  die("No ID reference provided");
}

function starts_with($haystack, $needle) {
  return substr($haystack, 0, strlen($needle)) === $needle;
}

function ends_with($haystack, $needle) {
  $length = strlen($needle);
  if ($length == 0) {
    return true;
  }

  return (substr($haystack, -$length) === $needle);
}

function simple_match($pattern) {
  global $contents;
  preg_match_all($pattern, $contents, $matches, PREG_PATTERN_ORDER);

  return count($matches) > 0 ? $matches[1][0] : "";
}

function get_code_value($code, $title = "") {
  global $contents, $name;
  preg_match_all("/\[" . $code . ",null,null,\d+,null,null,\\\\\"[^\\\]+\\\\\",(\d+),\\\\\"([^\\\]+)\\\\\",\d+\]/", $contents, $matches, PREG_PATTERN_ORDER);
  if (count($matches) > 0) {
    return array(
      "value" => $matches[1][0],
      "icon"  => $matches[2][0],
      "title" => "Number of " . $title . " by " . $name
    );
  } else {
    return array("value" => "", "icon" => "", "title" => "Number of " . $title . " by " . $name);
  }
}

/**
 * @param $id string the ID of the USEr
 */
function calculate($id) {
  /** @noinspection PhpUnusedLocalVariableInspection */
  global $contents, $name, $image, $level, $points, $values, $cached;

  $info_loaded = false;
  include("_load-cached.php");
    if ($info_loaded) {
      error_log('Using the database information');

      return;
    }

  error_log('Manual loading of the information');

  $contents = file_get_contents("https://www.google.com/maps/contrib/$id");

  error_log($contents);

  // Filter out the ampersand of Q&A \\u0026
  $contents = str_replace("\\\\u0026", "&", $contents);

  $name = simple_match("/<meta content=\"Contributions by ([^\"]*)\" itemprop=\"name\">/");
  $image = simple_match("/\[\\\\\"" . $name . "\\\\\",\[null,4,null,null,null,null,\[\\\\\"([^\\\\\"]*)\\\\\"\]/");

  $pattern = "/<meta content=\"Level (\d+) Local Guide | ([\d,]+) Points\" itemprop=\"description\">/";
  preg_match_all($pattern, $contents, $matches, PREG_PATTERN_ORDER);
  if (count($matches) > 0) {
    $level = $matches[1][0];
    $points = $matches[2][1];
  }

  $values = array(
    "reviews"      => get_code_value(1, "reviews"),
    "ratings"      => get_code_value(2, "ratings"),
    "photos"       => get_code_value(3, "photos"),
    "questions"    => get_code_value(4, "questions answered"),
    "places_added" => get_code_value(5, "places added"),
    "edits"        => get_code_value(6, "edits"),
    "facts"        => get_code_value(7, "facts checked"),
    "videos"       => get_code_value(10, "videos"),
    "qa"           => get_code_value(12, "Q&amp;A answered"),
    "roads"        => get_code_value(15, "roads added"),
    "lists"        => get_code_value(16, "lists published"),
    "photo_views"  => array("value" => simple_match("/\\\\\",(\d+),\[\[/"), "icon" => "", "title" => "Number of photo views by " . $name)
  );
  foreach ($values as $key => $value) {
    $values[$key]["icon_html"] = $values[$key]["icon"] == ""
      ? "<span class='local-guides-badge-fa-images'><i class='fas fa-eye'></i></span>"
      : "<img src=\"" . $values[$key]["icon"] . "\">";
  }
}

calculate($id);

include_once("_process.php");

$fields = ["photos", "photo_views", "reviews", "ratings", "videos", "lists"];

// Print debug information
//echo "Name: $name<br/>" . PHP_EOL;
//echo "ID: $id<br/>" . PHP_EOL;
//echo "Level: $level<br/>" . PHP_EOL;
//echo "Points: $points<br/>" . PHP_EOL;
//echo "Image: $image<br/>" . PHP_EOL;
//foreach ($values as $key => $value) {
//  if (is_array($value)) {
//    error_log("$key: " . print_r($value, true));
//  } else {
//    error_log("$key: $value");
//  }
//}
