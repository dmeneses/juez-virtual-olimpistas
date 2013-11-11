<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class SidebarWidget extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->getView()->render('layout/sidebar');
    }

}
