<?php
namespace Cotizador\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use Zend\Filter\ToNull;
use Zend\Validator\Digits;

/**
 * This form is used to collect bank account's data.
 */
class BankAccountForm extends Form
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('bank-account-form');

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
        // Add "bank" field
        $this->add([
            'type' => 'text',
            'name' => 'bank',
            'options' => [
                'label' => 'Banco',
            ],
        ]);

        // Add "number" field
        $this->add([
            'type' => 'text',
            'name' => 'number',
            'options' => [
                'label' => 'Cuenta',
            ],
        ]);

        // Add "clabe" field
        $this->add([
            'type' => 'text',
            'name' => 'clabe',
            'options' => [
                'label' => 'CLABE',
            ],
        ]);

        // Add "currency" field
        $this->add([
            'type' => 'radio',
            'name' => 'currency',
            'options' => [
                'label' => 'Moneda',
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
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        // Create main input filter
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        // Add input for "bank" field
        $inputFilter->add([
            'name'      => 'bank',
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

        // Add input for "number" field
        $inputFilter->add([
            'name'      => 'number',
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

        // Add input for "clabe" field
        $inputFilter->add([
            'name'      => 'clabe',
            'required'  => false,
            'filters'   => [
                ['name' => 'ToNull'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'      => 'StringLength',
                    'options'   => [
                        'min'   => 18,
                        'max'   => 18,
                    ],
                ],
                ['name' => 'Digits',],
            ],
        ]);

        // Add input for "currency" field
        $inputFilter->add([
            'name'      => 'currency',
            'required'  => true,
            'filters'   => [
                ['name' => 'ToInt'],
            ],
            'validators' => [
                [
                    'name' => 'GreaterThan',
                    'options' => ['min' => 1],
                ],
            ],
        ]);
    }
}
