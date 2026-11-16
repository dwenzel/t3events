<?php

declare(strict_types=1);

namespace DWenzel\T3events\Tests\Functional\Frontend;

use DWenzel\T3events\Fixture\FixtureSeeder;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Yaml\Yaml;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Frontend\Page\CacheHashCalculator;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Base for end-to-end tests of the frontend plugins: a site with root page and
 * root template is set up, the showcase fixtures are seeded, and the showcase
 * pages are requested like a visitor does.
 *
 * The default templates mark list items and detail views with data attributes
 * (data-t3events-event, data-t3events-performance, data-t3events-event-detail,
 * data-t3events-performance-detail); the tests read the record uids from them.
 * Expected records are given by the NEW ids of the showcase fixtures.
 */
abstract class AbstractShowcaseFrontendTestCase extends FunctionalTestCase
{
    protected const BASE_URL = 'https://website.local';
    protected const SHOWCASE_URL = self::BASE_URL . '/t3events-showcase/';

    protected const EVENT_TABLE = 'tx_t3events_domain_model_event';
    protected const PERFORMANCE_TABLE = 'tx_t3events_domain_model_performance';

    protected array $coreExtensionsToLoad = ['install', 'fluid_styled_content'];

    protected array $testExtensionsToLoad = ['dwenzel/t3extension-tools', 'dwenzel/t3events'];

    /** @var array<string, array<string, int>> table => [NEW id => uid] */
    protected array $uids = [];

    /** @var list<array{resource: string}> imports of the site configuration */
    protected array $siteImports = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/../Fixture/Fixtures/SiteRootAndAdmin.csv');
        $this->writeSiteConfiguration();
        $this->setUpFrontendRootPage(
            1,
            [],
            [
                'include_static_file' => 'EXT:fluid_styled_content/Configuration/TypoScript/',
                'config' => 'page = PAGE',
            ]
        );
        $backendUser = $this->setUpBackendUser(1);
        $GLOBALS['LANG'] = $this->get(LanguageServiceFactory::class)->createFromUserPreferences($backendUser);
        $seeder = $this->get(FixtureSeeder::class);
        $seeder->seed(1, new \DateTimeImmutable('today'));
        $this->uids = $seeder->getRecordUids();
    }

    protected function request(string $url): ResponseInterface
    {
        return $this->executeFrontendSubRequest(new InternalRequest($url));
    }

    /**
     * Sends form data as URL encoded body: the testing framework cannot build nested parsed bodies.
     *
     * @param array<string, mixed> $data
     */
    protected function postForm(string $url, array $data): ResponseInterface
    {
        $body = new Stream('php://temp', 'rw');
        $body->write(http_build_query($data));

        return $this->executeFrontendSubRequest(
            (new InternalRequest($url))
                ->withMethod('POST')
                ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
                ->withBody($body)
        );
    }

    /**
     * URL with plugin arguments and a valid cHash (requests without cHash are answered with 404).
     *
     * @param array<string, int|string> $arguments
     */
    protected function pluginUrl(string $pageNewId, string $slug, string $namespace, array $arguments): string
    {
        $query = HttpUtility::buildQueryString([$namespace => $arguments]);
        $cacheHash = GeneralUtility::makeInstance(CacheHashCalculator::class)->generateForParameters(
            HttpUtility::buildQueryString(['id' => $this->uids['pages'][$pageNewId], $namespace => $arguments])
        );

        return self::SHOWCASE_URL . $slug . '?' . $query . '&cHash=' . $cacheHash;
    }

    /**
     * @param list<string> $newIds
     * @return list<string>
     */
    protected function uidsOf(string $table, array $newIds): array
    {
        return array_map(fn(string $newId): string => (string)$this->uids[$table][$newId], $newIds);
    }

    /**
     * @return list<string> values of the attribute in document order
     */
    protected function attributeValues(ResponseInterface $response, string $attribute): array
    {
        $values = [];
        foreach ($this->xpath($response)->query('//*[@' . $attribute . ']/@' . $attribute) as $node) {
            $values[] = (string)$node->nodeValue;
        }

        return $values;
    }

    protected function xpath(ResponseInterface $response): \DOMXPath
    {
        $document = new \DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML((string)$response->getBody());
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return new \DOMXPath($document);
    }

    protected function writeSiteConfiguration(): void
    {
        $path = Environment::getConfigPath() . '/sites/main';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        file_put_contents($path . '/config.yaml', Yaml::dump(array_filter([
            'imports' => $this->siteImports,
            'rootPageId' => 1,
            'base' => self::BASE_URL . '/',
            'languages' => [
                [
                    'title' => 'English',
                    'enabled' => true,
                    'languageId' => 0,
                    'base' => '/',
                    'locale' => 'en_US.UTF-8',
                    'navigationTitle' => 'English',
                    'flag' => 'us',
                ],
            ],
        ]), 99, 2));
    }
}
