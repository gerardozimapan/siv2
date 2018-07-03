<?php
namespace Cotizador\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Filter\ToNull;
use Cotizador\Validator\ClassificationExistsValidator;

/**
 * This form is used to collect classification's data.
 */
class ClassificationForm extends Form
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * Current classification.
     * @var Cotizador\Entity\Classification
     */
    private $classification = null;

    /**
     * Constructor.
     */
    public function __constructor($entityManager = null, $classification = null)
    {
        // Define form name
        parent::__constructor('classification-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Save parameters for internal use.
        $this->entityManager = $entityManager;
        $this->classification = $classification;

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds element to form (input fields and submit button).
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

        // Add "description" field
        $this->add([
            'type' => 'text',
            'name' => 'description',
            'options' => [
                'label' => 'Descripción',
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
                        'max'   => 64,
                    ],
                ],
                [
                    'name'      => ClassificationExistsValidator::class,
                    'options'   => [
                        'entityManager'     => $this->entityManager,
                        'classification'    => $this->classification,
                    ],
                ],
            ],
        ]);

        // Add input for "description" field
        $inputFilter->add([
            'name'      => 'description',
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
                        'max'   => 254,
                    ],
                ]
            ],
        ]);
    }
}
