<?php
namespace UmitYatarkalkmaz;
use UmitYatarkalkmaz\StringHelper;
class Upload
{
    private int $maxFileSize = 2 * 1024 * 1024;
    private array $allowedExtension = ['jpg', 'jpeg', 'png', 'gif'];
    private string $targetDir = '/public/image/';
    private array $errors = [];
    private array $fileErrors = [
        0 => 'Successfully uploaded.',
        1 => 'File is too large', // The uploaded file exceeds the upload_max_filesize directive in php.ini
        2 => 'File is too large', // The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form
        3 => 'The uploaded file was only partially uploaded.',
        4 => 'No file was uploaded.',
        6 => 'Server side error', //'Missing a temporary folder.'
        7 => 'Server side error', //'Failed to write file to disk.'
        8 => 'Server side error' //'A PHP extension stopped the file upload.'
    ];

    public function upload($fileInputName)
    {
        $file = $_FILES[$fileInputName];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->fileErrors[$file['error']];
            return false;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = self::sanitizeFilename($file['name'], true);
        $uploadFilePath = $this->targetDir . '/' . $extension . '/' . basename($filename);
        if (file_exists($uploadFilePath)) {
            $this->errors[] = 'Already exist file this name.';
            return false;
        }

        if ($file['size'] > $this->maxFileSize) {
            $this->errors[] = 'File is too large';
            return false;
        }


        if (!in_array(strtolower($extension), $this->allowedExtension)) {
            $this->errors[] = 'File type not allowed';
            return false;
        }

        if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
            return true;
        } else {
            $this->errors[] = 'File upload error.';
            return false;
        }
    }

    public static function sanitizeFilename($filename, $shorting = false): string
    {
        $filename = StringHelper::cleanString($filename);
        if ($shorting) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $filename = substr($filename, 0, $shorting) . '_' . $extension;
        }

        return $filename;
    }

    public function config($maxFileSize = false, $allowedExtension = false, $targetDir = false): false|array
    {
        $success = [];
        $errors = [];

        if (isset($maxFileSize) && is_numeric($maxFileSize)) {
            $this->maxFileSize = $maxFileSize;
            $success[] = 'maxFileSize';
        } elseif (!is_numeric($maxFileSize)) {
            $errors[] = 'maxFileSize is not numeric';
        }

        if (isset($allowedExtension) && is_array($allowedExtension)) {
            $this->allowedExtension = $allowedExtension;
            $success[] = 'allowedExtension';
        } elseif (!is_array($allowedExtension)) {
            $errors[] = 'allowedExtension is not array';
        }

        if (isset($targetDir) && is_string($targetDir)) {
            $this->targetDir = $targetDir;
            $success[] = 'targetDir';
        } elseif (!is_string($targetDir)) {
            $errors[] = 'targetDir is not string';
        }

        if ($errors) {
            $this->errors['config'] = $errors;
            return false;
        }

        return $success;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
