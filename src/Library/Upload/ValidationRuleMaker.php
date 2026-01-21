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

namespace mudassar1\Legacy\Library\Upload;

use mudassar1\Legacy\Exception\NotSupportedException;

class ValidationRuleMaker
{
    /** @var string */
    private $fieldName;

    /** @var array */
    private $ci3Config;

    /** @var array */
    private $rules = [];

    public function convert(string $fieldName, array $ci3Config)
    {
        $this->fieldName = $fieldName;
        $this->ci3Config = $ci3Config;
        $this->rules = [];

        // Laravel's equivalent of 'uploaded' would be 'file' for file validation.
        $this->rules[] = 'file';

        $this->setExtIn();
        $this->setMaxSize();
        $this->setMaxDims();

        if (isset($this->ci3Config['min_width'])) {
            throw new NotSupportedException(
                'config "min_width" is not supported in Laravel validation.'
            );
        }

        if (isset($this->ci3Config['min_height'])) {
            throw new NotSupportedException(
                'config "min_height" is not supported in Laravel validation.'
            );
        }

        return [
            $fieldName => implode('|', $this->rules),
        ];
    }

    private function setExtIn()
    {
        if (isset($this->ci3Config['allowed_types'])) {
            $extsArray = explode('|', $this->ci3Config['allowed_types']);
            $exts = implode(',', $extsArray);
            $this->rules[] = 'mimes:' . implode(',', $extsArray);
        }
    }

    private function setMaxSize()
    {
        if (isset($this->ci3Config['max_size'])) {
            // Convert kilobytes to bytes as Laravel requires max size in kilobytes.
            $this->rules[] = 'max:' . $this->ci3Config['max_size'];
        }
    }

    private function setMaxDims()
    {
        $maxWidth = $this->ci3Config['max_width'] ?? 0;
        $maxHeight = $this->ci3Config['max_height'] ?? 0;

        if ($maxWidth === 0 && $maxHeight === 0) {
            return;
        }

        $this->rules[] = 'dimensions:max_width=' . $maxWidth . ',max_height=' . $maxHeight;
    }
}
