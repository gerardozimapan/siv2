<?php

namespace Cotizador\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represent a registered client.
 * @ORM\Entity()
 * @ORM\Table(name="client")
 */
class Client
{
    // Client status constants.
    const STATUS_INACTIVE   = 0; // Inactive client.
    const STATUS_ACTIVE     = 1; // Active client.

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /** @ORM\Column(length=128) */
    private $name;

    /** @ORM\Column(length=256, name="tax_address") */
    private $taxAddress;

    /** @ORM\Column(length=16) */
    private $rfc;

    /** @ORM\Column(length=128, name="payment_terms") */
    private $paymentTerms;

    /** @ORM\Column(type="decimal", name="credit_limit") */
    private $creditLimit;

    /** @ORM\Column(type="decimal") */
    private $discount;

    /** @ORM\Column(type="text") */
    private $comments;

    /** @ORM\Column(type="integer") */
    private $status;

    /**
     * One Client have Many Contacts.
     * @ORM\ManyToMany(targetEntity="contact")
     * @ORM\JoinTable(name="client_contact",
     *      joinColumns={@ORM\JoinColumn(name="client_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $contacts;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    /**
     * Returns supplier ID.
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
     * Sets name
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns tax address.
     * @return string
     */
    public function getTaxAddress()
    {
        return $this->taxAddress;
    }

    /**
     * Sets tax address.
     * @param string $taxAddress
     */
    public function setTaxAddress($taxAddress)
    {
        $this->taxAddress = $taxAddress;
    }

    /**
     * Returns rfc.
     * @return string
     */
    public function getRfc()
    {
        return $this->rfc;
    }

    /**
     * Sets rfc.
     * @param string $rfc
     */
    public function setRfc($rfc)
    {
        $this->rfc = $rfc;
    }

    /**
     * Returns payment terms.
     * @return string
     */
    public function getPaymentTerms()
    {
        return $this->paymentTerms;
    }

    /**
     * Sets payment terms.
     * @param string $paymentTerms
     */
    public function setPaymentTerms($paymentTerms)
    {
        $this->paymentTerms = $paymentTerms;
    }

    /**
     * Returns credit limit.
     * @return double
     */
    public function getCreditLimit()
    {
        return $this->creditLimit;
    }

    /**
     * Sets credit limit.
     * @param double $creditLimit
     */
    public function setCreditLimit($creditLimit)
    {
        $this->creditLimit = $creditLimit;
    }

    /**
     * Returns discount.
     * @return double
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Sets discount.
     * @param double $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * Returns comments.
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Sets comments
     * @param string $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * Returns status.
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns posible statuses as array.
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        ];
    }

    /**
     * Returns suppliers status as string.
     * @return string
     */
    public function getStatusAsSring()
    {
        $list = self::getStatusList();
        if (isset($list[$this->status])) {
            return $list[$this->status];
        }
        return 'Unknown';
    }

    /**
     * Sets status
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Returns contacts.
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Returns contact as array.
     * @return array
     */
    public function getContactsAsArray()
    {
        $contactsArray = [];
        foreach ($this->contacts as $contact) {
            $contactOne = [];
            $contactOne['id'] = $contact->getId();
            $contactOne['name'] = $contact->getName();
            $contactOne['phoneNumber'] = $contact->getPhoneNumber();
            $contactOne['email'] = $contact->getEmail();
            $contactOne['job'] = $contact->getJob();
            $contactsArray[] = $contactOne;
        }
        return $contactsArray;
    }

    /**
     * Remove a contact from contacts collection.
     * @param Cotizador\Entity\Contact $contact
     * @return void
     */
    public function removeContact($contact)
    {
        if (false === $this->contacts->contains($contact)) {
            return;
        }

        $this->contacts->removeElement($contact);
    }

    /**
     * Add a contact to contacts collection.
     * @param Cotizador\Entity\Contact $contact
     * @return void
     */
    public function addContact($contact)
    {
        if (true === $this->contacts->contains($contact)) {
            return;
        }

        $this->contacts->add($contact);
    }
}
