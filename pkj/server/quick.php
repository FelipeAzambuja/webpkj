<?php

function import($name) {
    resource()->import($name);
}

function alert($msg) {
    JS::alert($msg);
}

function console($msg) {
    JS::console($msg);
}

function redirect($page, $data = "") {
    JS::redirect($page, $data);
}

function popup($msg, $id = "pkj") {
    JS::popup($msg, $id);
}

function popup_close($id = "pkj") {
    JS::popup_close($id);
}

function value($id, $value) {
    setValue($id, $value);
}

function setValue($id, $value) {
    bind()->setValue($id, $value);
}

function html($id, $html) {
    bind()->setHtml($id, $html);
}

function setHtml($id, $html) {
    bind()->setHtml($id, $html);
}


function setText($id, $text) {
    bind()->setText($id, $text);
}

function append($id, $text) {
    bind()->append($id, $text);
}

function setEnable($id) {
    bind()->setEnable($id);
}

function setDisable($id) {
    bind()->setDisable($id);
}

function show($id) {
    bind()->show($id);
}

function hide($id) {
    bind()->hide($id);
}

function setInterval($function, $time, $parameters = array(), $page = "") {
    bind()->setInterval($function, $time, $parameters, $page);
}

function setTimeout($function, $time, $parameters = array(), $page = "") {
    bind()->setTimeout($function, $time, $parameters, $page);
}

function stopInterval($function) {
    bind()->stopInterval($function);
}

function stopTimeout($function) {
    bind()->stopTimeout($function);
}
