<?php
namespace Cotizador\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represent a category to clasificate to supplier.
 * @ORM\Entity()
 * @ORM\Table(name="category")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /** @ORM\Column(length=32) */
    private $name;

    /** @ORM\Column(length=256) */
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
