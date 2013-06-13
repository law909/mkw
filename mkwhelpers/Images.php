<?php
namespace mkwhelpers;

class Images
{
	protected $imagefile;
	protected $imageinfo;
	protected $height;
	protected $width;
	protected $newheight;
	protected $newwidth;
	protected $pngquality;
	protected $jpgquality;

	public function __construct($imagefile)
	{
		$this->imagefile=$imagefile;
		$this->getImageInfo();
	}

	public function getImageInfo()
	{
		$x=getimagesize($this->imagefile);
		$this->imageinfo=array('width'=>$x[0],'height'=>$x[1],'type'=>$x[2],'htmlsize'=>$x[3]);
		$this->width=$x[0];
		$this->height=$x[1];
	}

	protected function calcNewDimensions()
	{
		if (isset($this->newwidth)&&($this->newwidth>0))
		{
			$this->newheight=(100/($this->width/$this->newwidth))*.01;
			$this->newheight=round($this->newheight*$this->height);
		}
		elseif (isset($this->newheight)&&($this->newheight>0))
		{
			$this->newwidth=(100/($this->height/$this->newheight))*.01;
			$this->newwidth=round($this->newwidth*$this->width);
		}
		else
		{
			$this->newwidth=$this->width;
			$this->newheight=$this->height;
		}
	}

	public function Resample($newfilename,$newsize)
	{
		if ($this->width>=$this->height) {
			if (($this->width>$newsize) && ($newsize>0) && ($this->width/$newsize>0)) {
				$x=$newsize;
				$y=$this->height/($this->width/$newsize);
			}
			else {
				$x=$this->width;
				$y=$this->height;
			}
		}
		else {
			if (($this->height>$newsize) && ($newsize>0) && ($this->height/$newsize>0)) {
				$x=$this->width/($this->height/$newsize);
				$y=$newsize;
			}
			else {
				$x=$this->width;
				$y=$this->height;
			}
		}
		$inext=strtolower(pathinfo($this->imagefile,PATHINFO_EXTENSION));
		$outext=strtolower(pathinfo($newfilename,PATHINFO_EXTENSION));
		switch ($inext) {
			case 'jpg':
			case 'jpeg':
				$im=ImageCreateFromJPEG($this->imagefile);
				break;
			case 'png':
				$im=ImageCreateFromPNG($this->imagefile);
				break;
		}
		$thumb=ImageCreateTrueColor($x,$y);
		ImageCopyResampled($thumb,$im,0,0,0,0,$x,$y,$this->width,$this->height);
		switch ($outext) {
			case 'jpg':
			case 'jpeg':
				Imagejpeg($thumb,ltrim($newfilename,"/"),$this->jpgquality);
			case 'png':
				Imagepng($thumb,ltrim($newfilename,"/"),$this->pngquality);
				break;
		}
		imagedestroy($im);
	}

	public function getPngquality()
	{
	    return $this->pngquality;
	}

	public function setPngquality($pngquality)
	{
	    $this->pngquality = $pngquality;
	}

	public function getJpgquality()
	{
	    return $this->jpgquality;
	}

	public function setJpgquality($jpgquality)
	{
	    $this->jpgquality = $jpgquality;
	}
}