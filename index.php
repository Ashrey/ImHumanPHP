<?php
//Example use
//include Class
require 'im_human.php';
//Create instance
$img = new ImHuman();
//generate image
$img->generate();
//send image to browser
$img->render();
die();
