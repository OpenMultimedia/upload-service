<?php
global $config;
$config = array(
    'base_dir' => '/',
    'size_limit' => 100 * 1024 * 1024,
    'upload_location' => './uploads/',
    'debug'  => false,
    'ffmpeg_command' => 'ffmpeg -v 5 -i {filename}',
    'valid_formats' => array('mp4', 'avi', 'mov'),
    'upload_referer_check' => true
);