<?php
namespace Cotizador\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represent a component.
 * @ORM\Entity()
 * @ORM\Table(name="component")
 */
class Component
{
    // Purchase type constants.
    const PURCHASE_NATIONAL = 0;
    const PURCHASE_IMPORTED = 1;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /** @ORM\Column(length=64) */
    private $folio;

    /** @ORM\Column(length=64) */
    private $function;

    /** @ORM\Column(length=254) */
    private $description;

    /** @ORM\Column(length=64, name="list_code") */
    private $listCode;

    /** @ORM\Column(type="integer", name="purchase_type") */
    private $purchaseType;

    /**
     * @ORM\ManyToOne(targetEntity="Supplier")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

    /**
     * @ORM\ManyToOne(targetEntity="Brand")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity="Classification")
     * @ORM\JoinColumn(name="classification_id", referencedColumnName="id")
     */
    private $classification;

    /**
     * @ORM\ManyToOne(targetEntity="MeasureUnit")
     * @ORM\JoinColumn(name="purchase_unit", referencedColumnName="id")
     */
    private $purchaseUnit;

    /**
     * @ORM\ManyToOne(targetEntity="MeasureUnit")
     * @ORM\JoinColumn(name="inventory_unit", referencedColumnName="id")
     */
    private $inventoryUnit;

    /** @ORM\Column(length=64) */
    private $presentation;

    /** @ORM\Column(type="integer", name="amount_presentation") */
    private $amountPresentation;

    /** @ORM\Column(name="unit_price_purchase", type="decimal", precision=15, scale=3) */
    private $unitPricePurchase;

    /** @ORM\Column(name="presentation_purchase_price", type="decimal", precision=15, scale=3) */
    private $presentationPurchasePrice;

    /** @ORM\Column(name="sale_unit_price", type="decimal", precision=15, scale=3) */
    private $saleUnitPrice;

    /** @ORM\Column(name="sale_total_price", type="decimal", precision=15, scale=3) */
    private $saleTotalPrice;

    /** @ORM\Column(name="unit_price_import_purchase", type="decimal", precision=15, scale=3) */
    private $unitPriceImportPurchase;

    /** @ORM\Column(name="import_sale_price", type="decimal", precision=15, scale=3) */
    private $importSalePrice;

    /**
     * @ORM\ManyToOne(targetEntity="Currency")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    private $currency;

    /** @ORM\Column(length=64, name="supplier_delivery_time") */
    private $supplierDeliveryTime;

    /** @ORM\Column(length=64, name="datasheet_file") */
    private $datasheetFile;

    /** @ORM\Column(length=64, name="image_file") */
    private $imageFile;

    /** @ORM\Column(length=32, name="sat_code") */
    private $satCode;

    /**
     * Returns component ID.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns folio.
     * @return string
     */
    public function getFolio()
    {
        return $this->folio;
    }

    /**
     * Sets folio.
     * @param string $folio
    */
    public function setFolio($folio)
    {
        $this->folio = $folio;
    }

    /**
     * Returns component's function.
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * Sets component's function.
     * @param string $function
     */
    public function setFunction($function)
    {
        $this->function = $function;
    }

    /**
     * Returns component's description.
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets component's description.
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Returns list code.
     * @return string
     */
    public function getListCode()
    {
        return $this->listCode;
    }

    /**
     * Sets list code.
     * @param string $listCode
     */
    public function setListCode($listCode)
    {
        $this->listCode = $listCode;
    }

    /**
     * Return purchase type.
     * @return int
     */
    public function getPurchaseType()
    {
        return $this->purchaseType;
    }

    /**
     * Returns posible purchase type as array.
     * @return array
     */
    public static function getPurchaseTypeList()
    {
        return [
            self::PURCHASE_NATIONAL => 'Nacional',
            self::PURCHASE_IMPORTED => 'Importación',
        ];
    }

    /**
     * Returns purchase type as string.
     * @return string
     */
    public function getPurchaseTypeAsString()
    {
        $list = self::getPurchaseTypeList();
        if (isset($list[$this->purchaseType])) {
            return $list[$this->purchaseType];
        }
        return 'Unknown';
    }

    /**
     * Sets purchase type.
     * @param int $purchaseType
     */
    public function setPurchaseType($purchaseType)
    {
        $this->purchaseType = $purchaseType;
    }

    /**
     * Returns component's supplier.
     * @return Cotizador\Entity\Supplier
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Sets component's supplier.
     * @param Cotizador\Entity\Supplier $supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * Returns component's brand.
     * @return Cotizador\Entity\Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Sets component's brand.
     * @param Cotizador\Entity\Brand $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * Returns component's classification.
     * @return Cotizador\Entity\Classification
     */
    public function getClassification()
    {
        return $this->classification;
    }

    /**
     * Sets component's classification.
     * @param Cotizador\Entity\Classification $classification
     */
    public function setClassification($classification)
    {
        $this->classification = $classification;
    }

