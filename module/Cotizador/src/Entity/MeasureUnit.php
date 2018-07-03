<?php
namespace Cotizador\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represent a measure unit.
 * @ORM\Entity()
 * @ORM\Table(name="measure_unit")
 */
class MeasureUnit
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /** @ORM\Column(length=32) */
    private $name;

    /** @ORM\Column(length=4) */
    private $code;

    /** @ORM\Column(length=64) */
    private $description;

    /**
     * Returns category ID.
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
     * Returns description.
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets description
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
