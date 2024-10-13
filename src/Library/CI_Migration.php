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

namespace mudassar1\Legacy\Library;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;
use mudassar1\Legacy\Database\CI_DB;
use mudassar1\Legacy\Database\CI_DB_forge;

abstract class CI_Migration extends Migration
{
    /** @var CI_DB */
    protected $db_;

    /** @var CI_DB_forge */
    protected $dbforge;

    public function __construct(?Forge $forge = null)
    {
        parent::__construct($forge);

        $this->db_ = new CI_DB($this->db);
        $this->dbforge = new CI_DB_forge();
    }
}
