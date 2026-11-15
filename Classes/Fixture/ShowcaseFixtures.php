<?php

declare(strict_types=1);

namespace DWenzel\T3events\Fixture;

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

/**
 * Showcase of t3events: a page tree with a storage folder, event records and
 * pages with plugins in typical configurations.
 *
 * The result is a DataHandler data map. Records reference each other by
 * NEW ids; dates are relative to the given day, so "upcoming" and "past"
 * stay true whenever the fixtures are seeded.
 *
 * Integrators can use the plugin configurations below as examples: each
 * content element lists its settings in the subheader.
 */
class ShowcaseFixtures
{
    public const ROOT_PAGE = 'NEWshowcaseRoot';
    public const STORAGE_FOLDER = 'NEWstorageFolder';
    public const EVENT_DETAIL_PAGE = 'NEWeventDetailPage';

    public const ROOT_PAGE_TITLE = 't3events showcase';

    protected const ORDER_DATE_ASC = 'performances.date|asc,performances.begin|asc';
    protected const ORDER_DATE_DESC = 'performances.date|desc,performances.begin|desc';

    protected const DOKTYPE_PAGE = 1;
    protected const DOKTYPE_FOLDER = 254;

    /**
     * Pages below the showcase root, in menu order.
     * NEW id => [title, slug segment, hidden in menu]
     */
    protected const SUBPAGES = [
        'NEWupcomingPage' => ['Upcoming events', 'upcoming-events', false],
        'NEWpastPage' => ['Past events', 'past-events', false],
        'NEWconcertsPage' => ['Concerts only', 'concerts', false],
        'NEWperformancesPage' => ['Performances', 'performances', false],
        self::EVENT_DETAIL_PAGE => ['Event detail', 'event', true],
        self::STORAGE_FOLDER => ['Event records', 'event-records', true],
    ];

    /**
     * @return array<string, array<string, array<string, mixed>>> DataHandler data map
     */
    public function dataMap(int $parentPageUid, \DateTimeImmutable $today): array
    {
        return [
            'pages' => $this->pages($parentPageUid),
            'sys_template' => $this->templates(),
            'tx_t3events_domain_model_eventtype' => [
                'NEWtypeConcert' => ['pid' => self::STORAGE_FOLDER, 'title' => 'Concert'],
                'NEWtypeWorkshop' => ['pid' => self::STORAGE_FOLDER, 'title' => 'Workshop'],
                'NEWtypeFestival' => ['pid' => self::STORAGE_FOLDER, 'title' => 'Festival'],
            ],
            'tx_t3events_domain_model_genre' => [
                'NEWgenreJazz' => ['pid' => self::STORAGE_FOLDER, 'title' => 'Jazz'],
                'NEWgenreClassical' => ['pid' => self::STORAGE_FOLDER, 'title' => 'Classical'],
                'NEWgenreTheatre' => ['pid' => self::STORAGE_FOLDER, 'title' => 'Theatre'],
            ],
            'tx_t3events_domain_model_audience' => [
                'NEWaudienceAdults' => ['pid' => self::STORAGE_FOLDER, 'title' => 'Adults'],
                'NEWaudienceChildren' => ['pid' => self::STORAGE_FOLDER, 'title' => 'Children'],
            ],
            'tx_t3events_domain_model_venue' => [
                'NEWvenueHall' => ['pid' => self::STORAGE_FOLDER, 'title' => 'Main hall'],
                'NEWvenueStudio' => ['pid' => self::STORAGE_FOLDER, 'title' => 'Studio'],
            ],
            'tx_t3events_domain_model_organizer' => [
                'NEWorganizer' => ['pid' => self::STORAGE_FOLDER, 'name' => 'Showcase cultural centre'],
            ],
            'tx_t3events_domain_model_performancestatus' => [
                'NEWstatusSoldOut' => ['pid' => self::STORAGE_FOLDER, 'title' => 'Sold out', 'css_class' => 'sold-out', 'priority' => 10],
                'NEWstatusCancelled' => ['pid' => self::STORAGE_FOLDER, 'title' => 'Cancelled', 'css_class' => 'cancelled', 'priority' => 20],
            ],
            'tx_t3events_domain_model_eventlocation' => [
                'NEWlocationCentre' => [
                    'pid' => self::STORAGE_FOLDER,
                    'name' => 'Cultural centre',
                    'address' => 'Example street 1',
                    'zip' => '12345',
                    'place' => 'Example town',
                ],
            ],
            'tx_t3events_domain_model_event' => $this->events(),
            'tx_t3events_domain_model_performance' => $this->performances($today),
            'tt_content' => $this->contentElements(),
        ];
    }

