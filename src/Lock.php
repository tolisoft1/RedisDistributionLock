<?php
namespace fql;

interface Lock
{
    public function acquire();

    public function release();

    public function keepAlive();
}