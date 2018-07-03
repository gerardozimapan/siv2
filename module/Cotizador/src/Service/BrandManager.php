<?php
namespace Cotizador\Service;

use Cotizador\Entity\Brand;

/**
 * This service is responsible for adding/editing and delete
 * brand.
 */
class BrandManager
{
    /**
     * Dcotrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Construct the service.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * This method adds a new brand.
     * @param array $data
     * @return Cotizador\Entity\Brand
     */
    public function addBrand($data)
    {
        // Do not allow several brands with the same name.
        if ($this->checkBrandExists($data['name'])) {
            throw new \Exception("Brand with name " . $data['name'] . " already exists.");
        }

        // Create new Brand entity.
        $brand = new Brand();
        $brand->setName($data['name']);
        $brand->setDescription($data['description']);

        // Add the entity to the entity manager.
        $this->entityManager->persist($brand);

        // Apply changes to database.
        $this->entityManager->flush();

        return $brand;
    }

    /**
     * This method updates data of an existing brand.
     * @param Cotizador\Entity\Brand $brand
     * @param array $data
     * @return boolean
     */
    public function updateBrand($brand, $data)
    {
        // Do not allow to change brand name if another brand with such name already exists.
        if ($brand->getName() != $data['name'] &&
            $this->checkBrandExists($data['name'])) {
            throw new \Exception("Another brand with name " . $data['name'] . " already exists.");
        }

        $brand->setName($data['name']);
        $brand->setDescription($data['description']);

        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }

    /**
     * This method remove an existing brand.
     * @param Cotizador\Entity\Brand $brand
     * @return boolean
     */
    public function deleteBrand($brand)
    {
        $this->entityManager->remove($brand);

        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    /**
     * Check whether an brand with same name already exists in the database.
     * @param string $name
     * @return boolean
     */
    public function checkBrandExists($name)
    {
        $brand = $this->entityManager->getRepository(Brand::class)
            ->FindOneByName($name);

        return $brand !== null;
    }
}
