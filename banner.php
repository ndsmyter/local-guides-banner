<?php include("fetch_info.php"); ?>

<html lang="en">
<head>
  <script src="https://kit.fontawesome.com/20313097ff.js"></script>
  <title>Contributions by <?php echo $name; ?></title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
</head>
<style>
  body {
    font-family: 'Open Sans', sans-serif;
  }

  .local-guides-banner-card {
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
        <td title="<?php echo $values["photos"]["title"]; ?>"><?php echo $values["photos"]["icon_html"]; ?></td>
        <td title="<?php echo $values["photos"]["title"]; ?>"><?php echo number_format($values["photos"]["value"]); ?></td>
        <td title="<?php echo $values["photo_views"]["title"]; ?>"><?php echo $values["photo_views"]["icon_html"]; ?></td>
        <td title="<?php echo $values["photo_views"]["title"]; ?>"><?php echo number_format($values["photo_views"]["value"]); ?></td>
      </tr>
      <tr>
        <td title="<?php echo $values["reviews"]["title"]; ?>"><?php echo $values["reviews"]["icon_html"]; ?></td>
        <td title="<?php echo $values["reviews"]["title"]; ?>"><?php echo number_format($values["reviews"]["value"]); ?></td>
        <td title="<?php echo $values["ratings"]["title"]; ?>"><?php echo $values["ratings"]["icon_html"]; ?></td>
        <td title="<?php echo $values["ratings"]["title"]; ?>"><?php echo number_format($values["ratings"]["value"]); ?></td>
      </tr>
      <tr>
        <td title="<?php echo $values["videos"]["title"]; ?>"><?php echo $values["videos"]["icon_html"]; ?></td>
        <td title="<?php echo $values["videos"]["title"]; ?>"><?php echo number_format($values["videos"]["value"]); ?></td>
        <td title="<?php echo $values["lists"]["title"]; ?>"><?php echo $values["lists"]["icon_html"]; ?></td>
        <td title="<?php echo $values["lists"]["title"]; ?>"><?php echo number_format($values["lists"]["value"]); ?></td>
      </tr>
    </table>
  </div>
  <div class="clear"></div>
</a>
</html>
