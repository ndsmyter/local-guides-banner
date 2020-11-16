<?php /** @noinspection HtmlUnknownAttribute */

$id = "118432195486570227635";
$name = "";
$level = "";
$points = "";
$image = "";
$values = [];
$options = array();

function save_to_file($values) {
  file_put_contents("previous.json", json_encode($values));
  file_put_contents("data.json", json_encode(["views" => $values["photo_views"]["value"], "photos" => $values["photos"]["value"], "points" => $values["points"]]));
}

function load_from_file() {
  global $cached, $info_loaded, $values;
  $json = json_decode(file_get_contents("previous.json"), true);
  $info_loaded = $cached = time() - intval($json['fetch_time']) < 3601;
  if ($cached) {
    $values = $json;
  }
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
  preg_match_all("/\[" . $code . ",null,null,(\d+|null),null,null,\\\\\"[^\\\]+\\\\\",(\d+),\\\\\"([^\\\]+)\\\\\",\d+,\\\\\"[^\\\]+\\\\\"\]/", $contents, $matches, PREG_PATTERN_ORDER);
  if (count($matches) > 2 && count($matches[1]) > 0 && count($matches[2]) > 0) {
    return array(
      "value" => $matches[2][0],
      "icon"  => $matches[3][0],
      "title" => "Number of " . $title . " by " . $name
    );
  } else {
    return array("value" => "", "icon" => "", "title" => "Number of " . $title . " by " . $name);
  }
}

/**
 * @param $id string the ID of the User
 */
function calculate($id) {
  /** @noinspection PhpUnusedLocalVariableInspection */
  global $contents, $values, $cached, $info_loaded;

  $info_loaded = false;
  load_from_file();
  if ($info_loaded) {
    error_log('Using the file information');

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
  } else {
    $points = 0;
    $level = 0;
  }

  $values = array(
    "photos"      => get_code_value(3, "photos"),
    "photo_views" => array("value" => simple_match("/\\\\\",(\d+),\[\[/"), "icon" => "", "title" => "Number of photo views by " . $name)
  );
  foreach ($values as $key => $value) {
    $values[$key]["icon_html"] = $values[$key]["icon"] == ""
      ? "<span class='local-guides-badge-fa-images'><i class='fas fa-eye'></i></span>"
      : "<img src=\"" . $values[$key]["icon"] . "\">";
  }
  $values = array_merge($values, [
    "fetch_time" => time(),
    "avatar"     => $image,
    "name"       => $name,
    "points"     => $points,
    "level"      => $level
  ]);
  error_log(print_r($values, true));
}

calculate($id);

save_to_file($values);

