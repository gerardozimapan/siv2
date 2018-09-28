<?php
namespace Cotizador\Form;

use Cotizador\Validator\ComponentExistsValidator;
use Cotizador\Entity\Component;
use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\ArrayInput;
use Zend\I18n\Filter\NumberFormat;
use Zend\I18n\Filter\NumberParse;
use Zend\I18n\Validator\IsFloat;
use Zend\Filter\ToNull;

/**
 * This form is used to collect component's data.
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
            'validators' => [
                [
                    'name'  => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 64,
                    ],
                ],
                [
                    'name' => ComponentExistsValidator::class,
                    'options' => [
                        'entityManager' => $this->entityManager,
                        'component'     => $this->component,
                    ],
                ],
            ],
        ]);

        // Add input for "function" field.
        $inputFilter->add([
            'name'     => 'function',
            'required' => false,
            'filters'  => [
                ['name' => 'ToNull'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 64,
                    ],
                ]
            ],
        ]);

        // Add input for 'description' field.
        $inputFilter->add([
            'name'     => 'description',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 254,
                    ],
                ]
            ],
        ]);

        // Add input for "listCode" field.
        $inputFilter->add([
            'name'     => 'listCode',
            'required' => false,
            'filters'  => [
                ['name' => 'ToNull'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 64,
                    ],
                ],
            ],
        ]);

        // Add input for "purchaseType" field.
        $inputFilter->add([
            'name'     => 'purchaseType',
            'required' => false,
            'filters'  => [
                ['name' => 'ToInt'],
                ['name' => 'ToNull'],
            ],
            'validators' => [
                [
                    'name'    => 'GreaterThan',
                    'options' => ['min' => 1],
                ],
            ],
        ]);

        // Add input for "supplier" field.
        $inputFilter->add([
            'name'     => 'supplier',
            'required' => true,
            'filters'  => [
                ['name' => 'ToInt'],
            ],
            'validators' => [
                [
                    'name' => 'GreaterThan',
                    'options' => ['min' => 1],
                ],
            ],
        ]);

        // Add input for "brand" field.
        $inputFilter->add([
            'name'     => 'brand',
            'required' => true,
            'filters'  => [
                ['name' => 'ToInt'],
            ],
            'validators' => [
                [
                    'name' => 'GreaterThan',
                    'options' => ['min' => 1],
                ],
            ],
        ]);

        // Add input filter for "classification" field.
        $inputFilter->add([
            'name'     => 'classification',
            'required' => true,
            'filters'  => [
                ['name' => 'ToInt'],
            ],
            'validators' => [
                [
                    'name' => 'GreaterThan',
                    'options' => ['min' => 1],
                ],
            ],
        ]);

        // Add input filter for "purchaseUnit" field.
        $inputFilter->add([
            'name'     => 'purchaseUnit',
            'required' => false,
            'filters'  => [
                ['name' => 'ToInt'],
                ['name' => 'ToNull'],
            ],
            'validators' => [
                [
                    'name' => 'GreaterThan',
                    'options' => ['min' => 1],
                ],
            ],
        ]);

                // Add input filter for "purchaseUnit" field.
        $inputFilter->add([
            'name'     => 'purchaseUnit',
            'required' => false,
            'filters'  => [
                ['name' => 'ToInt'],
                ['name' => 'ToNull'],
            ],
            'validators' => [
                [
                    'name' => 'GreaterThan',
                    'options' => ['min' => 1],
                ],
            ],
        ]);

        // Add input filter for "inventoryUnit" field.
        $inputFilter->add([
            'name'     => 'inventoryUnit',
            'required' => false,
            'filters'  => [
                ['name' => 'ToInt'],
                ['name' => 'ToNull'],
            ],
            'validators' => [
                [
                    'name' => 'GreaterThan',
                    'options' => ['min' => 1],
                ],
            ],
        ]);

        // Add input filter for "presentation" field.
        $inputFilter->add([
            'name'     => 'presentation',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'ToNull'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 64,
                    ],
                ],
            ],
        ]);

        // Add input filter for "amountPresentation" field.
        $inputFilter->add([
            'name'     => 'amountPresentation',
            'required' => false,
            'filters'  => [
                ['name' => 'ToInt'],
                ['name' => 'ToNull'],
            ],
            'validators' => [
                [
                    'name' => 'GreaterThan',
                    'options' => ['min' => 1],
                ],
            ],
        ]);

        // Add input filter for "unitPricePurchase" field.
        $inputFilter->add([
            'name'     => 'unitPricePurchase',
            'required' => false,
            'filters'  => [
                ['name' => 'NumberParse'],
                ['name' => 'ToNull'],
            ],
            'validators' => [
                [
                    'name' => 'IsFloat'
                ]
            ],
        ]);

        // Add input filter for "presentationPurchasePrice" field.
        $inputFilter->add([
            'name'     => 'presentationPurchasePrice',
            'required' => false,
            'filters'  => [
                ['name' => 'NumberParse'],
                ['name' => 'ToNull'],
            ],
            'validators' => [
                [
                    'name' => 'IsFloat'
                ]
            ],
        ]);

        // Add input filter for "saleUnitPrice" field.
        $inputFilter->add([
            'name'     => 'saleUnitPrice',
            'required' => false,
            'filters'  => [
                ['name' => 'NumberParse'],
                ['name' => 'ToNull'],
            ],
            'validators' => [
                [
                    'name' => 'IsFloat'
                ]
            ],
        ]);

        // Add input filter for "saleTotalPrice" field.
        $inputFilter->add([
            'name'     => 'saleTotalPrice',
            'required' => false,
            'filters'  => [
                ['name' => 'NumberParse'],
                ['name' => 'ToNull'],
            ],
            'validators' => [
                [
                    'name' => 'IsFloat'
                ]
            ],
        ]);

        // Add input filter for "unitPriceImportPurchase" field.
        $inputFilter->add([
            'name'     => 'unitPriceImportPurchase',
            'required' => false,
            'filters'  => [
                ['name' => 'NumberParse'],
                ['name' => 'ToNull'],
            ],
            'validators' => [
                [
                    'name' => 'IsFloat'
                ]
            ],
        ]);

        // Add input filter for "importSalePrice" field.
        $inputFilter->add([
            'name'     => 'importSalePrice',
            'required' => false,
            'filters'  => [
                ['name' => 'NumberParse'],
                ['name' => 'ToNull'],
            ],
            'validators' => [
                [
                    'name' => 'IsFloat'
                ]
            ],
        ]);

        // Add input filter for "currency" field.
        $inputFilter->add([
            'name'     => 'currency',
            'required' => true,
            'filters'  => [
                ['name' => 'ToInt'],
            ],
            'validators' => [
                [
                    'name'    => 'GreaterThan',
                    'options' => ['min' => 1],
                ]
            ],
        ]);

        // Add input filter for "supplierDeliveryTime" field.
        $inputFilter->add([
            'name'     => 'supplierDeliveryTime',
            'required' => false,
            'filters'  => [
                ['name' => 'StringTrim'],
                ['name' => 'ToNull'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 64
                    ],
                ],
            ],
        ]);

        // Add input filter for "files" field.
        // Add input filter for "satCode" field.
        $inputFilter->add([
            'name'     => 'satCode',
            'required' => false,
            'filters'  => [
                ['name' => 'StringTrim'],
                ['name' => 'ToNull'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 32,
                    ],
                ],
            ],
        ]);
    }
}