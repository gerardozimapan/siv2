<?php
namespace Cotizador\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represent a contact registered.
 * @ORM\Entity()
 * @ORM\Table(name="contact")
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /** @ORM\Column(length=256) */
    private $name;

    /** @ORM\Column(length=32, name="phone_number") */
    private $phoneNumber;

    /** @ORM\Column(length=256) */
    private $email;

    /** @ORM\Column(length=256) */
    private $job;

    /**
     * Returns ID.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets name.
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns phone number.
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Sets phone number.
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Returns email.
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets email.
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Returns job.
     * @return string
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Sets job.
     * @param string $job
     */
    public function setJob($job)
    {
        $this->job = $job;
    }
}
