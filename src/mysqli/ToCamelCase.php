<?php

namespace PDO\Mysqli;

trait ToCamelCase{

    private function convertToCamelCase(string $string):string
    {
        $words = explode('_',$string);
        $words = array_map(
            function($string){
                return ucfirst($string);
            },
            $words
        );

        $newString = implode('',$words);
        return lcfirst($newString);

    }

}
