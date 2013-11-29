<?php

namespace Group\Model;

/**
 * Group of people to add in trainings.
 *
 * @author Daniela Meneses
 */
class Group {

    public $group_id;
    public $group_name;
    public $group_owner;

    public function exchangeArray($data) {
        $this->group_id = (!empty($data['group_id'])) ? $data['group_id'] : null;
        $this->group_name = (!empty($data['group_name'])) ? $data['group_name'] : null;        
        $this->group_owner = (!empty($data['group_owner'])) ? $data['group_owner'] : null;
    }

}
