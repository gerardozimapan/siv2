<?php
namespace Cotizador\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represent a bank account registered.
 * @ORM\Entity()
 * @ORM\Table(name="bank_account")
 */
class BankAccount
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /** @ORM\Column(length=128) */
    private $bank;

    /** @ORM\Column(length=128) */
    private $number;

    /** @ORM\Column(length=18) */
    private $clabe;

    /**
     * @ORM\ManyToOne(targetEntity="Currency")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    private $currency;

    /**
     * Many Bank Accounts have One Supplier.
     * @ORM\ManyToOne(targetEntity="Supplier", inversedBy="bankAccounts")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

    /**
     * Returns ID.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns bank name.
     * @return string
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets bank name.
     * @param string $bank
     */
    public function setBank($bank)
    {
        $this->bank = $bank;
    }

    /**
     * Returns number account.
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Sets number account.
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Returns CLABE.
     * @return string
     */
    public function getClabe()
    {
        return $this->clabe;
    }

    /**
     * Sets CLABE
     * @param string $clabe
     */
    public function setClabe($clabe)
    {
        $this->clabe = $clabe;
    }

    /**
     * Returns currency.
     * @return Cotizador\Entity\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Sets currency.
     * @param Cotizador\Entity\Currency $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Returns supplier.
     * @return Cotizador\Entity\Supplier
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Sets supplier.
     * @param Cotizador\Entity\Supplier $supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }
}
