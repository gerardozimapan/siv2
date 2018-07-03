<?php
namespace Cotizador\Service;

use Cotizador\Entity\Category;

/**
 * This service is reponsible for adding/editing and delete
 * category.
 */
class CategoryManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * This method adds a new category.
     * @param array $data
     * @return Cotizador\Entity\Category
     */
    public function addCategory($data)
    {
        // Do not allow several category with the same name.
        if ($this->checkCategoryExists($data['name'])) {
            throw new \Exception("Category with name " . $data['name'] . " already exists.");
        }

        // Create new Category entity.
        $category = new Category();
        $category->setName($data['name']);
        $category->setDescription($data['description']);

        // Add the entity to the entitymanager.
        $this->entityManager->persist($category);

        // Apply changes to database.
        $this->entityManager->flush();

        return $category;
    }

    /**
     * This method updates data of an existing category.
     * @param Cotizador\Entity\Category $category
     * @param array $data
     * @return boolean
     */
    public function updateCategory($category, $data)
    {
        // Do not allow to change category name if another category with such name already exists.
        if ($category->getName() != $data['name'] &&
            $this->checkCategoryExists($data['name'])) {
            throw new \Exception("Another category with name " . $data['name'] . " already exists.");
        }

        $category->setName($data['name']);
        $category->setDescription($data['description']);

        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }

    /**
     * This method remove an existing category.
     * @param Cotizador\Entity\Category $category
     * @return boolean
     */
    public function deleteCategory($category)
    {
        $this->entityManager->remove($category);

        // Apply changes to database.
        $this->entityManager->flush();
        return  true;
    }

    /**
     * Checks whether an category with same name already exists in the database.
     * @param string $name
     * @return boolean
     */
    public function checkCategoryExists($name)
    {
        $category = $this->entityManager->getRepository(Category::class)
            ->findOneByName($name);

        return $category !== null;
    }
}
