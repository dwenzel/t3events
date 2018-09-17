<?php

namespace Functional\Domain\Repository;
use DWenzel\T3events\Domain\Model\Person;
use DWenzel\T3events\Domain\Repository\PersonRepository;
use DWenzel\T3events\Object\DateImmutable;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;


/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

class PersonRepositoryTest extends FunctionalTestCase
{
    /**
     * @var PersonRepository
     */
    protected $subject;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    protected $testExtensionsToLoad = ['typo3conf/ext/t3events'];


    /**
     * set up
     * @throws \Nimut\TestingFramework\Exception\Exception
     */
    public function setUp()
    {
        parent::setUp();
        $this->importDataSet(__DIR__ . '/../../Fixtures/persons.xml');
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->subject = $this->objectManager->get(PersonRepository::class);
    }

    /**
     * @test
     */
    public function birthdayIsCorrectlyRestoredFromDatabase() {
        $expectedDateString = '2018-09-16T00:00:00+0200';
        $date = new DateImmutable($expectedDateString);

            /** @var QueryResult $persons */
        //$persons = $this->subject->findOneByName('validBirthdayISO8601-2018-09-16-GMT+2h');
        /** @var Person $person */
        $person = $this->subject->findByUid(1);
        $person->setBirthday($date);
        var_dump(get_class($person));

        $birthday = $person->getBirthday();
        var_dump($birthday);
        die();
        $this->assertSame(
            $birthday->format(\DateTime::ATOM),
            $expectedDateString
        );
    }
}