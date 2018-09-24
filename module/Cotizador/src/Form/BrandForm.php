<?php
namespace Cotizador\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Filter\ToNull;
use Cotizador\Validator\BrandExistsValidator;

/**
 * This form is used to collect brand's data.
 */
class BrandForm extends Form
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * Current brand.
     * @var Cotizador\Entity\Brand
     */
    private $brand = null;

    /**
     * Constructor.
     */
    public function __construct($entityManager = null, $brand = null)
    {
        // Define form name
        parent::__construct('brand-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Save parameters for internal use.
        $this->entityManager = $entityManager;
        $this->brand = $brand;

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
                    'name'      => BrandExistsValidator::class,
                    'options'   => [
                        'entityManager' => $this->entityManager,
                        'brand'         => $this->brand,
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
