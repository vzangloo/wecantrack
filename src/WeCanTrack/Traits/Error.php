<?php

namespace WeCanTrack\Traits;

/**
 * Class Error
 * @package WeCanTrack\Traits
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2021 Saleduck Asia Sdn Bhd
 */
trait Error
{
    protected array $errors = [];
    protected bool $debugMode = false;

    public function debug($enabled = true): self
    {
        $this->debugMode = $enabled;
        return $this;
    }

    public function addError($message)
    {
        $this->errors[] = $message;
    }

    public function addErrors(array $messages = [])
    {
        if ($messages) {
            $this->errors = array_merge($this->errors, $messages);
        }
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    public function hasError(): bool
    {
        return !$this->isValid();
    }

    /**
     * Get errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}