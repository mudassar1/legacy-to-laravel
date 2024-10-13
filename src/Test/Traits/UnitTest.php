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

namespace mudassar1\Legacy\Test\Traits;

use Config\Services;
use mudassar1\Legacy\Core\CI_Controller;
use mudassar1\Legacy\Core\CI_Model;

trait UnitTest
{
    /**
     * Create a controller instance.
     *
     * @param class-string $classname
     */
    public function newController(string $classname): CI_Controller
    {
        $this->resetInstance();

        $controller = new $classname();
        $controller->initController(
            Services::request(),
            Services::response(),
            Services::logger()
        );

        $this->CI = &get_instance();

        return $controller;
    }

    /**
     * Create a model instance.
     *
     * @param class-string $classname
     */
    public function newModel(string $classname): CI_Model
    {
        $this->resetInstance();

        $this->CI->load->model($classname);

        // Is the model in a sub-folder?
        $lastSlash = strrpos($classname, '/');
        if ($lastSlash !== false) {
            $classname = substr($classname, ++$lastSlash);
        }

        return $this->CI->$classname;
    }
}
