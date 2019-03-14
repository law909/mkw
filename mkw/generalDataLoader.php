<?php

namespace mkw;

class generalDataLoader {

    public function loadData($view) {
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            $view->setVar('mobilebrowser', true);
        }
        else {
            $view->setVar('mobilebrowser', false);
        }
        $view->setVar('theme', store::getConfigValue('theme'));
        $uitheme = \mkw\store::getAdminSession()->loggedinuser['uitheme'];
        if (!$uitheme) {
            $uitheme = 'sunny';
        }
        $view->setVar('uitheme', $uitheme);
        $view->setVar('mainurl', \mkw\store::getConfigValue('mainurl'));
        $view->setVar('userloggedin', \mkw\store::getAdminSession()->pk);
        $view->setVar('loggedinuser', \mkw\store::getAdminSession()->loggedinuser);
        $view->setVar('dev', \mkw\store::getConfigValue('developer', false));
        $view->setVar('jsversion', \mkw\store::getJSVersion());
        $view->setVar('bootstrapjsversion', \mkw\store::getBootstrapJSVersion());
        $view->setVar('localelist', \mkw\store::getLocaleList());
        $setup = \mkw\store::getSetup();
        $view->setVar('setup', $setup);
        $view->setVar('maintheme', \mkw\store::getTheme());
        $view->setVar('today', date(\mkw\store::$DateFormat));
        $menuc = new \Controllers\menuController(null);
        $view->setVar('menu', $menuc->getMenu());
        $jelenc = new \Controllers\jelenletiivController(null);
        $view->setVar('dolgozojelen', $jelenc->isDolgozoJelen(\mkw\store::getAdminSession()->pk));
        $view->setVar('webshop1name', \mkw\store::getParameter(\mkw\consts::Webshop1Name, '1'));
        $view->setVar('webshop2name', \mkw\store::getParameter(\mkw\consts::Webshop2Name, '2'));
        $view->setVar('webshop3name', \mkw\store::getParameter(\mkw\consts::Webshop3Name, '3'));
        $view->setVar('webshop4name', \mkw\store::getParameter(\mkw\consts::Webshop4Name, '4'));
        $view->setVar('webshop5name', \mkw\store::getParameter(\mkw\consts::Webshop5Name, '5'));
        $view->setVar('webshop6name', \mkw\store::getParameter(\mkw\consts::Webshop6Name, '6'));
        $view->setVar('webshop7name', \mkw\store::getParameter(\mkw\consts::Webshop7Name, '7'));
        $view->setVar('webshop8name', \mkw\store::getParameter(\mkw\consts::Webshop8Name, '8'));
        $view->setVar('webshop9name', \mkw\store::getParameter(\mkw\consts::Webshop9Name, '9'));
        $view->setVar('webshop10name', \mkw\store::getParameter(\mkw\consts::Webshop10Name, '10'));
        $view->setVar('webshop12name', \mkw\store::getParameter(\mkw\consts::Webshop11Name, '11'));
        $view->setVar('webshop12name', \mkw\store::getParameter(\mkw\consts::Webshop12Name, '12'));
        $view->setVar('webshop13name', \mkw\store::getParameter(\mkw\consts::Webshop13Name, '13'));
        $view->setVar('webshop14name', \mkw\store::getParameter(\mkw\consts::Webshop14Name, '14'));
        $view->setVar('webshop15name', \mkw\store::getParameter(\mkw\consts::Webshop15Name, '15'));
        $view->setVar('enabledwebshops', \mkw\store::getEnabledWebshops());
        $view->setVar('uithemes', array(
            'black-tie',
            'blitzer',
            'cupertino',
            'dark-hive',
            'dot-luv',
            'eggplant',
            'excite-bike',
            'flick',
            'hot-sneaks',
            'humanity',
            'le-frog',
            'mint-choc',
            'overcast',
            'pepper-grinder',
            'redmond',
            'smoothness',
            'south-street',
            'start',
            'sunny',
            'swanky-purse',
            'trontastic',
            'ui-darkness',
            'ui-lightness',
            'vader'));
    }

}
