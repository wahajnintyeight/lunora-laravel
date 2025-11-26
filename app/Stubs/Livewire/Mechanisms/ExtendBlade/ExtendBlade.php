<?php

namespace Livewire\Mechanisms\ExtendBlade;

/**
 * Stub class for Livewire's ExtendBlade when Livewire is not installed.
 * This prevents errors in Laravel's exception renderer.
 */
class ExtendBlade
{
    /**
     * Check if a Livewire component is being rendered.
     * Always returns false since Livewire is not installed.
     */
    public static function isRenderingLivewireComponent(): bool
    {
        return false;
    }
}

