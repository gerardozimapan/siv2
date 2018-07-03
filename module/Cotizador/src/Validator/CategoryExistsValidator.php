<?php
namespace Cotizador\Validator;

use Zend\Validator\AbstractValidator;
use Cotizador\Entity\Category;

/**
 * This validator class is designed for checking if there is an existing category
 * with such an name.
 */
class CategoryExistsValidator extends AbstractValidator
{
    /**
     * Available validator options.
     * @var array
     */
    protected $options = [
        'entityManager' => null,
        'category' => null,
    ];

    // Validation failure message IDs.
    const NOT_SCALAR = 'notScalar';
    const CATEGORY_EXISTS = 'categoryExists';

    /**
     * Validation failure messages.
     * @var array
     */
    protected $messageTemplate = [
        self::NOT_SCALAR => "The name must be a scalar value",
        self::CATEGORY_EXISTS => "Another category with such an name exists",
    ];

    /**
     * Constructor.
     */
    public function __construct($options = null)
    {
        // Set filter options (if provided).
        if (is_array($options)) {
            if (isset($options['entityManager'])) {
                $this->options['entityManager'] = $options['entityManager'];
            }
            if (isset($options['category'])) {
                $this->options['category'] = $options['category'];
            }
        }

        // Call the parent class constructor
        parent::__construct($options);
    }

    /**
     * Check if category exists.
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (! is_scalar($value)) {
            $this->error(self::NOT_SCALAR);
            return false;
        }

        // Get Doctrine entity manager.
        $entityManager = $this->options['entityManager'];

        $category = $entityManager->getRepository(Category::class)
                ->findOneByName($value);

        if (null == $this->options['category']) {
            $isValid = (null == $category);
        } else {
            if ($this->options['category']->getName() != $value && $category != null) {
                $isValid = false;
            } else {
                $isValid = true;
            }
        }

        // If there were an error, set error message.
        if (! $isValid) {
            $this->error(self::CATEGORY_EXISTS);
        }

        // Return validation result.
        return $isValid;
    }
}
