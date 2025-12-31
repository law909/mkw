<?php
/* Smarty version 4.3.0, created on 2025-12-31 01:17:25
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/termeklap.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_69546b95a83468_25902524',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '66e0680e937796d1191b12c89c77484d0b70b85c' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/termeklap.tpl',
      1 => 1767140242,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:termekertesitomodal.tpl' => 1,
  ),
),false)) {
function content_69546b95a83468_25902524 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_199732497569546b95a55df8_67685445', "meta");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_200930494869546b95a5ac58_79891178', "script");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_192255557369546b95a5d790_33101788', "kozep");
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "base.tpl");
}
/* {block "meta"} */
class Block_199732497569546b95a55df8_67685445 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'meta' => 
  array (
    0 => 'Block_199732497569546b95a55df8_67685445',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <meta property="og:title" content="<?php echo (($tmp = $_smarty_tpl->tpl_vars['pagetitle']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
"/>
    <meta property="og:url" content="<?php echo $_smarty_tpl->tpl_vars['serverurl']->value;?>
/termek/<?php echo $_smarty_tpl->tpl_vars['termek']->value['slug'];?>
"/>
    <meta property="og:description" content="<?php echo $_smarty_tpl->tpl_vars['termek']->value['rovidleiras'];?>
"/>
    <meta property="og:image" content="<?php echo $_smarty_tpl->tpl_vars['termek']->value['fullkepurl'];?>
"/>
    <meta property="og:type" content="product"/>
<?php
}
}
/* {/block "meta"} */
/* {block "script"} */
class Block_200930494869546b95a5ac58_79891178 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'script' => 
  array (
    0 => 'Block_200930494869546b95a5ac58_79891178',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <?php echo '<script'; ?>
 type="text/javascript" src="https://apis.google.com/js/plusone.js"><?php echo '</script'; ?>
>
    
    <?php echo '<script'; ?>
>
        // document.addEventListener('DOMContentLoaded', function () {
        //     if (typeof fbq === 'function') {
                fbq('track', 'ViewContent', {
                    content_ids: ['<?php echo $_smarty_tpl->tpl_vars['termek']->value['id'];?>
'],
                    content_name: '<?php echo strtr((string)$_smarty_tpl->tpl_vars['termek']->value['caption'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/", "<!--" => "<\!--", "<s" => "<\s", "<S" => "<\S" ));?>
',
                    content_type: 'product',
                    value: <?php echo number_format($_smarty_tpl->tpl_vars['termek']->value['bruttohuf'],0,',','.');?>
,
                    currency: '<?php echo $_smarty_tpl->tpl_vars['valutanemnev']->value;?>
'
                });
        //     }
        // });
    <?php echo '</script'; ?>
>
    
<?php
}
}
/* {/block "script"} */
/* {block "kozep"} */
class Block_192255557369546b95a5d790_33101788 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'kozep' => 
  array (
    0 => 'Block_192255557369546b95a5d790_33101788',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/vendor/smarty/smarty/libs/plugins/modifier.capitalize.php','function'=>'smarty_modifier_capitalize',),1=>array('file'=>'/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/vendor/smarty/smarty/libs/plugins/modifier.replace.php','function'=>'smarty_modifier_replace',),));
?>

<div class="container whitebg product-datasheet">
    
	<article itemtype="http://schema.org/Product" itemscope="">
        <div class="row product-datasheet__content">
            <div class="col">
                <div class="row">
                    <div class="col product-datasheet__image-column flex-tr">


                        <div class="product-carousel-container">
                            <div class="thumbs" id="thumbs"></div>


                            <div class="main-image-wrapper">
                                <img id="mainImage" class="main-image" src="" />

                                <div class="nav-btn-container flex-cr">
                                    <div class="nav-btn nav-left" id="prevBtn">⟨</div>
                                    <div class="nav-btn nav-right" id="nextBtn">⟩</div>
                                </div>
                            </div>
                        </div>


                        <?php echo '<script'; ?>
>
                        const images = [
                            <?php $_smarty_tpl->_assignInScope('kcnt', count($_smarty_tpl->tpl_vars['termek']->value['kepek']));?>
                            <?php if (($_smarty_tpl->tpl_vars['kcnt']->value > 0)) {?>
                                <?php $_smarty_tpl->_assignInScope('step', 4);?>
                                <?php
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['i']->step = $_smarty_tpl->tpl_vars['step']->value;$_smarty_tpl->tpl_vars['i']->total = (int) ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['kcnt']->value-1+1 - (0) : 0-($_smarty_tpl->tpl_vars['kcnt']->value-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0) {
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++) {
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration === 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration === $_smarty_tpl->tpl_vars['i']->total;?>
                                    <?php
$_smarty_tpl->tpl_vars['j'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['j']->step = 1;$_smarty_tpl->tpl_vars['j']->total = (int) ceil(($_smarty_tpl->tpl_vars['j']->step > 0 ? $_smarty_tpl->tpl_vars['step']->value-1+1 - (0) : 0-($_smarty_tpl->tpl_vars['step']->value-1)+1)/abs($_smarty_tpl->tpl_vars['j']->step));
if ($_smarty_tpl->tpl_vars['j']->total > 0) {
for ($_smarty_tpl->tpl_vars['j']->value = 0, $_smarty_tpl->tpl_vars['j']->iteration = 1;$_smarty_tpl->tpl_vars['j']->iteration <= $_smarty_tpl->tpl_vars['j']->total;$_smarty_tpl->tpl_vars['j']->value += $_smarty_tpl->tpl_vars['j']->step, $_smarty_tpl->tpl_vars['j']->iteration++) {
$_smarty_tpl->tpl_vars['j']->first = $_smarty_tpl->tpl_vars['j']->iteration === 1;$_smarty_tpl->tpl_vars['j']->last = $_smarty_tpl->tpl_vars['j']->iteration === $_smarty_tpl->tpl_vars['j']->total;?>
                                        <?php if (($_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['j']->value < $_smarty_tpl->tpl_vars['kcnt']->value)) {?>
                                            <?php $_smarty_tpl->_assignInScope('_kep', $_smarty_tpl->tpl_vars['termek']->value['kepek'][$_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['j']->value]);?>
                                            "<?php echo $_smarty_tpl->tpl_vars['imagepath']->value;
echo $_smarty_tpl->tpl_vars['_kep']->value['kepurl'];?>
",
                                        <?php }?>
                                    <?php }
}
?>
                                <?php }
}
?>
                            <?php }?>
                            
                        ];

                        // ########################
                        // Product profile carousel
                        // ########################                           
                        const thumbsContainer = document.getElementById("thumbs");
                        const mainImage = document.getElementById("mainImage");


                        let currentIndex = 0;


                        // Render thumbs only once
                        images.forEach((src, index) => {
                        const img = document.createElement("img");
                        img.src = src;
                        img.dataset.index = index;
                        if (index === 0) img.classList.add("active");
                        img.onclick = () => changeImage(index, true);
                        thumbsContainer.appendChild(img);
                        });


                        function setActiveThumb(index) {
                        const all = thumbsContainer.querySelectorAll("img");
                        all.forEach(t => t.classList.remove("active"));
                        all[index].classList.add("active");
                        }


                        function changeImage(newIndex) {
                            const wrapper = document.querySelector(".main-image-wrapper");
                            const oldImage = wrapper.querySelector(".main-image");

                            const direction = newIndex > currentIndex ? 1 : -1;

                            const newImg = document.createElement("img");
                            newImg.src = images[newIndex];
                            newImg.className = "main-image";
                            newImg.style.position = "absolute";
                            newImg.style.left = direction > 0 ? "100%" : "-100%";
                            newImg.style.top = 0;

                            wrapper.appendChild(newImg);

                            requestAnimationFrame(() => {
                                oldImage.style.transition = "transform 0.4s ease";
                                newImg.style.transition = "left 0.4s ease";

                                oldImage.style.transform = "translateX(" + (direction > 0 ? "-100%" : "100%") + ")";
                                newImg.style.left = "0";
                            });

                            setTimeout(() => {
                                oldImage.remove();
                                newImg.style.position = "";
                                newImg.style.left = "";
                                newImg.style.top = "";
                            }, 400);

                            currentIndex = newIndex;
                            setActiveThumb(newIndex);
                        }


                        document.getElementById("prevBtn").onclick = () => {
                            const newIndex = (currentIndex - 1 + images.length) % images.length;
                            changeImage(newIndex);
                        };


                        document.getElementById("nextBtn").onclick = () => {
                            const newIndex = (currentIndex + 1) % images.length;
                            changeImage(newIndex);
                        };


                        // Init
                        mainImage.src = images[0];

                        <?php echo '</script'; ?>
>


                                            </div>

                    <div class="col product-datasheet__details-column">
                        <div class="korbepadding">

                            <span class="page-header__breadcrumb flex-lc" itemprop="breadcrumb ">
                                <?php if (((($tmp = $_smarty_tpl->tpl_vars['navigator']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp))) {?>
                                    <a href="/" rel="v:url" property="v:title">
                                        <?php echo t('Home');?>

                                    </a>
                                    <i class="icon arrow-right"></i>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['navigator']->value, '_navi');
$_smarty_tpl->tpl_vars['_navi']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_navi']->value) {
$_smarty_tpl->tpl_vars['_navi']->do_else = false;
?>
                                        <?php if (((($tmp = $_smarty_tpl->tpl_vars['_navi']->value['url'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp))) {?>
                                            <span typeof="v:Breadcrumb">
                                                <a href="/termekfa/<?php echo $_smarty_tpl->tpl_vars['_navi']->value['url'];?>
" rel="v:url" property="v:title">
                                                    <?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['_navi']->value['caption'], 'UTF-8'));?>

                                                </a>
                                            </span>
                                            <i class="icon arrow-right"></i>
                                        <?php } else { ?>
                                            <?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['_navi']->value['caption'], 'UTF-8'));?>

                                        <?php }?>
                                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                <?php }?>
                            </span>
                            
                            <div class="textaligncenter product-datasheet__title"><h1 itemprop="name" class="termeknev"><?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['termek']->value['caption'], 'UTF-8'));?>
