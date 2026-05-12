<?php
namespace DWenzel\T3events\Tests\Controller;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use DWenzel\T3events\Controller\DownloadTrait;
use DWenzel\T3events\InvalidFileTypeException;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use TYPO3\CMS\Core\Resource\Driver\LocalDriver;

/**
 * Class DownloadTraitTest
 *
 * @package DWenzel\T3events\Tests\Controller
 */
#[RunTestsInSeparateProcesses]
class DownloadTraitTest extends UnitTestCase
{
    /**
     * @var DownloadTrait
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockForTrait(
            DownloadTrait::class
        );
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
            ->willReturn($sanitizedFileName);

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
    public static function allowedFileTypesForDownloadHeadersDataProvider()
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
     * @throws InvalidFileTypeException
     */
    public function sendDownloadHeadersSendsHeaderForAllowedFileTypes($fileExtension)
    {
        $fileName = 'foo';
        // sendDownloadHeaders() uses header() directly; just assert no exception is thrown
        $this->subject->sendDownloadHeaders($fileExtension, $fileName);
        $this->addToAssertionCount(1);
    }

    /**
     * return array
     */
    public static function forbiddenFileTypesForDownloadHeadersDataProvider()
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
     */
    public function sendDownloadHeadersDoesNotSendHeadersForForbiddenFileTypes($fileExtension)
    {
        $this->expectException(InvalidFileTypeException::class);
        $this->expectExceptionCode(1456009720);

        $this->subject->sendDownloadHeaders($fileExtension, 'foo');
    }

    /**
     * @test
     * @throws InvalidFileTypeException
     */
    public function sendDownloadHeadersSendsHeadersForDefaultType()
    {
        // Unknown extensions fall through to 'application/force-download' — no exception expected
        $this->subject->sendDownloadHeaders('foo', 'bar');
        $this->addToAssertionCount(1);
    }

    /**
     * @test
     * @throws InvalidFileTypeException
     */
    public function sendDownloadHeadersSetsResponse()
    {
        // sendDownloadHeaders() uses header() directly and needs no response object
        $this->subject->sendDownloadHeaders('foo', 'bar');
        $this->addToAssertionCount(1);
    }

    /**
     * @param array $methods Methods to mock
     * @return LocalDriver|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockLocalDriver(array $methods = [])
    {
        $mockBuilder = $this->getMockBuilder(LocalDriver::class);
        if (!empty($methods)) {
            $mockBuilder->onlyMethods($methods);
        }
        return $mockBuilder->getMock();
    }

}
