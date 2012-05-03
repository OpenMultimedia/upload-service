<?php
include('lib/common.php');

if ( $file_id ) {
    if ( uploaded_file_exists($file_id) ) {
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $file_id );
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . uploaded_file_size($file_id));
        ob_clean();
        flush();
        uploaded_file_read($file_id);
        exit();
    } else {
        header('HTTP/1.0 404 Not Found');
        header('Content-type: application/json');
        $response = array(
            'success' => false,
            'status' => 'error',
            'error' => 'archivo_no_existe'
        );
        echo( json_encode($response) );
        exit();
    }
} else {
    header('HTTP/1.0 400 Bad Request');
    header('Content-type: application/json');
    $response = array(
        'success' => false,
        'status' => 'error',
        'error' => 'archivo_no_indicado'
    );
    echo( json_encode($response) );
    exit();
}