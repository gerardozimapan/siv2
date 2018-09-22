<?php
namespace Cotizador\Validator;

use Zend\Validator\AbstractValidator;
use Cotizador\Entity\MeasureUnit;

/**
 * This Validator class is designated for checking if there is an existing measure unit
 * with such name.
 */
class MeasureUnitExistsValidator extends AbstractValidator
{
    /**
     * Avalible validator options.
     * @var array
     */
    protected $options = [
        'entityManager' => null,
        'measureUnit'   => null,
    ];

    // Validation failure message IDs.
    const NOT_SCALAR          = 'notScalar';
    const MEASURE_UNIT_EXISTS = 'measureUnitExists';

    /**
     * Validation failure messages.
     * @var array
     */
    protected $messageTemplate = [
        self::NOT_SCALAR          => 'The name must be a scalar value',
        self::MEASURE_UNIT_EXISTS => 'Another measure unit with such name exists',
    ];

    /**
     * Constructor
     */
    public function __construct($options = null)
    {
        // Set filter options (if provided).
        if (is_array($options)) {
            if (isset($options['entityManager'])) {
                $this->options['entityManager'] = $options['entityManager'];
            }
            if (isset($options['measureUnit'])) {
                $this->options['measureUnit'] = $options['measureUnit'];
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

        $measureUnit = $entityManager->getRepository(MeasureUnit::class)
            ->findOneByName($value);

        if (null == $this->options['measureUnit']) {
            $isValid = (null == $measureUnit);
        } else {
            if ($this->options['measureUnit']->getName() != $value && $measureUnit != null) {
                $isValid = false;
            } else {
                $isValid = true;
            }
        }

        // If there were an error, set error message.
        if (! $isValid) {
            $this->error(self::MEASURE_UNIT_EXISTS);
        }

        // Return validation result.
        return $isValid;
    }
}
