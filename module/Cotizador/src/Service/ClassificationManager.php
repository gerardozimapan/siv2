<?php
namespace Cotizador\Service;

use Cotizador\Entity\Classification;

/**
 * This service is responsible for adding/editing and delete
 * classification.
 */
class ClassificationManager
{
    /**
     * Dcotrine entuty manager.
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
     * This method adds a new classification.
     * @param array $data
     * @return Cotizador\Entity\Classification
     */
    public function addClassification($data)
    {
        // Do not allow several classifications with the same name.
        if ($this->checkClassificationExists($data['name'])) {
            throw new \Exception("Classification with name " . $data['name'] . "already exists.");
        }

        // Create new Classification entity.
        $classification = new Classification();
        $classification->setName($data['name']);
        $classification->setDescription($data['description']);

        // Add the entity to the entity manager.
        $this->entityManager->persist($classification);

        // Apply changes to database.
        $this->entityManager->flush();

        return $classification;
    }

    /**
     * This method updates data of an existing classification.
     * @param Cotizador\Entity\Classification $classification
     * @param array $data
     * @return boolean
     */
    public function updateClassification($classification, $data)
    {
        // Do not allow to change classification name if another classification
        // with such name already exists.
        if ($classification->getName() != $data['name'] &&
            $this->checkClassificationExists($data['name'])) {
            throw new \Exception("Another classification with name " . $data['name'] . " already exists.");
        }

        $classification->setName($data['name']);
        $classification->setDescription($data['description']);

        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }

    /**
     * This method remove an existing classification.
     * @param Cotizador\Entity\Classification $classification
     * @return boolean
     */
    public function deleteClassification($classification)
    {
        $this->entityManager->remove($classification);

        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    /**
     * Check whether an classification with same name already exists in the database.
     * @param string $name
     * @return boolean
     */
    public function checkClassificationExists($name)
    {
        $classification = $this->entityManager->getRepository(Classification::class)
            ->FindOneByName($name);

        return $classification !== null;
    }
}
