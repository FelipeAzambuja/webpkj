<?php
/* Smarty version 3.1.31, created on 2017-09-29 01:53:09
  from "C:\UwAmp\www\webpkj\pkj\templates\batata\teste 1.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_59cdd1b55d47a9_79957311',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '00e460582077df655fa3b275f9ca50ad8b7397e1' => 
    array (
      0 => 'C:\\UwAmp\\www\\webpkj\\pkj\\templates\\batata\\teste 1.tpl',
      1 => 1506660788,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59cdd1b55d47a9_79957311 (Smarty_Internal_Template $_smarty_tpl) {
?>
<ul>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lista']->value, 'i');
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
