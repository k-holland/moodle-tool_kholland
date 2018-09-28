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
                              '');

        $this->define_columns($tablecolumns);
        $this->define_headers($tableheaders);
        $this->define_baseurl($PAGE->url);

        $this->pageable(true);
        $this->collapsible(false);
        $this->sortable(false);
        $this->is_downloadable(false);
        $this->define_baseurl($PAGE->url);
        $this->context = context_course::instance($courseid);
        $this->set_sql('name, completed, priority, timecreated, timemodified',
            '{tool_kholland}', 'courseid = ?', [$courseid]);
    }

    /**
     * @return string sql to add to where statement.
     */
/*
    function get_sql_where() {
        $filter = optional_param('filter', '', PARAM_NOTAGS);
        list($wsql, $wparams) = parent::get_sql_where();
        if ($filter !== '') {
            $wsql .= ($wsql ? ' AND ' : '') . 'tg.name LIKE :tagfilter';
            $wparams['tagfilter'] = '%' . $filter . '%';
        }
        return array($wsql, $wparams);
    }
*/

    /**
     * Query the db. Store results in the table object for use by build_table.
     *
     * @param int $pagesize size of page for paginated displayed table.
     * @param bool $useinitialsbar do you want to use the initials bar. Bar
     * will only be used if there is a fullname column defined for the table.
     */
/*
    public function query_db($pagesize, $useinitialsbar = true) {
        global $DB;
        $where = '';
        if (!$this->is_downloading()) {
            $grandtotal = $DB->count_records_sql($this->countsql, $this->countparams);

            list($wsql, $wparams) = $this->get_sql_where();
            if ($wsql) {
                $this->countsql .= ' AND '.$wsql;
                $this->countparams = array_merge($this->countparams, $wparams);

                $where .= ' AND '.$wsql;
                $this->sql->params = array_merge($this->sql->params, $wparams);

                $total  = $DB->count_records_sql($this->countsql, $this->countparams);
            } else {
                $total = $grandtotal;
            }

            $this->pagesize = DEFAULT_PAGE_SIZE;
            $this->totalcount = $total;
        }

        // Fetch the attempts.
        $sort = $this->get_sql_sort();
        if ($sort) {
            $sort .= ", kh.name";
        } else {
            $sort = "kh.name";
        }

        $sql = "
            SELECT kh.*, COUNT(kh.id) AS count
            FROM {tool_kholland} kh
                       WHERE courseid = :courseid $where
            ORDER BY $sort";

        if (!$this->is_downloading()) {
            $this->rawdata = $DB->get_records_sql($sql, $this->sql->params, $this->get_page_start(), $this->get_page_size());
        } else {
            $this->rawdata = $DB->get_records_sql($sql, $this->sql->params);
        }
    }
*/

    /**
     * Column name
     *
     * @param stdClass $tag
     * @return string
     */
/*
    public function col_name($tag) {
        global $OUTPUT;
        $tagoutput = new tool_kholland\output\tagname($tag);
        return $tagoutput->render($OUTPUT);
    }
*/

    /**
     * Column flag
     *
     * @param stdClass $tag
     * @return string
     */
/*
    public function col_flag($tag) {
        global $OUTPUT;
        $tagoutput = new tool_kholland\output\tagflag($tag);
        return $tagoutput->render($OUTPUT);
    }
*/

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
    public function col_timemodified($row) {
        return userdate($row->timemodified, get_string('strftimedatetime'));
    }

}
