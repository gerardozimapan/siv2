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
 * This form is used to collect supplier's data, The form
 * can work in two scenarios - 'create' an 'update'.
 */
class SupplierForm extends Form
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('supplier-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    private function addElements()
    {
        // Add "name" field
        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Nombre',
            ],
        ]);

        // Add 'categories' checkboxes
        $this->add([
            'type' => 'MultiCheckbox',
            'name' => 'categories',
            'options' => [
                'label' => 'Categorias',
            ],
        ]);

        // Add "address" field
        $this->add([
            'type' => 'text',
            'name' => 'taxAddress',
            'options' => [
                'label' => 'Dirección',
            ],
        ]);

        // Add "rfc" field
        $this->add([
            'type' => 'text',
            'name' => 'rfc',
            'options' => [
                'label' => 'R.F.C.',
            ],
        ]);

        // Add "payment terms" field
        $this->add([
            'type' => 'text',
            'name' => 'paymentTerms',
            'options' => [
                'label' => 'Condiciones de Pago',
            ],
        ]);

        // Add "credit limit" field
        $this->add([
            'type' => 'text',
            'name' => 'creditLimit',
            'options' => [
                'label' => 'Límite de Crédito',
            ],
        ]);

        // Add "discount" field
        $this->add([
            'type' => 'text',
            'name' => 'discount',
            'options' => [
                'label' => 'Descuento',
            ],
        ]);

        // Add "delivery time" field
        $this->add([
            'type' => 'text',
            'name' => 'deliveryTime',
            'options' => [
                'label' => 'Tiempo de Entrega',
            ],
        ]);

        // Add "comments" field
        $this->add([
            'type' => 'text',
            'name' => 'comments',
            'options' => [
                'label' => 'Observaciones',
            ],
        ]);

        // Add "bankAccounts" field
        $this->add([
            'type' => 'hidden',
            'name' => 'bankAccounts',
        ]);

        // Add "contacts" field
        $this->add([
            'type' => 'hidden',
            'name' => 'contacts',
        ]);

        // Add the Submit button
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Create',
            ],
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        // Create main input filter
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        // Add input for "name" field
        $inputFilter->add([
            'name'      => 'name',
            'required'  => true,
            'filters'   => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'      => 'StringLength',
                    'options'   => [
                        'min'   => 1,
                        'max'   => 128,
                    ],
                ],
            ],
        ]);

        // Add input for "categories" field
        $inputFilter->add([
            'class'     => ArrayInput::class,
            'name'      => 'categories',
            'required'  => false,
            'filters'   => [
                ['name' => 'ToInt'],
            ],
            'validators' => [
                [
                    'name' => 'GreaterThan',
                    'options' => ['min' => 0],
                ],
            ],
        ]);

        // Add input for "taxAddress" field
        $inputFilter->add([
            'name'      => 'taxAddress',
            'required'  => true,
            'filters'   => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'      => 'StringLength',
                    'options'   => [
                        'min'   => 1,
                        'max'   => 256,
                    ],
                ],
            ],
        ]);

        // Add input for "rfc" field
        $inputFilter->add([
            'name'      => 'rfc',
            'required'  => false,
            'filters'   => [
                [
                    'name' => 'ToNull',
                ],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'      => 'StringLength',
                    'options'   => [
                        'min'   => 1,
                        'max'   => 16,
                    ],
                ],
            ],
        ]);

        // Add input for "paymentTerms" field
        $inputFilter->add([
            'name'      => 'paymentTerms',
            'required'  => false,
            'filters'   => [
                [
                    'name' => 'ToNull',
                ],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'      => 'StringLength',
                    'options'   => [
                        'min'   => 1,
                        'max'   => 128,
                    ],
                ],
            ],
        ]);

        // Add input for "creditLimit" field
        $inputFilter->add([
            'name'      => 'creditLimit',
            'required'  => false,
            'filters'   => [
                [
                    'name' => 'ToNull',
                ],
                [
                    'name' => 'NumberParse',
                    'options' => [
                        'locale' => 'es_MX',
                        'style' => \NumberFormatter::CURRENCY,
                    ],
                ],
            ],
            'validators' => [
                [
                    'name' => 'IsFloat',
                    'options' => [
                        'locale' => 'es_MX'
                    ],
                ],
            ],
        ]);

        // Add input for "discount" field
        $inputFilter->add([
            'name'      => 'discount',
            'required'  => false,
            'filters'   => [
                [
                    'name' => 'ToNull',
                ],
                [
                    'name' => 'NumberParse',
                    'options' => [
                        'locale' => 'es_MX',
                        'style' => \NumberFormatter::PERCENT,
                    ],
                ],
            ],
            'validators' => [
                [
                    'name' => 'IsFloat',
                    'options' => [
                        'locale' => 'es_MX'
                    ],
                ],
            ],
        ]);

        // Add input for "deliveryTime" field
        $inputFilter->add([
            'name'      => 'deliveryTime',
            'required'  => false,
            'filters'   => [
                [
                    'name' => 'ToNull',
                ],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'      => 'StringLength',
                    'options'   => [
                        'min'   => 1,
                        'max'   => 64,
                    ],
                ],
            ],
        ]);

        // Add input for "comments" field
        $inputFilter->add([
            'name'      => 'comments',
            'required'  => false,
            'filters'   => [
                [
                    'name' => 'ToNull',
                ],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'      => 'StringLength',
                    'options'   => [
                        'min'   => 1,
                        'max'   => 1024,
                    ],
                ],
            ],
        ]);
    }
}
