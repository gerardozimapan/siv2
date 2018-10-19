<?php
namespace Cotizador\Service;

use Cotizador\Entity\Brand;
use Cotizador\Entity\Classification;
use Cotizador\Entity\Component;
use Cotizador\Entity\Currency;
use Cotizador\Entity\MeasureUnit;
use Cotizador\Entity\Supplier;

/**
 * This service is responsible for adding/editing components
 * and changing component status.
 */
class ComponentManager
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
     * This method adds a new component.
     * @param array $data
     * @return Cotizador\Entity\Component
     */
    public function addComponent($data)
    {
        // Do not allow several category with the same description.
        if ($this->checkComponentExists($data['description'])) {
            throw new \Exception("Component with description " . $data['description'] . " already exists.");
        }

        // Get supplier entity.
        $supplier = $this->entityManager->getRepository(Supplier::class)
            ->find((int) $data['supplier']);

        // Get brand entity.
        $brand = $this->entityManager->getRepository(Brand::class)
            ->find((int) $data['brand']);

        // Get classification entity.
        $classification = $this->entityManager->getRepository(Classification::class)
            ->find((int) $data['classification']);

        // Get purchase unit (measureUnit) entity.
        $purchaseUnit = $this->entityManager->getRepository(MeasureUnit::class)
            ->find((int) $data['purchaseUnit']);
        
        // Get inventory unit (measureUnit) entity.
        $inventoryUnit = $this->entityManager->getRepository(MeasureUnit::class)
            ->find((int) $data['inventoryUnit']);
        
        // Get currency entity.
        $currency = $this->entityManager->getRepository(Currency::class)
            ->find((int) $data['currency']);

        // Create new Component entity.
        $component = new Component();
        $component->setFolio($data['folio']);
        $component->setFunction($data['function']);
        $component->setDescription($data['description']);
        $component->setListCode($data['listCode']);
        $component->setPurchaseType($data['purchaseType']);
        $component->setSupplier($supplier);
        $component->setBrand($brand);
        $component->setClassification($classification);
        $component->setPurchaseUnit($purchaseUnit);
        $component->setInventoryUnit($inventoryUnit);
        $component->setPresentation($data['presentation']);
        $component->setAmountPresentation($data['amountPresentation']);
        $component->setUnitPricePurchase($data['unitPricePurchase']);
        $component->setPresentationPurchasePrice($data['presentationPurchasePrice']);
        $component->setSaleUnitPrice($data['saleUnitPrice']);
        $component->setSaleTotalPrice($data['saleTotalPrice']);
        $component->setUnitPriceImportPurchase($data['unitPriceImportPurchase']);
        $component->setImportSalePrice($data['importSalePrice']);
        $component->setCurrency($currency);
        $component->setSupplierDeliveryTime($data['supplierDeliveryTime']);
        $component->setDatasheetFile($data['datasheetFile']);
        $component->setImageFile($data['imageFile']);
        $component->setSatCode($data['satCode']);

        // Add the entity to the entity manager.
        $this->entityManager->persist($component);

        // Apply changes to database.
        $this->entityManager->flush();

        return $component;
    }

    /**
     * This method updates data of an existing component.
     * @param Cotizador\Entity\Component $component
     * @param array $data
     * @return boolean
     */
    public function updateComponent($component, $data)
    {
        // Do not allow to change component description if another component 
        // with such description already exists.
        if ($component->getDescription() != $data['description'] && 
            $this->checkComponentExists($data['description'])) {
            throw new \Exception("Another component with description " . $data['description' . " already exists."]);
        }

        // Get supplier entity.
        $supplier = $this->entityManager->getRepository(Supplier::class)
            ->find((int) $data['supplier']);

        // Get brand entity.
        $brand = $this->entityManager->getRepository(Brand::class)
            ->find((int) $data['brand']);

        // Get classification entity.
        $classification = $this->entityManager->getRepository(Classification::class)
            ->find((int) $data['classification']);

        // Get purchase unit (measureUnit) entity.
        $purchaseUnit = $this->entityManager->getRepository(MeasureUnit::class)
            ->find((int) $data['purchaseUnit']);
        
        // Get inventory unit (measureUnit) entity.
        $inventoryUnit = $this->entityManager->getRepository(MeasureUnit::class)
            ->find((int) $data['inventoryUnit']);
        
        // Get currency entity.
        $currency = $this->entityManager->getRepository(Currency::class)
            ->find((int) $data['currency']);

        $component->setFolio($data['folio']);
        $component->setFunction($data['function']);
        $component->setDescription($data['description']);
        $component->setListCode($data['listCode']);
        $component->setPurchaseType($data['purchaseType']);
        $component->setSupplier($supplier);
        $component->setBrand($brand);
        $component->setClassification($classification);
        $component->setPurchaseUnit($purchaseUnit);
        $component->setInventoryUnit($inventoryUnit);
        $component->setPresentation($data['presentation']);
        $component->setAmountPresentation($data['amountPresentation']);
        $component->setUnitPricePurchase($data['unitPricePurchase']);
        $component->setPresentationPurchasePrice($data['presentationPurchasePrice']);
        $component->setSaleUnitPrice($data['saleUnitPrice']);
        $component->setSaleTotalPrice($data['saleTotalPrice']);
        $component->setUnitPriceImportPurchase($data['unitPriceImportPurchase']);
        $component->setImportSalePrice($data['importSalePrice']);
        $component->setCurrency($currency);
        $component->setSupplierDeliveryTime($data['supplierDeliveryTime']);
        $component->setDatasheetFile($data['datasheetFile']['name']);

        if (isset($data['imageFile']['name']) &&
            (trim($data['imageFile']['name']) != '') &&
            ($data['imageFile']['name'] != $component->getImageFile())) {
            $component->setImageFile($data['imageFile']['name']);
        }

        $component->setSatCode($data['satCode']);

        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }

    /**
     * Checks whether an component with same description already exists in the database.
     * @param string $description
     * @return boolean
     */
    public function checkComponentExists($description)
    {
        $component = $this->entityManager->getRepository(Component::class)
            ->findOneByDescription($description);

        return $component !== null;
    }
}