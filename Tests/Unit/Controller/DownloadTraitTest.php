<?php
namespace DWenzel\T3events\Tests\Controller;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use DWenzel\T3events\Controller\DownloadTrait;
use TYPO3\CMS\Core\Resource\Driver\LocalDriver;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Mvc\Web\Response;
/**
 * Class DownloadTraitTest
 *
 * @package DWenzel\T3events\Tests\Controller
 */
class DownloadTraitTest extends UnitTestCase
{
    /**
     * @var DownloadTrait
     */
    protected $subject;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            DownloadTrait::class
        );
        $this->objectManager = $this->getMock(
            ObjectManager::class, ['get']
        );

        $this->inject($this->subject, 'objectManager', $this->objectManager);
    }

    /**
     * @test
     */
    public function localDriverCanBeInjected() {
        $mockLocalDriver = $this->getMock(
            LocalDriver::class
        );

        $this->subject->injectLocalDriver($mockLocalDriver);

        $this->assertAttributeSame(
            $mockLocalDriver,
            'localDriver',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getDownloadFileNameReturnsSanitizedFileName() {
        $fileName = 'foo';
        $sanitizedFileName = 'bar';

        $mockLocalDriver = $this->getMock(
            LocalDriver::class, ['sanitizeFileName']
        );
        $this->subject->injectLocalDriver($mockLocalDriver);

        $mockLocalDriver->expects($this->once())
            ->method('sanitizeFileName')
            ->with($fileName)
            ->will($this->returnValue($sanitizedFileName));

        $this->assertSame(
            $sanitizedFileName,
            $this->subject->getDownloadFileName($fileName, false)
        );
    }

    /**
     * @test
     */
    public function getDownloadFileNamePrependsDate() {
        $date = date('Y-m-d_H-m');
        $fileName = 'foo';
        $expectedFileName = $date . '_' . $fileName;

        $mockLocalDriver = $this->getMock(
            LocalDriver::class, ['sanitizeFileName']
        );
        $this->subject->injectLocalDriver($mockLocalDriver);

        $mockLocalDriver->expects($this->once())
            ->method('sanitizeFileName')
            ->with($expectedFileName);

        $this->subject->getDownloadFileName($fileName);
    }

    /**
     * @return array
     */
    public function allowedFileTypesForDownloadHeadersDataProvider() {
        return [
            'csv' => ['csv', 'text/csv'],
            'txt' => ['txt', 'text/plain'],
            'pdf' => ['pdf', 'application/pdf'],
            'exe' => ['exe', 'application/octet-stream'],
            'zip' => ['zip', 'application/zip'],
            'doc' => ['doc', 'application/msword'],
            'xls' => ['xls', 'application/vnd.ms-excel'],
            'ppt' => ['ppt', 'application/vnd.ms-powerpoint'],
            'gif' => ['gif', 'image/gif'],
            'png' => ['png', 'image/png'],
            'jpeg' => ['jpeg', 'image/jpg'],
            'jpg' => ['jpg', 'image/jpg'],
            'mp3' => ['mp3', 'audio/mpeg'],
            'wav' => ['wav', 'audio/x-wav'],
            'mpeg' => ['mpeg', 'video/mpeg'],
            'mpg' => ['mpg', 'video/mpeg'],
            'mpe' => ['mpg', 'video/mpeg'],
            'mov' => ['mov', 'video/quicktime'],
            'avi' => ['avi', 'video/x-msvideo']
        ];
    }

    /**
     * @test
     * @dataProvider allowedFileTypesForDownloadHeadersDataProvider
     */
    public function sendDownloadHeadersSendsHeaderForAllowedFileTypes($fileExtension, $contentType) {
        $fileName = 'foo';
        $headers = [
            'Pragma' => 'public',
            'Expires' => 0,
            'Cache-Control' => 'public',
            'Content-Description' => 'File Transfer',
            'Content-Type' => $contentType,
            'Content-Disposition' => 'attachment; filename="' . $fileName . '.' . $fileExtension . '"',
            'Content-Transfer-Encoding' => 'binary',
        ];

        $mockResponse = $this->getMock(
            Response::class, ['sendHeaders', 'setHeader']
        );
        $this->inject($this->subject, 'response', $mockResponse);
        $mockResponse->expects($this->once())
            ->method('sendHeaders');

        $this->subject->sendDownloadHeaders($fileExtension, $fileName);
    }

    /**
     * return array
     */
    public function forbiddenFileTypesForDownloadHeadersDataProvider() {
        return [
            'inc' => ['inc'],
            'conf' => ['conf'],
            'sql' => ['sql'],
            'cgi' => ['cgi'],
            'htaccess' => ['htaccess'],
            'php' => ['php'],
            'php3' => ['php3'],
            'php4' => ['php4'],
            'php5' => ['php5'],
        ];
    }

    /**
     * @test
     * @dataProvider forbiddenFileTypesForDownloadHeadersDataProvider
     * @expectedException \DWenzel\T3events\InvalidFileTypeException
     * @expectedExceptionCode 1456009720
     */
    public function sendDownloadHeadersDoesNotSendHeadersForForbiddenFileTypes($fileExtension) {
        $fileName = 'foo';

        $mockResponse = $this->getMock(
            Response::class, ['sendHeaders', 'setHeader']
        );
        $this->inject($this->subject, 'response', $mockResponse);
        $mockResponse->expects($this->never())
            ->method('sendHeaders');

        $this->subject->sendDownloadHeaders($fileExtension, $fileName);

    }

    /**
     * @test
     */
    public function sendDownloadHeadersSendsHeadersForDefaultType() {
        $unknownValidExtension = 'foo';
        $fileName = 'bar';
        $mockResponse = $this->getMock(
            Response::class, ['sendHeaders', 'setHeader']
        );
        $this->inject($this->subject, 'response', $mockResponse);
        $mockResponse->expects($this->once())
            ->method('sendHeaders');

        $this->subject->sendDownloadHeaders($unknownValidExtension, $fileName);
    }

    /**
     * @test
     */
    public function sendDownloadHeadersSetsResponse() {
        $unknownValidExtension = 'foo';
        $fileName = 'bar';
        $mockResponse = $this->getMock(
            Response::class, ['sendHeaders', 'setHeader']
        );

        $this->objectManager->expects($this->once())
            ->method('get')
            ->with(Response::class)
            ->will($this->returnValue($mockResponse));

        $this->subject->sendDownloadHeaders($unknownValidExtension, $fileName);
    }
}
