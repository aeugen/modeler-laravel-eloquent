<?php

namespace Aeugen\Modeler\Meta;

interface Schema
{
    /**
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function connection();

    /**
     * @return string
     */
    public function schema();

    /**
     * @return \Aeugen\Modeler\Meta\Blueprint[]
     */
    public function tables();

    /**
     * @param string $table
     *
     * @return bool
     */
    public function has($table);

    /**
     * @param string $table
     *
     * @return \Aeugen\Modeler\Meta\Blueprint
     */
    public function table($table);

    /**
     * @param \Aeugen\Modeler\Meta\Blueprint $table
     *
     * @return array
     */
    public function referencing(Blueprint $table);
}
