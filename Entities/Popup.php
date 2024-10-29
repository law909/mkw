<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\PopupRepository")
 * @ORM\Table(name="popup")
 */
class Popup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="nev", type="string", length=255, nullable=true)
     */
    private $nev;

    /**
     * @ORM\Column(type="integer")
     */
    private $displaytime;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $backgroundimageurl;

    /**
     * @ORM\Column(type="string", length=7, options={"default": "#000000"})
     */
    private $overlaybackgroundcolor = '#000000';

    /**
     * Átlátszóság mértéke 0 és 1 között.
     *
     * @ORM\Column(type="float", options={"default": 0.5})
     */
    private $overlayopacity = 0.5;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $headertext;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bodytext;

    /**
     * @ORM\Column(type="string", length=50, options={"default": "Bezár"})
     */
    private $closebuttontext = 'Bezár';

    /**
     * @ORM\Column(type="integer", options={"default": 10})
     */
    private $popuporder = 10;

    /**
     * 1, ha az előző popup bezárása után indul.
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $triggerafterprevious = false;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $inaktiv = false;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contentwidth;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contentheight;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $closebuttoncolor;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $closebuttonbackgroundcolor;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contenttop;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNev()
    {
        return $this->nev;
    }

    /**
     * @param mixed $nev
     */
    public function setNev($nev): void
    {
        $this->nev = $nev;
    }

    /**
     * @return mixed
     */
    public function getDisplaytime()
    {
        return $this->displaytime;
    }

    /**
     * @param mixed $displaytime
     */
    public function setDisplaytime($displaytime): void
    {
        $this->displaytime = $displaytime;
    }

    /**
     * @return mixed
     */
    public function getBackgroundimageurl()
    {
        return $this->backgroundimageurl;
    }

    /**
     * @param mixed $backgroundimageurl
     */
    public function setBackgroundimageurl($backgroundimageurl): void
    {
        $this->backgroundimageurl = $backgroundimageurl;
    }

    /**
     * @return string
     */
    public function getOverlaybackgroundcolor()
    {
        return $this->overlaybackgroundcolor;
    }

    /**
     * @param string $overlaybackgroundcolor
     */
    public function setOverlaybackgroundcolor($overlaybackgroundcolor): void
    {
        $this->overlaybackgroundcolor = $overlaybackgroundcolor;
    }

    /**
     * @return float
     */
    public function getOverlayopacity()
    {
        return $this->overlayopacity;
    }

    /**
     * @param float $overlayopacity
     */
    public function setOverlayopacity($overlayopacity): void
    {
        $this->overlayopacity = $overlayopacity;
    }

    /**
     * @return mixed
     */
    public function getHeadertext()
    {
        return $this->headertext;
    }

    /**
     * @param mixed $headertext
     */
    public function setHeadertext($headertext): void
    {
        $this->headertext = $headertext;
    }

    /**
     * @return mixed
     */
    public function getBodytext()
    {
        return $this->bodytext;
    }

    /**
     * @param mixed $bodytext
     */
    public function setBodytext($bodytext): void
    {
        $this->bodytext = $bodytext;
    }

    /**
     * @return string
     */
    public function getClosebuttontext()
    {
        return $this->closebuttontext;
    }

    /**
     * @param string $closebuttontext
     */
    public function setClosebuttontext($closebuttontext): void
    {
        $this->closebuttontext = $closebuttontext;
    }

    /**
     * @return int
     */
    public function getPopuporder()
    {
        return $this->popuporder;
    }

    /**
     * @param int $popuporder
     */
    public function setPopuporder($popuporder): void
    {
        $this->popuporder = $popuporder;
    }

    /**
     * @return bool
     */
    public function isTriggerafterprevious()
    {
        return $this->triggerafterprevious;
    }

    /**
     * @param bool $triggerafterprevious
     */
    public function setTriggerafterprevious($triggerafterprevious): void
    {
        $this->triggerafterprevious = $triggerafterprevious;
    }

    /**
     * @return bool
     */
    public function isInaktiv()
    {
        return $this->inaktiv;
    }

    /**
     * @param bool $inaktiv
     */
    public function setInaktiv($inaktiv): void
    {
        $this->inaktiv = $inaktiv;
    }

    /**
     * @return mixed
     */
    public function getClosebuttoncolor()
    {
        return $this->closebuttoncolor;
    }

    /**
     * @param mixed $closebuttoncolor
     */
    public function setClosebuttoncolor($closebuttoncolor): void
    {
        $this->closebuttoncolor = $closebuttoncolor;
    }

    /**
     * @return mixed
     */
    public function getClosebuttonbackgroundcolor()
    {
        return $this->closebuttonbackgroundcolor;
    }

    /**
     * @param mixed $closebuttonbackgroundcolor
     */
    public function setClosebuttonbackgroundcolor($closebuttonbackgroundcolor): void
    {
        $this->closebuttonbackgroundcolor = $closebuttonbackgroundcolor;
    }

    /**
     * @return mixed
     */
    public function getContenttop()
    {
        return $this->contenttop;
    }

    /**
     * @param mixed $contenttop
     */
    public function setContenttop($contenttop): void
    {
        $this->contenttop = $contenttop;
    }

    /**
     * @return mixed
     */
    public function getContentwidth()
    {
        return $this->contentwidth;
    }

    /**
     * @param mixed $contentwidth
     */
    public function setContentwidth($contentwidth): void
    {
        $this->contentwidth = $contentwidth;
    }

    /**
     * @return mixed
     */
    public function getContentheight()
    {
        return $this->contentheight;
    }

    /**
     * @param mixed $contentheight
     */
    public function setContentheight($contentheight): void
    {
        $this->contentheight = $contentheight;
    }

}
