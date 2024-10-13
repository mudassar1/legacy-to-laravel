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

namespace mudassar1\Legacy\Test\TestCase;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Kenjis\PhpUnitHelper\TestDouble;
use mudassar1\Legacy\Test\Traits\ResetInstance;

class DbTestCase extends CIUnitTestCase
{
    use ResetInstance;
    use TestDouble;
    use DatabaseTestTrait;

    /**
     * Should run seeding only once?
     *
     * @var bool
     */
    protected $seedOnce = false;

    /**
     * The seed file(s) used for all tests within this test case.
     * Should be fully-namespaced or relative to $basePath.
     *
     * @var string|array
     */
    protected $seed = [];

    /**
     * The path to the seeds directory.
     * Allows overriding the default application directories.
     *
     * @var string
     */
    protected $basePath = SUPPORTPATH.'Database/';

    /**
     * Should run db migration?
     *
     * @var bool
     */
    protected $migrate = false;
}