    /**
     * Titles of the pages a tester visits, by NEW id.
     *
     * @return array<string, string>
     */
    public function frontendPages(): array
    {
        $pages = [self::ROOT_PAGE => self::ROOT_PAGE_TITLE];
        foreach (self::SUBPAGES as $id => [$title, , $hiddenInMenu]) {
            if (!$hiddenInMenu) {
                $pages[$id] = $title;
            }
        }

        return $pages;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function pages(int $parentPageUid): array
    {
        $pages = [
            self::ROOT_PAGE => [
                'pid' => $parentPageUid,
                'title' => self::ROOT_PAGE_TITLE,
                'slug' => '/t3events-showcase',
                'doktype' => self::DOKTYPE_PAGE,
                'hidden' => 0,
            ],
        ];
        // DataHandler inserts a page with a positive pid as first child:
        // insert in reverse order to get the menu order of SUBPAGES.
        foreach (array_reverse(self::SUBPAGES, true) as $id => [$title, $slug, $hiddenInMenu]) {
            $pages[$id] = [
                'pid' => self::ROOT_PAGE,
                'title' => $title,
                'slug' => '/t3events-showcase/' . $slug,
                'doktype' => $id === self::STORAGE_FOLDER ? self::DOKTYPE_FOLDER : self::DOKTYPE_PAGE,
                'nav_hide' => $hiddenInMenu ? 1 : 0,
                'hidden' => 0,
            ];
        }

        return $pages;
    }

    /**
     * Extension template on the showcase root: renders the content of the
     * showcase pages and a menu, includes the static TypoScript of t3events.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function templates(): array
    {
        return [
            'NEWtemplate' => [
                'pid' => self::ROOT_PAGE,
                'title' => 't3events showcase',
                'root' => 0,
                'clear' => 0,
                'include_static_file' => 'EXT:t3events/Configuration/TypoScript',
                'constants' => 'plugin.tx_t3events.persistence.storagePid = ',
                'config' => <<<'TYPOSCRIPT'
                    page >
                    page = PAGE
                    page {
                        10 = HMENU
                        10 {
                            special = directory
                            special.value.data = leveluid : 1
                            wrap = <nav><ul>|</ul></nav>
                            1 = TMENU
                            1.NO.wrapItemAndSub = <li>|</li>
                            1.CUR = 1
                            1.CUR.wrapItemAndSub = <li><strong>|</strong></li>
                        }
                        20 = TEXT
                        20.data = page:title
                        20.wrap = <h1>|</h1>
                        30 < styles.content.get
                    }
                    TYPOSCRIPT,
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function events(): array
    {
        $event = static fn(array $values): array => $values + [
            'pid' => self::STORAGE_FOLDER,
            'organizer' => 'NEWorganizer',
            'hidden' => 0,
        ];

        return [
            'NEWeventJazz' => $event([
                'headline' => 'Jazz evening',
                'subtitle' => 'Single performance',
                'teaser' => 'An event with exactly one upcoming performance.',
                'description' => '<p>Shows the most common case: one event, one date.</p>',
                'event_type' => 'NEWtypeConcert',
                'genre' => 'NEWgenreJazz',
                'venue' => 'NEWvenueHall',
                'audience' => 'NEWaudienceAdults',
                'performances' => 'NEWperfJazz',
            ]),
            'NEWeventWorkshop' => $event([
                'headline' => 'Photography workshop',
                'subtitle' => 'Several performances',
                'teaser' => 'An event with three upcoming dates.',
                'description' => '<p>The list shows the event once, the performance list shows every date.</p>',
                'event_type' => 'NEWtypeWorkshop',
                'venue' => 'NEWvenueStudio',
                'audience' => 'NEWaudienceAdults',
                'performances' => 'NEWperfWorkshop1,NEWperfWorkshop2,NEWperfWorkshop3',
            ]),
            'NEWeventFestival' => $event([
                'headline' => 'Summer festival',
                'subtitle' => 'Running now',
                'teaser' => 'One performance that started yesterday and ends in two days.',
                'description' => '<p>Visible in "upcoming" lists only if the end date is respected.</p>',
                'event_type' => 'NEWtypeFestival',
                'genre' => 'NEWgenreJazz,NEWgenreClassical',
                'venue' => 'NEWvenueHall',
                'audience' => 'NEWaudienceAdults,NEWaudienceChildren',
                'performances' => 'NEWperfFestival',
            ]),
            'NEWeventMatinee' => $event([
                'headline' => 'Chamber music matinee',
                'subtitle' => 'Past event',
                'teaser' => 'An event whose only performance was a month ago.',
                'description' => '<p>Appears in lists for past events only.</p>',
                'event_type' => 'NEWtypeConcert',
                'genre' => 'NEWgenreClassical',
                'venue' => 'NEWvenueHall',
                'audience' => 'NEWaudienceAdults',
                'performances' => 'NEWperfMatinee',
            ]),
            'NEWeventTheatre' => $event([
                'headline' => 'Children\'s theatre',
                'subtitle' => 'Sold out',
                'teaser' => 'An upcoming performance with the status "Sold out".',
                'description' => '<p>Shows how a performance status is rendered.</p>',
                'event_type' => 'NEWtypeConcert',
                'genre' => 'NEWgenreTheatre',
                'venue' => 'NEWvenueStudio',
                'audience' => 'NEWaudienceChildren',
                'performances' => 'NEWperfTheatre',
            ]),
            'NEWeventCancelled' => $event([
                'headline' => 'Open air concert',
                'subtitle' => 'Cancelled',
                'teaser' => 'An upcoming performance with the status "Cancelled".',
                'description' => '<p>The performance list on "Performances" excludes cancelled performances.</p>',
                'event_type' => 'NEWtypeConcert',
                'genre' => 'NEWgenreClassical',
                'venue' => 'NEWvenueHall',
                'audience' => 'NEWaudienceAdults',
                'performances' => 'NEWperfCancelled',
            ]),
            'NEWeventHidden' => $event([
                'headline' => 'Hidden event',
                'subtitle' => 'Not visible',
                'teaser' => 'A hidden record. It must not appear in the frontend.',
                'event_type' => 'NEWtypeConcert',
                'venue' => 'NEWvenueHall',
                'performances' => 'NEWperfHidden',
                'hidden' => 1,
            ]),
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function performances(\DateTimeImmutable $today): array
    {
        $performance = static fn(string $dayOffset, int $begin, int $end, array $values = []): array => $values + [
            'pid' => self::STORAGE_FOLDER,
            'date' => $today->modify($dayOffset)->getTimestamp(),
            'admission' => $begin - 1800,
            'begin' => $begin,
            'end' => $end,
            'event_location' => 'NEWlocationCentre',
        ];

        return [
            'NEWperfJazz' => $performance('+14 days', self::time(19, 30), self::time(22, 0)),
            'NEWperfWorkshop1' => $performance('+7 days', self::time(10, 0), self::time(16, 0)),
            'NEWperfWorkshop2' => $performance('+14 days', self::time(10, 0), self::time(16, 0)),
            'NEWperfWorkshop3' => $performance('+21 days', self::time(10, 0), self::time(16, 0), [
                'status' => 'NEWstatusSoldOut',
            ]),
            'NEWperfFestival' => $performance('-1 day', self::time(12, 0), self::time(23, 0), [
                'end_date' => $today->modify('+2 days')->getTimestamp(),
            ]),
            'NEWperfMatinee' => $performance('-30 days', self::time(11, 0), self::time(13, 0)),
            'NEWperfTheatre' => $performance('+10 days', self::time(15, 0), self::time(16, 30), [
                'status' => 'NEWstatusSoldOut',
            ]),
            'NEWperfCancelled' => $performance('+5 days', self::time(20, 0), self::time(22, 30), [
                'status' => 'NEWstatusCancelled',
            ]),
            'NEWperfHidden' => $performance('+3 days', self::time(18, 0), self::time(20, 0)),
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function contentElements(): array
    {
        return [
            'NEWcontentUpcoming' => $this->plugin(
                'NEWupcomingPage',
                't3events_eventlist',
                'Upcoming events',
                'Event list: period future only, respecting end dates, ordered by date ascending, details on "Event detail".',
                [
                    'sDEF' => ['settings.order' => self::ORDER_DATE_ASC, 'settings.maxItems' => '20'],
                    'constraints' => ['settings.period' => 'futureOnly', 'settings.respectEndDate' => '1'],
                    'pages' => ['settings.detailPid' => self::EVENT_DETAIL_PAGE],
                ]
            ),
            'NEWcontentPast' => $this->plugin(
                'NEWpastPage',
                't3events_eventlist',
                'Past events',
                'Event list: period past only, ordered by date descending.',
                [
                    'sDEF' => ['settings.order' => self::ORDER_DATE_DESC],
                    'constraints' => ['settings.period' => 'pastOnly'],
                    'pages' => ['settings.detailPid' => self::EVENT_DETAIL_PAGE],
                ]
            ),
            'NEWcontentConcerts' => $this->plugin(
                'NEWconcertsPage',
                't3events_eventlist',
                'Upcoming concerts',
                'Event list: period future only, event type "Concert".',
                [
                    'sDEF' => ['settings.order' => self::ORDER_DATE_ASC],
                    'constraints' => [
                        'settings.period' => 'futureOnly',
                        'settings.eventTypes' => 'NEWtypeConcert',
                    ],
                    'pages' => ['settings.detailPid' => self::EVENT_DETAIL_PAGE],
                ]
            ),
            'NEWcontentPerformances' => $this->plugin(
                'NEWperformancesPage',
                't3events_performancelist',
                'Upcoming performances',
                'Performance list: one entry per date, period future only, cancelled performances excluded, ordered by date ascending.',
                [
                    'sDEF' => ['settings.order' => self::ORDER_DATE_ASC],
                    'constraints' => [
                        'settings.period' => 'futureOnly',
                        'settings.statuses' => 'NEWstatusCancelled',
                        'settings.excludeSelectedStatuses' => '1',
                    ],
                    // the performance list links to the detail view of the event
                    'pages' => ['settings.detailPid' => self::EVENT_DETAIL_PAGE],
                ]
            ),
            'NEWcontentEventDetail' => $this->plugin(
                self::EVENT_DETAIL_PAGE,
                't3events_eventlist',
                'Event detail',
                'Event list plugin on the detail page: renders the event selected in an event or performance list.',
                ['pages' => ['settings.listPid' => 'NEWupcomingPage', 'settings.backPid' => 'NEWupcomingPage']]
            ),
        ];
    }

    /**
     * @param array<string, array<string, string>> $settingsBySheet sheet => [field => value]
     * @return array<string, mixed>
     */
    protected function plugin(string $page, string $contentType, string $header, string $description, array $settingsBySheet): array
    {
        $sheets = [];
        foreach ($settingsBySheet as $sheet => $fields) {
            foreach ($fields as $field => $value) {
                $sheets[$sheet]['lDEF'][$field]['vDEF'] = $value;
            }
        }

        return [
            'pid' => $page,
            'CType' => $contentType,
            'header' => $header,
            'subheader' => $description,
            'pages' => self::STORAGE_FOLDER,
            'pi_flexform' => ['data' => $sheets],
        ];
    }

    protected static function time(int $hours, int $minutes): int
    {
        return $hours * 3600 + $minutes * 60;
    }
}
