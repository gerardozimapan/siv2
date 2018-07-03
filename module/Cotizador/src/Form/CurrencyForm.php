<?php
namespace Cotizador\Form;

use Zend\InputFilter\InputFilter;
use Zend\Filter\ToInt;
use Zend\Form\Form;

/**
 * This form is used to collect currency's data.
 */
class CurrencyForm extends Form
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('currency-form');

        // Set POST method for this form.
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This methid adds elements to form (input fields and submit button).
     */
    private function addElements()
    {
        // Add "id" field
        $this->add([
            'type' => 'text',
            'name' => 'id',
            'options' => [
                'label' => 'ID',
            ],
        ]);

        // Add "name" field
        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Nombre',
            ],
        ]);

        // Add "code" field
        $this->add([
            'type' => 'text',
            'name' => 'code',
            'options' => [
                'label' => 'Código',
            ],
        ]);

        // Add "symbol" field
        $this->add([
            'type' => 'text',
            'name' => 'symbol',
            'options' => [
                'label' => 'Símbolo',
            ],
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
     * This method creates input filters (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        // Create main input filter.
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        // Add input for "id" field
        $inputFilter->add([
            'name'      => 'id',
            'required'  => true,
            'filters'   => [
                ['name' => 'ToInt'],
            ],
            'validators' => [
                [
                    'name'      => 'GreaterThan',
                    'options'   => [
                        'min'   => 0,
                    ],
                ],
            ],
        ]);

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
                        'max'   => 64,
                    ],
                ],
            ],
        ]);

        // Add input for "code" field
        $inputFilter->add([
            'name'      => 'code',
            'required'  => true,
            'filters'   => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'      => 'StringLength',
                    'options'   => [
                        'min'   => 1,
                        'max'   => 4,
                    ],
                ],
            ],
        ]);

        // Add input for "symbol" field
        $inputFilter->add([
            'name'      => 'symbol',
            'required'  => true,
            'filters'   => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'      => 'StringLength',
                    'options'   => [
                        'min'   => 1,
                        'max'   => 2,
                    ],
                ],
            ],
        ]);
    }
}
