<?php
namespace DWenzel\T3events\Domain\Model;

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
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;


/**
 * Class Performance
 * @package DWenzel\T3events\Domain\Model
 */
class Performance extends AbstractEntity
{
    use EqualsTrait;

    /**
     * date
     */
    protected ?\DateTime $date = null;

    /**
     * admission
     */
    protected ?int $admission = null;

    /**
     * begin
     */
    protected ?int $begin = null;

    /**
     * end
     */
    protected ?int $end = null;

    /**
     * statusInfo
     */
    protected ?string $statusInfo = null;

    /**
     * externalProviderLink
     */
    protected ?string $externalProviderLink = null;

    /**
     * additionalLink
     */
    protected ?string $additionalLink = null;

    /**
     * providerType
     */
    protected int $providerType = 0;

    /**
     * image
     */
    protected ?string $image = null;

    /**
     * images
     *
     * @var ObjectStorage<FileReference>
     */
    #[Lazy]
    protected ObjectStorage $images;

    /**
     * plan
     *
     * @var ObjectStorage<FileReference>
     */
    #[Lazy]
    protected ObjectStorage $plan;

    /**
     * noHandlingFee
     */
    protected bool $noHandlingFee = false;

    /**
     * priceNotice
     */
    protected ?string $priceNotice = null;

    /**
     * @var Event|null
     */
    #[Lazy]
    protected LazyLoadingProxy|Event|null $event = null;

    /**
     * eventLocation
     *
     * @var EventLocation|null
     */
    #[Lazy]
    protected LazyLoadingProxy|EventLocation|null $eventLocation = null;

    /**
     * ticketClass
     *
     * @var ObjectStorage<TicketClass>
     */
    #[Lazy]
    protected ObjectStorage $ticketClass;

    /**
     * status
     *
     * @var PerformanceStatus|null
     */
    #[Lazy]
    protected LazyLoadingProxy|PerformanceStatus|null $status = null;

    /**
     * hidden
     */
    protected int $hidden = 0;

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all \TYPO3\CMS\Extbase\Persistence\ObjectStorage properties.
     */
    protected function initStorageObjects(): void
    {
        /**
         * Do not modify this method!
         * It will be rewritten on each save in the extension builder
         * You may modify the constructor of this class instead
         */
        $this->images = new ObjectStorage();
        $this->ticketClass = new ObjectStorage();
        $this->plan = new ObjectStorage();
    }

    /**
     * Returns the event
     *
     * @return Event|null
     */
    public function getEvent(): ?Event
    {
        if ($this->event instanceof LazyLoadingProxy) {
            /** @var Event $instance */
            $instance = $this->event->_loadRealInstance();
            return $instance;
        }
        return $this->event;
    }

