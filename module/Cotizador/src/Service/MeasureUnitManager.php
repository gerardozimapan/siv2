<?php
namespace Cotizador\Service;

use Cotizador\Entity\MeasureUnit;

/**
 * This service is responsible for adding/editing and delete
 * measure unit.
 */
class MeasureUnitManager
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
     * This method adds a new measure unit.
     * @param array $data
     * @return Cotizador\Entity\MeasureUnit
     */
    public function addMeasureUnit($data)
    {
        // Do not allow several measure units with the same name.
        if ($this->checkMeasureUnitExists($data['name'])) {
            throw new \Exception("Measure unit with name " . $data['name'] . " already exists.");
        }

        // Create new MeasureUnit entity.
        $measureUnit = new MeasureUnit();
        $measureUnit->setName($data['name']);
        $measureUnit->setCode($data['code']);
        $measureUnit->setDescription($data['description']);

        // Add the entity to the entity manager.
        $this->entityManager->persist($measureUnit);

        // Apply changes to database.
        $this->entityManager->flush();

        return $measureUnit;
    }

    /**
     * This method updates data of an existing measure unit.
     * @param Cotizador\Entity\MeasureUnit $measureUnit
     * @param array $data
     * @return boolean
     */
    public function updateMeasureUnit($measureUnit, $data)
    {
        // Do not allow to change measure unit name if another measure unit with such name already exists.
        if ($measureUnit->getName() != $data['name'] &&
            $this->checkMeasureUnitExists($data['name'])) {
            throw new \Exception("Another measure unit with name " . $data['name'] . " already exists.");
        }

        $measureUnit->setName($data['name']);
        $measureUnit->setCode($data['code']);
        $measureUnit->setDescription($data['description']);

        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }

    /**
     * This method remove an existing measure unit.
     * @param Cotizador\Entity\MeasureUnit $measureUnit
     * @return boolean
     */
    public function deleteMeasureUnit($measureUnit)
    {
        $this->entityManager->remove($measureUnit);

        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    /**
     * Check whether a measure unit with same name already exists in the database.
     * @param string $name
     * @return boolean
     */
    public function checkMeasureUnitExists($name)
    {
        $measureUnit = $this->entityManager->getRepository(MeasureUnit::class)
            ->FindOneByName($name);

        return $measureUnit !== null;
    }
}
