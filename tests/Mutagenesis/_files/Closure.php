<?php

class Some_Class_With_Closure
{

    protected function setSession($session = null)
    {
        if ($session === null) {
            $dave = function(Closure $func, array $d) use ($session) {
                $d = $session;
            };
            return true;
        }

        return false;
    }
}
