<?php
namespace DWenzel\T3events\Controller;
use TYPO3\CMS\Core\Resource\Driver\LocalDriver;
use DWenzel\T3events\InvalidFileTypeException;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Response;

/**
 * Class DownloadTrait
 */
trait DownloadTrait
{
    /**
     * @var \TYPO3\CMS\Core\Resource\Driver\LocalDriver
     */
    protected $localDriver;

    /**
     * The current view, as resolved by resolveView()
     *
     * @var ViewInterface
     */
    protected $view = NULL;

    /**
     * Contains the settings of the current extension
     *
     * @var array
     */
    protected $settings;

    /**
     * @var \DWenzel\T3events\Utility\SettingsUtility
     */
    protected $settingsUtility;

    /**
     * The response which will be returned by this action controller
     *
     * @var \TYPO3\CMS\Extbase\Mvc\Response
     * @api
     */
    protected $response;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Injects the local driver for file system
     *
     * @param \TYPO3\CMS\Core\Resource\Driver\LocalDriver $localDriver
     */
    public function injectLocalDriver(LocalDriver $localDriver)
    {
        $this->localDriver = $localDriver;
    }

    /**
     * Gets a sanitized filename for download
     *
     * @param string $fileName
     * @param $prependDate
     * @return string
     * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
     */
    public function getDownloadFileName($fileName, $prependDate = true)
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
     * @throws \DWenzel\T3events\InvalidFileTypeException
     */
    public function getContentForDownload($fileExtension, $objectForFileName = null)
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
     * @param string $fileName
     * @throws InvalidFileTypeException
     */
    public function sendDownloadHeaders($ext, $fileName)
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
            'Expires' => 0,
            'Cache-Control' => 'public',
            'Content-Description' => 'File Transfer',
            'Content-Type' => $cType,
            'Content-Disposition' => 'attachment; filename="' . $fileName . '.' . $ext . '"',
            'Content-Transfer-Encoding' => 'binary',
        ];
        if (!$this->response instanceof Response) {
            $this->response = $this->objectManager->get(Response::class);
        }
        foreach ($headers as $header => $data) {
            $this->response->setHeader($header, $data);
        }
        $this->response->sendHeaders();
    }
}
