<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Kenji Suzuki
 * Copyright (c) 2022 Agung Sugiarto.
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/kenjis/ci3-to-4-upgrade-helper
 * @see https://github.com/agungsugiarto/legacy-to-laravel
 */

namespace mudassar1\Legacy\Core\Loader;

use mudassar1\Legacy\Core\CI_Controller;
use mudassar1\Legacy\Internal\DebugLog;

class ControllerPropertyInjector
{
    /** @var CI_Controller */
    private $controller;

    public function __construct(CI_Controller $controller)
    {
        $this->controller = $controller;
    }

    public function inject(string $property, object $obj): void
    {
        if (property_exists($this->controller, $property)) {
            $message = get_class($this->controller).'::$'.$property.' already exists';
            DebugLog::log(__METHOD__, $message);

            return;
        }

        $this->controller->$property = $obj;
    }

    public function injectMultiple(array $instances): void
    {
        foreach ($instances as $property => $instance) {
            $this->inject($property, $instance);
        }
    }
}
