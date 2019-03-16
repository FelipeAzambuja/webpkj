<?php
mail2_image('public/templates/eu arrumado.jpg','eu_arrumado');
mail2('felipe@newbgp.com.br', 'Email de teste', smarty('public/templates/email.tpl',[
    'nome' => 'Felipe Nunes Azambuja'
]));
