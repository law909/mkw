<?php
/* Smarty version 4.3.0, created on 2025-12-31 01:24:44
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/main.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_69546d4c882867_67274772',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6a0f11e4ae3cb8b7f12f76515892c5bc9c1d7af8' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/main.tpl',
      1 => 1767140680,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69546d4c882867_67274772 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_76389693669546d4c860e60_77781997', "meta");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_192101158269546d4c862ee3_66771129', "body");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_144377779869546d4c863e26_50952060', "kozep");
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "base.tpl");
}
/* {block "meta"} */
class Block_76389693669546d4c860e60_77781997 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'meta' => 
  array (
    0 => 'Block_76389693669546d4c860e60_77781997',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <meta property="og:title" content="<?php echo $_smarty_tpl->tpl_vars['globaltitle']->value;?>
">
    <meta property="og:url" content="http://www.mugenrace.com">
    <meta property="og:image" content="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
">
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="<?php echo $_smarty_tpl->tpl_vars['seodescription']->value;?>
">
<?php
}
}
/* {/block "meta"} */
/* {block "body"} */
class Block_192101158269546d4c862ee3_66771129 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_192101158269546d4c862ee3_66771129',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<div id="fb-root"></div>
<?php echo '<script'; ?>
>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));<?php echo '</script'; ?>
>
<?php
}
}
/* {/block "body"} */
/* {block "kozep"} */
class Block_144377779869546d4c863e26_50952060 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'kozep' => 
  array (
    0 => 'Block_144377779869546d4c863e26_50952060',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/vendor/smarty/smarty/libs/plugins/modifier.capitalize.php','function'=>'smarty_modifier_capitalize',),1=>array('file'=>'/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/vendor/smarty/smarty/libs/plugins/modifier.replace.php','function'=>'smarty_modifier_replace',),));
?>

<div class="container-full whitebg">
	

<div id="MainContent">
    <section class="hero-section">
        <img src="/themes/main/mugenrace2026/img/pages/mugenrace-home-1.jpg" alt="Hero">
        <div class="hero-content hero-content__inverse flex-col flex-cc">
            <h1><?php echo t('Új kollekció 2025');?>
</h1>
            <p><?php echo t('Ismerd meg legújabb termékeinket');?>
</p>
            <a href="#" class="button bordered inverse"><?php echo t('Részletek');?>
</a>
        </div>
    </section>

    <?php if (($_smarty_tpl->tpl_vars['legujabbtermekek']->value && count($_smarty_tpl->tpl_vars['legujabbtermekek']->value) > 0)) {?>
    <section class="featured-collection-slider featured-collection-slider__dark carousel-section">
        <div class="container section-header small row flex-cb">
            <div class="col flex-lc flex-col ta-l">
                <h2><?php echo t('Akciós termékeink');?>
</h2>
                                <p></p>
            </div>
            <div class="col flex-cr">
                <div class="carousel-controls">
                    <button class="carousel-btn carousel-prev" aria-label="Preview">‹</button>
                    <button class="carousel-btn carousel-next" aria-label="Next">›</button>
                </div>
            </div>
        </div>

        <div class="carousel-container">
            <div class="carousel-wrapper product-list">
                <?php $_smarty_tpl->_assignInScope('lntcnt', count($_smarty_tpl->tpl_vars['legujabbtermekek']->value));?>
                <?php $_smarty_tpl->_assignInScope('step', 3);?>
                <?php
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['i']->step = $_smarty_tpl->tpl_vars['step']->value;$_smarty_tpl->tpl_vars['i']->total = (int) ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['lntcnt']->value-1+1 - (0) : 0-($_smarty_tpl->tpl_vars['lntcnt']->value-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0) {
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++) {
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration === 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration === $_smarty_tpl->tpl_vars['i']->total;?>
                    <?php
$_smarty_tpl->tpl_vars['j'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['j']->step = 1;$_smarty_tpl->tpl_vars['j']->total = (int) ceil(($_smarty_tpl->tpl_vars['j']->step > 0 ? $_smarty_tpl->tpl_vars['step']->value-1+1 - (0) : 0-($_smarty_tpl->tpl_vars['step']->value-1)+1)/abs($_smarty_tpl->tpl_vars['j']->step));
if ($_smarty_tpl->tpl_vars['j']->total > 0) {
for ($_smarty_tpl->tpl_vars['j']->value = 0, $_smarty_tpl->tpl_vars['j']->iteration = 1;$_smarty_tpl->tpl_vars['j']->iteration <= $_smarty_tpl->tpl_vars['j']->total;$_smarty_tpl->tpl_vars['j']->value += $_smarty_tpl->tpl_vars['j']->step, $_smarty_tpl->tpl_vars['j']->iteration++) {
$_smarty_tpl->tpl_vars['j']->first = $_smarty_tpl->tpl_vars['j']->iteration === 1;$_smarty_tpl->tpl_vars['j']->last = $_smarty_tpl->tpl_vars['j']->iteration === $_smarty_tpl->tpl_vars['j']->total;?>
                        <?php if (($_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['j']->value < $_smarty_tpl->tpl_vars['lntcnt']->value)) {?>
                            <?php $_smarty_tpl->_assignInScope('_termek', $_smarty_tpl->tpl_vars['legujabbtermekek']->value[$_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['j']->value]);?>

                            <div class="carousel-item product-list-item spanmkw3 gtermek itemscope itemtype="http://schema.org/Product">
                                <div class="gtermekinner"><div class="gtermekinnest product-list-item__inner">
                                    <div class="textaligncenter product-list-item__image-container">
                                        <a href="/termek/<?php echo $_smarty_tpl->tpl_vars['_termek']->value['slug'];?>
"><img class="product-list-item__image itemprop="image" src="<?php echo $_smarty_tpl->tpl_vars['imagepath']->value;
echo $_smarty_tpl->tpl_vars['_termek']->value['kepurl'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['caption'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['caption'];?>
"></a>
                                    </div>
                                    <div class="textaligncenter product-list-item__content product-list-item__title">
                                        <a itemprop="url" href="/termek/<?php echo $_smarty_tpl->tpl_vars['_termek']->value['slug'];?>
"><span class="gtermekcaption" itemprop="name"><?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['_termek']->value['caption'], 'UTF-8'));?>
</span></a>
                                    </div>
                                    <div class="textaligncenter product-list-item__content product-list-item__code">
                                        <a href="/termek/<?php echo $_smarty_tpl->tpl_vars['_termek']->value['slug'];?>
"><?php echo $_smarty_tpl->tpl_vars['_termek']->value['cikkszam'];?>
</a>
                                    </div>
                                    <div class="textaligncenter product-list-item__content">
                                        <?php if (($_smarty_tpl->tpl_vars['_termek']->value['szallitasiido'] && (!$_smarty_tpl->tpl_vars['_termek']->value['nemkaphato']))) {?>
                                        <div class="textaligncenter"><span class="bold">Szállítási idő: </span><?php echo $_smarty_tpl->tpl_vars['_termek']->value['szallitasiido'];?>
 munkanap</div>
                                        <?php }?>
                                        <?php if (((($tmp = $_smarty_tpl->tpl_vars['_termek']->value['szinek'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp))) {?>
                                            <div class="js-valtozatbox product-list-item__variations-container">
                                                                                                <div class="pull-left gvaltozatcontainer product-list-item__variations">
                                                    <div class="pull-left gvaltozatnev termekvaltozat"><?php echo t('Szín');?>
:</div>
                                                    <div class="pull-left gvaltozatselect">
                                                    
                                                        <div class="option-selector color-selector" data-termek="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['id'];?>
">
                                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_termek']->value['szinek'], '_v');
$_smarty_tpl->tpl_vars['_v']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_v']->value) {
$_smarty_tpl->tpl_vars['_v']->do_else = false;
?>
                                                                <div class="select-option <?php echo smarty_modifier_replace(mb_strtolower($_smarty_tpl->tpl_vars['_v']->value, 'UTF-8'),'/','-');?>
" data-value="<?php echo $_smarty_tpl->tpl_vars['_v']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['_v']->value;?>
"></div>
                                                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                                        </div>
                                                    
                                                        <select class="js-szinvaltozatedit valtozatselect" data-termek="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['id'];?>
">
                                                            <option value=""><?php echo t('Válasszon');?>
</option>
                                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_termek']->value['szinek'], '_v');
$_smarty_tpl->tpl_vars['_v']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_v']->value) {
$_smarty_tpl->tpl_vars['_v']->do_else = false;
?>
                                                                <option value="<?php echo $_smarty_tpl->tpl_vars['_v']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_v']->value;?>
</option>
                                                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }?>
                                    </div>
                                    <div class="flex-tb ">
                                        <div class="termekprice pull-left" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                            <?php if (($_smarty_tpl->tpl_vars['_termek']->value['akcios'])) {?>
                                                <span class="akciosarszoveg">Eredeti ár: <span class="akciosar"><?php echo number_format($_smarty_tpl->tpl_vars['_termek']->value['eredetibruttohuf'],0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['_termek']->value['valutanemnev'];?>
</span></span>
                                            <?php }?>
                                            <?php if (($_smarty_tpl->tpl_vars['_termek']->value['nemkaphato'])) {?>
                                                <link itemprop="availability" href="http://schema.org/OutOfStock" content="Nem kapható">
                                            <?php } else { ?>
                                                <link itemprop="availability" href="http://schema.org/InStock" content="Kapható">
                                            <?php }?>
                                            <span class="product-list-item__price" itemprop="price"><?php echo number_format($_smarty_tpl->tpl_vars['_termek']->value['bruttohuf'],0,',',' ');?>

                                            <?php echo $_smarty_tpl->tpl_vars['_termek']->value['valutanemnev'];?>

                                            </span>
                                        </div>
                                        <div class="pull-right">
                                        <?php if (($_smarty_tpl->tpl_vars['_termek']->value['nemkaphato'])) {?>
                                            <a href="#" rel="nofollow" class="js-termekertesitobtn btn graybtn pull-right" data-termek="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['id'];?>
">
                                                <?php echo t('Elfogyott');?>

                                            </a>
                                        <?php } else { ?>
                                            <?php if (($_smarty_tpl->tpl_vars['_termek']->value['bruttohuf'] > 0)) {?>
                                            <a href="/kosar/add?id=<?php echo $_smarty_tpl->tpl_vars['_termek']->value['id'];?>
" rel="nofollow" class="js-kosarbaszinvaltozat button bordered small cartbtn pull-right" data-termek="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['id'];?>
">
                                                <?php echo t('Kosárba');?>

                                            </a>
                                            <?php }?>
                                        <?php }?>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <?php }?>
                        <?php }
}
?>
                    <?php }
}
?>
                
            </div>
                    </div>
    </section>
    <?php }?>
    
    <section class="content-grid">
        <div class="grid-item">
            <img src="/themes/main/mugenrace2026/img/pages/mugenrace-home-2.jpg" alt="Kategória 1">
            <div class="grid-content inverse flex-cc flex-col">
                <h3><?php echo t('Szponzorált versenyzők');?>
</h3>
                <a href="/statlap/sponsored-riders" class="button bordered inverse"><?php echo t('Tovább');?>
</a>
            </div>
        </div>
        <div class="grid-item">
            <img src="/themes/main/mugenrace2026/img/pages/mugenrace-home-3.jpg" alt="Kategória 2">
            <div class="grid-content inverse flex-cc flex-col">
                <h3><?php echo t('Csapatok');?>
</h3>
                <a href="#" class="button bordered inverse"><?php echo t('Tovább');?>
</a>
            </div>
        </div>
    </section>


    <section class="full-banner left inverse">
        <img src="/themes/main/mugenrace2026/img/pages/mugenrace-home-5.jpg" alt="Banner">
        <div class="banner-content flex-cc flex-col">
            <h2><?php echo t('Új Kollekció');?>
</h2>
            <p><?php echo t('Fedezd fel legújabb termékeinket');?>
</p>
            <a href="#" class="button bordered inverse"><?php echo t('Tudj Meg Többet');?>
</a>
        </div>
    </section>

    <section class="full-banner right inverse">
        <img src="/themes/main/mugenrace2026/img/pages/mugenrace-home-4.jpg" alt="Banner">
        <div class="banner-content flex-cc flex-col">
            <h2><?php echo t('Új Kollekció');?>
</h2>
            <p><?php echo t('Fedezd fel legújabb termékeinket');?>
</p>
            <a href="#" class="button bordered inverse"><?php echo t('Tudj Meg Többet');?>
</a>
        </div>
    </section>

    
    <section class="featured-collection-slider featured-collection-slider__dark carousel-section">
        <div class="container section-header small row flex-cb">
            <div class="col flex-lc flex-col ta-l">
                <h2><?php echo t('Hírek');?>
</h2>
                                <p></p>
            </div>
            <div class="col flex-cr">
                <div class="carousel-controls">
                    <button class="carousel-btn carousel-prev" aria-label="Preview">‹</button>
                    <button class="carousel-btn carousel-next" aria-label="Next">›</button>
                </div>
            </div>
        </div>

        <div class="carousel-container">
            <div class="carousel-wrapper news-list__items">   
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['hirek']->value, '_child');
$_smarty_tpl->tpl_vars['_child']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_child']->value) {
$_smarty_tpl->tpl_vars['_child']->do_else = false;
?>
                    <div class="carousel-item kat news-list__item" data-href="/hir/<?php echo $_smarty_tpl->tpl_vars['_child']->value['slug'];?>
">
                        <div class="kattext news-list__item-content">
                                                        <img src="https://picsum.photos/500/400" alt="<?php echo $_smarty_tpl->tpl_vars['_child']->value['cim'];?>
" class="news-list__item-image">
                            <div class="hiralairas news-list__item-date"><?php echo $_smarty_tpl->tpl_vars['_child']->value['datum'];?>
</div>
                            <div class="kattitle news-list__item-title"><a href="/hir/<?php echo $_smarty_tpl->tpl_vars['_child']->value['slug'];?>
"><?php echo $_smarty_tpl->tpl_vars['_child']->value['cim'];?>
</a></div>
                            <div class="katcopy news-list__item-lead"><?php echo $_smarty_tpl->tpl_vars['_child']->value['lead'];?>
</div>
                        </div>
                    </div>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </div>
        </div>
    </section>
</div>
<?php
}
}
/* {/block "kozep"} */
}
