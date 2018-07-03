<?php
namespace Cotizador\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Filter\ToNull;
use Cotizador\Validator\CategoryExistsValidator;

/**
 * This form is used to collect category's data, The form
 * can work in two scenarios - 'create' an 'update'.
 */
class CategoryForm extends Form
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * Current category.
     * @var Cotizador\Entity\Category
     */
    private $category = null;

    /**
     * Constructor.
     */
    public function __construct($entityManager = null, $category = null)
    {
        // Define form name
        parent::__construct('category-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Save parameters for internal use.
        $this->entityManager = $entityManager;
        $this->category = $category;

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
                    'name' => CategoryExistsValidator::class,
                    'options' => [
                        'entityManager' => $this->entityManager,
                        'category' => $this->category,
                    ],
                ],
            ],
        ]);

        // Add input for "description" field
        $inputFilter->add([
            'name'      => 'description',
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
                        'max'   => 256,
                    ],
                ],
            ],
        ]);
    }
}
