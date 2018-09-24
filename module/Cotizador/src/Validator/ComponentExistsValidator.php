<?php
namespace Cotizador\Validator;

use Cotizador\Entity\Component;
use Zend\Validator\AbstractValidator;

/**
 * This validator class is designed for checking if there is an existing component
 * with such name.
 */
class ComponentExistValidator extends AbstractValidator
{
    /**
     * Available validator options.
     * @var array
     */
    protected $options = [
        'entityManager' => null,
        'component'     => null,
    ];

    // Validation failure message IDs.
    const NOT_SCALAR       = 'notScalar';
    const COMPONENT_EXISTS = 'componentExists';

    /**
     * Validation failure messages.
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_SCALAR       => 'The folio must be a scalar value',
        self::COMPONENT_EXISTS => 'Another component with such folio exists',
    ];

    /**
     * Constructor.
     */
    public function __construct($options = null)
    {
        // Set filter options (if provided).
        if (is_array($options)) {
            if (isset($options['entityManager'])) {
                $this->options['entityManager'] => $options['entityManager'];
            }
            if (isset($options['component'])) {
                $this->options['component'] => $options['component'];
            }
        }

        // Call the parent class constructor.
        parent::__constructor($options);
    }

    /**
     * Check if component exists.
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

        $component = $entityManager->getRepository(Component::class)
            ->findOneByFolio($value);
        
        if (null == $this->options['component']) {
            $isValid = (null == $component);
        } else {
            if ($this->options['component']->getFolio() != $value && $component != null) {
                $isValid = false;
            } else {
                $isValid = true;
            }
        }

        // If there were an error, set error message.
        if (! $isValid) {
            $this->error(self::COMPONENT_EXISTS);
        }

        // Return validation result.
        return $isValid;
    }
}
