<?php
namespace Services;

class UploadCSV
{
    public $pathFile = '';
    public $error = '';
    private $allowExt;

    function __construct() {
        $this->allowExt = ['csv'];
    }

    public function load($file)
    {
        $fileTmpPath = $file['csv-file']['tmp_name'];
        $fileName = $file['csv-file']['name'];
        $fileSize = $file['csv-file']['size'];
        $fileType = $file['csv-file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        if (!in_array($fileExtension, $this->allowExt)) {
            $this->error = 'Файл имеет не допустимое расширение. Используйте файл для загрузки .csv';
            return false;
        }
        else {
            // directory in which the uploaded file will be moved
            $uploadFileDir = './import_files/';
            $dest_path = $uploadFileDir . $newFileName;
            if(move_uploaded_file($fileTmpPath, $dest_path))
            {
                $this->pathFile = $dest_path;
                return true;
            }
            else
            {
                $this->error = 'Файл не был загружен, пожалуйста проверьте существует ли директория или выставлены ли права на него для записи';
                return false;
            }

        }
    }

    public function gerError() {
        return $this->error;
    }

    public function getFilePath() {
        return $this->pathFile;
    }

}