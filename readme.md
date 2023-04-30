# Basic upload class
This class simply serves to add your files to the site.
## Dependence
This class using my string helper class. [My string helper class]('github.com/umityatarkalkmaz/phpStringHelper') is here.

```php
$imageUploader->upload('MyFile');
```
`upload($fileName)` It performs the installation and saves it to the given path.

```php
Upload::sanitizeFilename($filename, 10); 
```
`sanitizeFilename($filename,$shorting)` Sanitize and optionally shortens the filename.
```php
$documentUploader->config(5 * 1024 * 1024, ['doc', 'docx', 'pdf'], '/public/documents/'); 
```
`config($maxFileSize, $allowedExtension, $targetDir)` Optional changes the configuration variables.
```php
$imageUploader->getErrors()
```
`getErrors()` Return all errors.

Usage Example
```php
require 'upload.php';
use UmitYatarkalkmaz\Upload;
$imageUploader = new Upload();

$documentUpload = new Upload();
$documentUpload->config(5 * 1024 * 1024, ['doc', 'docx', 'pdf'], '/public/documents/');

$document = $documentUpload->upload('fileName');
if ($document){
    echo 'Document uploaded successfully'
}else{
    echo $documentUpload->getErrors();
}

```