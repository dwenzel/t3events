<?php

namespace DWenzel\T3events\Tests\Controller\Backend;

use DWenzel\T3events\Controller\AbstractBackendController;
use DWenzel\T3events\Controller\Backend\FormTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;

class FormTraitTest extends UnitTestCase
{

    /**
     * @var AbstractBackendController
     */
    protected $subject;

    /**
     * set up
     */
    protected function setUp()
    {
        $this->subject = $this->getMockForTrait(FormTrait::class);
    }

    /**
     * @test
     */
    public function dummy() {
        $this->markTestIncomplete();
    }
}
