<?php
namespace Cotizador\Service;

// The image manager service.
class ImageManager 
{
    /**
     * The directory where we save image files.
     * @var string
     */
    // private $saveToDir = './assets/upload';
    private $saveToDir = './assets';

    /**
     * This method returns path to the directory where we save the image files.
     * @return string
     */
    public function getSaveToDir()
    {
        return $this->saveToDir;
    }

    /**
     * This method returns an array with file names of all uploaded files.
     * @return array
     */
    public function getSavedFiles()
    {
        // Check whether the directory already exists, and if not,
        // create it.
        if (!is_dir($this->saveToDir)) {
            if (!mkdir($this->saveToDir)) {
                throw new \Exception('Could not create directory for uploads: ' .
                    error_get_last());
            }
        }

        // Scan the directory and create the list of uploaded files.
        $files = [];
        $handle = opendir($this->saveToDir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry == '.' || $entry == '..') {
                continue; // Skip current dir and parent dir.
            }

            $files[] = $entry;
        }

        // Return the list of uploaded files.
        return $files;
    }

    /**
     * This method return the path to the saved image file.
     * @param string $filename
     * @return string
     */
    public function getImagePathByName($filename)
    {
        // Take some precautions to make file name secure.
        $filename = str_replace("/", "", $filename);  // Remove slashes
        $filename = str_replace("\\", "", $filename); // Remove back-slashes.

        // Return concatenated directory name and file name.
        return $this->saveToDir . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Retrieves the file information (size, MIME type) by image path.
     * @param string $filePath
     * @return array|boolean
     */
    public function getImageFileInfo($filePath)
    {
        // Try to open file
        if (!is_readable($filePath)) {
            return false;
        }

        // Get file size in bytes.
        $fileSize = filesize($filePath);

        // Get MIME type of the file.
        $finfo = finfo_open(FILEINFO_MIME);
        $mimeType = finfo_file($finfo, $filePath);
        if ($mimeType === false) {
            $mimeType = 'application/octet-stream';
        }

        return [
            'size' => $fileSize,
            'type' => $mimeType
        ];
    }

    /**
     * This method return the image file content. On error, return boolean false.
     * @param string $filePath
     * @return string|boolean
     */
    public function getImageFileContent($filePath)
    {
        return file_get_contents($filePath);
    }

    /**
     * Resize the image, keeping its aspect ratio.
     * @param string $filePath
     * @param integer $desiredWidth
     * @return string
     */
    public function resizeImage($filePath, $desiredWidth = 240)
    {
        // Get original image dimensions.
        list($originalWidth, $originalHeight) = getimagesize($filePath);

        // Calculate aspect ratio
        $aspectRatio = $originalWidth / $originalHeight;
        // Calculate the resulting height
        $desiredHeight = $desiredWidth / $aspectRatio;

        // Get image info
        $fileInfo = $this->getImageFileInfo($filePath);

        // Resize the image
        $resultingImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
        if (substr($fileInfo['type'], 0, 9) == 'image/png') {
            $originalImage = imagecreatefrompng($filePath);
        } else {
            $originalImage = imagecreatefromjpeg($filePath);
        }

        imagecopyresampled($resultingImage, $originalImage, 0, 0, 0, 0,
            $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);

        // Save the resized image to temporary location
        $tmpFileName = tempnam('/tmp', 'FOO');
        imagejpeg($resultingImage, $tmpFileName, 80);

        // Return the path to resulting image.
        return $tmpFileName;
    }

    /**
     * Delete file from file system.
     * @param string $filename
     */
    public function deleteImageFile($filename)
    {
        $filepath = $this->getImagePathByName($filename);
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
}
