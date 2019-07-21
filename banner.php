<?php /** @noinspection HtmlUnknownAttribute */

$id = isset($_GET['id']) ? $_GET['id'] : "";
$name = "";
$level = "";
$points = "";
$image = "";
$values = [];
$photos = "";
$photo_views = "";
$reviews = "";
$ratings = "";

if ($id == "" || is_nan($id)) {
	die("No ID reference provided");
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
		return array("value" => $matches[1][0], "icon" => $matches[2][0], "title" => "Number of " . $title . " by " . $name);
	} else {
		return array("value" => "", "icon" => "", "title" => "Number of " . $title . " by " . $name);
	}
}

$contents = file_get_contents("https://www.google.com/maps/contrib/$id");

// Filter out the ampersand of Q&A \\u0026
$contents = str_replace("\\\\u0026", "&", $contents);

$name = simple_match("/<meta content=\"Contributions by ([^\"]*)\" itemprop=\"name\">/");
$image = simple_match("/\[\[\[\\\\\"" . $name . "\\\\\"\]\\\\n,\\\\\"\/\/maps.google.com\/maps\/contrib\/" . $id . "\/photos\\\\\",\\\\\"([^\\\]+)\\\\\"/");

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
	$values[$key]["icon"] = $values[$key]["icon"] == ""
		? "<span class='local-guides-badge-fa-images'><i class='fas fa-eye'></i></span>"
		: "<img src=\"" . $values[$key]["icon"] . "\">";
}


// Print debug information
//echo "Name: $name<br/>" . PHP_EOL;
//echo "ID: $id<br/>" . PHP_EOL;
//echo "Level: $level<br/>" . PHP_EOL;
//echo "Points: $points<br/>" . PHP_EOL;
//echo "Image: $image<br/>" . PHP_EOL;
//foreach ($values as $key => $value) {
//	if (is_array($value)) {
//		echo "$key: " . print_r($value, true) . "<br/>" . PHP_EOL;
//	} else {
//		echo "$key: $value<br/>" . PHP_EOL;
//	}
//}
?>

<html lang="en">
<head>
    <script src="https://kit.fontawesome.com/20313097ff.js"></script>
    <title>Contributions by <?php echo $name; ?></title>
</head>
<style>
    .local-guides-banner-card {
        /*TODO Make lightyellow */
        background-color: #f1a340;
        display: inline-block;
        cursor: pointer;
        color: black;
        text-decoration: none;
    }

    .local-guides-banner-information {
        margin: 5px 15px 5px 115px;
        white-space: nowrap;
    }

    .local-guides-banner-image {
        float: left;
        margin: 15px 10px 5px 10px;
    }

    .local-guides-banner-name {
        font-weight: bold;
    }

    .local-guides-banner-values-table {
        width: 95%;
    }

    .local-guides-banner-values-table img, .local-guides-banner-values-table .local-guides-badge-fa-images {
        background-color: #5e3c99;
        border-radius: 50%;
        display: inline-block;
        height: 16px;
        padding: 4px;
        vertical-align: middle;
        width: 18px;
    }

    .local-guides-banner-values-table td:nth-child(1),
    .local-guides-banner-values-table td:nth-child(3) {
        width: 30px;
    }

    .local-guides-banner-values-table td:nth-child(2),
    .local-guides-banner-values-table td:nth-child(4) {
        width: calc(50% - 30px);
    }

    .local-guides-badge-fa-images {
        color: white;
    }

    .clear {
        clear: both;
    }
</style>

<a href="//www.google.com/maps/contrib/<?php echo $id; ?>" class="local-guides-banner-card" target="_blank" title="Profile of <?php echo $name; ?> on Google Maps">
    <img class="local-guides-banner-image" src="<?php echo $image; ?>" alt="Profile picture of <?php echo $name; ?>">
    <div class="local-guides-banner-information">
        <div class="local-guides-banner-name">Contributions by <?php echo $name; ?></div>
        <div>
            <span class="local-guides-banner-level">Level <?php echo $level; ?> Local Guide</span> |
            <span class="local-guides-banner-points"><?php echo $points; ?> points</span>
        </div>
        <table class="local-guides-banner-values-table">
            <tr>
                <td title="<?php echo $values["photos"]["title"]; ?>"><?php echo $values["photos"]["icon"]; ?></td>
                <td title="<?php echo $values["photos"]["title"]; ?>"><?php echo number_format($values["photos"]["value"]); ?></td>
                <td title="<?php echo $values["photo_views"]["title"]; ?>"><?php echo $values["photo_views"]["icon"]; ?></td>
                <td title="<?php echo $values["photo_views"]["title"]; ?>"><?php echo number_format($values["photo_views"]["value"]); ?></td>
            </tr>
            <tr>
                <td title="<?php echo $values["reviews"]["title"]; ?>"><?php echo $values["reviews"]["icon"]; ?></td>
                <td title="<?php echo $values["reviews"]["title"]; ?>"><?php echo number_format($values["reviews"]["value"]); ?></td>
                <td title="<?php echo $values["ratings"]["title"]; ?>"><?php echo $values["ratings"]["icon"]; ?></td>
                <td title="<?php echo $values["ratings"]["title"]; ?>"><?php echo number_format($values["ratings"]["value"]); ?></td>
            </tr>
            <tr>
                <td title="<?php echo $values["videos"]["title"]; ?>"><?php echo $values["videos"]["icon"]; ?></td>
                <td title="<?php echo $values["videos"]["title"]; ?>"><?php echo number_format($values["videos"]["value"]); ?></td>
                <td title="<?php echo $values["lists"]["title"]; ?>"><?php echo $values["lists"]["icon"]; ?></td>
                <td title="<?php echo $values["lists"]["title"]; ?>"><?php echo number_format($values["lists"]["value"]); ?></td>
            </tr>
        </table>
    </div>
    <div class="clear"></div>
</a>
</html>