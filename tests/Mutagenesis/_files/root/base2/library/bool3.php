<?php

class Bool5
{
    public function boolAndAnonymousFunction()
    {
        $testArray = array();
        array_walk($testArray, function(&$parameter) {
            $parameter = count($parameter);
        });
        return $testArray;
    }
}
