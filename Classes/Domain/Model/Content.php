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
    /**
     * @var \DateTime
     */
    protected $crdate;

    /**
     * @var \DateTime
     */
    protected $tstamp;

    /**
     * @var string
     */
    protected $CType;

    /**
     * @var string
     */
    protected $header;

    /**
     * @var string
     */
    protected $headerPosition;

    /**
     * @var string
     */
    protected $bodytext;

    /**
     * @var int
     */
    protected $colPos;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var int
     */
    protected $imagewidth;

    /**
     * @var int
     */
    protected $imageorient;

    /**
     * @var string
     */
    protected $imagecaption;

    /**
     * @var int
     */
    protected $imagecols;

    /**
     * @var int
     */
    protected $imageborder;

    /**
     * @var string
     */
    protected $media;

    /**
     * @var string
     */
    protected $layout;

    /**
     * @var int
     */
    protected $cols;

    /**
     * @var string
     */
    protected $subheader;

    /**
     * @var string
     */
    protected $headerLink;

    /**
     * @var string
     */
    protected $imageLink;

    /**
     * @var string
     */
    protected $imageZoom;

    /**
     * @var string
     */
    protected $altText;

    /**
     * @var string
     */
    protected $titleText;

    /**
     * @var string
     */
    protected $headerLayout;

    /**
     * @var string
     */
    protected $listType;

    /**
     * @return \DateTime
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * @param $crdate
     */
    public function setCrdate($crdate): void
    {
        $this->crdate = $crdate;
    }

    /**
     * @return \DateTime
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * @param $tstamp
     */
    public function setTstamp($tstamp): void
    {
        $this->tstamp = $tstamp;
    }

    /**
     * @return string
     */
    public function getCType()
    {
        return $this->CType;
    }

    /**
     * @param $ctype
     */
    public function setCType($ctype): void
    {
        $this->CType = $ctype;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param $header
     */
    public function setHeader($header): void
    {
        $this->header = $header;
    }

    /**
     * @return string
     */
    public function getHeaderPosition()
    {
        return $this->headerPosition;
    }

    /**
     * @param $headerPosition
     */
    public function setHeaderPosition($headerPosition): void
    {
        $this->headerPosition = $headerPosition;
    }

    /**
     * @return string
     */
    public function getBodytext()
    {
        return $this->bodytext;
    }

    /**
     * @param $bodytext
     */
    public function setBodytext($bodytext): void
    {
        $this->bodytext = $bodytext;
    }

    /**
     * Get the colpos
     */
    public function getColPos(): int
    {
        return (int)$this->colPos;
    }

    /**
     * Set colpos
     *
     * @param int $colPos
     */
    public function setColPos($colPos): void
    {
        $this->colPos = $colPos;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getImagewidth()
    {
        return $this->imagewidth;
    }

    /**
     * @param $imagewidth
     */
    public function setImagewidth($imagewidth): void
    {
        $this->imagewidth = $imagewidth;
    }

    /**
     * @return int
     */
    public function getImageorient()
    {
        return $this->imageorient;
    }

    /**
     * @param $imageorient
     */
    public function setImageorient($imageorient): void
    {
        $this->imageorient = $imageorient;
    }

    /**
     * @return string
     */
    public function getImagecaption()
    {
        return $this->imagecaption;
    }

    /**
     * @param $imagecaption
     */
    public function setImagecaption($imagecaption): void
    {
        $this->imagecaption = $imagecaption;
    }

    /**
     * @return int
     */
    public function getImagecols()
    {
        return $this->imagecols;
    }

    /**
     * @param $imagecols
     */
    public function setImagecols($imagecols): void
    {
        $this->imagecols = $imagecols;
    }

    /**
     * @return int
     */
    public function getImageborder()
    {
        return $this->imageborder;
    }

    /**
     * @param $imageborder
     */
    public function setImageborder($imageborder): void
    {
        $this->imageborder = $imageborder;
    }

    /**
     * @return string
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param $media
     */
    public function setMedia($media): void
    {
        $this->media = $media;
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param $layout
     */
    public function setLayout($layout): void
    {
        $this->layout = $layout;
    }

    /**
     * @return int
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * @param $cols
     */
    public function setCols($cols): void
    {
        $this->cols = $cols;
    }

    /**
     * @return string
     */
    public function getSubheader()
    {
        return $this->subheader;
    }

    /**
     * @param $subheader
     */
    public function setSubheader($subheader): void
    {
        $this->subheader = $subheader;
    }

    /**
     * @return string
     */
    public function getHeaderLink()
    {
        return $this->headerLink;
    }

    /**
     * @param $headerLink
     */
    public function setHeaderLink($headerLink): void
    {
        $this->headerLink = $headerLink;
    }

    /**
     * @return string
     */
    public function getImageLink()
    {
        return $this->imageLink;
    }

    /**
     * @param $imageLink
     */
    public function setImageLink($imageLink): void
    {
        $this->imageLink = $imageLink;
    }

    /**
     * @return string
     */
    public function getImageZoom()
    {
        return $this->imageZoom;
    }

    /**
     * @param $imageZoom
     */
    public function setImageZoom($imageZoom): void
    {
        $this->imageZoom = $imageZoom;
    }

    /**
     * @return string
     */
    public function getAltText()
    {
        return $this->altText;
    }

    /**
     * @param $altText
     */
    public function setAltText($altText): void
    {
        $this->altText = $altText;
    }

    /**
     * @return string
     */
    public function getTitleText()
    {
        return $this->titleText;
    }

    /**
     * @param $titleText
     */
    public function setTitleText($titleText): void
    {
        $this->titleText = $titleText;
    }

    /**
     * @return string
     */
    public function getHeaderLayout()
    {
        return $this->headerLayout;
    }

    /**
     * @param $headerLayout
     */
    public function setHeaderLayout($headerLayout): void
    {
        $this->headerLayout = $headerLayout;
    }

    /**
     * @return string
     */
    public function getListType()
    {
        return $this->listType;
    }

    /**
     * @param $listType
     */
    public function setListType($listType): void
    {
        $this->listType = $listType;
    }
}
