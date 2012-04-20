<?php
include('lib/config.base.php');
if ( file_exists('lib/config.user.php') ) {
    include('lib/config.user.php');
}
include('lib/firelogger.php');

FireLogger::$enabled = $config['debug'];

global $file_id;

$file_id = urldecode($_SERVER['REQUEST_URI']);

$file_id = preg_replace('#/+#', '/', $file_id);

flog('Unprocessed File ID', $file_id);

if ( $config['base_dir'] ) {
    $regex = '/^\/*' . preg_quote($config['base_dir'],'/') . '\/*/i';
    $file_id = preg_replace($regex, '', $file_id);

    flog('File ID Sin Dir Base', $file_id);
}

$subpos = strpos($file_id, '/');

if ( $subpos !== false ) {
    $file_id = substr($file_id, 0, $subpos);
    flog('File ID Sin Subarchivos', $file_id);
}

$file_id = trim($file_id);

flog('File ID Final', $file_id);

function uploaded_file_exists($file_id) {
    global $config;
    $dotcheck = strpos($file_id,'.');
    if ( $dotcheck === 0 ) {
        return false;
    }
    return file_exists($config['upload_location'] . '/' . $file_id);
}

function uploaded_file_size($file_id) {
    global $config;
    return filesize($config['upload_location'] . '/' . $file_id);
}

function uploaded_file_read($file_id) {
    global $config;
    return readfile($config['upload_location'] . '/' . $file_id);
}

function uploaded_file_delete($file_id) {
    global $config;
    return unlink($config['upload_location'] . '/' . $file_id);
}

function validate_video($file_path, &$error_desc) {
    $output = array();
    $retvalue = 0;

    global $config;

    $command = str_replace('{filename}', "\"{$file_path}\"",  $config['ffmpeg_command'].' 2>&1');
    $output = shell_exec($command);

    flog($command, $output);

    $regex = "/^Input #[\d+], ((?:\w+,)+) from '" . preg_quote($file_path,'/') . "':\s*$/mi";

    $match_no = preg_match_all($regex, $output, $pieces);
    for ( $i = 0; $i < $match_no; $i ++ ) {
        $formats = explode(',', $pieces[1][$i]);

        foreach( $formats as $format ) {
            if ( in_array($format, $config['valid_formats'] ) ) {
                return true;
            }
        }
    }

    return false;
}