</h1></div>
                            
                            <div id="termekprice<?php echo $_smarty_tpl->tpl_vars['termek']->value['id'];?>
" class="itemPrice product-datasheet__price textalignright" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                <?php if (($_smarty_tpl->tpl_vars['termek']->value['nemkaphato'])) {?>
                                    <link itemprop="availability" href="http://schema.org/OutOfStock" content="Nem kapható">
                                <?php } else { ?>
                                    <link itemprop="availability" href="http://schema.org/InStock" content="Kapható">
                                <?php }?>
                                <span itemprop="price"><?php echo number_format($_smarty_tpl->tpl_vars['termek']->value['bruttohuf'],0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['valutanemnev']->value;?>
</span>
                            </div>
                            
                            <div>
                                <span class="bold"><?php echo t('Cikkszám');?>
:</span> <span itemprop="productID"><?php echo $_smarty_tpl->tpl_vars['termek']->value['cikkszam'];?>
</span>
                            </div>
                            
                            <?php if (($_smarty_tpl->tpl_vars['termek']->value['me'])) {?>
                                <div><span class="bold"><?php echo t('Kiszerelés');?>
:</span> <?php echo $_smarty_tpl->tpl_vars['termek']->value['me'];?>
</div>
                            <?php }?>
                            
                            <?php if (($_smarty_tpl->tpl_vars['termek']->value['szallitasiido'] && (!$_smarty_tpl->tpl_vars['termek']->value['nemkaphato']))) {?>
                                <div><span class="bold"><?php echo t('Szállítási idő');?>
:</span> max. <span id="termekszallitasiido<?php echo $_smarty_tpl->tpl_vars['termek']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['termek']->value['szallitasiido'];?>
</span> <?php echo t('munkanap');?>
</div>
                            <?php }?>
                            
                            <div>
                                <ul class="simalista">
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['termek']->value['cimkeakciodobozban'], '_jelzo');
$_smarty_tpl->tpl_vars['_jelzo']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_jelzo']->value) {
$_smarty_tpl->tpl_vars['_jelzo']->do_else = false;
?>
                                    <li><?php echo $_smarty_tpl->tpl_vars['_jelzo']->value['caption'];?>
