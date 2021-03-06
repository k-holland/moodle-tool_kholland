<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * CRUD form.
 *
 * @package     tool_kholland
 * @copyright   2018 Karen Holland <karen@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

class kholland_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG, $PAGE;
 
        $mform = $this->_form; // Don't forget the underscore! 
 
        $mform->addElement('text', 'name', get_string('name')); // Add elements to your form
        $mform->setType('name', PARAM_NOTAGS);                   //Set type of element
        $mform->setDefault('name', 'Please enter name');        //Default value
        $mform->addElement('advcheckbox', 'completed', get_string('completed', 'tool_kholland'));
        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('editor', 'description_editor', 
            get_string('description', 'tool_kholland'), 
            null, tool_kholland_api::editor_options());

        $this->add_action_buttons();
    }

    //Custom validation should be added here
    function validation($data, $files) {
        global $DB;

        $errors = parent::validation($data, $files);

        // Check that name is unique for the course.
        if ($DB->record_exists_select('tool_kholland',
                'name = :name AND id <> :id AND courseid = :courseid',
                ['name' => $data['name'], 'id' => $data['id'], 'courseid' => $data['courseid']])) {
                $errors['name'] = get_string('nameerror', 'tool_kholland');
        }

        return $errors;
    }
}
