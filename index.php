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
 * Displays recent and upcoming deadlines of activities based on course tags.
 *
 * @package     report_multicoursetimeline
 * @copyright   2018 Karen Holland <karen@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

//admin_externalpage_setup('tool_kholland');

$id = optional_param('id', 0, PARAM_INT);

$PAGE->set_url(new moodle_url('/admin/tool/kholland/index.php'));
$PAGE->set_pagelayout('report');
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Hello to the KHolland list');
$PAGE->set_heading(get_string('pluginname', 'tool_kholland'));

echo $OUTPUT->header();
echo $OUTPUT->heading($PAGE->title);

echo html_writer::div(get_string('hello', 'tool_kholland', $id));
//echo(get_string('hello', 'tool_kholland'));

echo $OUTPUT->footer();

// Custom renderer
/*
$output = $PAGE->get_renderer('tool_kholland');
 
echo $output->header();
echo $output->heading($PAGE->title);

//echo(get_string('hello', 'tool_kholland'));
$renderable = new \tool_kholland\output\index_page(get_string('hello', 'tool_kholland'));
echo $output->render($renderable);
*/

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
 
echo $output->footer();
//echo(get_string('hello', 'tool_kholland'));