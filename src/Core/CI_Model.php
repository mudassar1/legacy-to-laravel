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

/**
 * @property \mudassar1\Legacy\Core\CI_Benchmark $benchmark
 * @property \mudassar1\Legacy\Core\CI_Config $config
 * @property \mudassar1\Legacy\Database\CI_DB_query_builder $db
 * @property \mudassar1\Legacy\Core\CI_Input $input
 * @property \mudassar1\Legacy\Core\CI_Lang $lang
 * @property \mudassar1\Legacy\Core\CI_Loader $loader
 * @property \mudassar1\Legacy\Core\CI_Log $log
 * @property \mudassar1\Legacy\Core\CI_Output $output
 * @property \mudassar1\Legacy\Core\CI_Router $router
 * @property \mudassar1\Legacy\Core\CI_Security $security
 * @property \mudassar1\Legacy\Core\CI_Session $session
 * @property \mudassar1\Legacy\Core\CI_URI $uri
 * @property \mudassar1\Legacy\Core\CI_Utf8 $utf8
 */
class CI_Model extends \Illuminate\Database\Eloquent\Model
{
    public $timestamps = false;

//    protected $guarded = [];

    public function __construct()
    {
        if (!isset(get_instance()->db)) {
            get_instance()->load->database();
            get_instance()->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
        }
    }


    //ci
//    public function __get($key)
//    {
//        // Debugging note:
//        //  If you're here because you're getting an error message
//        //  saying 'Undefined Property: system/core/Model.php', it's
//        //  most likely a typo in your model code.
//        return get_instance()->$key;
//    }

    //laravel
//    public function __get($key)
//    {
//        return $this->getAttribute($key);
//    }


    public function __get($key)
    {
        // First, attempt to get the attribute as an Eloquent property
        if ($this->hasGetMutator($key)
            || array_key_exists($key, $this->attributes)
            || array_key_exists($key, $this->original)
            || array_key_exists($key, $this->changes)
            || array_key_exists($key, $this->casts)
            || array_key_exists($key, $this->classCastCache)
            || array_key_exists($key, $this->attributeCastCache)
            || array_key_exists($key, $this->relations)
            || array_key_exists($key, $this->appends)
        ) {
            return $this->getAttribute($key);
        }

        // If the property is not an Eloquent attribute, defer to CodeIgniter’s get_instance()
        return get_instance()->$key;
    }

}
