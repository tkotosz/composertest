<?php

namespace Foo\Bar;

use Symfony\Component\Yaml\Dumper;

class FooPlugin
{
    public function load()
    {
        var_dump(__METHOD__);
    }
}