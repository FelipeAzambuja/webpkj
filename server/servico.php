<?php

include './pkj/all.php';

function teste($form) {
    ons_navigator("navegador")->pushPage("demo/login.html", "termina");
}

function termina($form) {
    JS::alert("Pronto");
}
