<?php
namespace NotReady\Lib;


interface implA{
    public function methodA();
}

interface implB{
    public function methodB();
}

abstract class super{
    abstract protected function method();
}

class ConClass extends super implements implA, implB{
    public function methodA()
    {
        echo "methodA call";
    }

    public function methodB()
    {
        echo "methodB call";
    }

    public function method()
    {
        // TODO: Implement method() method.
    }
}