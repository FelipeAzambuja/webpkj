<?php
/**
 * 
 * @return \Undefined
 */
function undefined() {
    return new Undefined();
}

class Undefined {

    /**
     * 
     * @return \Undefined
     */
    public static function create() {
        return new Undefined();
    }

    public function __toString() {
        return '';
    }

}

function set_undefined(&$var) {
    $var = undefined();
}
function is_undefined(&$var) {
    return $var instanceof Undefined;
}