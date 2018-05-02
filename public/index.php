<?php
$template = 'templates/template.php';

/**
 * 
 * @param type $varname
 * @return \Vue
 */
function vue($varname) {
    return new Vue($varname);
}

class Vue {

    var $varname = '';
    var $data = [];

    function __construct($varname) {
        $this->varname = $varname;
        $this->data = $_POST['vue'][$varname];
    }

    function data($data = null) {
        if ($data === null) {
            return $this->data;
        } else {
            foreach ($data as $key => $value) {
                $value = json_encode($value);
                echo "{$this->varname}.{$key} = {$value};";
            }
        }
    }

    function load($vueFile, $slug = null) {
        global $url;
        if (stringy($vueFile)->endsWith('.php')) {
            $vueFile = stringy($vueFile)->replace('.php', '');
        }
        $component = file_get_html($url . $vueFile);

        $dom = new DOMDocument;
        $dom->loadHTML($component);
        $xpath = new DOMXPath($dom);
        libxml_use_internal_errors(false);

        $template = $dom->saveHTML($dom->getElementsByTagName('template')->item(0));
        $script = $dom->getElementsByTagName('script')->item(0)->textContent;
        $style = $dom->getElementsByTagName('style')->item(0)->textContent;

        $script = implode(PHP_EOL . ' ', array_splice(explode(PHP_EOL, trim($script)), 1, -1));
        if ($slug === null) {
            $slug = explode('/', $vueFile);
            $slug = $slug[count($slug) - 1];
        }
        ?>
        Vue.component('<?= $slug ?>', {<?= $script ?>,template: "<?= JS::addslashes($template) ?>"});
        <?php
    }

}

function main() {

    $data = [
        'nome' => 'Felipe',
        'nascimento' => '26/08/1989',
        'idade' => '28',
        'email' => 'felipe@newbgp.com.br'
    ];
    $data = [
        'nome' => '',
        'nascimento' => '26/08/1989',
        'idade' => '28',
        'email' => 'felipe@a.com'
    ];

    $validator = is_valid($data, [
        'nome' => 'required',
        'nascimento' => 'required|date,d/m/Y',
        'email' => 'required|valid_email',
        'idade' => 'required|integer'
    ]);
    vue('vue')->load('vue/itemLista.php');
//    popup(@d());
}
?>
<script>
    $(function () {
        vue = new Vue({
            el: '#main',
            data: {
                itens: [
                    {
                        texto: ''
                    }
                ]
            }
        });
    });
</script>
<form init="main" id="main" >
    <ul>
        <itemLista></itemLista>
    </ul>
    <?php
    label_text('Nome', 'nome', 12);
    label_upload('Arquivo', 'arquivo[]', 'multiple="true"', 12);
    button('Mostrar Nome', 'click="mostrarNome(feliz)" ', 12);

    function mostrarNome($form) {
        if (is_empty($form['nome'])) {
            focus('#nome');
            notify('O Campo nome é obrigatório');
            exit();
        }
    }
    ?>
</form>