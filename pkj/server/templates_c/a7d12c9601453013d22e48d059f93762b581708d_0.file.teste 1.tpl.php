<?php
/* Smarty version 3.1.31, created on 2017-09-29 02:41:10
  from "C:\UwAmp\www\webpkj\pkj\templates\teste\teste 1.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_59cddcf69e37e2_42318606',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a7d12c9601453013d22e48d059f93762b581708d' => 
    array (
      0 => 'C:\\UwAmp\\www\\webpkj\\pkj\\templates\\teste\\teste 1.tpl',
      1 => 1506663669,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59cddcf69e37e2_42318606 (Smarty_Internal_Template $_smarty_tpl) {
?>
<ul>
    <?php echo combo('aa',$_smarty_tpl->tpl_vars['lista']->value,$_smarty_tpl->tpl_vars['lista']->value,12);?>

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
