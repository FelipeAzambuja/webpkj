<?php

class MenuResponsive {

    var $menuArray = [];
    var $backgroundColor = "red";
    var $hoverColor = "#dd0000";
    var $buttonBorderColor = "#dd0000";
    var $buttonBackgroundColor = "#ec0000";
    var $textColor = "white";

    function render() {
        if(count($this->menuArray) == 0 || count($this->menuArray) > 6){
            throw new Exception("Quantidade de itens de menu invalidos");
        }
        ob_start();
        ?>
        <style type="text/css">
            .barra-navegacao-desktop{
                height: 4em;
                background-color: <?=$this->backgroundColor?>;
                line-height: 4em;
            }
            .barra-navegacao-desktop a{
                text-decoration: none;
                color: <?=$this->textColor?>;
                font-size: 1em;
                font-weight: bold;
            }

            .barra-navegacao-desktop div[class*="col"]{
                text-align: center;
            }

            .barra-navegacao-desktop div[class*="col"]:hover{
                background-color: <?=$this->hoverColor?>;
            }
            .barra-navegacao-mobile{
                background-color: <?=$this->backgroundColor?>;
                height:4em;
            }
            .barra-navegacao-mobile div[class*="col"]{
                padding-top: 0.20em;
            }
            .barra-navegacao-mobile div[class*="col"] button:hover{
                background-color: <?=$this->hoverColor?>;
            }
            .barra-navegacao-mobile div[class*="col"] button{
                height: 3.5em;
                border:0;
                color:<?=$this->textColor?>;
                background-color: <?=$this->buttonBackgroundColor?>;
                border:1px solid <?=$this->buttonBorderColor?>;
            }
            .barra-navegacao-menu{
                display: none;
                background-color: red;
            }
            .barra-navegacao-menu div[class*="col"]{
                height: 2em;
                line-height: 2em;
                text-align: center;
            }
            .barra-navegacao-menu div[class*="col"] a{
                text-decoration: none;
                color:white;
            }
        </style>

        <div class="row hidden-sm hidden-md hidden-lg barra-navegacao-mobile">
            <div class="col-xs-1">
                <button class="btn fa fa-bars" onclick="$('.barra-navegacao-menu').toggle()"></button>
            </div>
        </div>
        <div class="row barra-navegacao-menu">
            <?php foreach($this->menuArray as $item):?>
            <div class="col-xs-12">
                <a href="<?=$item["link"]?>">
                    <?=$item["text"]?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="row barra-navegacao-desktop hidden-xs" >
            <?php foreach($this->menuArray as $item):?>
            <div class="col-sm-2">
                <a href="<?=$item["link"]?>">
                    <?=$item["text"]?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        echo $html;
    }

}
