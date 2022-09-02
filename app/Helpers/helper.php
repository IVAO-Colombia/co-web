<?php
function clean($string)
{
    $string = str_replace(" ", "-", $string); // Replaces all spaces with hyphens.

    return strtolower(preg_replace("/[^a-zA-Z0-9_.]/", "", $string)); // Removes special chars.
}
?>