    /**
     * Sets the event
     */
    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }

    /**
     * Returns the eventLocation
     */
    public function getEventLocation(): ?EventLocation
    {
        if ($this->eventLocation instanceof LazyLoadingProxy) {
            /** @var EventLocation $instance */
            $instance = $this->eventLocation->_loadRealInstance();
            return $instance;
        }
        return $this->eventLocation;
    }

    /**
     * Sets the eventLocation
     */
    public function setEventLocation(EventLocation $eventLocation): void
    {
        $this->eventLocation = $eventLocation;
    }

    /**
     * Returns the date
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * Sets the date
     */
    public function setDate(?\DateTime $date): void
    {
        $this->date = $date;
    }

    protected ?\DateTime $endDate = null;

    /**
     * Gets the end date
     */
    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    /**
     * Sets the end date
     */
    public function setEndDate(?\DateTime $date): void
    {
        $this->endDate = $date;
    }

    /**
     * Returns the admission
     */
    public function getAdmission(): ?int
    {
        return $this->admission;
    }

    /**
     * Sets the admission
     */
    public function setAdmission(?int $admission): void
    {
        $this->admission = $admission;
    }

    /**
     * Returns the begin
     */
    public function getBegin(): ?int
    {
        return $this->begin;
    }

    /**
     * Sets the begin
     */
    public function setBegin(?int $begin): void
    {
        $this->begin = $begin;
    }

    /**
     * Returns the end
     */
    public function getEnd(): ?int
    {
        return $this->end;
    }

    /**
     * Sets the end
     */
    public function setEnd(?int $end): void
    {
        $this->end = $end;
    }

    /**
     * Returns the statusInfo
     */
    public function getStatusInfo(): ?string
    {
        return $this->statusInfo;
    }

    /**
     * Sets the statusInfo
     */
    public function setStatusInfo(?string $statusInfo): void
    {
        $this->statusInfo = $statusInfo;
    }

    /**
     * Returns the image
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Sets the image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /**
     * Adds an image
     *
     * @param FileReference $image Image
     */
    public function addImages(FileReference $image): void
    {
        $this->images->attach($image);
    }

    /**
     * Removes an image
     *
     * @param FileReference $imageToRemove Image
     */
    public function removeImages(FileReference $imageToRemove): void
    {
        $this->images->detach($imageToRemove);
    }

    /**
     * Returns the images
     *
     * @return ObjectStorage<FileReference>
     */
    public function getImages(): ObjectStorage
    {
        return $this->images;
    }

    /**
     * Sets the images
     *
     * @param ObjectStorage<FileReference> $images
     */
    public function setImages(ObjectStorage $images): void
    {
        $this->images = $images;
    }

    /**
     * Adds a plan
     *
     * @param FileReference $plan Plan
     */
    public function addPlan(FileReference $plan): void
    {
        $this->plan->attach($plan);
    }

    /**
     * Removes a plan
     *
     * @param FileReference $planToRemove $planToRemove
     */
    public function removePlan(FileReference $planToRemove): void
    {
        $this->plan->detach($planToRemove);
    }

    /**
     * Returns the plan
     *
     * @return ObjectStorage<FileReference>
     */
    public function getPlan(): ObjectStorage
    {
        return $this->plan;
    }

    /**
     * Sets the plan
     *
     * @param ObjectStorage<FileReference> $plan
     */
    public function setPlan(ObjectStorage $plan): void
    {
        $this->plan = $plan;
    }

    /**
     * Returns the noHandlingFee
     */
    public function getNoHandlingFee(): bool
    {
        return $this->noHandlingFee;
    }

    /**
     * Sets the noHandlingFee
     */
    public function setNoHandlingFee(bool $noHandlingFee): void
    {
        $this->noHandlingFee = $noHandlingFee;
    }

    /**
     * Returns the boolean state of noHandlingFee
     */
    public function isNoHandlingFee(): bool
    {
        return $this->getNoHandlingFee();
    }

    /**
     * Returns the status
     */
    public function getStatus(): ?PerformanceStatus
    {
        if ($this->status instanceof LazyLoadingProxy) {
            /** @var PerformanceStatus $instance */
            $instance = $this->status->_loadRealInstance();
            return $instance;
        }
        return $this->status;
    }

    /**
     * Sets the status
     */
    public function setStatus(PerformanceStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * Returns the priceNotice
     */
    public function getPriceNotice(): ?string
    {
        return $this->priceNotice;
    }

    /**
     * Sets the priceNotice
     */
    public function setPriceNotice(?string $priceNotice): void
    {
        $this->priceNotice = $priceNotice;
    }

    /**
     * Adds a TicketClass
     */
    public function addTicketClass(TicketClass $ticketClass): void
    {
        $this->ticketClass->attach($ticketClass);
    }

    /**
     * Removes a TicketClass
     *
     * @param TicketClass $ticketClassToRemove The TicketClass to be removed
     */
    public function removeTicketClass(TicketClass $ticketClassToRemove): void
    {
        $this->ticketClass->detach($ticketClassToRemove);
    }

    /**
     * Returns the ticketClass
     *
     * @return ObjectStorage<TicketClass>
     */
    public function getTicketClass(): ObjectStorage
    {
        return $this->ticketClass;
    }

    /**
     * Sets the ticketClass
     *
     * @param ObjectStorage<TicketClass> $ticketClass
     */
    public function setTicketClass(ObjectStorage $ticketClass): void
    {
        $this->ticketClass = $ticketClass;
    }

    /**
     * Returns the additionalLink
     */
    public function getAdditionalLink(): ?string
    {
        return $this->additionalLink;
    }

    /**
     * Sets the additionalLink
     */
    public function setAdditionalLink(?string $additionalLink): void
    {
        $this->additionalLink = $additionalLink;
    }

    /**
     * Returns the externalProviderLink
     */
    public function getExternalProviderLink(): ?string
    {
        return $this->externalProviderLink;
    }

    /**
     * Sets the externalProviderLink
     */
    public function setExternalProviderLink(?string $externalProviderLink): void
    {
        $this->externalProviderLink = $externalProviderLink;
    }

    /**
     * Returns the providerType
     */
    public function getProviderType(): int
    {
        return $this->providerType;
    }

    /**
     * Sets the providerType
     */
    public function setProviderType(int $providerType): void
    {
        $this->providerType = $providerType;
    }

    /**
     * Return hidden
     */
    public function getHidden(): int
    {
        return $this->hidden;
    }

    /**
     * Set hidden
     */
    public function setHidden(int $hidden): void
    {
        $this->hidden = $hidden;
    }
}
