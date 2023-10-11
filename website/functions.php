<?php

    function getTypeFromArray(array $array): array {
        $res = array();
        foreach($array as $element){
            array_push($res, gettype($element));
        }
        return $res;
    }

    function getTypesToString(array $arrayOfTypes): string{
        $res = "";
        foreach($arrayOfTypes as $type) {
            $res += substr($type, 0);
    }
    return $res;
}
