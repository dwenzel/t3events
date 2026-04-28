<?php
namespace DWenzel\T3events\Controller;

use DWenzel\T3events\Utility\SettingsUtility;
use TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException;
use TYPO3\CMS\Core\Resource\Driver\LocalDriver;
use DWenzel\T3events\InvalidFileTypeException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class DownloadTrait
 */
trait DownloadTrait
{
    protected LocalDriver $localDriver;

    protected SettingsUtility $settingsUtility;

    /**
     * Injects the local driver for file system
     */
    public function injectLocalDriver(LocalDriver $localDriver): void
    {
        $this->localDriver = $localDriver;
    }

    public function getLocalDriver(): LocalDriver
    {
        return $this->localDriver;
    }


    /**
     * Gets a sanitized filename for download
     *
     * @param string $fileName
     * @param $prependDate
     * @return string
     * @throws InvalidFileNameException
     */
    public function getDownloadFileName(string $fileName, bool $prependDate = true): string
    {
        if ($prependDate) {
            $fileName = date('Y-m-d_H-m') . '_' . $fileName;
        }

        return $this->localDriver->sanitizeFileName($fileName);
    }

    /**
     * Creates a download file name, sends download headers renders
     * the view and returns the result
     *
     * @param string $fileExtension
     * @param object $objectForFileName
     * @return string
     * @throws InvalidFileTypeException
     */
    public function getContentForDownload(string $fileExtension, mixed $objectForFileName = null): string
    {
        $controllerKey = $this->settingsUtility->getControllerKey($this);
        $fileName = $controllerKey;
        if (
            $objectForFileName
            && !empty($this->settings['fileName'])
        ) {
            $fileName = $this->settingsUtility->getValue(
                $objectForFileName,
                $this->settings['fileName']
            );
        }

        $fileName = $this->getDownloadFileName($fileName);
        $this->sendDownloadHeaders($fileExtension, $fileName);

        return $this->view->render();
    }


    /**
     * Sends download headers
     *
     * @param string $ext
     * @throws InvalidFileTypeException
     */
    public function sendDownloadHeaders(string $ext, string $fileName): void
    {
        switch ($ext) {
            case 'csv':
                $cType = 'text/csv';
                break;
            case 'txt':
                $cType = 'text/plain';
                break;
            case 'pdf':
                $cType = 'application/pdf';
                break;
            case 'exe':
                $cType = 'application/octet-stream';
                break;
            case 'zip':
                $cType = 'application/zip';
                break;
            case 'doc':
                $cType = 'application/msword';
                break;
            case 'xls':
                $cType = 'application/vnd.ms-excel';
                break;
            case 'ppt':
                $cType = 'application/vnd.ms-powerpoint';
                break;
            case 'gif':
                $cType = 'image/gif';
                break;
            case 'png':
                $cType = 'image/png';
                break;
            case 'jpeg':
            case 'jpg':
                $cType = 'image/jpg';
                break;
            case 'mp3':
                $cType = 'audio/mpeg';
                break;
            case 'wav':
                $cType = 'audio/x-wav';
                break;
            case 'mpeg':
            case 'mpg':
            case 'mpe':
                $cType = 'video/mpeg';
                break;
            case 'mov':
                $cType = 'video/quicktime';
                break;
            case 'avi':
                $cType = 'video/x-msvideo';
                break;

            //forbidden filetypes
            case 'inc':
            case 'conf':
            case 'sql':
            case 'cgi':
            case 'htaccess':
            case 'php':
            case 'php3':
            case 'php4':
            case 'php5':
                throw new InvalidFileTypeException(
                    'Invalid file type ' . $ext . ' for download with file name ' . $fileName,
                    1456009720
                );

            default:
                $cType = 'application/force-download';
                break;
        }

        $headers = [
            'Pragma' => 'public',
            'Expires' => '0',
            'Cache-Control' => 'public',
            'Content-Description' => 'File Transfer',
            'Content-Type' => $cType,
            'Content-Disposition' => 'attachment; filename="' . $fileName . '.' . $ext . '"',
            'Content-Transfer-Encoding' => 'binary',
        ];
        foreach ($headers as $header => $data) {
            header($header . ': ' . $data);
        }
    }
}
