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

namespace mudassar1\Legacy\Core;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use mudassar1\Legacy\Internal\DebugLog;

/**
 * @property \mudassar1\Legacy\Core\CI_Benchmark            $benchmark
 * @property \mudassar1\Legacy\Core\CI_Config               $config
 * @property \mudassar1\Legacy\Database\CI_DB_query_builder $db
 * @property \mudassar1\Legacy\Core\CI_Input                $input
 * @property \mudassar1\Legacy\Core\CI_Lang                 $lang
 * @property \mudassar1\Legacy\Core\CI_Loader               $loader
 * @property \mudassar1\Legacy\Core\CI_Log                  $log
 * @property \mudassar1\Legacy\Core\CI_Output               $output
 * @property \mudassar1\Legacy\Core\CI_Router               $router
 * @property \mudassar1\Legacy\Core\CI_Security             $security
 * @property \mudassar1\Legacy\Core\CI_Session              $session
 * @property \mudassar1\Legacy\Core\CI_URI                  $uri
 * @property \mudassar1\Legacy\Core\CI_Utf8                 $utf8
 */
class CI_Controller extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var CI_Controller */
    private static $instance;

    /** @var CI_Loader */
    public $load;

    /**
     * Helpers that will be automatically loaded on class instantiation.
     *
     * @var array
     */
    protected $helpers = [];

    public function __construct()
    {
        $message = 'Creating Controller "'.static::class.'"';
        DebugLog::log(__METHOD__, $message);

        self::$instance = &$this;

        $this->load = new CI_Loader();
        $this->load->setController($this);

        $this->autoloadLibraries();
        $this->autoloadHelpers();
    }

    private function autoloadLibraries()
    {
        if (! isset($this->libraries)) {
            return;
        }

        foreach ($this->libraries as $library) {
            if ($library === 'database') {
                $this->load->database();

                continue;
            }

            $this->load->library($library);
        }
    }

    private function autoloadHelpers()
    {
        $this->load->helper($this->helpers);
    }

    public static function &get_instance(): CI_Controller
    {
        // In case when called without instantiation
        if (self::$instance === null) {
            new CI_Controller();
        }

        return self::$instance;
    }
}
