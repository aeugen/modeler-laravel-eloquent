<?php

namespace Aeugen\Modeler\Meta;

interface Column
{
    /**
     * @return \Illuminate\Support\Fluent
     */
    public function normalize();
}
