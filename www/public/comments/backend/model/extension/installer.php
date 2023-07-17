<?php
namespace Commentics;

class ExtensionInstallerModel extends Model
{
    public function install()
    {
        // Load the error messages
        $error = $this->loadWord('extension/installer');

        // Name of the temp folder to store the uploaded zip file
        $temp_folder = CMTX_DIR_UPLOAD . 'temp-' . $this->variable->random();

        // Create the temp folder
        @mkdir($temp_folder, 0777);

        // Check if the temp folder exists
        if (!is_dir($temp_folder)) {
            return $error['lang_error_no_temp_dir'];
        }

        // Location of the zip file once uploaded
        $zip_file = $temp_folder . '/upload.zip';

        // Store the uploaded zip file in the temp folder
        move_uploaded_file($this->request->files['file']['tmp_name'], $zip_file);

        // If the uploaded zip file exists
        if (file_exists($zip_file)) {
            // Check if it's an acceptable file
            if (!is_file($zip_file) || substr(str_replace('\\', '/', realpath($zip_file)), 0, strlen(CMTX_DIR_UPLOAD)) != CMTX_DIR_UPLOAD) {
                remove_directory($temp_folder);

                return $error['lang_error_zip_issue'];
            }

            // We use the ZipArchive class
            $zip = new \ZipArchive();

            // Open the zip file
            if ($zip->open($zip_file) === true) {
                // Extract the zip file
                if (!$zip->extractTo($temp_folder)) {
                    return $error['lang_error_zip_extract'];
                }

                $zip->close();
            } else {
                remove_directory($temp_folder);

                return $error['lang_error_zip_open'];
            }

            // Delete the zip file
            @unlink($zip_file);
        } else {
            remove_directory($temp_folder);

            return $error['lang_error_zip_not_stored'];
        }

        // Path to the /upload/ folder inside the extracted zip
        $directory = $temp_folder . '/upload/';

        // Check the extracted zip has the /upload/ folder inside it
        if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(CMTX_DIR_UPLOAD)) != CMTX_DIR_UPLOAD) {
            remove_directory($temp_folder);

            return $error['lang_error_no_upload_in_zip'];
        }

        // Import translated countries if they exist
        if (file_exists($temp_folder . '/countries.csv')) {
            $this->loadModel('tool/export_import');

            $csv_data = $this->request->getCsvData($temp_folder . '/countries.csv');

            $this->model_tool_export_import->importCountries($csv_data);
        }

        // Import translated emails if they exist
        if (file_exists($temp_folder . '/emails.csv')) {
            $this->loadModel('tool/export_import');

            $csv_data = $this->request->getCsvData($temp_folder . '/emails.csv');

            $this->model_tool_export_import->importEmails($csv_data);
        }

        // Import translated questions if they exist
        if (file_exists($temp_folder . '/questions.csv')) {
            $this->loadModel('tool/export_import');

            $csv_data = $this->request->getCsvData($temp_folder . '/questions.csv');

            $this->model_tool_export_import->importQuestions($csv_data);
        }

        // Variable to store the list of files to upload
        $files = array();

        $path = array($directory . '*');

        while (count($path) != 0) {
            $next = array_shift($path);

            foreach (glob($next) as $file) {
                if (is_dir($file)) {
                    $path[] = $file . '/*';
                }

                $files[] = $file;
            }
        }

        // For every file to upload
        foreach ($files as $file) {
            $destination = substr($file, strlen($directory));

            // Set the corresponding server path depending on its starting folder
            if (substr($destination, 0, 7) == 'backend') {
                $destination = CMTX_DIR_THIS . substr($destination, 7);
            } else if (substr($destination, 0, 8) == 'frontend') {
                $destination = CMTX_DIR_FRONTEND . substr($destination, 8);
            } else if (substr($destination, 0, 6) == 'system') {
                $destination = CMTX_DIR_SYSTEM . substr($destination, 6);
            } else if (substr($destination, 0, 8) == '3rdparty') {
                $destination = CMTX_DIR_3RDPARTY . substr($destination, 8);
            } else {
                $destination = CMTX_DIR_ROOT . $destination;
            }

            // If it's a directory then create it
            if (is_dir($file)) {
                if (!file_exists($destination)) {
                    if (!@mkdir($destination, 0777, true)) {
                        remove_directory($temp_folder);

                        $this->log->setFilename('installer');
                        $this->log->write('Could not mkdir: ' . $destination);
                        $this->log->write(error_get_last());

                        return $error['lang_error_create_dir'];
                    }
                }
            }

            // If it's a file then copy it there
            if (is_file($file)) {
                if (!@copy($file, $destination)) {
                    remove_directory($temp_folder);

                    $this->log->setFilename('installer');
                    $this->log->write('Could not copy: ' . $destination);
                    $this->log->write(error_get_last());

                    return $error['lang_error_copy_file'];
                }
            }
        }

        // Delete the temp folder
        remove_directory($temp_folder);

        // Clear modification cache
        remove_directory(CMTX_DIR_CACHE . 'modification/', false, false);

        return false;
    }
}
