<?php

use Random\RandomException;

class Utils {
    /**
     * @throws RandomException
     */
    public function genKey(): string {
        return bin2hex(random_bytes(16));
    }
}