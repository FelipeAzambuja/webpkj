<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?=@$title?></title>
        <?php
        import('jquery');
        import('bind2');
        import('pkj');
        import('notify');
        import('bpopup');
        import('bootstrap');
        import('mask');
       ?>
    </head>
    <body>
        <div class="container">
            <?php echo $content ?>    
        </div>
    </body>
</html>