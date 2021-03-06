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
require_once($CFG->libdir.'/adminlib.php');

$courseid = required_param('courseid', PARAM_INT);

require_login($courseid);
$context = context_course::instance($courseid);
require_capability('tool/kholland:view', $context);

if (($deleteid = optional_param('delete', null, PARAM_INT)) && (confirm_sesskey())) {
    $record = tool_kholland_api::retrieve($deleteid, $courseid);
    require_login(get_course($record->courseid));
    require_capability('tool/kholland:edit', context_course::instance($record->courseid));
    tool_kholland_api::delete($record->id);
    redirect(new moodle_url('/admin/tool/kholland/index.php', ['courseid' => $record->courseid]));
}

$PAGE->set_url(new moodle_url('/admin/tool/kholland/index.php', array('courseid' => $courseid)));
$PAGE->set_title('Hello to the KHolland list');
$PAGE->set_heading(get_string('pluginname', 'tool_kholland'));

$outputpage = new \tool_kholland\output\index_page($courseid);
$output = $PAGE->get_renderer('tool_kholland');

echo $output->header();
echo $output->render($outputpage);
echo $output->footer();


// Custom renderer
// Testing output functions here
/*
$userinput = 'no <b>tags</b> allowed in strings';
$userinput = '<span class="multilang" lang="en">RIGHT</span><span class="multilang" lang="fr">WRONG</span>';
$userinput = 'a" onmouseover="alert(\'XSS\')" asdf="';
$userinput = "3>2";
$userinput = "2<3"; // Interesting effect, huh?

echo html_writer::div(s($userinput)); // Used when you want to escape the value.
echo html_writer::div(format_string($userinput)); // Used for one-line strings, such as forum post subject.
echo html_writer::div(format_text($userinput)); // Used for multil-line rich-text contents such as forum post body.
*/
