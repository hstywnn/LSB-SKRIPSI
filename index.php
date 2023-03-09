<?php

// Create an image instance
$im = imagecreatefrompng(
'https://media.geeksforgeeks.org/wp-content/uploads/geeksforgeeks-13.png');

// Save the image as image1.png
imagepng($im, 'image1.png');

// // Save the image as image2.png with all filters to disable size compression
// imagepng($im, 'image2.png', null, PNG_ALL_FILTERS);

// imagedestroy($im);
?>
