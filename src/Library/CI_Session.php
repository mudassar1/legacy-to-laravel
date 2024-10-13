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

use mudassar1\Legacy\Exception\NotSupportedException;

class CI_Session
{
    /** @var \Illuminate\Session\Store */
    private $session;

    /**
     * @param App|array|null $params Configuration parameters
     *
     * @return void
     */
    public function __construct($params = null)
    {
        if (is_array($params)) {
            throw new NotSupportedException(
                'Configuration array is not supported.'
            );
        }

        $this->session = session();
    }

    /**
     * Set userdata.
     *
     * Legacy CI_Session compatibility method
     *
     * @param mixed $data  Session data key or an associative array
     * @param mixed $value Value to store
     *
     * @return void
     */
    public function set_userdata($data, $value = null)
    {
        $this->session->put($data, $value);
    }

    /**
     * Userdata (fetch).
     *
     * Legacy CI_Session compatibility method
     *
     * @param string $key Session data key
     *
     * @return mixed Session data value or NULL if not found
     */
    public function userdata(?string $key = null)
    {
        return $this->session->get($key);
    }

    /**
     * __set().
     *
     * @param string $key   Session data key
     * @param mixed  $value Session data value
     *
     * @return void
     */
    public function __set(string $key, $value)
    {
        $this->session->put($key, $value);
    }

    /**
     * __get().
     *
     * @param string $key 'session_id' or a session data key
     *
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->session->get($key);
    }

    /**
     * Unset userdata.
     *
     * Legacy CI_Session compatibility method
     *
     * @param mixed $key Session data key(s)
     *
     * @return void
     */
    public function unset_userdata($key)
    {
        $this->session->remove($key);
    }

    /**
     * Set flashdata.
     *
     * Legacy CI_Session compatibility method
     *
     * @param mixed $data  Session data key or an associative array
     * @param mixed $value Value to store
     *
     * @return void
     */
    public function set_flashdata($data, $value = null)
    {
        $this->session->flash($data, $value);
    }

    /**
     * Flashdata (fetch).
     *
     * Legacy CI_Session compatibility method
     *
     * @param string $key Session data key
     *
     * @return mixed Session data value or NULL if not found
     */
    public function flashdata(?string $key = null)
    {
        return $this->session->get($key);
    }

    /**
     * Session destroy.
     *
     * Legacy CI_Session compatibility method
     */
    public function sess_destroy(): void
    {
        $this->session->invalidate();
    }
}
