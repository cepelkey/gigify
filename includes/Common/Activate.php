<?php

namespace GIGify\Common;

class Activate
{
    public static function activate() {
        flush_rewrite_rules();
    }
}