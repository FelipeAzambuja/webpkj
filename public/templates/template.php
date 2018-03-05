<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?=@$title?></title>
        <?php
        import('jquery');
        import('bind');
        import('pkj');
        import('bpopup');
        import('bootstrap');
//        import('ui');
        import('mask');
        import('datatables');
       ?>
    </head>
    <body>
        <div class="container">
            <?php echo $content ?>    
            <?= @$table ?>
        </div>

    </body>
</html>