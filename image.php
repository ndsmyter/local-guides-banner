<?php /** @noinspection HtmlUnknownAttribute */

include("fetch_info.php");

function load_image($img_url) {
  $image_url = $img_url;
  if (starts_with($image_url, "//")) {
    $image_url = "https:" . $img_url;
  } else if (strlen($image_url) == 0) {
    $image_url = "eye_16.png";
  }

  $im = null;
  if (ends_with($image_url, ".png")) {
    $im = imagecreatefrompng($image_url);
  } else if (ends_with($image_url, ".jpg")) {
    $im = imagecreatefrompng($image_url);
//    $im = imagecreatefromjpeg($image_url);
  } else {
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

header('Content-Type: image/jpeg');

// Avatar Information
$avatar = load_image($image);
$avatar_width = imagesx($avatar);
$avatar_height = imagesy($avatar);
// Fonts
$title_font = "fonts/OpenSans-Regular.ttf";
$subtitle_font = $normal_text_font = "fonts/OpenSans-Light.ttf";
// Font sizes
$title_size = 15;
$subtitle_size = 12;
$normal_text_size = 10;
// Title Information
$title = "Contributions by " . $name;
$title_width_array = imagettfbbox($title_size, 0, $title_font, $title);
$title_width = $title_width_array[2] - $title_width_array[1];

$margin = 10;
$width = $avatar_width + $title_width + $margin * 3;
$left_margin_text = $avatar_width + 2 * $margin;
$column_width = 100;
$column2_text = $left_margin_text + $column_width;
$height = $avatar_height + 2 * $margin;
$canvas = imagecreatetruecolor($width, $height);
$bg_color = imagecolorallocate($canvas, 241, 163, 64);
$fg_color = imagecolorallocate($canvas, 0, 0, 0);
$circle_color = imagecolorallocate($canvas, 94, 60, 153);
$bg_rectangle = imagefilledrectangle($canvas, 0, 0, $width, $height, $bg_color);

// Avatar
imagecopy($canvas, $avatar, $margin, $margin, 0, 0, $avatar_width, $avatar_height);
imagedestroy($avatar);

// Title + Subtitle
$text_start = $title_size;
imagettftext($canvas, $title_size, 0, $left_margin_text, $margin + $text_start, $fg_color, $title_font, $title);
$text_start += $subtitle_size + $margin;
imagettftext($canvas, $subtitle_size, 0, $left_margin_text, $margin + $text_start, $fg_color, $subtitle_font, "Level " . $level . " Local Guide | " . $points . " points");

// Some calculations
$icon_margin_top = 7;
$icon_margin_right = 30;
$next_line = $normal_text_size + $margin;
$icon_start = $text_start + $margin + $icon_margin_top;
$text_start += $next_line + $margin;
$left_margin_icon_text = $left_margin_text + $icon_margin_right;
$circle_margin_right = 10;
$circle_left = $left_margin_text + $circle_margin_right - 2;
$circle_top = $text_start - 5;
$column2_text_left = $column2_text + $icon_margin_right;
$circle_diameter = 25;
$circle_radius = $circle_diameter / 2;

// Background for icons
imagefilledellipse($canvas, $circle_left, $circle_top, $circle_diameter, $circle_diameter, $circle_color);
imagefilledellipse($canvas, $circle_left, $circle_top + 40, $circle_diameter, $circle_diameter, $circle_color);
imagefilledellipse($canvas, $circle_left + $column_width, $circle_top, $circle_diameter, $circle_diameter, $circle_color);
imagefilledellipse($canvas, $circle_left + $column_width, $circle_top + 40, $circle_diameter, $circle_diameter, $circle_color);
imagefilledrectangle($canvas, $circle_left - $circle_radius + 1, $circle_top, $circle_left + $circle_radius, $circle_top + 40, $circle_color);
imagefilledrectangle($canvas, $circle_left + $column_width - $circle_radius + 1, $circle_top, $circle_left + $column_width + $circle_radius, $circle_top + 40, $circle_color);

function add_image($canvas, $icon_url, $left_margin_text, $icon_start) {
  list($icon, $icon_width, $icon_height) = get_icon($icon_url);
  imagecopy($canvas, $icon, $left_margin_text, $icon_start, 0, 0, $icon_width, $icon_height);
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

// Create a jpeg
imagejpeg($canvas, null, 90);
imagedestroy($canvas);