    /**
     * Returns purchase unit
     * @return Cotizador\Entity\MeasureUnit
     */
    public function getPurchaseUnit()
    {
        return $this->purchaseUnit;
    }

    /**
     * Sets purchase unit
     * @param Cotizador\Entity\MeasureUnit $purchaseUnit
     */
    public function setPurchaseUnit($purchaseUnit)
    {
        $this->purchaseUnit = $purchaseUnit;
    }

    /**
     * Returns inventory unit.
     * @return Cotizador\Entity\MeasureUnit
     */
    public function getInventoryUnit()
    {
        return $this->inventoryUnit;
    }

    /**
     * Sets inventory unit.
     * @param Cotizador\Entity\MeasureUnit $inventoryUnit
     */
    public function setInventoryUnit($inventoryUnit)
    {
        $this->inventoryUnit = $inventoryUnit;
    }

    /**
     * Returns purchase presentation.
     * @return string
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * Sets purchase presentation.
     * @param string $presentation
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;
    }

    /**
     * Returns amount presentation.
     * @return int
     */
    public function getAmountPresentation()
    {
        return $this->amountPresentation;
    }

    /**
     * Sets amount presentation.
     * @param int $amountPresentation
     */
    public function setAmountPresentation($amountPresentation)
    {
        $this->amountPresentation = $amountPresentation;
    }

    /**
     * Returns unit price purchase.
     * @return decimal
     */
    public function getUnitPricePurchase()
    {
        return $this->unitPricePurchase;
    }

    /**
     * Sets unit price purchase.
     * @param decimal $unitPricePurchase
     */
    public function setUnitPricePurchase($unitPricePurchase)
    {
        $this->unitPricePurchase = $unitPricePurchase;
    }

    /**
     * Returns presentation purchase price.
     * @return decimal
     */
    public function getPresentationPurchasePrice()
    {
        return $this->presentationPurchasePrice;
    }

    /**
     * Sets presentation purchase price.
     * @param decimal $presentationPurchasePrice
     */
    public function setPresentationPruchasePrice($presentationPurchasePrice)
    {
        $this->presentationPruchasePrice = $presentationPurchasePrice;
    }

    /**
     * Returns sale unit price.
     * @return decimal
     */
    public function getSaleUnitPrice()
    {
        return $this->saleUnitPrice;
    }

    /**
     * Sets sale unit price.
     * @param decimal $saleUnitPrice
     */
    public function setSaleUnitPrice($saleUnitPrice)
    {
        $this->saleUnitPrice = $saleUnitPrice;
    }

    /**
     * Returns sale total price.
     * @return decimal
     */
    public function getSaleTotalPrice()
    {
        return $this->saleTotalPrice;
    }

    /**
     * Sets sale total price.
     * @param decimal $saleTotalPrice
     */
    public function setSaleTotalPrice($saleTotalPrice)
    {
        $this->saleTotalPrice = $saleTotalPrice;
    }

    /**
     * Returns unit price import purchase.
     * @return decimal
     */
    public function getUnitPriceImportPurchase()
    {
        return $this->unitPriceImportPurchase;
    }

    /**
     * Sets unit price import purchase.
     * @param decimal $unitPriceImportPurchase
     */
    public function setUnitPriceImportPurchase($unitPriceImportPurchase)
    {
        $this->unitPriceImportPurchase = $unitPriceImportPurchase;
    }

    /**
     * Returns import sale price.
     * @return decimal
     */
    public function getImportSalePrice()
    {
        return $this->importSalePrice;
    }

    /**
     * Sets import sale price.
     * @param decimal $importSalePrice
     */
    public function setImportSalePrice($importSalePrice)
    {
        $this->importSalePrice = $importSalePrice;
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
     * Returns supplier delivery time.
     * @return string
     */
    public function getSupplierDeliveryTime()
    {
        return $this->supplierDeliveryTime;
    }

    /**
     * Sets supplier delivery time.
     * @param string $supplierDeliveryTime
     */
    public function setSupplierDeliveryTime($supplierDeliveryTime)
    {
        $this->supplierDeliveryTime = $supplierDeliveryTime;
    }

    /**
     * Returns datasheet file name.
     * @return string
     */
    public function getDatasheetFile()
    {
        return $this->datasheetFile;
    }

    /**
     * Sets datasheet file name.
     * @param string $datasheetFile
     */
    public function setDatasheetFile($datasheetFile)
    {
        $this->datasheetFile = $datasheetFile;
    }

    /**
     * Returns image file name.
     * @return string
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Sets image file name.
     * @param string $imageFile
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
    }

    /**
     * Returns SAT code.
     * @return string
     */
    public function getSatCode()
    {
        return $this->satCode;
    }

    /**
     * Sets SAT code.
     * @param string $satCode
     */
    public function setSatCode($satCode)
    {
        $this->satCode = $satCode;
    }
}
