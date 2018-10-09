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
 * Contains class tool_kholland_table
 *
 * @package   tool_kholland
 * @copyright 2018 Karen Holland
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

define('DEFAULT_PAGE_SIZE', 5);

require_once($CFG->libdir . '/tablelib.php');

/**
 * Class tool_kholland_table
 *
 * @package   tool_kholland
 * @copyright 2018 Karen Holland
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_kholland_table extends table_sql {

    /** @var int */
    protected $context;

    /**
     * Constructor
     *
     * @param string $uniqueid unique id of form.
     * @param int $courseid
     */
    public function __construct($uniqueid, $courseid) {
        global $CFG, $PAGE;
        parent::__construct($uniqueid);

        $tablecolumns = array('name', 'completed', 'priority', 'timecreated', 'timemodified');
        $tableheaders = array(get_string('name'),
                              get_string('completed', 'tool_kholland'),
                              get_string('priority', 'tool_kholland'),
                              get_string('timecreated', 'tool_kholland'),
                              get_string('timemodified', 'tool_kholland'),
                              );

        $this->context = context_course::instance($courseid);
        if (has_capability('tool/kholland:edit', $this->context)) {
            $tablecolumns[] = 'edit';
            $tableheaders[] = '';
        }


        $this->define_columns($tablecolumns);
        $this->define_headers($tableheaders);
        $this->define_baseurl($PAGE->url);

        $this->pageable(true);
        $this->collapsible(false);
        $this->sortable(false);
        $this->is_downloadable(false);
        $this->define_baseurl($PAGE->url);
        $this->context = context_course::instance($courseid);
        $this->set_sql('name, completed, priority, timecreated, timemodified, id',
            '{tool_kholland}', 'courseid = ?', [$courseid]);
    }

    /**
     * Displays column completed
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_completed($row) {
        return $row->completed ? get_string('yes') : get_string('no');
    }
     /**
     * Displays column priority
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_priority($row) {
        return $row->priority ? get_string('yes') : get_string('no');
    }

    /**
     * Displays column name
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_name($row) {
        return format_string($row->name, true,
            ['context' => $this->context]);
    }
     /**
     * Displays column timecreated
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_timecreated($row) {
        return userdate($row->timecreated, get_string('strftimedatetime'));
    }

    /**
     * Column time modified
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_timemodified($row) {
        return userdate($row->timemodified, get_string('strftimedatetime'));
    }

    /**
     * Column time modified
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_edit($row) {
        return html_writer::link(new moodle_url('/admin/tool/kholland/edit.php', ['id' => $row->id]), get_string('edit'));
    }

}
