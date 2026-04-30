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

    public function getCrdate()
    {
        return $this->crdate;
    }

    public function setCrdate(?\DateTime $crdate)
    {
        $this->crdate = $crdate;
    }

    public function getTstamp()
    {
        return $this->tstamp;
    }

    public function setTstamp(?\DateTime $tstamp)
    {
        $this->tstamp = $tstamp;
    }

    public function getCType()
    {
        return $this->CType;
    }

    public function setCType(?string $ctype)
    {
        $this->CType = $ctype;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function setHeader(?string $header)
    {
        $this->header = $header;
    }

    public function getHeaderPosition()
    {
        return $this->headerPosition;
    }

    public function setHeaderPosition(?string $headerPosition)
    {
        $this->headerPosition = $headerPosition;
    }

    public function getBodytext()
    {
        return $this->bodytext;
    }

    public function setBodytext(?string $bodytext)
    {
        $this->bodytext = $bodytext;
    }

    public function getColPos()
    {
        return (int)$this->colPos;
    }

    public function setColPos(?int $colPos)
    {
        $this->colPos = $colPos;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage(?string $image)
    {
        $this->image = $image;
    }

    public function getImagewidth()
    {
        return $this->imagewidth;
    }

    public function setImagewidth(?int $imagewidth)
    {
        $this->imagewidth = $imagewidth;
    }

    public function getImageorient()
    {
        return $this->imageorient;
    }

    public function setImageorient(?int $imageorient)
    {
        $this->imageorient = $imageorient;
    }

    public function getImagecaption()
    {
        return $this->imagecaption;
    }

    public function setImagecaption(?string $imagecaption)
    {
        $this->imagecaption = $imagecaption;
    }

    public function getImagecols()
    {
        return $this->imagecols;
    }

    public function setImagecols(?int $imagecols)
    {
        $this->imagecols = $imagecols;
    }

    public function getImageborder()
    {
        return $this->imageborder;
    }

    public function setImageborder(?int $imageborder)
    {
        $this->imageborder = $imageborder;
    }

    public function getMedia()
    {
        return $this->media;
    }

    public function setMedia(?string $media)
    {
        $this->media = $media;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function setLayout(?string $layout)
    {
        $this->layout = $layout;
    }

    public function getCols()
    {
        return $this->cols;
    }

    public function setCols(?int $cols)
    {
        $this->cols = $cols;
    }

    public function getSubheader()
    {
        return $this->subheader;
    }

    public function setSubheader(?string $subheader)
    {
        $this->subheader = $subheader;
    }

    public function getHeaderLink()
    {
        return $this->headerLink;
    }

    public function setHeaderLink(?string $headerLink)
    {
        $this->headerLink = $headerLink;
    }

    public function getImageLink()
    {
        return $this->imageLink;
    }

    public function setImageLink(?string $imageLink)
    {
        $this->imageLink = $imageLink;
    }

    public function getImageZoom()
    {
        return $this->imageZoom;
    }

    public function setImageZoom(?string $imageZoom)
    {
        $this->imageZoom = $imageZoom;
    }

    public function getAltText()
    {
        return $this->altText;
    }

    public function setAltText(?string $altText)
    {
        $this->altText = $altText;
    }

    public function getTitleText()
    {
        return $this->titleText;
    }

    public function setTitleText(?string $titleText)
    {
        $this->titleText = $titleText;
    }

    public function getHeaderLayout()
    {
        return $this->headerLayout;
    }

    public function setHeaderLayout(?string $headerLayout)
    {
        $this->headerLayout = $headerLayout;
    }

    public function getListType()
    {
        return $this->listType;
    }

    public function setListType(?string $listType)
    {
        $this->listType = $listType;
    }
}
