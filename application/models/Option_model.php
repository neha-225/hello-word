<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Manages DB operations for `options` table
*/

class Option_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function update_option($option_name, $option_value) {

        $option_name = trim( $option_name );
        if ( empty( $option_name ) ) {
            return false;
        }

        $where = array('option_name'=>$option_name);
        $option = $this->db->select("optionID")->from(OPTIONS)->where($where)->limit(1)->get()->row();

        $set_data = $where;
        $set_data['option_value'] = $option_value;
        $set_data['updated_at'] = datetime();
        if(!empty($option->optionID)) {
            //Update option
            $set_where = array('optionID'=>$option->optionID);
            $this->common_model->updateFields(OPTIONS, $set_data, $set_where);
            $option_id = $option->optionID;
        } else {
            //Insert Option
            $set_data['created_at'] = datetime();
            $option_id = $this->common_model->insertData(OPTIONS, $set_data);
        }
        return $option_id;
    }

    public function get_option($option_name, $all=false) {

        $option_name = trim( $option_name );
        if ( empty( $option_name ) ) {
            return false;
        }

        $where = array('option_name'=>$option_name);
        $query = $this->db->select("option_value")->from(OPTIONS)->where($where)->limit(1)->get();
        if(!$all) {
            $option = $query->row();
            return $option->option_value;
        } else {
            $option = $query->result();
            return $option; //object
        }
    }
}