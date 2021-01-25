<?php
/*
 * image.php
 *
 * @author Nicolas De Smyter <nicolasdesmyter@gmail.com>
 * @package local-guides-banner
 * @copyright 2019 Nicolas De Smyter
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GNU GENERAL PUBLIC LICENSE Version 3
 * @version 1.0.0
 * @link https://ndsmyter.be
 * @since 16/11/20, 21:49
 */

/** @noinspection HtmlUnknownAttribute */

include("fetch_info.php");

function load_image($img_url) {
  $image_url = $img_url;
  if (starts_with($image_url, "//")) {
    $image_url = "https:" . $img_url;
  } else if (strlen($image_url) == 0) {
    $image_url = "eye_16.png";
  }

  $im = null;
  $file_dimensions = getimagesize($image_url);
  switch (strtolower(strtolower($file_dimensions['mime']))) {
    case 'image/png':
      $im = imagecreatefrompng($image_url);
      break;
    case 'image/jpeg':
      $im = imagecreatefromjpeg($image_url);
      break;
    default:
      error_log("Type not supported: " . $image_url);
  }
  if (!$im) {
    error_log("Image couldn't be created: " . $image_url);
  }

  return $im;
}

/**
 * @param string $icon_url the URL of the icon
 *
 * @return array the information of the icon: the resource, the width, the height
 */
function get_icon($icon_url) {
  $icon = load_image($icon_url);
  $icon_width = imagesx($icon);
  $icon_height = imagesy($icon);

  return array($icon, $icon_width, $icon_height);
}

function add_image($canvas, $icon_url, $left_margin_text, $icon_start) {
  global $size;
  list($icon, $icon_width, $icon_height) = get_icon($icon_url);
  $scale = $size["icon"];
  imagecopyresized($canvas, $icon, $left_margin_text, $icon_start, 0, 0, $icon_width * $scale, $icon_height * $scale, $icon_width, $icon_height);
  imagedestroy($icon);
}

function add_row($canvas, array $values, array $fields, $i, $icon_start, $text_start) {
  global $left_margin_text, $normal_text_size, $left_margin_icon_text, $fg_color, $normal_text_font, $column2_text, $column2_text_left;
  add_image($canvas, $values[$fields[$i]]["icon"], $left_margin_text, $icon_start);
  imagettftext($canvas, $normal_text_size, 0, $left_margin_icon_text, $text_start, $fg_color, $normal_text_font, "" . number_format($values[$fields[$i]]["value"]));
  $i++;
  add_image($canvas, $values[$fields[$i]]["icon"], $column2_text, $icon_start);
  imagettftext($canvas, $normal_text_size, 0, $column2_text_left, $text_start, $fg_color, $normal_text_font, "" . number_format($values[$fields[$i]]["value"]));
  $i++;

  return $i;
}

// Default values
$background_color = [241, 163, 64];
$foreground_color = [0, 0, 0];
$sizes = [
  [
    "margin"               => 10, // pixels
    "column"               => 100,// pixels
    "title"                => 15, // pixels
    "subtitle"             => 12, // pixels
    "text"                 => 10, // pixels
    "avatar"               => 1,  // percentage
    "icon"                 => 1,  // percentage
    "icon_bg_diam"         => 25, // pixels
    "icon_bg_margin_right" => 10, // pixels
    "icon_bg_correction"   => 1,  // pixels
  ],
  [
    "margin"               => 9,
    "column"               => 90,
    "title"                => 12,
    "subtitle"             => 10,
    "text"                 => 9,
    "avatar"               => 0.8,
    "icon"                 => 0.9,
    "icon_bg_diam"         => 22,
    "icon_bg_margin_right" => 9,
    "icon_bg_correction"   => 0,
  ],
  [
    "margin"               => 7,
    "column"               => 80,
    "title"                => 9,
    "subtitle"             => 8,
    "text"                 => 8,
    "avatar"               => 0.6,
    "icon"                 => 0.7,
    "icon_bg_diam"         => 16,
    "icon_bg_margin_right" => 7,
    "icon_bg_correction"   => 0
  ]
];
$size = $sizes[0];

// Inputs
if (isset($_GET["bg"])) {
  list($r, $g, $b) = sscanf($_GET["bg"], "%02x%02x%02x");
  $background_color = [$r, $g, $b];
}
if (isset($_GET["fg"])) {
  list($r, $g, $b) = sscanf($_GET["fg"], "%02x%02x%02x");
  $foreground_color = [$r, $g, $b];
}
if (isset($_GET["s"])) {
  $s = intval($_GET["s"]);
  if (!is_null($s) && $s > 0 && $s < count($sizes)) {
    $size = $sizes[$s];
  }
}

header('Content-Type: image/jpeg');

