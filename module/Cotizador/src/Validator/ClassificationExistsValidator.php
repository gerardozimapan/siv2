<?php
namespace Cotizador\Validator;

use Zend\Validator\AbstractValidator;
use Cotizador\Entity\Classification;

/**
 * This validator class is designed for checking if there is an existing classification
 * with such name.
 */
class ClassificationExistsValidator extends AbstractValidator
{
    /**
     * Available validator options.
     * @var array
     */
    protected $options = [
        'entityManager'     => null,
        'classification'    => null,
    ];

    // Validation failure message IDs.
    const NOT_SCALAR            = 'notScalar';
    const CLASSIFICATION_EXISTS = 'classificationExists';

    /**
     * Validation failure messages.
     * @var array
     */
    protected $messageTemplate = [
        self::NOT_SCALAR            => 'The name must be a scalr value',
        self::CLASSIFICATION_EXISTS => 'Another classification with such name exists',
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
            if (isset($options['classification'])) {
                $this->options['classification'] = $options['classification'];
            }
        }

        // Call the parent class constructor.
        parent::__construct($options);
    }

    /**
     * Check if classification exists.
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

        $classification = $entityManager->getRepository(Classification::class)
                ->findOneByName($value);

        if (null == $this->options['classification']) {
            $isValid = (null == $classification);
        } else {
            if ($this->options['classification']->getName() != $value && $classification != null) {
                $isValid = false;
            } else {
                $isValid = true;
            }
        }

        // If there were an error, set error message.
        if (! $isValid) {
            $this->error(self::CLASSIFICATION_EXISTS);
        }

        // Return validation result.
        return $isValid;
    }
}
