<?php
    $dir_files = scandir('../upload');
    $all_files = array_diff($dir_files,array('.','..'));

    foreach( $all_files as $i => $file){
        $all_files[$i] = DIRECTORY_SEPARATOR.$file;
    }
    if(count($all_files) == 0){
        $all_files = array();
        $all_files['count'] = 0;
    }
    echo json_encode($all_files);