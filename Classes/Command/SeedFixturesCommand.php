<?php

declare(strict_types=1);

namespace DWenzel\T3events\Command;

/**
 * This file is part of the "Events" project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DWenzel\T3events\Fixture\ApplicationContextGuard;
use DWenzel\T3events\Fixture\FixtureSeeder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Core\ApplicationContext;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Site\SiteFinder;

/**
 * Seeds (or purges) the showcase fixtures: a page tree with event records and
 * pages with plugins in typical configurations. Used for manual tests and as
 * examples for integrators.
 *
 * Idempotent: a seed purges previously seeded fixtures first, `--purge` only
 * removes them. Refused outside Development, Testing and Staging contexts.
 */
class SeedFixturesCommand extends Command
{
    public const OPTION_PURGE = 'purge';
    public const OPTION_DRY_RUN = 'dry-run';
    public const OPTION_PARENT = 'parent';

    public function __construct(
        protected readonly FixtureSeeder $seeder,
        protected readonly ApplicationContextGuard $contextGuard,
        protected readonly SiteFinder $siteFinder,
        protected readonly LanguageServiceFactory $languageServiceFactory,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Seeds (or, with --purge, removes) the t3events showcase fixtures. Idempotent. Refused in Production.');
        $this->setHelp(
            'Creates the page tree "t3events showcase" with a storage folder, event records and pages with'
            . ' event and performance list plugins. Dates are relative to today. Running the command again'
            . ' replaces the previously seeded fixtures.'
        );
        $this->addOption(
            self::OPTION_PURGE,
            null,
            InputOption::VALUE_NONE,
            'Remove the previously seeded fixtures instead of (re-)seeding them.'
        );
        $this->addOption(
            self::OPTION_DRY_RUN,
            null,
            InputOption::VALUE_NONE,
            'Report what would be inserted or removed, then roll back without persisting anything.'
        );
        $this->addOption(
            self::OPTION_PARENT,
            null,
            InputOption::VALUE_REQUIRED,
            'Uid of the page below which the showcase page tree is created. Default: the root page of the first site.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $context = $this->applicationContext();
        $dryRun = (bool)$input->getOption(self::OPTION_DRY_RUN);

        $io->text(sprintf('Application context: %s', (string)$context));
        $this->contextGuard->assertAllowed($context);
        $this->initializeBackendUser();

        if ($dryRun) {
            $io->note('Dry run: everything is executed inside a transaction and rolled back. Nothing is persisted.');
        }

        if ($input->getOption(self::OPTION_PURGE)) {
            $purged = $this->seeder->purge($dryRun);
            $io->table(['Table', 'Rows deleted'], $this->rows($purged));
            $io->success(sprintf($dryRun ? 'Would delete %d row(s).' : 'Deleted %d row(s).', array_sum($purged)));

            return Command::SUCCESS;
        }

        $parentPageUid = $this->determineParentPageUid($input->getOption(self::OPTION_PARENT));
        if ($parentPageUid === null) {
            $io->error('No parent page: no site is configured. Create a site or pass --' . self::OPTION_PARENT . '.');

            return Command::FAILURE;
        }

        $report = $this->seeder->seed($parentPageUid, new \DateTimeImmutable('today'), $dryRun);
        if ($report['purged'] !== []) {
            $io->text('Previously seeded fixtures removed first:');
            $io->table(['Table', 'Rows deleted'], $this->rows($report['purged']));
        }
        $io->text($dryRun ? 'Would be inserted:' : 'Inserted:');
        $io->table(['Table', 'Records'], $this->rows($report['inserted']));
        if (!$dryRun) {
            $io->text('Pages to visit:');
            $io->table(['Page', 'Uid', 'URL'], $this->pageRows($report['pages']));
        }
        $io->success(sprintf(
            $dryRun ? 'Would seed %d record(s).' : 'Seeded %d record(s).',
            array_sum($report['inserted'])
        ));

        return Command::SUCCESS;
    }

    protected function applicationContext(): ApplicationContext
    {
        return Environment::getContext();
    }

    protected function initializeBackendUser(): void
    {
        Bootstrap::initializeBackendAuthentication();
        $GLOBALS['LANG'] = $this->languageServiceFactory->createFromUserPreferences($GLOBALS['BE_USER']);
    }

    protected function determineParentPageUid(mixed $option): ?int
    {
        if (is_string($option) && ctype_digit($option)) {
            return (int)$option;
        }
        foreach ($this->siteFinder->getAllSites() as $site) {
            return $site->getRootPageId();
        }

        return null;
    }

    /**
     * @param array<int, string> $pages uid => title
     * @return list<array{string, int, string}>
     */
    protected function pageRows(array $pages): array
    {
        $rows = [];
        foreach ($pages as $uid => $title) {
            try {
                $url = (string)$this->siteFinder->getSiteByPageId($uid)->getRouter()->generateUri($uid);
            } catch (SiteNotFoundException) {
                $url = '-';
            }
            $rows[] = [$title, $uid, $url];
        }

        return $rows;
    }

    /**
     * @param array<string, int> $counts
     * @return list<array{string, int}>
     */
    protected function rows(array $counts): array
    {
        $rows = [];
        foreach ($counts as $table => $count) {
            $rows[] = [$table, $count];
        }

        return $rows;
    }
}
