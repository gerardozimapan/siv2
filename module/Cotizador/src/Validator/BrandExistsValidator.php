<?php
namespace Cotizador\Validator;

use Zend\Validator\AbstractValidator;
use Cotizador\Entity\Brand;

/**
 * This validator class is designed for checking if there is an existing brand
 * with such name.
 */
class BrandExistsValidator extends AbstractValidator
{
    /**
     * Available validator options.
     * @var array
     */
    protected $options = [
        'entityManager' => null,
        'brand'         => null,
    ];

    // Validation failure message IDs.
    const NOT_SCALAR = 'notScalar';
    const BRAND_EXISTS = 'brandExists';

    /**
     * Validation failure messages.
     * @var array
     */
    protected $messageTemplate = [
        self::NOT_SCALAR    => 'The name must be a scalr value',
        self::BRAND_EXISTS  => 'Another brand with such name exists',
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
            if (isset($options['brand'])) {
                $this->options['brand'] = $options['brand'];
            }
        }

        // Call the parent class constructor.
        parent::__construct($options);
    }

    /**
     * Check if brand exists.
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

        $brand = $entityManager->getRepository(Brand::class)
                ->findOneByName($value);

        if (null == $this->option['brand']) {
            $isValid = (null == $brand);
        } else {
            if ($this->options['brand']->getName() != $value && $brand != null) {
                $isValid = false;
            } else {
                $isValid = true;
            }
        }

        // If there were an error, set error message.
        if (! $isValid) {
            $this->error(self::BRAND_EXISTS);
        }

        // Return validation result.
        return $isValid;
    }
}
