<?php

namespace Src;

class Utilities
{
    static public function hayError($error){
        if ($error){
            header("Location: {$_SERVER['PHP_SELF']}");
            die();
        }
    }

    static public function mostrarError($nombre)
    {
        if (isset($_SESSION[$nombre])) {
            echo "<p class='mt-2' style='color:brown; font-size:0.8em; font-weight:bolder;'>{$_SESSION[$nombre]}</p>";
            unset($_SESSION[$nombre]);
        }
    }

    static public function getMimeImages(): array{
        return ['image/png', 'image/jpeg', 'image/webp', 'image/tiff', 'iamge/ico', 'image/bmp'];
    }
}
