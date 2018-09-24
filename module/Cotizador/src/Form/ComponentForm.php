<?php
namespace Cotizador\Form;

use Cotizador\Validator\ComponentExistsValidator;
use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\ArrayInput;
use Zend\I18n\Filter\NumberFormat;
use Zend\I18n\Filter\NumberParse;
use Zend\I18n\Validator\IsFloat;
use Zend\Filter\ToNull;

/**
 * This form is used to caollect component's data.
 */
class ComponentForm extends Form
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Define form name.
        parent::__construct('component-form');

        // Set POST method for this form.
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    private function addElements()
    {
        // Add "folio" field
        $this->add([
            'type' => 'text',
            'name' => 'folio',
            'options' => [
                'label' => 'Folio',
            ],
        ]);

        // Add "function" field.
        $this->add([
            'type' => 'text',
            'name' => 'function',
            'options' => [
                'label' => 'Función',
            ],
        ]);

        // Add "description" field.
        $this->add([
            'type' => 'textarea',
            'name' => 'description',
            'options' => [
                'label' => 'Descripción',
            ],
        ]);

        // Add "listCode" field.
        $this->add([
            'type' => 'text',
            'name' => 'listCode',
            'options' => [
                'label' => 'Catálogo',
            ],
        ]);

        // Add "purchaseType" field.
        $this->add([
            'type' => 'radio',
            'name' => 'purchaseType',
            'options' => [
                'label' => 'Tipo de Compra',
                'value_options' => [
                    Component::PURCHASE_NATIONAL => 'Nacional',
                    Component::PURCHASE_IMPORTED => 'Importación',
                ],
            ],
        ]);

        // Add "supplier" field.
        $this->add([
            'type' => 'text',
            'name' => 'supplier',
            'options' => [
                'label' => 'Proveedor',
            ],
        ]);

        // Add "brand" field.
        $this->add([
            'type' => 'select',
            'name' => 'brand',
            'options' => [
                'label' => 'Marca',
            ],
        ]);

        // Add "classification" field.
        $this->add([
            'type' => 'select',
            'name' => 'classification',
            'options' => [
                'label' => 'Grupo de Articulos',
            ],
        ]);

        // Add "purchaseUnit" field.
        $this->add([
            'type' => 'select',
            'name' => 'purchaseUnit',
            'options' => [
                'label' => 'Unidad de Compra',
            ],
        ]);

        // Add "inventoryUnit" field.
        $this->add([
            'type' => 'select',
            'name' => 'inventoryUnit',
            'options' => [
                'label' => 'Unidad de inventario',
            ],
        ]);

        // Add "presentation" field.
        $this->add([
            'type' => 'text',
            'name' => 'presentation',
            'options' => [
                'label' => 'Presentación',
            ],
        ]);

        // Add "amountPresentation" field.
        $this->add([
            'type' => 'text',
            'name' => 'amountPresentation',
            'options' => [
                'label' => 'Cantidad por Presentación',
            ],
        ]);

        // Add "unitPricePurchase" field.
        $this->add([
            'type' => 'text',
            'name' => 'unitPricePurchase',
            'options' => [
                'label' => 'Precio de Compra Unitario',
            ],
        ]);

        // Add "presentationPurchasePrice" field.
        $this->add([
            'type' => 'text',
            'name' => 'presentationPurchasePrice',
            'options' => [
                'label' => 'Precio de Compra por Presentación',
            ],
        ]);

        // Add "saleUnitPrice" field.
        $this->add([
            'type' => 'text',
            'name' => 'saleUnitPrice',
            'options' => [
                'label' => 'Precio de Venta Unitario',
            ],
        ]);

        // Add "saleTotalPrice" field.
        $this->add([
            'type' => 'text',
            'name' => 'saleTotalPrice',
            'options' => [
                'label' => 'Precio de Venta Total',
            ],
        ]);

        // Add "unitPriceImportPurchase" field.
        $this->add([
            'type' => 'text',
            'name' => 'unitPriceImportPurchase',
            'options' => [
                'label' => 'Precio de Compra Unitario de Importación',
            ],
        ]);

        // Add "importSalePrice" field.
        $this->add([
            'type' => 'text',
            'name' => 'importSalePrice',
            'options' => [
                'label' => 'Precio de Venta de Imporación',
            ],
        ]);

        // Add "currency" field.
        $this->add([
            'type' => 'radio',
            'name' => 'currency',
            'options' => [
                'label' => 'Moneda',
            ],
        ]);

        // Add "supplierDeliveryTime" field.
        $this->add([
            'type' => 'text',
            'name' => 'supplierDeliveryTime',
            'options' => [
                'label' => 'Tiempo de Entrega del Proveedor',
            ],
        ]);

        // Add "files" field.
        $this->add([
            'type' => 'file',
            'name' => 'files',
            'attributes' => [
                'id' => 'files',
            ],
            'options' => [
                'label' => 'Archivos del Artículo',
            ],
        ]);

        // Add "satCode" field.
        $this->add([
            'type' => 'text',
            'name' => 'satCode',
            'options' => [
                'label' => 'Clave SAT del Artículo',
            ],
        ]);

        // Add the submit button.
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'create',
            ],
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        // Create main inout filter.
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        // Add input for "folio" field.
        $inputFilter->add([
            'name'     => 'folio',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'Validators' => [
                [
                    'name'  => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 64,
                    ],
                ],
                [
                    'name' => ComponentExistValidator::class,
                    'options' => [
                        'entityManager' => $this->entityManager,
                        'component'     => $this->component,
                    ],
                ],
            ],
        ]);
    }
}