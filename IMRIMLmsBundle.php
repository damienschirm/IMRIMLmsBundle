<?php

namespace IMRIM\Bundle\LmsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class IMRIMLmsBundle extends Bundle
{
    public function __construct() {
        set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));
    }
}
