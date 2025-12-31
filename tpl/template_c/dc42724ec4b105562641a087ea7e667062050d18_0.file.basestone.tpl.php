<?php
/* Smarty version 4.3.0, created on 2025-12-31 00:57:44
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/basestone.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_695466f8848055_75022263',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dc42724ec4b105562641a087ea7e667062050d18' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/basestone.tpl',
      1 => 1767112556,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:headtrackingcodes.tpl' => 1,
  ),
),false)) {
function content_695466f8848055_75022263 (Smarty_Internal_Template $_smarty_tpl) {
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
        <meta property="og:site_name" content="mugenrace.com"/>
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_224577141695466f8845c31_52612865', "meta");
?>

		<title><?php echo (($tmp = $_smarty_tpl->tpl_vars['pagetitle']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
</title>
		<?php $_smarty_tpl->_subTemplateRender('file:headtrackingcodes.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
		<link type="application/rss+xml" rel="alternate" title="<?php echo (($tmp = $_smarty_tpl->tpl_vars['feedhirtitle']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
" href="/feed/hir">
		<link type="application/rss+xml" rel="alternate" title="<?php echo (($tmp = $_smarty_tpl->tpl_vars['feedtermektitle']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
" href="/feed/termek">
		<!--link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2026/bootstrap.min.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2026/bootstrap-responsive.min.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2026/jquery.slider.min.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2026/magnific-popup.css"-->
        <link rel="stylesheet" href="/themes/main/mugenrace2026/royalslider/royalslider.css">
        <link rel="stylesheet" href="/themes/main/mugenrace2026/royalslider/skins/default-inverted/rs-default-inverted.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2026/mgr.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2026/style.css">
				<link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2026/style-2.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2026/magnify.css">
		<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1039170228695466f88468a5_66667454', "css");
?>

        <?php if (($_smarty_tpl->tpl_vars['dev']->value)) {?>
        <?php echo '<script'; ?>
 src="/js/main/mugenrace2026/jquery-1.11.1.min.js"><?php echo '</script'; ?>
>

		<?php echo '<script'; ?>
 src="/js/main/mugenrace2026/jquery-migrate-1.2.1.js"><?php echo '</script'; ?>
>

        <?php echo '<script'; ?>
 src="/js/main/mugenrace2026/mgrerrorlog.js"><?php echo '</script'; ?>
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
 src="/js/main/mugenrace2026/jquery.magnify.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="/js/main/mugenrace2026/jquery.magnify-mobile.js"><?php echo '</script'; ?>
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
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1744078048695466f8846fd3_93690453', "script");
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
	<body class="bgimg">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_342024406695466f8847944_90825713', "body");
?>

		<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_378706660695466f8847c68_23835476', "stonebody");
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
class Block_224577141695466f8845c31_52612865 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'meta' => 
  array (
    0 => 'Block_224577141695466f8845c31_52612865',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block "meta"} */
/* {block "css"} */
class Block_1039170228695466f88468a5_66667454 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'css' => 
  array (
    0 => 'Block_1039170228695466f88468a5_66667454',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block "css"} */
/* {block "script"} */
class Block_1744078048695466f8846fd3_93690453 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'script' => 
  array (
    0 => 'Block_1744078048695466f8846fd3_93690453',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block "script"} */
/* {block "body"} */
class Block_342024406695466f8847944_90825713 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_342024406695466f8847944_90825713',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php
}
}
/* {/block "body"} */
/* {block "stonebody"} */
class Block_378706660695466f8847c68_23835476 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'stonebody' => 
  array (
    0 => 'Block_378706660695466f8847c68_23835476',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

		<?php
}
}
/* {/block "stonebody"} */
}
