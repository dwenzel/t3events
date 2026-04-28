<?php
namespace DWenzel\T3events\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class Content extends AbstractEntity
{
    protected ?\DateTime $crdate = null;
    protected ?\DateTime $tstamp = null;
    protected ?string $CType = null;
    protected ?string $header = null;
    protected ?string $headerPosition = null;
    protected ?string $bodytext = null;
    protected ?int $colPos = null;
    protected ?string $image = null;
    protected ?int $imagewidth = null;
    protected ?int $imageorient = null;
    protected ?string $imagecaption = null;
    protected ?int $imagecols = null;
    protected ?int $imageborder = null;
    protected ?string $media = null;
    protected ?string $layout = null;
    protected ?int $cols = null;
    protected ?string $subheader = null;
    protected ?string $headerLink = null;
    protected ?string $imageLink = null;
    protected ?string $imageZoom = null;
    protected ?string $altText = null;
    protected ?string $titleText = null;
    protected ?string $headerLayout = null;
    protected ?string $listType = null;

    public function getCrdate(): ?\DateTime
    {
        return $this->crdate;
    }

    public function setCrdate(?\DateTime $crdate): void
    {
        $this->crdate = $crdate;
    }

    public function getTstamp(): ?\DateTime
    {
        return $this->tstamp;
    }

    public function setTstamp(?\DateTime $tstamp): void
    {
        $this->tstamp = $tstamp;
    }

    public function getCType(): ?string
    {
        return $this->CType;
    }

    public function setCType(?string $ctype): void
    {
        $this->CType = $ctype;
    }

    public function getHeader(): ?string
    {
        return $this->header;
    }

    public function setHeader(?string $header): void
    {
        $this->header = $header;
    }

    public function getHeaderPosition(): ?string
    {
        return $this->headerPosition;
    }

    public function setHeaderPosition(?string $headerPosition): void
    {
        $this->headerPosition = $headerPosition;
    }

    public function getBodytext(): ?string
    {
        return $this->bodytext;
    }

    public function setBodytext(?string $bodytext): void
    {
        $this->bodytext = $bodytext;
    }

    public function getColPos(): int
    {
        return (int)$this->colPos;
    }

    public function setColPos(?int $colPos): void
    {
        $this->colPos = $colPos;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getImagewidth(): ?int
    {
        return $this->imagewidth;
    }

    public function setImagewidth(?int $imagewidth): void
    {
        $this->imagewidth = $imagewidth;
    }

    public function getImageorient(): ?int
    {
        return $this->imageorient;
    }

    public function setImageorient(?int $imageorient): void
    {
        $this->imageorient = $imageorient;
    }

    public function getImagecaption(): ?string
    {
        return $this->imagecaption;
    }

    public function setImagecaption(?string $imagecaption): void
    {
        $this->imagecaption = $imagecaption;
    }

    public function getImagecols(): ?int
    {
        return $this->imagecols;
    }

    public function setImagecols(?int $imagecols): void
    {
        $this->imagecols = $imagecols;
    }

    public function getImageborder(): ?int
    {
        return $this->imageborder;
    }

    public function setImageborder(?int $imageborder): void
    {
        $this->imageborder = $imageborder;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(?string $media): void
    {
        $this->media = $media;
    }

    public function getLayout(): ?string
    {
        return $this->layout;
    }

    public function setLayout(?string $layout): void
    {
        $this->layout = $layout;
    }

    public function getCols(): ?int
    {
        return $this->cols;
    }

    public function setCols(?int $cols): void
    {
        $this->cols = $cols;
    }

    public function getSubheader(): ?string
    {
        return $this->subheader;
    }

    public function setSubheader(?string $subheader): void
    {
        $this->subheader = $subheader;
    }

    public function getHeaderLink(): ?string
    {
        return $this->headerLink;
    }

    public function setHeaderLink(?string $headerLink): void
    {
        $this->headerLink = $headerLink;
    }

    public function getImageLink(): ?string
    {
        return $this->imageLink;
    }

    public function setImageLink(?string $imageLink): void
    {
        $this->imageLink = $imageLink;
    }

    public function getImageZoom(): ?string
    {
        return $this->imageZoom;
    }

    public function setImageZoom(?string $imageZoom): void
    {
        $this->imageZoom = $imageZoom;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function setAltText(?string $altText): void
    {
        $this->altText = $altText;
    }

    public function getTitleText(): ?string
    {
        return $this->titleText;
    }

    public function setTitleText(?string $titleText): void
    {
        $this->titleText = $titleText;
    }

    public function getHeaderLayout(): ?string
    {
        return $this->headerLayout;
    }

    public function setHeaderLayout(?string $headerLayout): void
    {
        $this->headerLayout = $headerLayout;
    }

    public function getListType(): ?string
    {
        return $this->listType;
    }

    public function setListType(?string $listType): void
    {
        $this->listType = $listType;
    }
}