</li>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                </ul>
                            </div>
                            
                            <?php $_smarty_tpl->_assignInScope('_kosarbaclass', "js-kosarba");?>

                                                        <?php if (($_smarty_tpl->tpl_vars['termek']->value['szinek'])) {?>
                            <?php $_smarty_tpl->_assignInScope('_kosarbaclass', "js-kosarbaszinvaltozat");?>
                            <div class="row  product-datasheet__cart-container flex-col">
                                <div class="js-valtozatbox kosarbacontainer ">
                                    <div class="pull-left gvaltozatcontainer">
                                        <div class="pull-left gvaltozatnev termekvaltozat"><?php echo t('Szín');?>
:</div>
                                        <div class="pull-left gvaltozatselect">
                                            <div class="option-selector color-selector" data-termek="<?php echo $_smarty_tpl->tpl_vars['termek']->value['id'];?>
">
                                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['termek']->value['szinek'], '_v');
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

                                            <select class="js-szinvaltozatedit valtozatselect" data-termek="<?php echo $_smarty_tpl->tpl_vars['termek']->value['id'];?>
">
                                                <option value=""><?php echo t('Válasszon');?>
</option>
                                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['termek']->value['szinek'], '_v');
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
                            </div>
                            <?php }?>
                            
                            <div class="kosarbacontainer">
                                                                <?php if (($_smarty_tpl->tpl_vars['termek']->value['nemkaphato'])) {?>
                                    <div class="textalignright">
                                        <a href="#" rel="nofollow" class="js-termekertesitobtn button bordered graybtn" data-termek="<?php echo $_smarty_tpl->tpl_vars['termek']->value['id'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['termek']->value['id'];?>
">
                                            <?php echo t('Elfogyott');?>

                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <?php if (($_smarty_tpl->tpl_vars['termek']->value['bruttohuf'] > 0)) {?>
                                    <div class="textalignright">
                                        <a href="/kosar/add?id=<?php echo $_smarty_tpl->tpl_vars['termek']->value['id'];?>
" rel="nofollow" class="<?php echo $_smarty_tpl->tpl_vars['_kosarbaclass']->value;?>
 button primary full-width cartbtn" data-termek="<?php echo $_smarty_tpl->tpl_vars['termek']->value['id'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['termek']->value['id'];?>
" data-price="<?php echo number_format($_smarty_tpl->tpl_vars['termek']->value['bruttohuf'],0,',',' ');?>
" data-currency="<?php echo $_smarty_tpl->tpl_vars['valutanemnev']->value;?>
" data-name="<?php echo strtr((string)$_smarty_tpl->tpl_vars['termek']->value['caption'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/", "<!--" => "<\!--", "<s" => "<\s", "<S" => "<\S" ));?>
">
                                            <?php echo t('Kosárba');?>

                                        </a>
                                    </div>
                                    <?php }?>
                                <?php }?>
                            </div>
                            
                            <div class="accordion">
                                <div class="accordion-item">
                                    <div class="accordion-header"><?php echo t('Leírás');?>
<span class="arrow"></span></div>
                                    <div class="accordion-content">
                                        <span itemprop="description"><?php echo $_smarty_tpl->tpl_vars['termek']->value['leiras'];?>
</span>
                                    </div>
                                </div>

                                <?php if ((count($_smarty_tpl->tpl_vars['termek']->value['cimkelapon']) != 0)) {?>
                                <div class="accordion-item">
                                    <div class="accordion-header"><?php echo t('Tulajdonságok');?>
<span class="arrow"></span></div>
                                    <div class="accordion-content">
                                        <table class="table table-striped table-condensed"><tbody>
                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['termek']->value['cimkelapon'], '_cimke');
$_smarty_tpl->tpl_vars['_cimke']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_cimke']->value) {
$_smarty_tpl->tpl_vars['_cimke']->do_else = false;
?>
                                                <tr>
                                                    <td><?php echo $_smarty_tpl->tpl_vars['_cimke']->value['kategorianev'];?>
</td>
                                                    <td><?php if (($_smarty_tpl->tpl_vars['_cimke']->value['ismarka'])) {?><a href="<?php echo $_smarty_tpl->tpl_vars['_cimke']->value['termeklisturl'];?>
"><?php }?>
                                                        <?php if (($_smarty_tpl->tpl_vars['_cimke']->value['kiskepurl'] != '')) {?><img src="<?php echo $_smarty_tpl->tpl_vars['imagepath']->value;
echo $_smarty_tpl->tpl_vars['_cimke']->value['kiskepurl'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['_cimke']->value['caption'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['_cimke']->value['caption'];?>
"> <?php }?>
                                                        <?php if ((!$_smarty_tpl->tpl_vars['_cimke']->value['dontshowcaption'] || $_smarty_tpl->tpl_vars['_cimke']->value['kiskepurl'] == '')) {
echo $_smarty_tpl->tpl_vars['_cimke']->value['caption'];
}?>
                                                        <?php if (($_smarty_tpl->tpl_vars['_cimke']->value['ismarka'])) {?></a><?php }?>
                                                        <?php if (($_smarty_tpl->tpl_vars['_cimke']->value['leiras'])) {?><i class="icon-question-sign tooltipbtn hidden-phone js-tooltipbtn" title="<?php echo $_smarty_tpl->tpl_vars['_cimke']->value['leiras'];?>
"></i><?php }?>
                                                    </td>
                                                </tr>
                                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                        </tbody></table>
                                    </div>
                                </div>
                                <?php }?>
                                <?php if ((count($_smarty_tpl->tpl_vars['termek']->value['kapcsolodok']) != 0)) {?>
                                <div class="accordion-item product-datasheet__related-products">
                                    <div class="accordion-header"><?php echo t('Kapcsolódó termékek');?>
<span class="arrow"></span></div>
                                    <div class="accordion-content">
                                        <?php $_smarty_tpl->_assignInScope('lntcnt', count($_smarty_tpl->tpl_vars['termek']->value['kapcsolodok']));?>
                                        <?php $_smarty_tpl->_assignInScope('step', 4);?>
                                        <?php
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['i']->step = $_smarty_tpl->tpl_vars['step']->value;$_smarty_tpl->tpl_vars['i']->total = (int) ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['lntcnt']->value-1+1 - (0) : 0-($_smarty_tpl->tpl_vars['lntcnt']->value-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0) {
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++) {
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration === 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration === $_smarty_tpl->tpl_vars['i']->total;?>
                                            <div>
                                            <?php
$_smarty_tpl->tpl_vars['j'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['j']->step = 1;$_smarty_tpl->tpl_vars['j']->total = (int) ceil(($_smarty_tpl->tpl_vars['j']->step > 0 ? $_smarty_tpl->tpl_vars['step']->value-1+1 - (0) : 0-($_smarty_tpl->tpl_vars['step']->value-1)+1)/abs($_smarty_tpl->tpl_vars['j']->step));
if ($_smarty_tpl->tpl_vars['j']->total > 0) {
for ($_smarty_tpl->tpl_vars['j']->value = 0, $_smarty_tpl->tpl_vars['j']->iteration = 1;$_smarty_tpl->tpl_vars['j']->iteration <= $_smarty_tpl->tpl_vars['j']->total;$_smarty_tpl->tpl_vars['j']->value += $_smarty_tpl->tpl_vars['j']->step, $_smarty_tpl->tpl_vars['j']->iteration++) {
$_smarty_tpl->tpl_vars['j']->first = $_smarty_tpl->tpl_vars['j']->iteration === 1;$_smarty_tpl->tpl_vars['j']->last = $_smarty_tpl->tpl_vars['j']->iteration === $_smarty_tpl->tpl_vars['j']->total;?>
                                                <?php if (($_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['j']->value < $_smarty_tpl->tpl_vars['lntcnt']->value)) {?>
                                                <?php $_smarty_tpl->_assignInScope('_kapcsolodo', $_smarty_tpl->tpl_vars['termek']->value['kapcsolodok'][$_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['j']->value]);?>
                                                <div class="textaligncenter pull-left product-datasheet__list-item">                                                    <div class="kapcsolodoTermekInner">
                                                        <a href="<?php echo $_smarty_tpl->tpl_vars['_kapcsolodo']->value['link'];?>
">
                                                            <div class="kapcsolodoImageContainer product-datasheet__list-item-image">
                                                                <img src="<?php echo $_smarty_tpl->tpl_vars['imagepath']->value;
echo $_smarty_tpl->tpl_vars['_kapcsolodo']->value['minikepurl'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['_kapcsolodo']->value['caption'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['_kapcsolodo']->value['caption'];?>
">
                                                            </div>
                                                            <div class="product-datasheet__list-item-caption"><?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['_kapcsolodo']->value['caption'], 'UTF-8'));?>
</div>
                                                            <div class="product-datasheet__list-item-sku"><?php echo $_smarty_tpl->tpl_vars['_kapcsolodo']->value['cikkszam'];?>
</div>
                                                            <h5>
                                                                <span><?php echo number_format($_smarty_tpl->tpl_vars['_kapcsolodo']->value['bruttohuf'],0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['valutanemnev']->value;?>
</span>
                                                            </h5>
                                                            <a href="<?php echo $_smarty_tpl->tpl_vars['_kapcsolodo']->value['link'];?>
" class="button bordered okbtn"><?php echo t('Részletek');?>
</a>
                                                        </a>
                                                    </div>
                                                </div>
                                                <?php }?>
                                            <?php }
}
?>
                                            </div>
                                        <?php }
}
?>
                                    </div>
                                </div>
                                <?php }?>

                                <?php if ((count($_smarty_tpl->tpl_vars['termek']->value['hasonlotermekek']) != 0)) {?>
                                <div class="accordion-item product-datasheet__similar-products">
                                    <div class="accordion-header"><?php echo t('Hasonló termékek');?>
<span class="arrow"></span></div>
                                    <div class="accordion-content">
                                        <?php $_smarty_tpl->_assignInScope('lntcnt', count($_smarty_tpl->tpl_vars['termek']->value['hasonlotermekek']));?>
                                        <?php $_smarty_tpl->_assignInScope('step', 4);?>
                                        <?php
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['i']->step = $_smarty_tpl->tpl_vars['step']->value;$_smarty_tpl->tpl_vars['i']->total = (int) ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['lntcnt']->value-1+1 - (0) : 0-($_smarty_tpl->tpl_vars['lntcnt']->value-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0) {
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++) {
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration === 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration === $_smarty_tpl->tpl_vars['i']->total;?>
                                            <div>
                                            <?php
$_smarty_tpl->tpl_vars['j'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['j']->step = 1;$_smarty_tpl->tpl_vars['j']->total = (int) ceil(($_smarty_tpl->tpl_vars['j']->step > 0 ? $_smarty_tpl->tpl_vars['step']->value-1+1 - (0) : 0-($_smarty_tpl->tpl_vars['step']->value-1)+1)/abs($_smarty_tpl->tpl_vars['j']->step));
if ($_smarty_tpl->tpl_vars['j']->total > 0) {
for ($_smarty_tpl->tpl_vars['j']->value = 0, $_smarty_tpl->tpl_vars['j']->iteration = 1;$_smarty_tpl->tpl_vars['j']->iteration <= $_smarty_tpl->tpl_vars['j']->total;$_smarty_tpl->tpl_vars['j']->value += $_smarty_tpl->tpl_vars['j']->step, $_smarty_tpl->tpl_vars['j']->iteration++) {
$_smarty_tpl->tpl_vars['j']->first = $_smarty_tpl->tpl_vars['j']->iteration === 1;$_smarty_tpl->tpl_vars['j']->last = $_smarty_tpl->tpl_vars['j']->iteration === $_smarty_tpl->tpl_vars['j']->total;?>
                                                <?php if (($_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['j']->value < $_smarty_tpl->tpl_vars['lntcnt']->value)) {?>
                                                <?php $_smarty_tpl->_assignInScope('_hasonlo', $_smarty_tpl->tpl_vars['termek']->value['hasonlotermekek'][$_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['j']->value]);?>
                                                <div class="textaligncenter pull-left product-datasheet__list-item">                                                    <div class="kapcsolodoTermekInner">
                                                        <a href="<?php echo $_smarty_tpl->tpl_vars['_hasonlo']->value['link'];?>
">
                                                            <div class="kapcsolodoImageContainer product-datasheet__list-item-image">
                                                                <img src="<?php echo $_smarty_tpl->tpl_vars['imagepath']->value;
echo $_smarty_tpl->tpl_vars['_hasonlo']->value['kepurl'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['_hasonlo']->value['caption'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['_hasonlo']->value['caption'];?>
">
                                                            </div>
                                                            <div class="product-datasheet__list-item-caption"><?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['_hasonlo']->value['caption'], 'UTF-8'));?>
</div>
                                                            <div class="product-datasheet__list-item-sku"><?php echo $_smarty_tpl->tpl_vars['_hasonlo']->value['cikkszam'];?>
</div>
                                                            <h5>
                                                                <span><?php echo number_format($_smarty_tpl->tpl_vars['_hasonlo']->value['bruttohuf'],0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['valutanemnev']->value;?>
</span>
                                                            </h5>
                                                            <a href="<?php echo $_smarty_tpl->tpl_vars['_hasonlo']->value['link'];?>
" class="button bordered okbtn"><?php echo t('Részletek');?>
</a>
                                                        </a>
                                                    </div>
                                                </div>
                                                <?php }?>
                                            <?php }
}
?>
                                            </div>
                                        <?php }
}
?>
                                    </div>
                                </div>
                                <?php }?>

                                <?php if (((isset($_smarty_tpl->tpl_vars['szallitasifeltetelsablon']->value)))) {?>
                                <div class="accordion-item">
                                    <div class="accordion-header"><?php echo t('Szállítás és fizetés');?>
<span class="arrow"></span></div>
                                    <div class="accordion-content">
                                        <?php echo $_smarty_tpl->tpl_vars['szallitasifeltetelsablon']->value;?>

                                    </div>
                                </div>
                                <?php }?>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="span9">
                                            </div>
                </div>
                <?php if ((count($_smarty_tpl->tpl_vars['hozzavasarolttermekek']->value) > 0)) {?>
                <div class="row">
                    <div class="span9">
                    <div class="blockHeader">
                        <h2 class="main"><?php echo t('Ehhez a termékhez vásárolták még');?>
</h2>
                    </div>
                    <div id="hozzavasarolttermekslider" class="royalSlider contentSlider rsDefaultInv termekSlider">
                        <?php $_smarty_tpl->_assignInScope('lntcnt', count($_smarty_tpl->tpl_vars['hozzavasarolttermekek']->value));?>
                        <?php $_smarty_tpl->_assignInScope('step', 3);?>
                        <?php
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['i']->step = $_smarty_tpl->tpl_vars['step']->value;$_smarty_tpl->tpl_vars['i']->total = (int) ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['lntcnt']->value-1+1 - (0) : 0-($_smarty_tpl->tpl_vars['lntcnt']->value-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0) {
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++) {
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration === 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration === $_smarty_tpl->tpl_vars['i']->total;?>
                            <div>
                            <?php
$_smarty_tpl->tpl_vars['j'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['j']->step = 1;$_smarty_tpl->tpl_vars['j']->total = (int) ceil(($_smarty_tpl->tpl_vars['j']->step > 0 ? $_smarty_tpl->tpl_vars['step']->value-1+1 - (0) : 0-($_smarty_tpl->tpl_vars['step']->value-1)+1)/abs($_smarty_tpl->tpl_vars['j']->step));
if ($_smarty_tpl->tpl_vars['j']->total > 0) {
for ($_smarty_tpl->tpl_vars['j']->value = 0, $_smarty_tpl->tpl_vars['j']->iteration = 1;$_smarty_tpl->tpl_vars['j']->iteration <= $_smarty_tpl->tpl_vars['j']->total;$_smarty_tpl->tpl_vars['j']->value += $_smarty_tpl->tpl_vars['j']->step, $_smarty_tpl->tpl_vars['j']->iteration++) {
$_smarty_tpl->tpl_vars['j']->first = $_smarty_tpl->tpl_vars['j']->iteration === 1;$_smarty_tpl->tpl_vars['j']->last = $_smarty_tpl->tpl_vars['j']->iteration === $_smarty_tpl->tpl_vars['j']->total;?>
                                <?php if (($_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['j']->value < $_smarty_tpl->tpl_vars['lntcnt']->value)) {?>
                                <?php $_smarty_tpl->_assignInScope('_termek', $_smarty_tpl->tpl_vars['hozzavasarolttermekek']->value[$_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['j']->value]);?>
                                <div class="textaligncenter pull-left" style="width:<?php echo 100/$_smarty_tpl->tpl_vars['step']->value;?>
%">
                                    <div class="termekSliderTermekInner">
                                        <a href="/termek/<?php echo $_smarty_tpl->tpl_vars['_termek']->value['slug'];?>
">
                                            <div class="termekSliderImageContainer">
                                                <img src="<?php echo $_smarty_tpl->tpl_vars['imagepath']->value;
echo $_smarty_tpl->tpl_vars['_termek']->value['minikepurl'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['caption'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['caption'];?>
">
                                            </div>
                                            <div><?php echo $_smarty_tpl->tpl_vars['_termek']->value['caption'];?>
</div>
                                            <div><?php echo $_smarty_tpl->tpl_vars['_termek']->value['cikkszam'];?>
</div>
                                            <h5 class="main"><span><?php echo number_format($_smarty_tpl->tpl_vars['_termek']->value['bruttohuf'],0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['valutanemnev']->value;?>
</span></h5>
                                        </a>
                                    </div>
                                </div>
                                <?php }?>
                            <?php }
}
?>
                            </div>
                        <?php }
}
?>
                    </div>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        <div class="row product-datasheet__popular-products flex-col">
            <div class="col">
                <h4 class="textaligncenter"><?php echo t('Legnépszerűbb termékeink');?>
</h4>
            </div>
            <div class="col product-datasheet__popular-products-list">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['legnepszerubbtermekek']->value, '_nepszeru');
$_smarty_tpl->tpl_vars['_nepszeru']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_nepszeru']->value) {
$_smarty_tpl->tpl_vars['_nepszeru']->do_else = false;
?>
                <div class="textaligncenter product-datasheet__list-item">
                    <div class="kapcsolodoTermekInner">
                        <a href="<?php echo $_smarty_tpl->tpl_vars['_nepszeru']->value['link'];?>
">
                            <div class="kapcsolodoImageContainer  product-datasheet__list-item-image">
                                <img src="<?php echo $_smarty_tpl->tpl_vars['imagepath']->value;
echo $_smarty_tpl->tpl_vars['_nepszeru']->value['kepurl'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['_nepszeru']->value['caption'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['_nepszeru']->value['caption'];?>
">
                            </div>
                            <div class="product-datasheet__list-item-caption"><?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['_nepszeru']->value['caption'], 'UTF-8'));?>
</div>
                            <div class="product-datasheet__list-item-sku"><?php echo $_smarty_tpl->tpl_vars['_nepszeru']->value['cikkszam'];?>
</div>
                            <h5>
                                <span><?php echo number_format($_smarty_tpl->tpl_vars['_nepszeru']->value['bruttohuf'],0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['valutanemnev']->value;?>
</span>
                            </h5>
                            <a href="<?php echo $_smarty_tpl->tpl_vars['_nepszeru']->value['link'];?>
" class="button bordered okbtn"><?php echo t('Részletek');?>
</a>
                        </a>
                    </div>
                </div>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </div>
        </div>
	</article>
</div>
<?php $_smarty_tpl->_subTemplateRender('file:termekertesitomodal.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
/* {/block "kozep"} */
}
