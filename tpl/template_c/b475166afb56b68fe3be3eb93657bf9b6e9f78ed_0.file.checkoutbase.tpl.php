<?php
/* Smarty version 4.3.0, created on 2025-12-31 01:21:13
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/checkoutbase.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_69546c790c8326_31244383',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b475166afb56b68fe3be3eb93657bf9b6e9f78ed' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/checkoutbase.tpl',
      1 => 1767112538,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:headtrackingcodes.tpl' => 1,
    'file:header.tpl' => 1,
  ),
),false)) {
function content_69546c790c8326_31244383 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<!DOCTYPE html>
<html lang="hu">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="<?php echo (($tmp = $_smarty_tpl->tpl_vars['seodescription']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta property="og:site_name" content="Mugenrace webshop"/>
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_39987234369546c790c38e4_82043586', "meta");
?>

		<title><?php echo (($tmp = $_smarty_tpl->tpl_vars['pagetitle']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
</title>
		<?php $_smarty_tpl->_subTemplateRender('file:headtrackingcodes.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
		<link type="application/rss+xml" rel="alternate" title="<?php echo (($tmp = $_smarty_tpl->tpl_vars['feedhirtitle']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
" href="/feed/hir">
		<link type="application/rss+xml" rel="alternate" title="<?php echo (($tmp = $_smarty_tpl->tpl_vars['feedtermektitle']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
" href="/feed/termek">
        <link rel="stylesheet" href="/themes/main/mugenrace2026/royalslider/royalslider.css">
        <link rel="stylesheet" href="/themes/main/mugenrace2026/royalslider/skins/default-inverted/rs-default-inverted.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2026/mgr.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2026/style.css">
				<link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2026/style-2.css">
		<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10122194569546c790c6287_12320879', "css");
?>

        <?php if (($_smarty_tpl->tpl_vars['dev']->value)) {?>
        <?php echo '<script'; ?>
 src="/js/main/mugenrace2026/jquery-1.11.1.min.js"><?php echo '</script'; ?>
>

		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/jquery-migrate-1.2.1.js"><?php echo '</script'; ?>
>

		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/jquery.magnific-popup.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/jquery.slider.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="/js/main/mugenrace2026/jquery.royalslider.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="/js/main/mugenrace2026/jquery.debounce.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="/js/main/mugenrace2026/bootstrap-transition.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/bootstrap-modal.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/bootstrap-tab.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/bootstrap-typeahead.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/bootstrap-tooltip.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/h5f.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/matt-accordion.js"><?php echo '</script'; ?>
>
        <?php } else { ?>
        <?php echo '<script'; ?>
 src="/js/main/mugenrace2026/mgrbootstrap.js?v=<?php echo $_smarty_tpl->tpl_vars['bootstrapjsversion']->value;?>
"><?php echo '</script'; ?>
>
        <?php }?>
		<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_90810178169546c790c6d37_42060243', "script");
?>

        <?php if (($_smarty_tpl->tpl_vars['dev']->value)) {?>
		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/mgrmsg.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/mgr.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/checks.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/checkout.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/cart.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/fiok.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/mugenrace.js"><?php echo '</script'; ?>
>
        <?php } else { ?>
        <?php echo '<script'; ?>
 src="/js/main/mugenrace2026/mgrapp.js?v=<?php echo $_smarty_tpl->tpl_vars['jsversion']->value;?>
"><?php echo '</script'; ?>
>
        <?php }?>
        <?php if (($_smarty_tpl->tpl_vars['GAFollow']->value)) {?>
        <?php echo '<script'; ?>
 type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '<?php echo $_smarty_tpl->tpl_vars['GAFollow']->value;?>
']);
            _gaq.push(['_trackPageview']);

            (function() {
              var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
              ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
              var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        <?php echo '</script'; ?>
>
        <?php }?>
	</head>
	<body class="whitebg checkout-page">
		<header>
			<?php $_smarty_tpl->_subTemplateRender('file:header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
		</header>
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_134944754769546c790c7a29_47311794', "body");
?>

		<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_145598503169546c790c7db9_57043629', "stonebody");
?>

		<div id="dialogcenter" class="modal hide fade" tabindex="-1" role="dialog">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
		  </div>
		  <div class="modal-body"></div>
		  <div class="modal-footer"></div>
		</div>
		<div id="messagecenter" class="mfp-hide"></div>
	</body>
</html><?php }
/* {block "meta"} */
class Block_39987234369546c790c38e4_82043586 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'meta' => 
  array (
    0 => 'Block_39987234369546c790c38e4_82043586',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block "meta"} */
/* {block "css"} */
class Block_10122194569546c790c6287_12320879 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'css' => 
  array (
    0 => 'Block_10122194569546c790c6287_12320879',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block "css"} */
/* {block "script"} */
class Block_90810178169546c790c6d37_42060243 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'script' => 
  array (
    0 => 'Block_90810178169546c790c6d37_42060243',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block "script"} */
/* {block "body"} */
class Block_134944754769546c790c7a29_47311794 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_134944754769546c790c7a29_47311794',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php
}
}
/* {/block "body"} */
/* {block "stonebody"} */
class Block_145598503169546c790c7db9_57043629 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'stonebody' => 
  array (
    0 => 'Block_145598503169546c790c7db9_57043629',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

		<?php
}
}
/* {/block "stonebody"} */
}
