<?php
include './config/API.php';

$API = new API();
$body = "fields id, category, generation, name, slug, platform_family.name,
versions.name, versions.platform_logo.image_id, versions.platform_version_release_dates.region, versions.platform_version_release_dates.date;
limit 500;";
echo '<pre>';
$API->save_plats();
echo '</pre>';