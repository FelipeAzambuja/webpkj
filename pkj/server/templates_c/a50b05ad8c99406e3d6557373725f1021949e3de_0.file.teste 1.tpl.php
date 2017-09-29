<?php
/* Smarty version 3.1.31, created on 2017-09-29 01:50:10
  from "C:\UwAmp\www\webpkj\pkj\templates\teste 1.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_59cdd102cfc763_91515965',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a50b05ad8c99406e3d6557373725f1021949e3de' => 
    array (
      0 => 'C:\\UwAmp\\www\\webpkj\\pkj\\templates\\teste 1.tpl',
      1 => 1506659714,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59cdd102cfc763_91515965 (Smarty_Internal_Template $_smarty_tpl) {
?>
<ul>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, array(1,2,3,4,5,6), 'i');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['i']->value) {
?>
        <li><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
</li>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

</ul><?php }
}
