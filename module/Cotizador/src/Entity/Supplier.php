<?php

namespace Cotizador\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represent a registered supplier.
 * @ORM\Entity()
 * @ORM\Table(name="supplier")
 */
class Supplier
{
    // Supplier status constants.
    const STATUS_INACTIVE   = 0; // Inactive supplier.
    const STATUS_ACTIVE     = 1; // Active supplier.

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

    /** @ORM\Column(length=64, name="delivery_time") */
    private $deliveryTime;

    /** @ORM\Column(type="text") */
    private $comments;

    /** @ORM\Column(type="integer") */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity="Cotizador\Entity\Category")
     * @ORM\JoinTable(name="supplier_category",
     *      joinColumns={@ORM\JoinColumn(name="supplier_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     *      )
     */
    private $categories;

    /**
     * One Supplier have Many Bank Accounts.
     * @ORM\OneToMany(targetEntity="BankAccount", mappedBy="supplier")
     */
    private $bankAccounts;

    /**
     * One Supplier have Many Contacts.
     * @ORM\ManyToMany(targetEntity="contact")
     * @ORM\JoinTable(name="supplier_contact",
     *      joinColumns={@ORM\JoinColumn(name="supplier_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $contacts;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->bankAccounts = new ArrayCollection();
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
     * Returns delivery time.
     * @return string
     */
    public function getDeliveryTime()
    {
        return $this->deliveryTime;
    }

    /**
     * Sets delivery time.
     * @param string $deliveryTime
     */
    public function setDeliveryTime($deliveryTime)
    {
        $this->deliveryTime = $deliveryTime;
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
     * Returns categories.
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Returns the string of assigned categories names.
     * @return string
     */
    public function getCategoriesAsString()
    {
        $categoryList = '';

        $count = count($this->categories);
        $i = 0;
        foreach ($this->categories as $category) {
            $categoryList .= $category->getName();
            if ($i < $count - 1) {
                $categoryList .= ', ';
            }
            $i++;
        }

        return $categoryList;
    }

    /**
     * Remove a category from categories collection.
     * @param Cotizador\Entity\Category $category
     * @return void
     */
    public function removeCategory($category)
    {
        if (false === $this->categories->contains($category)) {
            return;
        }

        $this->categories->removeElement($category);
        //$category->removeSupplier($this);
    }

    /**
     * Adds a category to categories collection.
     * @param Cotizador\Entity\Category $category
     * @return void
     */
    public function addCategory($category)
    {
        if (true === $this->categories->contains($category)) {
            return;
        }

        $this->categories->add($category);
        //$category->addSupplier($this);
    }

    /**
     * Returns bank accounts.
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getBankAccounts()
    {
        return $this->bankAccounts;
    }

    /**
     * Returns bank accounts as array.
     * @return array
     */
    public function getBankAccountsAsArray()
    {
        $bankAccountsArray = [];
        foreach ($this->bankAccounts as $bankAccount) {
            $bankAccountOne = [];
            $bankAccountOne['id'] = $bankAccount->getId();
            $bankAccountOne['bank'] = $bankAccount->getBank();
            $bankAccountOne['number'] = $bankAccount->getNumber();
            $bankAccountOne['clabe'] = $bankAccount->getClabe();
            $bankAccountOne['currency'] = $bankAccount->getCurrency();
            $bankAccountsArray[] = $bankAccountOne;
        }
        return $bankAccountsArray;
    }

    /**
     * Remove a bank account from bank accounts collection.
     * @param Cotizador\Entity\BankAccount $bankAccount
     * @return void
     */
    public function removeBankAccount($bankAccount)
    {
        if (false === $this->bankAccounts->contains($bankAccount)) {
            return;
        }

        $this->bankAccounts->removeElement($bankAccount);
        //$bankAccount->setSupplier(null);
    }

    /**
     * Adds a bank account to bank accounts collection.
     * @param Cotizador\Entity\BankAccount $bankAccount
     * @return void
     */
    public function addBankAccount($bankAccount)
    {
        if (true === $this->bankAccounts->contains($bankAccount)) {
            return;
        }

        $this->bankAccounts->add($bankAccount);
        $bankAccount->setSupplier($this);
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
