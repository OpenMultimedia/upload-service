<?php
include('lib/common.php');

$result = array();

if ( $config['upload_referer_check'] && ( ! isset($_REQUEST['HTTP_REFERER']) || ! preg_match('/^([\w]+\.)*?(tlsur|telesurtv)\.net(\/.*)?$/i', $_REQUEST['HTTP_REFERER']) ) ) {
    header("HTTP/1.0 403 Forbidden");
    
    $result = array(
        'status' => 'error',
        'error' => 'no_autorizado'
    );
    
    echo( json_encode( $result ) );
        
    exit();
}


if ( empty( $_FILES ) ) {
    if (empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
        $result = array(
            'status' => 'error',
            'error' => 'archivo_demasiado_grande'
        );
    } else {
        header("HTTP/1.0 400 Bad Request", false, 400);
        $result = array(
            'status' => 'error',
            'error' => 'archivo_ausente'
        );
    }
    
    echo( json_encode( $result ) );
    
    exit();
}

if ( count($_FILES) > 1 ) {
    header("HTTP/1.0 500 Internal Server Error", false, 500);
    $result = array(
        'status' => 'error',
        'error' => 'archivos_multiples'
    );
    
    echo( json_encode( $result ) );
    
    exit();
}

foreach ( $_FILES as $input_name => $file ) {
    //$file["error"]
    //$file["size"]
    //$file["type"]
    
    if ( $file['error'] === UPLOAD_ERR_OK ) {   
        if ( ! validate_video( $file['tmp_name'], $error_desc ) ) {

            header("HTTP/1.0 400 Bad Request", false, 400);
            
            $result = array(
               'status' => 'error',
               'error' => 'formato_archivo_invalido'
            );
            
            echo( json_encode( $result ) );
            
            exit();
        }
        
        $new_filename = md5( $file["name"] . '-' . date("Y-m-d H:i:s") );
        
        move_uploaded_file($file["tmp_name"], $config['upload_location'] . '/' . $new_filename);
             
        $result = array(
            'status' => 'success',
            'id' => $new_filename
        );
        
        echo json_encode($result);
        exit();
    }
    
    if ($file['error'] === UPLOAD_ERR_INI_SIZE) {

        header("HTTP/1.0 400 Bad Request", false, 400);
        
        $result = array(
            'status' => 'error',
            'error' => 'archivo_demasiado_grande'
        );
        
        echo json_encode($result);
        exit();
    }
    
    if ($file['error'] === UPLOAD_ERR_FORM_SIZE) {
        
        header("HTTP/1.0 400 Bad Request", false, 400);
        
        $result = array(
            'status' => 'error',
            'error' => 'archivo_demasiado_grande_cliente'
        );
        
        echo json_encode($result);
        exit();
    }
        
    if ($file['error'] === UPLOAD_ERR_PARTIAL) {
        
        header("HTTP/1.0 400 Bad Request", false, 400);
        
        $result = array(
            'status' => 'error',
            'error' => 'archivo_incompleto'
        );
        
        echo json_encode($result);
        exit();
    }
    
    // $file['error'] === UPLOAD_ERR_NO_FILE
    // $file['error'] === UPLOAD_ERR_NO_TMP_DIR
    // $file['error'] === UPLOAD_ERR_CANT_WRITE
    // $file['error'] === UPLOAD_ERR_EXTENSION
    header("HTTP/1.0 500 Internal Server Error", false, 500);
    
    $result = array(
        'status' => 'error',
        'error' => 'error_general'
    );
    
    echo(json_encode($result));
    exit();
}

//Unreacheable code
header("HTTP/1.0 500 Internal Server Error", false, 500);

$result = array(
    'status' => 'error',
    'error' => 'error_general'
);

exit();