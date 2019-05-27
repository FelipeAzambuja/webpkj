<?php
//use Faker;
$template = 'templates/template.php';

function tabela_aleatoria ( $form ) {
    $faker = faker ();
    ob_start ();
    ?>
    <div class="bstable-toolbar">dsadsadsa</div>
    <table class="bstable bstable-responsive" width="100%" >
        <thead>
            <tr>
                <?php foreach ( range ( 1 , 10 ) as $value ): ?>
                    <th>aaaaaaaaaaaaaaa</th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( range ( 0 , random_int ( 0 , 500 ) ) as $i ): ?>
                <?php
                $data = $faker->dateTime;
                ?>
                <tr>
                    <?php foreach ( range ( 1 , 10 ) as $value ): ?>
                        <td>dsadsadadadasdasdassdsa</td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    $html = ob_get_clean ();
    html ( '#tabela' , $html );
//    setTimeout('tabela_aleatoria', 1000 * 5);
}
?> 
<form class="row no-gutters mt-4" init="tabela_aleatoria()">
    <?php if ( false ): ?>



        <?php
        foreach ( range ( 1 , 12 ) as $value ) {
            label_text ( 'Campo ' . $value , 'campo' , 1 );
        }
        foreach ( range ( 1 , 6 ) as $value ) {
            label_text ( 'Campo ' . $value , 'campo' , 2 );
        }
        foreach ( range ( 1 , 6 ) as $value ) {
            label_textarea ( '' , 'campo' , 2 );
        }
        foreach ( range ( 1 , 6 ) as $value ) {
            label_textarea ( 'AAAAA' , 'campo' , 2 );
        }
        foreach ( range ( 1 , 3 ) as $value ) {
            label_combo ( 'AAAAA' , 'campo' , [] , [] , 2 );
        }
        foreach ( range ( 1 , 3 ) as $value ) {
            label_button ( 'AAAAA' , 'campo' , 2 );
        }
        foreach ( range ( 1 , 3 ) as $value ) {
            label_text ( 'AAAAA' , 'campo' , 2 );
        }
        foreach ( range ( 1 , 3 ) as $value ) {
            label_button ( 'AAAAA' , 'campo' , 2 );
        }
        foreach ( range ( 1 , 6 ) as $value ) {
            $rnd = 2510.15;
            label_money ( 'Campo ' . $value , 'campo' , $value , "value='$rnd'" , 2 );
        }
        ?>

    <?php endif; ?>
</form>
<div id="tabela"></div>