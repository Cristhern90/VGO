<?php

include "../../plugins/simple_html_dom/simple_html_dom.php";
$search_query = "Final Fantasy XV cover";
$search_query = urlencode($search_query);
$html = file_get_html("https://www.google.com/search?q=$search_query&tbm=isch");
echo $html;
$image_container = $html->find('table.GpQGbf', 0);
$images = $image_container->find('a');

$image_count = 10; //Enter the amount of images to be shown
$i = 0;


