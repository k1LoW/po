<?php
App::uses('AppHelper', 'View/Helper');
App::uses('L10n', 'I18n');
App::uses('PoParser', 'Po.Lib');

class PoHelper extends AppHelper {

    public $helpers = array('Html', 'Form');

    public $settings = array(
        'cacheConfig' => 'po',
        'cachePath' => false,
    );

    public function __construct($View, $options = array()) {
        $this->settings['cachePath'] = TMP . 'cache' . DS . 'po' . DS;
        $this->settings = array_merge($this->settings, $options);

        $obj = new Folder($this->settings['cachePath'], true, 0777);
        Cache::config($this->settings['cacheConfig'], array('engine'=>'File', 'path' => $this->settings['cachePath']));
        $this->l10n = new L10n();
        parent::__construct($View, $options);
    }

    /**
     * afterLayout callback
     *
     * @param string $layoutFile
     * @return void
     */
    public function afterLayout($layoutFile) {
        if (!$this->request->is('requested')) {
            $this->send();
        }
    }

    /**
     * send method
     *
     * @return void
     */
    public function send() {
        $view = $this->_View;
        $head = '';
        if (isset($view->viewVars['poJavascript'])) {
            foreach ($view->viewVars['poJavascript'] as $script) {
                if ($script) {
                    $head .= $this->Html->script($script);
                }
            }
        }

        /**
         * load po
         */
        if (!empty($_SESSION['Config']['language'])) {
            $language = $_SESSION['Config']['language'];
        } else {
            $language = Configure::read('Config.language');
        }
        $catalog = $this->l10n->catalog($language);
        if (!empty($catalog['locale'])) {
            $lang = $catalog['locale'];
        } elseif (!empty($catalog['localeFallback'])) {
            $lang = $catalog['localeFallback'];
        } else {
            $lang = 'eng';
        }

        $js = '{}';
        $cacheKey = $lang;
        if (Configure::read('debug') == 0 && Cache::read($cacheKey, $this->settings['cacheConfig'])) {
            $js = Cache::read($cacheKey, $this->settings['cacheConfig']);
        }

        if ($js === '{}') {
            $searchPaths = App::path('locales');
            $parsed = array();
            foreach ($searchPaths as $directory) {
                $app = $directory . $lang . DS . 'LC_MESSAGES' . DS;
                if (file_exists($app . 'default.po')) {
                    $parsed = array_merge(PoParser::parsePoFile($app . 'default.po'), $parsed);
                }
            }
            if (!empty($parsed)) {
                $filterd = array();
                foreach ($parsed['contents'] as $key => $value) {
                    if (!empty($value['msgstr'])) {
                        $filterd[$key] = $value['msgstr'];
                    }
                }
                $js = json_encode($filterd, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                Cache::write($cacheKey, $js, $this->settings['cacheConfig']);
            }
        }
        $head .= '<script>';
        $head .= 'var po = ' . $js . ';';
        $head .= '</script>';

        if (preg_match('#</head>#', $view->output)) {
            $view->output = preg_replace('#</head>#', $head . "\n</head>", $view->output, 1);
        }
    }

}