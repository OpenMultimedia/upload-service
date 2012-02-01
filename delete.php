<?php
require('lib/common.php');

$result = array(
    'status' => 'error',
    'error' => 'error_desconocido'
);

if ( $file_id ) {
    if ( uploaded_file_exists($file_id) ) {
        if ( uploaded_file_delete($file_id) === true ) {
            $result = array(
                'status' => 'success'
            );
        } else {
            header("HTTP/1.0 500 Internal Server Error");
            $result = array(
                'status' => 'error',
                'error' => 'error_interno'
            );
        }
    } else {
        header("HTTP/1.0 404 Not Found");
        $result = array(
            'status' => 'error',
            'error' => 'archivo_no_existe'
        );
    }
} else {
    header("HTTP/1.0 400 Bad Request");
    $result = array(
        'status' => 'error',
        'error' => 'archivo_no_indicado'
    );
}

echo( json_encode( $result ) );
exit();