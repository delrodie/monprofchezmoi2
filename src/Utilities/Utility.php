<?php

namespace App\Utilities;

class Utility
{

    /**
     * Formattage du contenu du texte avec éléminitation des balises HTML pour le resumé du texte
     *
     * @param $string
     * @return false|string
     */
    public function resume($string)
    {
        return substr(strip_tags($string), 0, 155);
    }

}