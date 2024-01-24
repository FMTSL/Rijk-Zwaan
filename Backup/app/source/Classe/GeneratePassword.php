<?php

namespace Source\Classe;


class GeneratePassword
{

public function generate($size, $capital, $lowercase, $numbers, $symbols){
        $a = "ABCDEFGHIJKLMNOPQRSTUVYXWZ";
        $b = "abcdefghijklmnopqrstuvyxwz";
        $c = "0123456789";
        $d = "!@#$%¨&*()_+=";

        if ($capital){
            $password .= str_shuffle($a);
        }

        if ($lowercase){
            $password .= str_shuffle($b);
        }

        if ($numbers){
            $password .= str_shuffle($c);
        }

        if ($symbols){
            $password .= str_shuffle($d);
        }
        return substr(str_shuffle($password),0,$size);
    }

}
