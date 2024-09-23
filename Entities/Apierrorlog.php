<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\ApierrorlogRepository")
 * @ORM\Table(name="apierrorlog",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Apierrorlog
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $created;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $type;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $objectid;

    /** @ORM\Column(type="text",nullable=true) */
    private $message;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $closed = false;

    public function toLista()
    {
        return [
            'id' => $this->getId(),
            'created' => $this->getCreatedStr(),
            'type' => $this->getType(),
            'objectid' => $this->getObjectid(),
            'message' => $this->getMessage(),
            'closed' => $this->isClosed()
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getCreatedStr()
    {
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearCreated()
    {
        $this->created = null;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getObjectid()
    {
        return $this->objectid;
    }

    /**
     * @param mixed $objectid
     */
    public function setObjectid($objectid): void
    {
        $this->objectid = $objectid;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function isClosed()
    {
        return $this->closed;
    }

    /**
     * @param bool $closed
     */
    public function setClosed($closed): void
    {
        $this->closed = $closed;
    }

}