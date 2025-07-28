<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CacheInvalidation
{
    use Dispatchable, SerializesModels;

    public string $type;
    public mixed $key;

    /**
     * Create a new event instance.
     *
     * @param string $type
     * @param mixed|null $key
     */
    public function __construct(string $type, mixed $key = null)
    {
        $this->type = $type;
        $this->key = $key;
    }
}
