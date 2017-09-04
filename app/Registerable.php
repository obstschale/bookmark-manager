<?php

namespace BookmarkManager;

/**
 * Interface Registerable.
 *
 * An object that can be `register()`ed.
 *
 * @since   0.2.0
 */
interface Registerable
{

    /**
     * Register the current Registerable.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function register();
}
