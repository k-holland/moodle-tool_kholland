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

require_once($CFG->libdir.'/formslib.php');

class kholland_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG, $COURSE;
 
        $mform = $this->_form; // Don't forget the underscore! 
 
        $mform->addElement('text', 'name', get_string('name')); // Add elements to your form
        $mform->setType('name', PARAM_NOTAGS);                   //Set type of element
        $mform->setDefault('name', 'Please enter name');        //Default value
        $mform->addElement('advcheckbox', 'completed', '', get_string('completed', 'tool_kholland'), [], [0,1]);
        $mform->addElement('hidden', 'courseid', $COURSE->id);
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons();
    }

    //Custom validation should be added here
    function validation($data, $files) {
        global $DB;

        $errors = [];

        $entry = $DB->get_record('tool_kholland', ['name' => $data['name'], 'courseid' => $data['courseid']]);

print_r($entry);
print_r($data);
            // Need validation to ignore edits.
            if (($entry) && ($entry->id != $data['id'])) {
                $errors['name'] = get_string('nameerror', 'tool_kholland');
            }

        return $errors;
    }
}
