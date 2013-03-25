<?php

class PoComponent extends Component {

    public $javascript = array(
        'i18n' => '/po/js/i18n',
    );

    public $settings = array(
    );

    public function __construct(ComponentCollection $collection, $settings = array()) {
        parent::__construct($collection, array_merge($this->settings, (array)$settings));
    }

    public function startup(Controller $controller) {
        $controller->helpers[] = 'Po.Po';
    }

    /**
     * beforeRender
     *
     */
    public function beforeRender(Controller $controller){
        $controller->set(array(
                'poJavascript' => $this->javascript,
            ));
    }

}