// Avatar Information
$avatar = load_image($image);
$original_avatar_width = imagesx($avatar);
$original_avatar_height = imagesy($avatar);
if ($original_avatar_width < 30) {
  $avatar = null;
  $original_avatar_height = 120;
  $original_avatar_width = 120;
}
$avatar_width = $original_avatar_width * $size["avatar"];
$avatar_height = $original_avatar_height * $size["avatar"];
// Fonts
$title_font = __DIR__ . "/fonts/OpenSans-Regular.ttf";
$subtitle_font = $normal_text_font = __DIR__ . "/fonts/OpenSans-Light.ttf";
// Font sizes
$title_size = $size["title"];
$subtitle_size = $size["subtitle"];
$normal_text_size = $size["text"];
// Title Information
$title = "Contributions by " . $name;
$title_width_array = imagettfbbox($title_size, 0, $title_font, $title);
$title_width = $title_width_array[2] - $title_width_array[1];

$margin = $size["margin"];
$column_width = $size["column"];
$width = $avatar_width + $title_width + $margin * 3;
$left_margin_text = $avatar_width + 2 * $margin;
$column2_text = $left_margin_text + $column_width;
$height = $avatar_height + 2 * $margin;
$canvas = imagecreatetruecolor($width, $height);
$bg_color = imagecolorallocate($canvas, $background_color[0], $background_color[1], $background_color[2]);
$fg_color = imagecolorallocate($canvas, $foreground_color[0], $foreground_color[1], $foreground_color[2]);
$circle_color = imagecolorallocate($canvas, 94, 60, 153);
$bg_rectangle = imagefilledrectangle($canvas, 0, 0, $width, $height, $bg_color);

// Avatar
//imagecopy($canvas, $avatar, $margin, $margin, 0, 0, $avatar_width, $avatar_height);
if (!is_null($avatar)) {
  imagecopyresized($canvas, $avatar, $margin, $margin, 0, 0, $avatar_width, $avatar_height, $original_avatar_width, $original_avatar_height);
  imagedestroy($avatar);
}

// Title + Subtitle
$text_start = $title_size;
imagettftext($canvas, $title_size, 0, $left_margin_text, $margin + $text_start, $fg_color, $title_font, $title);
$text_start += $subtitle_size + $margin;
imagettftext($canvas, $subtitle_size, 0, $left_margin_text, $margin + $text_start, $fg_color, $subtitle_font, "Level " . $level . " Local Guide | " . $points . " points");

// Some calculations
$icon_margin_top = 7;
$icon_margin_right = 30;
$circle_margin_right = $size["icon_bg_margin_right"];
$circle_diameter = $size["icon_bg_diam"];
$next_line = $normal_text_size + $margin;
$icon_start = $text_start + $margin + $icon_margin_top;
$text_start += $next_line + $margin;
$left_margin_icon_text = $left_margin_text + $icon_margin_right;
$circle_left = $left_margin_text + $circle_margin_right - 2;
$circle_top = $text_start - 5;
$column2_text_left = $column2_text + $icon_margin_right;
$circle_radius = $circle_diameter / 2;

// Background for icons
imagefilledellipse($canvas, $circle_left, $circle_top, $circle_diameter, $circle_diameter, $circle_color);
imagefilledellipse($canvas, $circle_left, $circle_top + 40, $circle_diameter, $circle_diameter, $circle_color);
imagefilledellipse($canvas, $circle_left + $column_width, $circle_top, $circle_diameter, $circle_diameter, $circle_color);
imagefilledellipse($canvas, $circle_left + $column_width, $circle_top + 40, $circle_diameter, $circle_diameter, $circle_color);
imagefilledrectangle($canvas, $circle_left - $circle_radius + $size["icon_bg_correction"], $circle_top, $circle_left + $circle_radius, $circle_top + 40, $circle_color);
imagefilledrectangle($canvas, $circle_left + $column_width - $circle_radius + $size["icon_bg_correction"], $circle_top, $circle_left + $column_width + $circle_radius, $circle_top + 40, $circle_color);

// Values - Row 0
$i = add_row($canvas, $values, $fields, 0, $icon_start, $text_start);
$text_start += $next_line;
$icon_start += $next_line;

// Values - Row 1
$i = add_row($canvas, $values, $fields, $i, $icon_start, $text_start);
$text_start += $next_line;
$icon_start += $next_line;

// Values - Row 2
add_row($canvas, $values, $fields, $i, $icon_start, $text_start);

imagefttext($canvas, 10, 0, $width - 100, $height - 6, $fg_color, $normal_text_font, "© ndsmyter.be");

// Create a jpeg
imagejpeg($canvas, null, 99);
imagedestroy($canvas);
