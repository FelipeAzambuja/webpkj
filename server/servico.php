<?php

include './pkj/all.php';

function teste($form) {
    JS::alert("$form[nome]");
    ons_navigator("navegador")->pushPage("demo/login.html", "termina");
}

function termina($form) {
    JS::alert("Pronto");
}
