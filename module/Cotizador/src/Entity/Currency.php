<?php
namespace Cotizador\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represent a currency
 * @ORM\Entity()
 * @ORM\Table(name="currency")
 */
class Currency
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /** @ORM\Column(length=64) */
    private $name;

    /** @ORM\Column(length=4) */
    private $code;

    /** @ORM\Column(length=2) */
    private $symbol;

    /**
     * Returns ID.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets ID.
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * Returns code.
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets code.
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Returns symbol.
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Sets symbol.
     * @param string $symbol
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }
}
