<?php
    define('_MAX_SIZE',pow(2,22));

    if($_SERVER['REQUEST_METHOD'] == "POST"):
        if(isset($_FILES['my_file'])):

            $images           = $_FILES['my_file'];
            $number_of_files = count($images['name']);
            $extensions     = array('jpeg','png','jpg','gif');
            $phpFileUploadErrors = array(
                1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                3 => 'The uploaded file was only partially uploaded',
                4 => 'No file was uploaded',
                6 => 'Missing a temporary folder',
                7 => 'Failed to write file to disk.',
                8 => 'A PHP extension stopped the file upload.',
            );
            $ajax           = array();

            for ($i = 0; $i <$number_of_files;$i++):
                $errors[$i]     = array();
                $image_name     = $images['name'][$i];
                $image_size     = $images['size'][$i];
                $image_tmp_name = $images['tmp_name'][$i];
                $image_type     = $images['type'][$i];
                $image_error    = $images['error'][$i];
                $unique_name    = uniqid(true) . "__".$image_name;
                $destination    = "..".DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR.$unique_name;
                $exploded_image = explode('.',$image_name);
                $image_ext      = strtolower(end($exploded_image));

                if($image_error !== 0):
                    array_push($errors[$i],$phpFileUploadErrors[$image_error]);
                else:
                    if($image_size > _MAX_SIZE ):
                        array_push($errors[$i],"Maximum size is "._MAX_SIZE." Kb");
                    endif;
                    if(!in_array($image_ext,$extensions)):
                        array_push($errors[$i],"Unknown file type , allowed types are : jpeg-jpg-gif-png");
                    endif;
                endif;

                if(empty($errors[$i])):
                    move_uploaded_file($image_tmp_name,$destination);
                    $ajax['success'][$i] = true;
                    $ajax['path'][$i]   = $destination;
                else:
                    $ajax['success'][$i] = false;
                    $ajax['path'][$i]   = "";
                    $ajax['on_error'][$i] = $errors[$i];
                endif;
            endfor;
            echo json_encode($ajax);

        endif;
    endif;
