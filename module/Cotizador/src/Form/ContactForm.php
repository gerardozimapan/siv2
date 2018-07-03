<?php
namespace Cotizador\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Filter\ToNull;

/**
 * This form is used to collect contact's data.
 */
class ContactForm extends Form
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('contact-form');

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

        // Add "phoneNumber" field
        $this->add([
            'type' => 'text',
            'name' => 'phoneNumber',
            'options' => [
                'label' => 'Teléfono',
            ],
        ]);

        // Add "email" field
        $this->add([
            'type' => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'Email',
            ],
        ]);

        // Add "job" field
        $this->add([
            'type' => 'text',
            'name' => 'job',
            'options' => [
                'label' => 'Puesto',
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
                        'max'   => 256,
                    ],
                ],
            ],
        ]);

        // Add input for "phoneNumber" field
        $inputFilter->add([
            'name'      => 'phoneNumber',
            'required'  => false,
            'filters'   => [
                ['name' => 'ToNull'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'      => 'StringLength',
                    'options'   => [
                        'min'   => 1,
                        'max'   => 32,
                    ],
                ],
            ],
        ]);

        // Add input for "email" field
        $inputFilter->add([
            'name'      => 'email',
            'required'  => false,
            'filters'   => [
                ['name' => 'ToNull'],
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
                [
                    'name'      => 'EmailAddress',
                    'options'   => [
                        'allow'         => \Zend\Validator\Hostname::ALLOW_DNS,
                        'useMxCheck'    => false,
                    ],
                ],
            ],
        ]);

        // Add input for "job" field
        $inputFilter->add([
            'name'      => 'job',
            'required'  => false,
            'filters'   => [
                ['name' => 'ToNull'],
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
    }
}
