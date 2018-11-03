<?php
namespace Cotizador\Service;

/**
 * This service is responsible for manager, displaching and create 
 * thumbnail for display.
 */
class DatasheetManager
{
    /**
     * The directory where we save pdf datasheet files.
     * @var string
     */
    private $saveToDir = './assets';

    /**
     * This method returns path to the directory where we save the pdf file.
     * @return string
     */
    public function getSaveToDir()
    {
        return $this->saveToDir;
    }

    /**
     * This method return the path to the saved pdf datasheet file.
     * @param string $filename
     * @return string
     */
    public function getDatasheetPathByName($filename)
    {
        // Take some precautions to make file name secure.
        $filename = str_replace("/", "", $filename);  // Remove slashes.
        $filename = str_replace("\\", "", $filename); // REmove back-slashes.

        // Return concatenated directory name and file name.
        return $this->saveToDir . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Retrieves the file information (size, MIME type) by file path.
     * @param string $filepath
     * @return array|boolean
     */
    public function getDatasheetFileInfo($filepath)
    {
        // Try to open file
        if (!is_readable($filepath)) {
            return false;
        }

        // Get file size in bytes.
        $filesize = filesize($filepath);

        // Get MIME type of the file.
        $finfo = finfo_open(FILEINFO_MIME);
        $mimeType = finfo_file($finfo, $filepath);
        if ($mimeType === false) {
            $mimeType = 'application/octet-stream';
        }

        return [
            'size' => $filesize,
            'type' => $mimeType,
        ];
    }

    /**
     * This method return the datasheet file content. On error, return boolean false.
     * @param string $filepath
     * @return string|boolean
     */
    public function getDatasheetFileContent($filepath)
    {
        return file_get_contents($filepath);
    }

    /**
     * This method create a thumbnail and return path to it.
     * @param string $filepath
     * @param int $width
     * @param int height
     * @return string
     */
    public function createThumbnail($filename)
    {
        //$target = $this->saveToDir . DIRECTORY_SEPARATOR . 'ds' . $id . '.jpg';

        // Save the thumbnail image to temporary location
        $tmpFileName = tempnam('/tmp', 'FOO');

        $filepath = $filename; // $this->getDatasheetPathByName($filename);
		$im     = new \Imagick($filepath."[0]"); // 0-first page, 1-second page
		//$im->setImageColorspace(255); // prevent image colors from inverting
		$im->setimageformat("jpeg");
		$im->thumbnailimage(210, 280); // width and height
        // $im->writeimage($target);
        $im->writeimage($tmpFileName);
		$im->clear();
        $im->destroy();
        return $tmpFileName;
    }

    /**
     * Delete file from file system.
     * @param  string $filename
     */
    public function deleteDatasheetFile($filename)
    {
        $filepath = $this->getDatasheetPathByName($filename);
        if (file_exists($filename)) {
            unlink($filepath);
        }
    }
}