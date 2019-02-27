<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\ApiconsumelogRepository")
 * @ORM\Table(name="apiconsumelog",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Apiconsumelog {

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

    /** @ORM\Column(type="string",length=32,nullable=true) */
    private $ip;

    /**
     * @ORM\ManyToOne(targetEntity="Apiconsumer")
     * @ORM\JoinColumn(name="apiconsumer_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Apiconsumer
     */
    private $apiconsumer;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $apiconsumernev;

    /** @ORM\Column(type="text",nullable=true) */
    private $query;

    /** @ORM\Column(type="text",nullable=true) */
    private $result;

    public function getId() {
        return $this->id;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getCreatedStr() {
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearCreated() {
        $this->created = null;
    }

    /**
     * @return Apiconsumer
     */
    public function getApiconsumer() {
        return $this->apiconsumer;
    }

    /**
     * @param Apiconsumer $apiconsumer
     */
    public function setApiconsumer($apiconsumer) {
        if ($this->apiconsumer !== $apiconsumer) {
            if (!$apiconsumer) {
                $this->removeApiconsumer();
            }
            else {
                $this->apiconsumer = $apiconsumer;
                $this->setApiconsumernev($apiconsumer->getNev());
            }
        }
    }

    public function removeApiconsumer() {
        if (!is_null($this->apiconsumer)) {
            $this->apiconsumer = null;
            $this->setApiconsumernev(null);
        }
    }

    /**
     * @return mixed
     */
    public function getApiconsumernev() {
        return $this->apiconsumernev;
    }

    /**
     * @param mixed $apiconsumernev
     */
    public function setApiconsumernev($apiconsumernev) {
        $this->apiconsumernev = $apiconsumernev;
    }

    /**
     * @return mixed
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query) {
        $this->query = $query;
    }

    /**
     * @return mixed
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result) {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip) {
        $this->ip = $ip;
    }
}