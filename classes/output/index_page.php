<?php
// Standard GPL and phpdocs
namespace tool_kholland\output;                                                                                                         
 
use renderer_base;                                                                                                                  
use moodle_url;
use tool_kholland_table;
use context_course;
use stdClass;
 
class index_page implements \renderable, \templatable {                                                                               

    /** @var string $sometext Some text to show how to pass data to a template. */                                                  
    protected $courseid;
 
    public function __construct($courseid) {
        $this->courseid = $courseid;
    }
 
    /**                                                                                                                             
     * Export this data so it can be used as the context for a mustache template.                                                   
     *                                                                                                                              
     * @return stdClass                                                                                                             
     */                                                                                                                             
    public function export_for_template(renderer_base $output) {                                                                    

        $context = context_course::instance($this->courseid);

        $data = new stdClass();
        if (has_capability('tool/kholland:edit', $context)) {
            $url = new moodle_url('/admin/tool/kholland/edit.php', ['courseid' => $this->courseid]);
            $data->addlink = $url->out(false);
        }
        $data->courseid = $this->courseid;                                                                                          
        $data->heading = get_string('title', 'tool_kholland');
        $data->khtable = "Filler";

        ob_start();
        $table = new tool_kholland_table('tool_kholland', $this->courseid);
        $table->out(0, false);
        $data->khtable = ob_get_clean();

        return $data;                                                                                                               
    }
}
