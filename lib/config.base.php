<?php
global $config;
$config = array(
    'base_dir' => '/',
    
    'upload_location' => './files',
    'debug'  => false,
    'ffmpeg_command' => 'ffmpeg -v 5 -i {filename}',
    'valid_formats' => array('mp4', 'avi', 'mov'),
    'upload_referer_check' => true
);