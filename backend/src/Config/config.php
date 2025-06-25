<?php

namespace Src\Config;

class Config {
    public static function get(string $key): ?string {
        return getenv(strtoupper($key)) ?: null;
    }
}