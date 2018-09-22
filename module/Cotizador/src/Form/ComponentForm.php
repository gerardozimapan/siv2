<?php
namespace Cotizador\Form;

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
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {

    }
}