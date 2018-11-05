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
 * Test plugin
 *
 * @package     tool_kholland
 * @copyright   2018 Karen Holland <karen@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../../config.php');
require_once(__DIR__.'/classes/editform.php');

$id = optional_param('id', 0, PARAM_INT);
if ($id) {
    $entry = tool_kholland_api::retrieve($id);
    $courseid = $entry->courseid;
    $urlparams = ['id' => $id];
    $title = get_string('editentry', 'tool_kholland');
} else {
    $entry = new stdClass();
    $courseid = required_param('courseid', PARAM_INT);
    $entry = (object)['id' => null, 'courseid' => $courseid];
    $urlparams = ['courseid' => $courseid];
    $title = get_string('addentry', 'tool_kholland');
}

require_login($courseid);
$context = context_course::instance($courseid);
require_capability('tool/kholland:edit', $context);

$PAGE->set_url(new moodle_url('/admin/tool/kholland/edit.php', $urlparams));
$PAGE->set_title($title);
$PAGE->set_heading(get_string('pluginname', 'tool_kholland'));

//Instantiate simplehtml_form 
$mform = new kholland_form();

$textfieldoptions = array('maxfiles'=>-1, 'maxbytes'=>0, 'context'=>$PAGE->context);

if (!empty($entry->id)) {
    // Preparing editor data
    file_prepare_standard_editor($entry, 'description', 
        tool_kholland_api::editor_options(), $PAGE->context, 
        'tool_kholland', 'entry', $entry->id);
}

$mform->set_data($entry);

$returnurl = new moodle_url('/admin/tool/kholland/index.php', ['courseid' => $courseid]); 
//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($returnurl);

} else if ($data = $mform->get_data()) {
  //In this case you process validated data. $mform->get_data() returns data posted in form.

    if ($data->id == 0) {

        $insertdata = array_intersect_key((array)$data,
            ['courseid' => 1, 'name' => 1, 'completed' => 1, 'priority' => 1,
            'description' => 1, 'descriptionformat' => 1]);
        $insertdata['timemodified'] = $insertdata['timecreated'] = time();
        $entryid = tool_kholland_api::insert((object)$insertdata);

        // Entry id known, now can update editor data
        if (isset($data->description_editor)) {
           $data = file_postupdate_standard_editor($data, 'description',
                $textfieldoptions, $PAGE->context, 'tool_devcourse', 'entry', $entryid); 
            $updatedata = ['id' => $entryid, 'description' => $data->description,
                'descriptionformat' => $data->descriptionformat];
            tool_kholland_api::update((object)$updatedata);
        }

        // TODO there should be a function in another file that creates an entry.

    } else {
        // Edit entry. Never modify courseid.

        if (isset($data->description_editor)) {
            $data = file_postupdate_standard_editor($data, 'description',
                $textfieldoptions, $PAGE->context, 'tool_kholland', 'entry', $data->id);
        }

        $updatedata = array_intersect_key((array)$data, 
            ['id' => 1, 'name' => 1, 'completed' => 1, 'priority' => 1, 
            'description' => 1, 'descriptionformat' => 1]); 
        tool_kholland_api::update((object)$updatedata);
    }

    redirect($returnurl);
} else {
  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
  // or on the first display of the form.
 
}

echo $OUTPUT->header();
echo $OUTPUT->heading($title);

$mform->display();

echo $OUTPUT->footer();
