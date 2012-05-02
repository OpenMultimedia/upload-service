<?php
include('lib/common.php');
include('lib/qqUploader.php');

// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = $config['valid_formats'];
// max file size in bytes
$sizeLimit = $config['size_limit'];

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload($config['upload_location']);
// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
