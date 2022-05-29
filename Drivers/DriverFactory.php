<?php

namespace Ady\Bundle\MaintenanceBundle\Drivers;

use Symfony\Contracts\Translation\TranslatorInterface;
use function trigger_deprecation;

/**
 * Factory for create driver.
 *
 * @author  Gilles Gauthier <g.gauthier@lexik.fr>
 */
class DriverFactory
{
    /**
     * @var array
     */
    protected $driverOptions;

    /**
     * @var DatabaseDriver
     */
    protected $dbDriver;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    const DATABASE_DRIVER = 'Ady\Bundle\MaintenanceBundle\Drivers\DatabaseDriver';

    /**
     * Constructor driver factory.
     *
     * @param DatabaseDriver      $dbDriver      The databaseDriver Service
     * @param TranslatorInterface $translator    The translator service
     * @param array               $driverOptions Options driver
     *
     * @throws \ErrorException
     */
    public function __construct(DatabaseDriver $dbDriver, TranslatorInterface $translator, array $driverOptions)
    {
        $this->driverOptions = $driverOptions;

        if (!isset($this->driverOptions['class'])) {
            throw new \ErrorException('You need to define a driver class');
        }

        $this->dbDriver = $dbDriver;
        $this->translator = $translator;
    }

    /**
     * Return the driver.
     *
     * @return mixed
     *
     * @throws \ErrorException
     */
    public function getDriver()
    {
        $class = $this->driverOptions['class'];

        if (!class_exists($class)) {
            throw new \ErrorException("Class '".$class."' not found in ".get_class($this));
        }

        if (!\is_array($this->driverOptions['options'])) {
            trigger_deprecation(
                'ady/maintenance-bundle',
                '3.0.6',
                "The optional configuration 'driver.options' must be an array. Other types are deprecated"
            );
            $this->driverOptions['options'] = (array) $this->driverOptions['options'];
        }

        if (self::DATABASE_DRIVER === $class) {
            $driver = $this->dbDriver;
            $driver->setOptions($this->driverOptions['options']);
        } else {
            $driver = new $class($this->driverOptions['options']);
        }

        $driver->setTranslator($this->translator);

        return $driver;
    }
}
