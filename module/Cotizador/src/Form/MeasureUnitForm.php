<?php
namespace Cotizador\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Filter\ToNull;
use Cotizador\Validator\MeasureUnitExistsValidator;

/**
 * This form is used to collect measure unit's data.
 */
class MeasureUnitForm extends Form
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * Current measure unit.
     * @var Cotizador\Entity\MeasureUnit
     */
    private $measureUnit = null;

    /**
     * Constructor.
     */
    public function __constructor($entityManager = null, $measureUnit = null)
    {
        // Define form name
        parent::__constructor('measure-unit-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Save parameters for internal use.
        $this->entityManager = $entityManager;
        $this->measureUnit = $measureUnit;

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

        // Add "code" field
        $this->add([
            'type' => 'text',
            'name' => 'code',
            'options' => [
                'label' => 'Código',
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
                        'max'   => 32,
                    ],
                ],
                [
                    'name'      => MeasureUnitExistsValidator::class,
                    'options'   => [
                        'entityManager' => $this->entityManager,
                        'measureUnit'   => $this->measureUnit,
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
                        'max'   => 64,
                    ],
                ]
            ],
        ]);
    }
}
