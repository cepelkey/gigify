<?php

namespace GIGify\Common;

class Deactivate
{
    public static function deactivate() {
        flush_rewrite_rules();
    }
}