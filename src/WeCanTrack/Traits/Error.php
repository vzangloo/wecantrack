<?php

namespace WeCanTrack\Traits;

/**
 * Trait Error
 * @package WeCanTrack\Traits
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2021 Saleduck Asia Sdn Bhd
 */
trait Error
{
    /**
     * @var array $errors The errors array
     */
    protected array $errors = [];

    /**
     * @var bool $debugMode The debug mode. Default is false.
     */
    protected bool $debugMode = false;

    /**
     * Enable debug mode
     *
     * @param bool $enabled Set true to enable debug mode. Default is true.
     * @return $this
     */
    public function debug(bool $enabled = true): self
    {
        $this->debugMode = $enabled;
        return $this;
    }

    /**
     * Add error message.
     *
     * @param string $message The error message.
     * @return void
     */
    public function addError(string $message)
    {
        $this->errors[] = $message;
    }

    /**
     * Add array of error messages.
     *
     * @param array $messages Array of error messages.
     * @return void
     */
    public function addErrors(array $messages = [])
    {
        if ($messages) {
            $this->errors = array_merge($this->errors, $messages);
        }
    }

    /**
     * Check is valid.
     *
     * @return bool True if valid.
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * Check is has error.
     *
     * @return bool True if has error.
     */
    public function hasError(): bool
    {
        return !$this->isValid();
    }

    /**
     * Get all errors
     *
     * @return array Array of error messages.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
