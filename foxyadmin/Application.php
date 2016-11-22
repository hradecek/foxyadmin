<?php

namespace Foxytouch;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application as App;

/**
 * Main application class.
 *
 * @package Foxytouch
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class Application extends App
{
    /**
     * Main application directory.
     */
    const APP_DIR = 'foxyadmin';

    /**
     * Autoload directory.
     */
    const AUTOLOAD_DIR = '/vendor/autoload.php';

    /**
     * Composer class loader.
     * 
     * @var
     */
    public $classLoader;

    /**
     * Alias loader;
     * 
     * @var
     */
    public $aliasLoader;

    /**
     * Application constructor.
     * 
     * @param null|string $basePath
     */
    public function __construct($basePath)
    {
        parent::__construct($basePath);
        $this->classLoader = require base_path() . self::AUTOLOAD_DIR;
        $this->aliasLoader = AliasLoader::getInstance();
    }

    /**
     * Get the path to the application directory.
     *
     * @return string
     */
    public function path()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . self::APP_DIR;
    }
}

