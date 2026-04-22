<?php
namespace DWenzel\T3events\Tests\Controller;

use DWenzel\T3events\Tests\Unit\Object\MockObjectManagerTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
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
    use MockObjectManagerTrait;

    /**
     * @var DownloadTrait
     */
    protected $subject;

    /**
     * @var ObjectManager|\PHPUnit_Framework_MockObject_MockObject
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
        $this->objectManager = $this->getMockObjectManager();

        $this->inject($this->subject, 'objectManager', $this->objectManager);
    }

    /**
     * @test
     */
    public function localDriverCanBeInjected()
    {
        $mockLocalDriver = $this->getMockLocalDriver();

        $this->subject->injectLocalDriver($mockLocalDriver);

        self::assertSame(
            $mockLocalDriver,
            $this->subject->getLocalDriver()
        );
    }

    /**
     * @test
     * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
     */
    public function getDownloadFileNameReturnsSanitizedFileName()
    {
        $fileName = 'foo';
        $sanitizedFileName = 'bar';

        $mockLocalDriver = $this->getMockLocalDriver(['sanitizeFileName']);
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
     * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
     */
    public function getDownloadFileNamePrependsDate()
    {
        $date = date('Y-m-d_H-m');
        $fileName = 'foo';
        $expectedFileName = $date . '_' . $fileName;

        $mockLocalDriver = $this->getMockLocalDriver(['sanitizeFileName']);
        $this->subject->injectLocalDriver($mockLocalDriver);

        $mockLocalDriver->expects($this->once())
            ->method('sanitizeFileName')
            ->with($expectedFileName);

        $this->subject->getDownloadFileName($fileName);
    }

    /**
     * @return array
     */
    public function allowedFileTypesForDownloadHeadersDataProvider()
    {
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
     * @param $fileExtension
     * @throws \DWenzel\T3events\InvalidFileTypeException
     */
    public function sendDownloadHeadersSendsHeaderForAllowedFileTypes($fileExtension)
    {
        $fileName = 'foo';
        $mockResponse = $this->getMockResponse();
        $this->inject($this->subject, 'response', $mockResponse);
        $mockResponse->expects($this->once())
            ->method('sendHeaders');

        $this->subject->sendDownloadHeaders($fileExtension, $fileName);
    }

    /**
     * return array
     */
    public function forbiddenFileTypesForDownloadHeadersDataProvider()
    {
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
    public function sendDownloadHeadersDoesNotSendHeadersForForbiddenFileTypes($fileExtension)
    {
        $fileName = 'foo';

        $mockResponse = $this->getMockResponse();
        $this->inject($this->subject, 'response', $mockResponse);
        $mockResponse->expects($this->never())
            ->method('sendHeaders');

        $this->subject->sendDownloadHeaders($fileExtension, $fileName);
    }

    /**
     * @test
     * @throws \DWenzel\T3events\InvalidFileTypeException
     */
    public function sendDownloadHeadersSendsHeadersForDefaultType()
    {
        $unknownValidExtension = 'foo';
        $fileName = 'bar';
        $mockResponse = $this->getMockResponse();
        $this->inject($this->subject, 'response', $mockResponse);
        $mockResponse->expects($this->once())
            ->method('sendHeaders');

        $this->subject->sendDownloadHeaders($unknownValidExtension, $fileName);
    }

    /**
     * @test
     * @throws \DWenzel\T3events\InvalidFileTypeException
     */
    public function sendDownloadHeadersSetsResponse()
    {
        $unknownValidExtension = 'foo';
        $fileName = 'bar';
        $mockResponse = $this->getMockResponse();

        $this->objectManager->expects($this->once())
            ->method('get')
            ->with(Response::class)
            ->will($this->returnValue($mockResponse));

        $this->subject->sendDownloadHeaders($unknownValidExtension, $fileName);
    }

    /**
     * @param array $methods Methods to mock
     * @return LocalDriver|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockLocalDriver(array $methods = [])
    {
        $mockBuilder = $this->getMockBuilder(LocalDriver::class);
        if (!empty($methods)) {
            $mockBuilder->setMethods($methods);
        }
        return $mockBuilder->getMock();
    }

    /**
     * @param array $methods Methods to mock
     * @return Response|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockResponse(array $methods = ['sendHeaders', 'setHeader'])
    {
        return $this->getMockBuilder(Response::class)
            ->setMethods($methods)->getMock();
    }
}
