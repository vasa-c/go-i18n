<?php
/**
 * Initialization of unit tests for go\I18n package
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n;

use go\I18n\Autoloader;

require_once(__DIR__.'/../src/Autoloader.php');

Autoloader::register();
Autoloader::registerForTests(__DIR__);
