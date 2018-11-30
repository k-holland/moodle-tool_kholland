<?php
// Standard GPL and phpdocs
//namespace tool_kholland\output;                                                                                                         
 
defined('MOODLE_INTERNAL') || die;                                                                                                  

use tool_kholland\output\index_page;
 
class tool_kholland_renderer extends plugin_renderer_base {

    /**                                                                                                                             
     * Defer to template.                                                                                                           
     *                                                                                                                              
     * @param index_page $page                                                                                                      
     *                                                                                                                              
     * @return string html for the page                                                                                             
     */                                                                                                                             
    protected function render_index_page($page) {                                                                                      
        $data = $page->export_for_template($this);                                                                                  
        return $this->render_from_template('tool_kholland/index_page', $data);                                                         
    }           


}
