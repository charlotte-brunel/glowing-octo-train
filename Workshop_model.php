<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
---------------------------------------------------------------
*   Class:          Workshop_model extends Model defined in Core libraries
*   Author:         Pancham bansal/Pancham Bansal
*   Platform:       Codeigniter
*   Company:        Cogniter Technologies
*   Description:    Manage database functionality for Workshops.
---------------------------------------------------------------
*/

class Workshop_model extends VB_Model {
    private $_uploaded;
    function __construct() {
        parent::__construct();
        $this->table = 'workshop';
    }

        //Inserting data into workshop_recurrence
    public function add_recurrence_workshop($wid = NULL){

        $weekdays_array = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');

        $recurrent_data = array();

        if(empty($wid)){
            return FALSE;
        }

        $recurrent_data += array(
            'workshop_id' => $wid
        );

        $repeat_type = trim($this->input->post('repeat_type'));
        if(!empty($repeat_type)){
            $recurrent_data += array(
                'repeat_type' => $repeat_type
            );
        }
        $step = 1;
        $repeat_every = trim($this->input->post('repeat_every'));
        if(!empty($repeat_every)){
            $recurrent_data += array(
                'repeat_every' => $repeat_every
            );

        }
        $recurrent_start_date = trim($this->input->post('recurrent_start_date'));

        if(!empty($recurrent_start_date)){
            $recurrent_start_date = date("Y-m-d", strtotime($recurrent_start_date));
            $recurrent_data += array(
                'start_date' => $recurrent_start_date
            );
        }

        // echo '<br>recurrent_start_date:';
        $date_of_month = $recurrent_start_date;

        $final_dates = array();
        $weekly_dates = array();
        $current_days_of_week = date("w");
        switch ($repeat_type) {
            case RECURRENCE_WEEKLY:
                // echo '<br>days_of_week:';
                $days_of_week = date("w",strtotime($recurrent_start_date));
                // echo '<br>startdate:';
                $start_date_of_week = date("Y-m-d", strtotime($recurrent_start_date." - ".$days_of_week." days" ) );
                // echo '<br>enddate:';
                $end_date_of_week = date("Y-m-d", strtotime($start_date_of_week." + 6 days" ) );

                $repeat_on = $days_of_week;
                if(empty($this->input->post('repeat_on[]'))){
                    $recurrent_data += array(
                        'repeat_on' => $repeat_on
                    );

                }
                else{
                    $repeat_on = implode(',', $this->input->post('repeat_on[]'));
                    $recurrent_data += array(
                        'repeat_on' => $repeat_on
                    );
                }

                $this_week_flag = FALSE;
                $repeat_on_arr = explode(',', $repeat_on);
                foreach ($repeat_on_arr as $repeat_week) {
                    if($repeat_week >= $current_days_of_week){
                        $weekly_dates[] = date('Y-m-d', strtotime($weekdays_array[$repeat_week], strtotime($start_date_of_week))); //Get date of the week between $start_date_of_week and $end_date_of_week
                        $this_week_flag = TRUE;
                    }
                }
                if($this_week_flag == FALSE){ // If false then find the next week date as $start_date_of_week
                    $start_date_of_week = date("Y-m-d", strtotime($recurrent_start_date." + ".$repeat_every." weeks" ) );
                    foreach ($repeat_on_arr as $repeat_week) {
                        if($repeat_week >= $current_days_of_week){
                            // echo '<br>next weekly_date:';
                            // echo date('Y-m-d', strtotime($weekdays_array[$repeat_week], strtotime($start_date_of_week)));
                            $weekly_dates[] = date('Y-m-d', strtotime($weekdays_array[$repeat_week], strtotime($start_date_of_week))); //Get date of the week between $start_date_of_week and $end_date_of_week
                        }
                    }
                }
                break;

            case RECURRENCE_MONTHLY:

                $ordinals = ["", "first", "second", "third", "fourth", "fifth"];
                $date_of_week = date("j",strtotime($recurrent_start_date)); //date: 2016-10-14, return 14
                $name_of_month = date("F",strtotime($recurrent_start_date)); //date: 2016-10-14, return October
                $what_is_year = date("Y",strtotime($recurrent_start_date)); //date: 2016-10-14, return 2016
                $days_of_week = date("w",strtotime($recurrent_start_date)); //date: 2016-10-14, return 5

                $one_day_previous_date = $date_of_week - 1;

                $first_day_of_month = date("Y-m-01",strtotime($recurrent_start_date));
                $last_day_of_month = date("Y-m-t",strtotime($recurrent_start_date));

                $repeat_by = trim($this->input->post('repeat_by'));

                if($repeat_by == RECURRENCE_MONTH_DATE){

                    $date_of_month = $recurrent_start_date;
                    // $date_of_month = date("Y-m-d", strtotime("$first_day_of_month + $one_day_previous_date days" ) );

                }
                else if($repeat_by == RECURRENCE_WEEK_DAYS){
                    // echo '<br>$str_date:';
                    $str_date = $ordinals[ceil($date_of_week/7)].' '. $weekdays_array[$days_of_week].' of '.$name_of_month.' '.$what_is_year;
                    $date_of_month = date('Y-m-d', strtotime($str_date));
                }

                $recurrent_data += array(
                    'repeat_on' => $repeat_by
                );
                break;
        }

        $end_type = trim($this->input->post('end_type'));
        if(!empty($end_type)){
            $recurrent_data += array(
                'end_type' => $end_type
            );
        }

        switch ($end_type) {
            case RECURRENCE_END_NEVER:
                if(!empty($weekly_dates)){
                    $final_dates = $weekly_dates;
                }
                else{
                    $final_dates[] = $date_of_month;
                }
                // echo 'never final : <pre>';

                break;

            case RECURRENCE_END_AFTER:
                $end_value_after = trim($this->input->post('end_value_after'));
                $recurrent_data += array(
                    'end_value' => $end_value_after
                );

                if(!empty($weekly_dates)){
                    $final_dates = $weekly_dates;
                }
                else{
                    $final_dates[] = $date_of_month;
                }
                // echo 'after final : <pre>';
                break;

            case RECURRENCE_END_DATE:
                $recurrent_end_date = trim($this->input->post('recurrent_end_date'));
                $recurrent_data += array(
                    'end_value' => $recurrent_end_date
                );

                if(!empty($weekly_dates)){
                    foreach ($weekly_dates as $week_date) {
                        if(strtotime($week_date) <= strtotime($recurrent_end_date) ){
                            $final_dates[] = $week_date;
                        }
                    }
                }
                else{
                    $final_dates[] = $date_of_month;
                }
                // echo 'date final : <pre>';
                break;
        }

        $timeslots_array = $this->input->post('rec_timeslots[]');
        if(!empty($timeslots_array)){
            $timeslots = implode(',', $timeslots_array);
            $recurrent_data += array(
                'time_slots' => $timeslots
            );
        }


        if($this->db->insert("workshop_recurrence", $recurrent_data)){

            $data_workshop_rec_slots = array();

            foreach ($final_dates as $final_date) {

                $workshop_start_date = date("m/d/Y", strtotime($final_date));

                //loop on time slots
                foreach( $this->input->post('rec_timeslots[]') as $timeslot) {
                    $workshop_slottime = explode("-", $timeslot);
                    $m = '00'; $s = '00';
                    $from_time = (int)$workshop_slottime[0].":".$m.":".$s;
                    $end_time  = (int)$workshop_slottime[1].":".$m.":".$s;

                    $workshop_rec_slots_data = array(
                        'workshop_date'    => $workshop_start_date,
                        'workshop_enddate' => $workshop_start_date,
                        'from_time'        => $from_time,
                        'end_time'         => $end_time,
                        'is_reccurent'     => 1,
                        'workshop_id'      => $wid,
                        'available_seats'  => $this->input->post('no_of_attendees'),
                    );
                    $this->db->insert("workshop_availability", $workshop_rec_slots_data);
                }
                //loop on time slots close
            }

            $end_type_option = trim($this->input->post('end_type'));
            if($end_type_option == RECURRENCE_END_AFTER)
            {
                $repeated_loop = trim($this->input->post('end_value_after'));
            }
            else
            {
                $repeat_type = trim($this->input->post('repeat_type'));
                $recurrent_start_date = trim($this->input->post('recurrent_start_date'));
                $recurrent_end_date   = trim($this->input->post('recurrent_end_date'));
                if( $repeat_type == RECURRENCE_WEEKLY)
                {
                    $repeated_loop = trim($this->datediffInWeeks($recurrent_start_date, $recurrent_end_date));
                }
                else
                {
                    $repeated_loop = trim($this->nb_mois($recurrent_start_date, $recurrent_end_date)) - 1;
                }
            }

            for($nxt = 0; $nxt < $repeated_loop; $nxt++)
            {
                $this->add_next_immediate_recurrent_dates($wid);
            }

            return TRUE;
        }
        return FALSE;
    }

    public function datediffInWeeks($date1, $date2)
    {
        if($date1 > $date2)
            return datediffInWeeks($date2, $date1);

        $first  = DateTime::createFromFormat('d-m-Y', $date1);
        $second = DateTime::createFromFormat('d-m-Y', $date2);
        return floor($first->diff($second)->days/7);
    }

    public function nb_mois($date1, $date2)
    {
        $begin = new DateTime( $date1 );
        $end = new DateTime( $date2 );
        $end = $end->modify( '+1 month' );

        $interval = DateInterval::createFromDateString('1 month');

        $period = new DatePeriod($begin, $interval, $end);
        $counter = 0;
        foreach($period as $dt) {
            $counter++;
        }

        return $counter;
    }

    // Updating workshop_recurrence data
    public function update_recurrence_workshop($wid = NULL){

        $weekdays_array = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');

        $recurrent_data = array();

        if(empty($wid)){
            return FALSE;
        }

        $recurrent_data += array(
            'workshop_id' => $wid
        );

        $repeat_type = trim($this->input->post('repeat_type'));
        if(!empty($repeat_type)){
            $recurrent_data += array(
                'repeat_type' => $repeat_type
            );
        }
        $step = 1;
        $repeat_every = trim($this->input->post('repeat_every'));
        if(!empty($repeat_every)){
            $recurrent_data += array(
                'repeat_every' => $repeat_every
            );

        }
        $recurrent_start_date = trim($this->input->post('recurrent_start_date'));

        if(!empty($recurrent_start_date)){
            $recurrent_start_date = date("Y-m-d", strtotime($recurrent_start_date));
            $recurrent_data += array(
                'start_date' => $recurrent_start_date
            );
        }

        // echo '<br>recurrent_start_date:';
        $date_of_month = $recurrent_start_date;

        $final_dates = array();
        $weekly_dates = array();
        $current_days_of_week = date("w");
        switch ($repeat_type) {
            case RECURRENCE_WEEKLY:
                // echo '<br>days_of_week:';
                $days_of_week = date("w",strtotime($recurrent_start_date));
                // echo '<br>startdate:';
                $start_date_of_week = date("Y-m-d", strtotime($recurrent_start_date." - ".$days_of_week." days" ) );
                // echo '<br>enddate:';
                $end_date_of_week = date("Y-m-d", strtotime($start_date_of_week." + 6 days" ) );

                $repeat_on = $days_of_week;
                if(empty($this->input->post('repeat_on[]'))){
                    $recurrent_data += array(
                        'repeat_on' => $repeat_on
                    );

                }
                else{
                    $repeat_on = implode(',', $this->input->post('repeat_on[]'));
                    $recurrent_data += array(
                        'repeat_on' => $repeat_on
                    );
                }

                $this_week_flag = FALSE;
                $repeat_on_arr = explode(',', $repeat_on);
                foreach ($repeat_on_arr as $repeat_week) {
                    if($repeat_week >= $current_days_of_week){
                        $weekly_dates[] = date('Y-m-d', strtotime($weekdays_array[$repeat_week], strtotime($start_date_of_week))); //Get date of the week between $start_date_of_week and $end_date_of_week
                        $this_week_flag = TRUE;
                    }
                }
                if($this_week_flag == FALSE){ // If false then find the next week date as $start_date_of_week
                    $start_date_of_week = date("Y-m-d", strtotime($recurrent_start_date." + ".$repeat_every." weeks" ) );
                    foreach ($repeat_on_arr as $repeat_week) {
                        if($repeat_week >= $current_days_of_week){
                            // echo '<br>next weekly_date:';
                            // echo date('Y-m-d', strtotime($weekdays_array[$repeat_week], strtotime($start_date_of_week)));
                            $weekly_dates[] = date('Y-m-d', strtotime($weekdays_array[$repeat_week], strtotime($start_date_of_week))); //Get date of the week between $start_date_of_week and $end_date_of_week
                        }
                    }
                }
                break;

            case RECURRENCE_MONTHLY:

                $ordinals = ["", "first", "second", "third", "fourth", "fifth"];
                $date_of_week = date("j",strtotime($recurrent_start_date)); //date: 2016-10-14, return 14
                $name_of_month = date("F",strtotime($recurrent_start_date)); //date: 2016-10-14, return October
                $what_is_year = date("Y",strtotime($recurrent_start_date)); //date: 2016-10-14, return 2016
                $days_of_week = date("w",strtotime($recurrent_start_date)); //date: 2016-10-14, return 5

                $one_day_previous_date = $date_of_week - 1;

                $first_day_of_month = date("Y-m-01",strtotime($recurrent_start_date));
                $last_day_of_month = date("Y-m-t",strtotime($recurrent_start_date));

                $repeat_by = trim($this->input->post('repeat_by'));

                if($repeat_by == RECURRENCE_MONTH_DATE){

                    $date_of_month = $recurrent_start_date;
                    // $date_of_month = date("Y-m-d", strtotime("$first_day_of_month + $one_day_previous_date days" ) );

                }
                else if($repeat_by == RECURRENCE_WEEK_DAYS){
                    // echo '<br>$str_date:';
                    $str_date = $ordinals[ceil($date_of_week/7)].' '. $weekdays_array[$days_of_week].' of '.$name_of_month.' '.$what_is_year;
                    $date_of_month = date('Y-m-d', strtotime($str_date));
                }

                $recurrent_data += array(
                    'repeat_on' => $repeat_by
                );
                break;
        }

        $end_type = trim($this->input->post('end_type'));
        if(!empty($end_type)){
            $recurrent_data += array(
                'end_type' => $end_type
            );
        }

        switch ($end_type) {
            case RECURRENCE_END_NEVER:
                if(!empty($weekly_dates)){
                    $final_dates = $weekly_dates;
                }
                else{
                    $final_dates[] = $date_of_month;
                }
                // echo 'never final : <pre>';

                break;

            case RECURRENCE_END_AFTER:
                $end_value_after = trim($this->input->post('end_value_after'));
                $recurrent_data += array(
                    'end_value' => $end_value_after
                );

                if(!empty($weekly_dates))
                {
                    $final_dates = $weekly_dates;
                }
                else
                {
                    $final_dates[] = $date_of_month;
                }
                // echo 'after final : <pre>';
                break;

            case RECURRENCE_END_DATE:
                $recurrent_end_date = trim($this->input->post('recurrent_end_date'));
                $recurrent_data += array(
                    'end_value' => $recurrent_end_date
                );

                if(!empty($weekly_dates))
                {
                    foreach ($weekly_dates as $week_date) {
                        if(strtotime($week_date) <= strtotime($recurrent_end_date) )
                        {
                            $final_dates[] = $week_date;
                        }
                    }
                }
                else
                {
                    $final_dates[] = $date_of_month;
                }
                // echo 'date final : <pre>';
                break;
        }

        $timeslots_array = $this->input->post('rec_timeslots[]');
        if(!empty($timeslots_array)){
            $timeslots = implode(',', $timeslots_array);
            $recurrent_data += array(
                'time_slots' => $timeslots
            );
        }

        //pr($final_dates);

        $this->db->where("workshop_id", $wid);
        if( $this->db->delete("workshop_recurrence") )
        {
            $this->db->where("workshop_id", $wid);
            $this->db->delete("workshop_availability");

            if($this->db->insert("workshop_recurrence", $recurrent_data))
            {

                $data_workshop_rec_slots = array();

                foreach ($final_dates as $final_date) {

                    $workshop_start_date = date("m/d/Y", strtotime($final_date));

                    //loop on time slots
                    foreach( $this->input->post('rec_timeslots[]') as $timeslot){
                        $workshop_slottime = explode("-", $timeslot);
                        $m = '00';
                        $s = '00';
                        $from_time = (int)$workshop_slottime[0].":".$m.":".$s;
                        $end_time  = (int)$workshop_slottime[1].":".$m.":".$s;

                        // $data_workshop_rec_slots[] = array(
                        $workshop_rec_slots_data = array(
                            'workshop_date'    => $workshop_start_date,
                            'workshop_enddate' => $workshop_start_date,
                            'from_time'        => $from_time,
                            'end_time'         => $end_time,
                            'is_reccurent'     => 1,
                            'workshop_id'      => $wid,
                            'available_seats'  => $this->input->post('no_of_attendees'),
                        );


                        $this->db->insert("workshop_availability", $workshop_rec_slots_data);

                    }
                    //loop on time slots close
                }


                $end_type_option = trim($this->input->post('end_type'));
                if($end_type_option == RECURRENCE_END_AFTER)
                {
                    $repeated_loop = trim($this->input->post('end_value_after'));
                }
                else
                {
                    $repeat_type = trim($this->input->post('repeat_type'));
                    $recurrent_start_date = trim($this->input->post('recurrent_start_date'));
                    $recurrent_end_date   = trim($this->input->post('recurrent_end_date'));
                    if( $repeat_type == RECURRENCE_WEEKLY)
                    {
                        $repeated_loop = trim($this->datediffInWeeks($recurrent_start_date, $recurrent_end_date));
                    }
                    else
                    {
                        $repeated_loop = trim($this->nb_mois($recurrent_start_date, $recurrent_end_date) - 1);
                    }
                }

                for($nxt = 0; $nxt < $repeated_loop; $nxt++)
                {
                    $this->add_next_immediate_recurrent_dates($wid);
                }
                return TRUE;
            }
        }

        return FALSE;
    }

    public function add_next_immediate_recurrent_dates($wid = NULL){

        $this->db->select('*, MIN(STR_TO_DATE(CONCAT_WS(" ", workshop_date, from_time), "%m/%d/%Y %H:%i:%s")) as min_date, MAX(STR_TO_DATE(CONCAT_WS(" ", workshop_date, from_time), "%m/%d/%Y %H:%i:%s")) as max_date, COUNT(workshop_id) as workshop_occurrence');
        $this->db->from('workshop_availability');
        $this->db->where('is_reccurent', 1);
        $this->db->where('workshop_id', $wid);
        $this->db->group_by('workshop_id');

        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) {
                $this->calc_n_add_workshop_availability( $row->workshop_id, $row->workshop_occurrence , $row->max_date );
            }
        }

    }


    //Add next nearest date for recurrence workshop only if last workshop date will be expired.
    public function add_next_recurrent_date(){

        $this->db->select('*, MIN(STR_TO_DATE(CONCAT_WS(" ", workshop_date, from_time), "%m/%d/%Y %H:%i:%s")) as min_date, MAX(STR_TO_DATE(CONCAT_WS(" ", workshop_date, from_time), "%m/%d/%Y %H:%i:%s")) as max_date, COUNT(workshop_id) as workshop_occurrence');
        $this->db->from('workshop_availability');
        $this->db->where('is_reccurent', 1);
        $this->db->group_by('workshop_id');
        $this->db->having('max_date < NOW()');

        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) {
                $this->calc_n_add_workshop_availability( $row->workshop_id, $row->workshop_occurrence , $row->max_date );
            }
        }

    }


    //Calculate nearest next day of workshop and insert into Workshop Availability
    public function calc_n_add_workshop_availability($wid = NULL, $workshop_occurrence = NULL, $max_last_datetime = NULL){

        $current_datetime = date("Y-m-d H:i:s");
        $ordinals = ["", "first", "second", "third", "fourth", "last"];
        $weekdays_array = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');

        $date_of_month = 0;
        $weekly_dates = array();
        $final_dates = array();

        if(empty($wid)){
            return FALSE;
        }

        //Second get workshop recurrence details
        $this->db->from("workshop_recurrence");
        $this->db->where('workshop_id', $wid);
        $query_wr = $this->db->get();

        if(!empty($query_wr->result())){

            foreach ($query_wr->result() as $row_wr) {
                $wr_id = $row_wr->id;
                $repeat_type = $row_wr->repeat_type;
                $repeat_every = $row_wr->repeat_every;
                $repeat_on = $row_wr->repeat_on;
                $repeat_on_array = explode(',', $repeat_on);
                $repeat_on_count = count($repeat_on_array);
                $start_date = $row_wr->start_date;
                $end_type = $row_wr->end_type;
                $end_value = $row_wr->end_value;

                $time_slots = $row_wr->time_slots;
                $time_slots_array = explode(',', $time_slots);
                $time_slots_count = count($time_slots_array);

                $recurrent_start_date = date("Y-m-d", strtotime($max_last_datetime));

                $current_days_of_week = date("w");

                // echo '<pre><br>repeat_type:'.$repeat_type;
                // echo '<pre><br>recurrent_start_date:'.$recurrent_start_date;

                switch ($repeat_type) {
                    case RECURRENCE_WEEKLY:

                        $next_end_date = date("Y-m-d", strtotime($recurrent_start_date." + ".$repeat_every." weeks" ) );

                        $days_of_week = date("w",strtotime($next_end_date));

                        $start_date_of_week = date("Y-m-d", strtotime($next_end_date." - ".$days_of_week." days" ) );
                        $end_date_of_week = date("Y-m-d", strtotime($start_date_of_week." + 6 days" ) );
                        // echo '<pre><br>recurrent_start_date:'.$recurrent_start_date;

                        foreach ($repeat_on_array as $repeat_week) {

                                $weekly_dates[] = date('Y-m-d', strtotime($weekdays_array[$repeat_week], strtotime($start_date_of_week))); //Get date of the week between $start_date_of_week and $end_date_of_week

                        }
                        break;

                    case RECURRENCE_MONTHLY:
                        $repeat_by = $repeat_on;
                        $global_start_date_of_month = date("j",strtotime($start_date)); //date: 2016-10-14, return 14
                        $global_days_of_week = date("w",strtotime($start_date)); //date: 2016-10-14, return 14

                        $first_day_of_start_date = date("Y-m-01",strtotime($recurrent_start_date));
                        $expected_date = date("Y-m-d", strtotime($first_day_of_start_date." + ".$repeat_every." months" ) );
                        $last_day_of_expected_date = date("Y-m-t",strtotime($expected_date));
                        $day_of_expected_date = date("j",strtotime($last_day_of_expected_date)); //date: 2016-10-14, return 14
                        $month_of_expected_date = date("n",strtotime($last_day_of_expected_date)); //date: 2016-10-14, return 10
                        $month_name_of_expected_date = date("F",strtotime($last_day_of_expected_date)); //date: 2016-10-14, return October
                        $year_of_expected_date = date("Y",strtotime($last_day_of_expected_date)); //date: 2016-10-14, return 2016

                        if($repeat_by == RECURRENCE_MONTH_DATE){
                            if($global_start_date_of_month > $day_of_expected_date){
                                $date_of_month = $last_day_of_expected_date;
                            }
                            else{
                                $date_of_month = date("Y-m-d", strtotime($year_of_expected_date.'-'.$month_of_expected_date.'-'.$global_start_date_of_month ) );
                            }
                        }
                        else if($repeat_by == RECURRENCE_WEEK_DAYS){
                            $str_date = $ordinals[ceil($global_start_date_of_month/7)].' '. $weekdays_array[$global_days_of_week].' of '.$month_name_of_expected_date.' '.$year_of_expected_date;
                            $date_of_month = date('Y-m-d', strtotime($str_date));
                        }

                        break;

                }

                switch ($end_type) {
                    case RECURRENCE_END_NEVER:
                        //Insert into workshop availbility
                        if(!empty($weekly_dates)){
                            $final_dates = $weekly_dates;
                        }

                        if(!empty($date_of_month)){
                            $final_dates[] = $date_of_month;
                        }

                        break;

                    case RECURRENCE_END_AFTER:
                        $total_recurrence = (int)$repeat_on_count * (int)$end_value * (int)$time_slots_count;
                        if($workshop_occurrence < $total_recurrence){
                            //Insert into workshop availbility
                            if(!empty($weekly_dates)){
                                $final_dates = $weekly_dates;
                            }

                            if(!empty($date_of_month)){
                                $final_dates[] = $date_of_month;
                            }
                        }
                        break;

                    case RECURRENCE_END_DATE:
                        $greater_datetime = '1970-01-01';
                        foreach ($time_slots_array as $key => $value) {
                            $time_slot = explode('-', $value);
                            $from_time = $time_slot[0].':00:00';
                            $from_datetime = $end_value." ".$from_time;

                            strtotime($from_datetime).'|'.strtotime($greater_datetime);

                            if( strtotime($from_datetime) > strtotime($greater_datetime) ){
                                $greater_datetime = $from_datetime;
                            }
                        }
                        if(strtotime($greater_datetime) > strtotime($max_last_datetime) ){
                            //Insert into workshop availbility
                            if(!empty($weekly_dates)){
                                $final_dates = $weekly_dates;
                            }

                            if(!empty($date_of_month)){
                                $final_dates[] = $date_of_month;
                            }
                        }
                        break;
                }

            }

            $return_status = array();
            //Get number_of_attendees w.r.t workshop id
            $no_of_attendees = $this->get_actual_no_of_attendee_in_workshop($wid);

            //Inserting data into workshop_availability
            $data_workshop_rec_slots = array();

            foreach ($final_dates as $final_date) {

                $workshop_start_date = date("m/d/Y", strtotime($final_date));

                //loop on time slots
                foreach( $time_slots_array as $timeslot) {
                    $workshop_slottime = explode("-", $timeslot);
                    $m = '00'; $s = '00';
                    $from_time = (int)$workshop_slottime[0].":".$m.":".$s;
                    $end_time  = (int)$workshop_slottime[1].":".$m.":".$s;
                    // $data_workshop_rec_slots[] = array(
                    $workshop_rec_slots_data = array(
                        'workshop_date'    => $workshop_start_date,
                        'workshop_enddate' => $workshop_start_date,
                        'from_time'        => $from_time,
                        'end_time'         => $end_time,
                        'is_reccurent'     => 1,
                        'workshop_id'      => $wid,
                        'available_seats'  => $no_of_attendees,
                    );
                    // echo '<pre>';
                    // print_r($workshop_rec_slots_data);
                    // die;
                    $workshop_datetime = $workshop_start_date." ".$from_time;
                    if( $this->db->insert("workshop_availability", $workshop_rec_slots_data) ){
                        $return_status[$workshop_datetime] = TRUE;
                    }
                    else{
                        $return_status[$workshop_datetime] = FALSE;
                    }

                }
                //loop on time slots close
            }

            echo json_encode($return_status, TRUE);

        }
    }

    public function get_actual_no_of_attendee_in_workshop($workshop_id = NULL){
        $this->db->from('workshop');
        $this->db->where('id', $workshop_id);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                return $row->number_of_attendees;
            }
        }
        return 0;
    }

    /**
    *   Add New Workshop  ( add )
    *   @param :
    *
    **/
    public function add_workshop($userid = null){
        if( empty($userid) )
        {
            return FALSE;
        }

        /* Insert into Workshop Table */
        $number_of_attendees = $this->input->post('no_of_attendees');
        $price_per_attendee = floatval(str_replace(array('.', ','), array('', '.'), $this->input->post('price_per_attendee')) );
        $price_per_attendee = number_format($price_per_attendee,2,".","");

        $tax = $price_per_attendee * (TAX_AMOUNT / 100);
        $price_per_attendee_including_tax = $price_per_attendee + $tax;
        //$price_per_attendee_including_tax = number_format($price_per_attendee_including_tax, 2, ".", "");

        $incremented_no_of_attendees = 0;
        $decremented_no_of_attendees = 0;
        $this->db->where('id', $wid);
        $query_ws = $this->db->get("workshop");
        if ($query_ws->num_rows() > 0) {
            foreach ( $query_ws->result() as $row_ws ) {
                $number_of_attendees_ws = $row_ws->number_of_attendees;
                if( $number_of_attendees < $number_of_attendees_ws ){
                    $decremented_no_of_attendees = $number_of_attendees_ws - $number_of_attendees;
                }
                else if( $number_of_attendees > $number_of_attendees_ws ){
                    $incremented_no_of_attendees = $number_of_attendees - $number_of_attendees_ws;
                }
            }
        }

        if( !empty($this->input->post('campaign_start_date')) && !empty($this->input->post('campaign_end_date'))){
            $data_workshop = array(
                'title' => $this->input->post('title'),
                'slug'  => $this->slug->create_uri( $this->input->post('title') ),
                'number_of_attendees' => $number_of_attendees,
                'price_per_attendee'  => $price_per_attendee,
                'price_per_attendee_including_tax' => $price_per_attendee_including_tax,
                'status'              => 0,
                'workshop_type'       => 1,
                'is_manual'           => $this->input->post('validation_process'),
                'users_id'            => $userid,
            );
        }
        else
        {
            $data_workshop = array(
                'title' => $this->input->post('title'),
                'slug'  => $this->slug->create_uri( $this->input->post('title') ),
                'number_of_attendees' => $number_of_attendees,
                'price_per_attendee'  => $price_per_attendee,
                'price_per_attendee_including_tax' => $price_per_attendee_including_tax,
                'is_manual'           => $this->input->post('validation_process'),
                'users_id'            => $userid,
            );
        }

        $this->db->insert("workshop", $data_workshop);
        $wid = $this->db->insert_id();
        $_SESSION['insertid'] = $wid;

        if( !empty($wid) ){

            /****************************************************/

            $myda = $_POST['image-data'];
            $image_data =  json_decode($myda, true);
            $this->table = "workshop_images";

            $config['upload_path'] = dirname( dirname(  dirname( __FILE__ ) ) ).'/www/uploads/';

            for($i = 0; $i < count($image_data); $i++)
            {
                $data_wr = str_replace('data:image/png;base64,', '', $image_data[$i]);
                $data = base64_decode($data_wr);
                $new_image_name = 'image_' . time() . '_' . $wid .'_'. $i . '.png';
                file_put_contents(dirname( dirname(  dirname( __FILE__ ) ) ).'/www/uploads/'.$new_image_name, $data );

                $data_arr = array(
                    'image_name'   => $new_image_name,
                    'image_path'   => dirname( dirname(  dirname( __FILE__ ) ) ).'/www/uploads/',
                    'workshop_id'  => $wid,
                );


                $target = $config['upload_path'].'/371x371';
                if(!is_dir($target)){
                    $this->_mkdir($target);
                }
                workshop_thumbs($target, $new_image_name, 371, 371);


                $target = $config['upload_path'].'/510x308';
                if(!is_dir($target)){
                    $this->_mkdir($target);
                }
                workshop_thumbs($target, $new_image_name, 510, 308);

                $this->db->insert($this->table, $data_arr);
            }

            /****************************************************/

            if( !empty($this->input->post('selected_wall_image')))
            {
                /* Insert Wall Image*/
                $data_wall_workshop = array(
                    'wall_images_id' => $this->input->post('selected_wall_image'),
                    'workshop_id'    => $wid,
                );
                $this->db->insert("workshop_wall_images", $data_wall_workshop);
            }

            /* Insert Workshop Availability */
            if( $this->input->post('is_recurrence') == 1)
            {
                $this->add_recurrence_workshop($wid);
            }
            else
            {
                $data_workshop_availability = array();
                $data_workshop_slots = array();
                $workshop_dates = explode(",", $this->input->post('selected_dates'));
                if( count($workshop_dates) > 1){

                    $workshop_slot = explode("-", $this->input->post('timeslots[0]'));
                    $m = 00; $s = 00;
                    $from_time = (int)$workshop_slot[0].":".$m.":".$s;
                    $end_time  = (int)$workshop_slot[1].":".$m.":".$s;

                    for( $i = 0; $i < count($workshop_dates); $i++){
                        $data_workshop_availability[] = array(
                            'workshop_date'    => trim($workshop_dates[$i]),
                            'workshop_enddate' => trim($workshop_dates[$i]),
                            'from_time'        => $from_time,
                            'end_time'         => $end_time,
                            'workshop_id'      => $wid,
                            'available_seats'  => $number_of_attendees,
                        );
                    }

                    $this->db->where('workshop_id', $wid);
                    if($this->db->delete("workshop_availability"))
                        $this->db->insert_batch("workshop_availability", $data_workshop_availability);
                } else {
                    foreach( $this->input->post('timeslots[]') as $timeslot) {
                        $workshop_slottime = explode("-", $timeslot);
                        $m = 00; $s = 00;
                        $from_time = (int)$workshop_slottime[0].":".$m.":".$s;
                        $end_time  = (int)$workshop_slottime[1].":".$m.":".$s;
                        $data_workshop_slots[] = array(
                            'workshop_date'    => trim($this->input->post('selected_dates') ),
                            'workshop_enddate' => trim($this->input->post('selected_dates') ),
                            'from_time'        => $from_time,
                            'end_time'         => $end_time,
                            'workshop_id'      => $wid,
                            'available_seats'  => $number_of_attendees,
                        );
                    }
                    $this->db->where('workshop_id', $wid);
                    if($this->db->delete("workshop_availability"))
                        $this->db->insert_batch("workshop_availability", $data_workshop_slots);
                }
            }

            /* Insert Workshop Description */
            $data_workshop_desc = array(
                'descrition'  => $this->input->post('workshop_desc'),
                'workshop_id' => $wid,
            );
            $this->db->insert("workshop_description", $data_workshop_desc);

            /* Insert Workshop Location */
            $data_workshop_locs = array(
                    'address'     => $this->input->post('address'),
                    // 'postal_code' => $this->input->post('postal_code'),
                    // 'town'        => $this->input->post('town'),
                    'latitude'    => $this->input->post('lat'),
                    'longitude'   => $this->input->post('lon'),
                    'workshop_id' => $wid,
            );
            $this->db->insert("workshop_locations", $data_workshop_locs);

            /* Insert Workshop Tags */
            $data_workshop_tagsid = array();
            if( !empty($this->input->post('workshop_tags[]')) ) {
                for($tag = 0; $tag < count($this->input->post('workshop_tags[]')); $tag++){
                    $data_workshop_tagsid[] = array(
                        'workshop_id' => $wid,
                        'tags_id'  => $this->input->post('workshop_tags['.$tag.']'),
                    );
                }
                $this->db->insert_batch("workshop_tags", $data_workshop_tagsid);
            }

            /* Insert Workshop Charateristics */
            $data_workshop_Charateristics = array();
            if( !empty($this->input->post('workshop_characteristics[]')) ){
                for($characteristics_loop = 0; $characteristics_loop < count($this->input->post('workshop_characteristics[]')); $characteristics_loop++){
                    $data_workshop_Charateristics[] = array(
                        'workshop_id' => $wid,
                        'characteristics_id'  => $this->input->post('workshop_characteristics['.$characteristics_loop.']'),
                    );
                }
                $this->db->insert_batch("workshop_characteristics", $data_workshop_Charateristics);
            }

            /* Insert Workshop Campaign */
            if( !empty($this->input->post('campaign_start_date')) &&
                !empty($this->input->post('campaign_end_date'))){
                $data_workshop_campaign = array(
                    'from_date'   => date("Y-m-d", strtotime( $this->input->post('campaign_start_date') ) ),
                    'end_date'    => date("Y-m-d", strtotime( $this->input->post('campaign_end_date') ) ),
                    'workshop_id' => $wid,
                );
                if( $this->db->insert("workshop_campaign", $data_workshop_campaign) ){

                    $this->db->where("id", $wid);
                    $this->db->update("workshop", array('workshop_type' => 1));

                }
            }

            /* Insert Workshop Equipment */
            if( !empty($this->input->post('material_text')))
            {
                if( empty($_FILES['material_file']['name']))
                {
                    $data_workshop_equip = array(
                        'equipment_text'  => $this->input->post('material_text'),
                        'workshop_id' => $wid,
                    );
                    $this->db->insert("workshop_equipments", $data_workshop_equip);
                }
                else
                {
                    $config1['file_name'] = $_FILES['material_file']['name'];
                    $config1['upload_path'] = dirname( dirname(  dirname( __FILE__ ) ) ).'/www/uploads/equipments';
                    $config1['allowed_types'] = 'gif|jpg|png|jpeg';

                    $this->load->library('upload', $config1);

                    if ( ! $this->upload->do_upload('material_file') )
                    {
                        $error = array('error' => $this->upload->display_errors());
                    }
                    else
                    {
                        $data_workshop_equip = array(
                            'equipment_text'  => $this->input->post('material_text'),
                            'workshop_id' => $wid,
                        );

                        $data_img = $this->upload->data();
                        $file_name = $data_img['file_name'];

                        $target = $config1['upload_path'].'/870x451';
                        if(!is_dir($target)){
                            $this->_mkdir($target);
                        }
                        workshop_equipment_thumbs($target, $file_name, 870, 451);


                        $data_workshop_equip += array( 'equipment_image' => $file_name );


                        $this->db->insert("workshop_equipments", $data_workshop_equip);

                    }
                }
            }
            return TRUE;
        } else {
            return FALSE;
        }
        return TRUE;
    }


    /**
    *   Save new offer to database  ( add )
    *   @param :
    *
    **/
    public function save_workshops() {
        $config['upload_path'] = dirname( dirname(  dirname( __FILE__ ) ) ).'/www/admin/uploads/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload()){
            die($this->upload->display_errors());
            $error = array('error' => $this->upload->display_errors());
        } else {
            $data = array(
                'title' => $this->input->post('offer_title'),
                'date_from' => $this->input->post('offer_datefrom'),
                'date_to' => $this->input->post('offer_dateto'),
                'short_desc' => $this->input->post('teasing_text'),
                'description' => $this->input->post('offer_text'),
            );
            $data_img  = $this->upload->data();
            $file_name = $data_img['file_name'];
            $data += array( 'image' => $file_name );

            if($this->db->insert($this->table, $data)) {
                return $this->db->insert_id();
            } else {

            }
        }
        return FALSE;
    }

    /**
    * Uploading workshop images
    * @param: int $workshop_id
    *
    **/
    public function upload_workshop_images($workshop_id = null){
        if ( !$this->is_workshop_exist($workshop_id) ){
            return FALSE;
        }

        //Save user details
        $data = array(
            'workshop_id' => $workshop_id,
        );

        $files = $_FILES;
        $cpt = count($_FILES['workshop_image']['name']);
        for($i=0; $i<$cpt; $i++)
        {
            $config['upload_path'] = dirname( dirname(  dirname( __FILE__ ) ) ).'/www/uploads/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['file_name'] = rand().'_'.$files['workshop_image']['name'][$i];

            $this->load->library('upload');
            $this->upload->initialize($config);

            $_FILES['workshop_image']['name']   = $files['workshop_image']['name'][$i];
            $_FILES['workshop_image']['type']   = $files['workshop_image']['type'][$i];
            $_FILES['workshop_image']['tmp_name']= $files['workshop_image']['tmp_name'][$i];
            $_FILES['workshop_image']['error']  = $files['workshop_image']['error'][$i];
            $_FILES['workshop_image']['size']   = $files['workshop_image']['size'][$i];


            $is_upload = $this->upload->do_upload('workshop_image');

            if ( $is_upload ) {
                $data_img = $this->upload->data();
                $file_name = $data_img['file_name'];
                $data += array('image_name' => $file_name );
                $data += array('image_path' => base_url() .'www/uploads'. $file_name );
            }
            else {

            }

            if ( empty($data) ){
                return FALSE;
            }

            if ( $this->db->insert( 'workshop_images', $data ) ) {
                return $this->db->insert_id();
            }
        }

        return FALSE;
    }

    /**
    *   check workshop already exists or not
    *   @param : int $workshop_id
    *   Created by: Pancham Bansal
    *
    **/
    public function is_workshop_exist($workshop_id = null){

        if( empty( $workshop_id ) ){
            return FALSE;
        }

        $this->db->from($this->table);
        $this->db->where('id',$workshop_id);
        $query = $this->db->get();

        if( count($query->result()) > 0 ) {
            return TRUE; //workshop found
        } else {
            return FALSE; //workshop not found
        }
    }

    public function get_workshop_list_for_sitemap() {

        $this->db->select('workshop.slug, workshop.created_at');
        $this->db->from('workshop');
        $this->db->where('STR_TO_DATE(CONCAT_WS(" ", workshop_availability.workshop_date, workshop_availability.from_time), "%m/%d/%Y %H:%i:%s") >= NOW()');
        $this->db->join('workshop_availability', 'workshop_availability.workshop_id = workshop.id', 'left');

        $this->db->group_by('workshop.id');

        $this->db->order_by("STR_TO_DATE(CONCAT_WS(' ', workshop_availability.workshop_date, workshop_availability.from_time), '%m/%d/%Y %H:%i:%s') asc");
        $result = $this->db->get();
        return $data = $result->result_array();
    }

    public function get_list() {

        $this->db->select('
            workshop.id, workshop.title, workshop.slug, workshop.number_of_attendees, workshop.price_per_attendee,
            workshop_availability.workshop_date, workshop_availability.from_time, workshop_availability.end_time,
            workshop_description.descrition,
            workshop_equipments.equipment_text, workshop_equipments.equipment_image,
            workshop_locations.address,
            // workshop_locations.postal_code, workshop_locations.town,
            workshop_locations.latitude, workshop_locations.longitude,
            workshop_reviews.rating, workshop_reviews.name, workshop_reviews.email, workshop_reviews.description,
            workshop_tags.tags_id,
            workshop_images.image_name, workshop_images.image_path,
        ');
        $this->db->from('workshop');
        $this->db->join('workshop_availability', 'workshop_availability.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_description', 'workshop_description.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_equipments', 'workshop_equipments.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_locations', 'workshop_locations.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_reviews', 'workshop_reviews.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_tags', 'workshop_tags.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_images', 'workshop_images.workshop_id = workshop.id', 'left');
        $result = $this->db->get();
        return $data = $result->result_array();
    }

    /**
    * Gets list of workshops which are related to a professional
    * Created by: Pancham Bansal
    **/
    public function get_workshop_list_of_professional($users_id = null, $wptitle = null) {

        if(empty($users_id)){
            return FALSE;
        }

        $this->db->select('
            workshop.id, workshop.title, workshop.number_of_attendees as max_attendees, workshop.price_per_attendee, workshop.status,
            workshop_availability.id as workahop_avail_id, workshop_availability.workshop_date, workshop_availability.from_time, workshop_availability.end_time,
            workshop_description.descrition,
            workshop_equipments.equipment_text, workshop_equipments.equipment_image,
            workshop_locations.address,
            // workshop_locations.postal_code, workshop_locations.town, workshop_locations.latitude, workshop_locations.longitude,
            AVG(workshop_reviews.rating) as avg_rating, Count(DISTINCT(workshop_reviews.id)) as total_reviews, workshop_reviews.name, workshop_reviews.email, workshop_reviews.description,
            workshop_tags.tags_id,
            workshop_images.image_name, workshop_images.image_path, Sum(order_items.number_of_attendees) as attendees_count
        ');
        $this->db->from('workshop');

        if( !empty($wptitle)){
            $this->db->like('workshop.title', $wptitle);
        }

        $this->db->where('workshop.users_id', $users_id);
        //$this->db->where('order_items.payment_status!=', PENDING);

        $this->db->join('workshop_availability', 'workshop_availability.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_description', 'workshop_description.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_equipments', 'workshop_equipments.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_locations', 'workshop_locations.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_reviews', 'workshop_reviews.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_tags', 'workshop_tags.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_images', 'workshop_images.workshop_id = workshop.id', 'left');
        $this->db->join('order_items', 'order_items.workshop_id = workshop.id', 'left');

        $this->db->group_by('workshop.id');
        $this->db->order_by("workshop.created_at", "desc");



        $query = $this->db->get();

        $data = array();
        if($query->num_rows()>0){
            foreach ($query->result_array() as $row) {
                $wid = $row['id'];
                $row['attendees_count'] = 0;
                $joined_attendee = $this->get_number_of_attendees_wrt_workshop_order_items($wid);
                if($joined_attendee){
                    $row['attendees_count'] = $joined_attendee;
                }
                $data[]=$row;
            }
        }
        return $data;


    }


    /**
    * Gets count of offers
    * Created by: Pancham Bansal
    **/
    public function get_offers_count() {

        $filter_theme   = $this->input->post('filter_theme');//common tags
        $filter_tags    = $this->input->post('filter_tags'); //currently tags

        $this->db->select('
            count(offers.id) as offers_count
        ');
        $this->db->from('offers');
        $this->db->where('offers.status',1);
        if(!empty($filter_theme)){
            $this->db->where('offer_tags.tags_id', $filter_theme);
        }
        if(!empty($filter_tags)){
            $this->db->where('offer_tags.tags_id', $filter_tags);
        }

        $this->db->join('offer_tags', 'offer_tags.offers_id = offers.id', 'left');
        $this->db->group_by('offers.id');

        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            return $row['offers_count'];
        }
    }


    /**
    * Gets list of advertised workshops
    * Created by: Pancham Bansal
    **/
    public function get_advertised_workshops() {

        $where_array2 = array();

        $search_title2   = $this->input->post('search_title2');
        if(!empty($search_title2)){
            $where_array2 += array(
                'workshop.title LIKE' => '%'.$search_title2.'%'
            );
        }
        $search_date2    = $this->input->post('search_date2');
        if(!empty($search_date2)){
            $where_array2 += array(
                'STR_TO_DATE(workshop_availability.workshop_date, "%m/%d/%Y") =' => $search_date2
            );
        }

        $search_from_time2 = $this->input->post('search_from_time2');
        if(!empty($search_from_time2)){
            $where_array2 += array(
                'workshop_availability.from_time >=' => $search_from_time2
            );
        }
        $search_to_time2 = $this->input->post('search_to_time2');
        if(!empty($search_to_time2)){
            $where_array2 += array(
                'workshop_availability.end_time <=' => $search_to_time2
            );
        }

        $filter_theme2   = $this->input->post('filter_theme2');//common tags
        if(!empty($filter_theme2)){
            $where_array2 += array(
                'workshop_tags.tags_id' => $filter_theme2
            );
        }
        $filter_tags2    = $this->input->post('filter_tags2'); //currently tags
        if(!empty($filter_tags2)){
            $where_array2 += array(
                'workshop_tags.tags_id' => $filter_tags2
            );
        }
        $filter_from_price2 = $this->input->post('filter_from_price2');
        $filter_to_price2 = $this->input->post('filter_to_price2');
        if((!empty($filter_from_price2) || $filter_from_price2>=0) && !empty($filter_to_price2)){
            $where_array2 += array(
                'workshop.price_per_attendee>=' => $filter_from_price2,
                'workshop.price_per_attendee<=' => $filter_to_price2
            );
        }

        if(!empty($where_array2)){
            $this->db->where($where_array2);
        }

        $this->db->select('
            workshop.id, workshop.title, workshop.slug, workshop.price_per_attendee, workshop.status, workshop.is_suspended,
            workshop_availability.workshop_date, workshop_availability.from_time, workshop_availability.end_time,
            workshop_description.descrition,
            AVG(workshop_reviews.rating) as avg_rating, Count(DISTINCT(workshop_reviews.id)) as total_reviews, workshop_reviews.name as reviewer_name, user_details.fname, user_details.lname, user_details.user_image,
            GROUP_CONCAT(DISTINCT(CONVERT(workshop_tags.tags_id, CHAR(60))) SEPARATOR ",") as workshop_tag_ids,
            workshop_images.image_name as workshop_image, workshop_images.image_path
        ');
        $this->db->from('workshop');
        $this->db->where('workshop.status',1);
        $this->db->where('workshop.workshop_type',1); //1 for advertised workshop and 0 for non-advertised workshop
        if(empty($search_date2)){
            $this->db->where('STR_TO_DATE(CONCAT_WS(" ", workshop_availability.workshop_date, workshop_availability.from_time), "%m/%d/%Y %H:%i:%s") >= NOW()');
        }
        $this->db->join('workshop_availability', 'workshop_availability.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_description', 'workshop_description.workshop_id = workshop.id', 'left');
        $this->db->join('user_details', 'user_details.users_id = workshop.users_id', 'left');
        $this->db->join('workshop_reviews', 'workshop_reviews.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_tags', 'workshop_tags.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_images', 'workshop_images.workshop_id = workshop.id', 'left');

        $this->db->group_by('workshop.id');

        $this->db->order_by("STR_TO_DATE(CONCAT_WS(' ', workshop_availability.workshop_date, workshop_availability.from_time), '%m/%d/%Y %H:%i:%s') asc");

        $query2 = $this->db->get();

        return $query2;

    }


    /**
    * Gets list of offers wrt tags using search and filter
    * Created by: Pancham Bansal
    **/
    public function get_tagwise_offers($offset) {

        $result_per_search_offer_limit = 1;

        $where_array1 = array();

        $filter_theme   = $this->input->post('filter_theme');//common tags
        $filter_tags    = $this->input->post('filter_tags'); //currently tags

        $this->db->select('
            offers.id, offers.title, offers.image, offers.date_from, offers.date_to, offers.short_desc, offers.description, offers.status, offer_tags.tags_id
        ');
        $this->db->from('offers');
        $this->db->where('offers.status',1);
        if(!empty($filter_theme)){
            $this->db->where('offer_tags.tags_id', $filter_theme);
        }
        if(!empty($filter_tags)){
            $this->db->where('offer_tags.tags_id', $filter_tags);
        }

        $this->db->join('offer_tags', 'offer_tags.offers_id = offers.id', 'left');
        $this->db->group_by('offers.id');
        $this->db->limit($result_per_search_offer_limit, $offset);
        $query = $this->db->get();
        return $query;

    }

    /**
    * Gets list of workshops using search and filter
    * Created by: Pancham Bansal
    **/
    public function get_workshop_list_by_search() {

        $result_per_search_limit = 8;
        $result_data['offer_1'] = array();
        $result_data['offer_2'] = array();
        $result_data['workshops'] = array();
        $result_data['advertised_workshops'] = array();
        $result_data['has_next_record'] = 0;

        $offset = $this->input->post('offset');
        $offer_count = $this->input->post('offer_count');

        if($offset==0){
            //Get count of offers
            $number_of_offers = $this->get_offers_count();

            //Get list of advertised workshop
            $query_advertised_workshop = $this->get_advertised_workshops();
            $adv_workshop_count = $query_advertised_workshop->num_rows();
            if($adv_workshop_count>0){
                $result_data['advertised_workshops'] = $query_advertised_workshop->result_array();
                $result_per_search_limit = $result_per_search_limit - $adv_workshop_count;
            }

            switch ($offer_count) {
                case '0':
                    if($number_of_offers==1){
                        // get tagwise first offer
                        $offer_offset = 0;
                        $offer_1_list_query = $this->get_tagwise_offers($offer_offset);
                        $offer_1_count = $offer_1_list_query->num_rows();
                        $offer_1_list_data = $offer_1_list_query->result_array();
                        $result_data['offer_1'] = $offer_1_list_data;

                    }
                    else if($number_of_offers > 1){

                        // get tagwise first offer
                        $offer_offset = 0;
                        $offer_1_list_query = $this->get_tagwise_offers($offer_offset);
                        $offer_1_count = $offer_1_list_query->num_rows();
                        $offer_1_list_data = $offer_1_list_query->result_array();
                        $result_data['offer_1'] = $offer_1_list_data;

                        // get tagwise second offer
                        $offer_offset = 1;
                        $offer_2_list_query = $this->get_tagwise_offers($offer_offset);
                        $offer_2_count = $offer_2_list_query->num_rows();
                        $offer_2_list_data = $offer_2_list_query->result_array();
                        $result_data['offer_2'] = $offer_2_list_data;
                    }
                    break;

                case '1':
                    if($number_of_offers>1){

                        // get tagwise second offer
                        $offer_offset = 1;
                        $offer_2_list_query = $this->get_tagwise_offers($offer_offset);
                        $offer_2_count = $offer_2_list_query->num_rows();
                        $offer_2_list_data = $offer_2_list_query->result_array();
                        $result_data['offer_2'] = $offer_2_list_data;
                    }
                    break;

            }
        }

        $result_offset = $offset*$result_per_search_limit;

        $where_array = array();

        $search_title   = $this->input->post('search_title');
        if(!empty($search_title)){
            $where_array += array(
                'workshop.title LIKE' => '%'.$search_title.'%'
            );
        }
        $search_date    = $this->input->post('search_date');
        if(!empty($search_date)){
            $search_date = date('Y-m-d', strtotime( $search_date ) );
            $where_array += array(
                'STR_TO_DATE(workshop_availability.workshop_date, "%m/%d/%Y") =' => $search_date
            );
        }

        $search_from_time = $this->input->post('search_from_time');
        $search_to_time = $this->input->post('search_to_time');

        if( !empty($search_from_time) && !empty($search_to_time) ){
            $where_array += array(
                'workshop_availability.from_time >=' => $search_from_time.':00:00',
                'workshop_availability.end_time <=' => $search_to_time.':00:00'
            );
        }
        else if(!empty($search_from_time)){
            $where_array += array(
                'workshop_availability.from_time >=' => $search_from_time.':00:00'
            );
        }
        else if(!empty($search_to_time)){
            $where_array += array(
                'workshop_availability.end_time <=' => $search_to_time.':00:00'
            );
        }

        $filter_theme   = $this->input->post('filter_theme');//common tags
        $filter_tags    = $this->input->post('filter_tags'); //currently tags
        if( !empty($filter_theme) && !empty($filter_tags) ){

            $this->db->group_start();
            $this->db->where_in('workshop_tags.tags_id', explode(",", $filter_theme));
            $this->db->or_where_in('workshop_currently_tags.tags_id', explode(",", $filter_tags));
            $this->db->group_end();
        }
        else if( !empty($filter_theme) ){
            $this->db->where_in('workshop_tags.tags_id', explode(",", $filter_theme));
        }
        else if( !empty($filter_tags) ){
            $this->db->where_in('workshop_currently_tags.tags_id', explode(",", $filter_tags));
        }

        $filter_from_price = $this->input->post('filter_from_price');
        $filter_to_price = $this->input->post('filter_to_price');
        if((!empty($filter_from_price) || $filter_from_price>=0) && !empty($filter_to_price)){
            $where_array += array(
                'workshop.price_per_attendee_including_tax >=' => $filter_from_price,
                'workshop.price_per_attendee_including_tax <=' => $filter_to_price
            );
        }

        if(!empty($where_array)){
            $this->db->where($where_array);
        }

        $sort_by_price   = $this->input->post('sort_by_price');

        $this->db->select('
            workshop.id, workshop.title, workshop.slug, workshop.price_per_attendee, workshop.status, workshop.is_suspended,
            workshop_availability.id as workshop_avail_id, workshop_availability.workshop_date, workshop_availability.from_time, workshop_availability.end_time,
            workshop_description.descrition,
            AVG(workshop_reviews.rating) as avg_rating, Count(DISTINCT(workshop_reviews.id)) as total_reviews, workshop_reviews.name as reviewer_name, user_details.fname, user_details.lname, user_details.user_image,
            GROUP_CONCAT(DISTINCT(CONVERT(workshop_tags.tags_id, CHAR(60))) SEPARATOR ",") as workshop_tag_ids,
            GROUP_CONCAT(DISTINCT(CONVERT(workshop_currently_tags.tags_id, CHAR(60))) SEPARATOR ",") as workshop_currently_tag_ids,
            workshop_images.image_name as workshop_image, workshop_images.image_path, users.is_suspended
        ');
        $this->db->from('workshop');
        $this->db->where('workshop.status',1);
        $this->db->where('workshop.workshop_type', 0);
        $this->db->where('users.is_suspended', 0);
        /*if( !empty($search_title) ){
            $this->db->or_like('workshop_description.descrition', $search_title);
        }*/
        if(empty($search_date)){
            // $this->db->where('STR_TO_DATE(workshop_availability.workshop_date, "%m/%d/%Y") >= CURDATE()');
            $this->db->where('STR_TO_DATE(CONCAT_WS(" ", workshop_availability.workshop_date, workshop_availability.from_time), "%m/%d/%Y %H:%i:%s") >= NOW()');
        }
        $this->db->join('workshop_availability', 'workshop_availability.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_description', 'workshop_description.workshop_id = workshop.id', 'left');
        $this->db->join('users', 'users.id = workshop.users_id', 'left');
        $this->db->join('user_details', 'user_details.users_id = workshop.users_id', 'left');
        $this->db->join('workshop_reviews', 'workshop_reviews.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_tags', 'workshop_tags.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_currently_tags', 'workshop_currently_tags.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_images', 'workshop_images.workshop_id = workshop.id', 'left');

        $this->db->group_by('workshop.id');

        if($sort_by_price == 1)
        {
            $this->db->order_by("workshop.price_per_attendee", "asc");
        }
        else if($sort_by_price == 2)
        {
            $this->db->order_by("workshop.price_per_attendee", "desc");
        }
        else
        {
            // $this->db->order_by("STR_TO_DATE(workshop_availability.workshop_date, '%m/%d/%Y') asc");
            $this->db->order_by("STR_TO_DATE(CONCAT_WS(' ', workshop_availability.workshop_date, workshop_availability.from_time), '%m/%d/%Y %H:%i:%s') asc");
        }

        $this->db->limit($result_per_search_limit, $result_offset);
        $query1 = $this->db->get();

        $result_data['workshops'] = $query1->result_array();

        // has_next_record
        $has_next_record = $this->has_next_record_in_search();
        if(empty($has_next_record)){
            $has_next_record = 0;
        }
        $result_data['has_next_record'] = $has_next_record;
        // has_next_record close

        return $result_data;

    }

    /**
    * Gets list of workshops using search and filter
    * Created by: Pancham Bansal
    **/
    public function has_next_record_in_search() {

        $result_per_search_limit = 8;

        $offset = $this->input->post('offset');
        $next_offset = $offset+1;
        $next_offset = $next_offset*$result_per_search_limit;
        $offset = $offset*$result_per_search_limit;

        $where_array = array();

        $search_title   = $this->input->post('search_title');
        if(!empty($search_title)){
            $where_array += array(
                'workshop.title LIKE' => '%'.$search_title.'%'
            );
        }
        $search_date    = $this->input->post('search_date');
        if(!empty($search_date)){
            $where_array += array(
                'STR_TO_DATE(workshop_availability.workshop_date, "%m/%d/%Y") =' => $search_date
            );
        }

        $search_from_time = $this->input->post('search_from_time');
        if(!empty($search_from_time)){
            $where_array += array(
                'workshop_availability.from_time >=' => $search_from_time
            );
        }
        $search_to_time = $this->input->post('search_to_time');
        if(!empty($search_to_time)){
            $where_array += array(
                'workshop_availability.end_time <=' => $search_to_time
            );
        }

        $filter_theme   = $this->input->post('filter_theme');//common tags
        $filter_tags    = $this->input->post('filter_tags'); //currently tags
        if( !empty($filter_theme) && !empty($filter_tags) ){

            $this->db->group_start();
            $this->db->where_in('workshop_tags.tags_id', explode(",", $filter_theme));
            $this->db->or_where_in('workshop_currently_tags.tags_id', explode(",", $filter_tags));
            $this->db->group_end();
        }
        else if( !empty($filter_theme) ){
            $this->db->where_in('workshop_tags.tags_id', explode(",", $filter_theme));
        }
        else if( !empty($filter_tags) ){
            $this->db->where_in('workshop_currently_tags.tags_id', explode(",", $filter_tags));
        }

        $filter_from_price = $this->input->post('filter_from_price');
        $filter_to_price = $this->input->post('filter_to_price');
        if((!empty($filter_from_price) || $filter_from_price>=0) && !empty($filter_to_price)){
            $where_array += array(
                'workshop.price_per_attendee >=' => $filter_from_price,
                'workshop.price_per_attendee <=' => $filter_to_price
            );
        }

        if(!empty($where_array)){
            $this->db->where($where_array);
        }

        $this->db->select('
            workshop.id, workshop.title, workshop.price_per_attendee, workshop.status, workshop.is_suspended,
            workshop_availability.workshop_date, workshop_availability.from_time, workshop_availability.end_time,
            workshop_description.descrition,
            AVG(workshop_reviews.rating) as avg_rating, Count(DISTINCT(workshop_reviews.id)) as total_reviews, workshop_reviews.name as reviewer_name, user_details.fname, user_details.lname, user_details.user_image,
            GROUP_CONCAT(DISTINCT(CONVERT(workshop_tags.tags_id, CHAR(60))) SEPARATOR ",") as workshop_tag_ids,
            workshop_images.image_name as workshop_image, workshop_images.image_path
        ');
        $this->db->from('workshop');
        $this->db->where('workshop.status',1);
        /*if( !empty($search_title) ){
            $this->db->or_like('workshop_description.descrition', $search_title);
        }*/
        if(empty($search_date)){
            $this->db->where('STR_TO_DATE(CONCAT_WS(" ", workshop_availability.workshop_date, workshop_availability.from_time), "%m/%d/%Y %H:%i:%s") >= NOW()');
        }
        $this->db->join('workshop_availability', 'workshop_availability.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_description', 'workshop_description.workshop_id = workshop.id', 'left');
        $this->db->join('user_details', 'user_details.users_id = workshop.users_id', 'left');
        $this->db->join('workshop_reviews', 'workshop_reviews.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_tags', 'workshop_tags.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_currently_tags', 'workshop_currently_tags.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_images', 'workshop_images.workshop_id = workshop.id', 'left');

        $this->db->group_by('workshop.id');

        $this->db->order_by("STR_TO_DATE(CONCAT_WS(' ', workshop_availability.workshop_date, workshop_availability.from_time), '%m/%d/%Y %H:%i:%s') asc");

        $this->db->limit($result_per_search_limit, $next_offset);
        $query = $this->db->get();
        return $has_next_record = $query->num_rows();

    }

    /**
    * Gets list of all tags used in filter scrollbar
    * Created by: Pancham Bansal
    **/
    public function get_filter_tags() {

        $future_workshop_ids = [];
        $this->db->select('workshop_availability.workshop_id');
        $this->db->from('workshop_availability');
        $this->db->join('workshop', 'workshop.id = workshop_availability.workshop_id', 'left');
        $this->db->join('users', 'users.id = workshop.users_id', 'left');
        $this->db->where('STR_TO_DATE(CONCAT_WS(" ", workshop_availability.workshop_date, workshop_availability.from_time), "%m/%d/%Y %H:%i:%s") >= NOW()');
        $this->db->where('workshop.status', 1);
        $this->db->where('workshop.is_suspended', 0);
        $this->db->where('users.is_suspended', 0);

        $query_workshop = $this->db->get();
        foreach ($query_workshop->result_array() as $row) {
            $future_workshop_ids[] = $row['workshop_id'];
        }


        $this->db->select('tags.*, COUNT(workshop_tags.tags_id) as w_tag_count, COUNT(offer_tags.tags_id) as o_tag_count, COUNT(workshop_currently_tags.tags_id) as w_currently_tag_count');
        $this->db->from('tags');
        $this->db->where('tags.is_active', 1);
        $this->db->where_in('workshop_tags.workshop_id', $future_workshop_ids);
        $this->db->or_where_in('workshop_currently_tags.workshop_id', $future_workshop_ids);
        $this->db->join('workshop_tags', 'workshop_tags.tags_id = tags.id', 'left');
        $this->db->join('offer_tags', 'offer_tags.tags_id = tags.id', 'left');
        $this->db->join('workshop_currently_tags', 'workshop_currently_tags.tags_id = tags.id', 'left');

        $this->db->group_by('tags.id');
        $this->db->order_by('tags.tag_name', 'asc');

        $query = $this->db->get();
        // return $this->db->last_query();
        $data = $query->result_array();

        return $data;
    }
    /**
    *   delete workshop image
    *   @param : int $workshop_image_id
    *   Created by: Pancham Bansal
    *
    **/
    public function delete_workshop_image($workshop_image_id = null){

        if( empty( $workshop_image_id ) ){
            return FALSE;
        }

        $this->table = "workshop_images";
        $this->db->where('id', $workshop_image_id);
        return $this->db->delete($this->table);

    }

    public function workshop($id = NULL){
        if(empty($id)){
            return FALSE;
        }

        $this->table = 'workshop';
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    /*
    * (SR) - To get all workshop dates and time slots
    * @param: int $id (workshop id), bool $currently_workshop
    */
    public function workshop_availability($id = NULL, $currently_workshop = FALSE){
        $this->table = 'workshop_availability';
        $this->db->where('workshop_id', $id);
        if(!empty($currently_workshop))
            $this->db->where('STR_TO_DATE(workshop_date, "%m/%d/%Y") >= CURDATE()');
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    /*
    * Add wall image for workshop
    * @param: int $id (workshop id)
    */
    public function workshop_wallimage($id = NULL){

        $this->db->select('workshop_wall_images.wall_images_id, wall_images.image_name as wallimage')
         ->from('workshop_wall_images')
         ->join('wall_images', 'workshop_wall_images.wall_images_id = wall_images.id');

        $this->db->where("workshop_wall_images.workshop_id", $id);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function workshop_campaign($id = NULL) {
        $this->table = 'workshop_campaign';
        $this->db->where('workshop_id', $id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function workshop_description($id = NULL){
        $this->table = 'workshop_description';
        $this->db->where('workshop_id', $id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function workshop_images($id = NULL){
        $this->table = 'workshop_images';
        $this->db->where('workshop_id', $id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function workshop_equipments($id = NULL){
        $this->table = 'workshop_equipments';
        $this->db->where('workshop_id', $id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function workshop_location($id = NULL){
        $this->table = 'workshop_locations';
        $this->db->where('workshop_id', $id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    /*
    * (SR) - To get professional detail of that workshop_id
    * @param: int $id (it is workshop id)
    */
    public function workshop_professional_detail($id = NULL){
        if(empty($id)){
            return FALSE;
        }

        $user_id = $this->get_professional_id_wrt_workshop($id); // gets professional id from workshop table
        if(!empty($user_id)){
            $this->db->select('
                user_details.users_id, user_details.fname, user_details.lname, user_details.email, user_details.phone, user_details.user_image, user_companies.title as company_title, user_companies.company as company_name, user_companies.description as company_description
            ');
            $this->db->from('user_details');
            $this->db->where('user_details.users_id', $user_id);
            $this->db->join('user_companies', 'user_companies.users_id = user_details.users_id', 'left');
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return FALSE;

    }

    /*
    * (SR) - To get professional user id w.r.t. workshop
    * @param: int $id (it is workshop id)
    */

    public function get_professional_id_wrt_workshop($id = null){
        if(empty($id)){
            return FALSE;
        }

        $this->table = 'workshop';
        $this->db->where('id',$id);
        $query = $this->db->get($this->table);
        $last = $this->db->last_query();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                return $row['users_id'];
            }
        }
        return FALSE;

    }

    /*
    * (SR) - To get 2 latest reviews given to workshop
    * @param: int $id (it is workshop id)
    */
    public function workshop_reviews( $id = null ){
        if(empty($id)){
            return FALSE;
        }

        $this->table = 'workshop_reviews';
        $this->db->where('workshop_id', $id);
        $this->db->group_by('id');
        $this->db->order_by('created_at', 'desc');
        $this->db->limit(2);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    /*
    * (SR) - To get global average rating and total reviews given to workshop
    * @param: int $id (it is workshop id)
    */
    public function workshop_global_reviews( $id = null ){
        if(empty($id)){
            return FALSE;
        }

        $this->db->select('
            AVG(workshop_reviews.rating) as avg_rating, Count(workshop_reviews.rating) as total_reviews
        ');
        $this->table = 'workshop_reviews';
        $this->db->where('workshop_id', $id);

        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    /*
    * (SR) - To get workshop's characteristics
    * @param: int $id (it is workshop id)
    */
    public function workshop_characteristics( $id = null ){
        if(empty($id)){
            return FALSE;
        }

        $this->db->select('
            workshop_characteristics.id, workshop_characteristics.characteristics_id, characteristics.name, characteristics.icon
        ');
        $this->table = 'workshop_characteristics';
        $this->db->where('workshop_characteristics.workshop_id', $id);
        $this->db->join('characteristics','characteristics.id = workshop_characteristics.characteristics_id', 'left');
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function workshop_all_tags($id = NULL){
        if( $id==null){
            return FALSE;
        }

        $this->table = "workshop_tags";
        $this->db->where('workshop_id', $id);

        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $workshop_tag_ids[] = $row->tags_id;
            }
            $this->table = "tags";
            $this->db->where_in('id', $workshop_tag_ids);
            //$this->db->where('is_active', 1);
            $query = $this->db->get($this->table);
            return $query->result_array();
        }
        return FALSE;
    }

    public function workshop_tags($id = NULL){
        if( $id==null){
            return FALSE;
        }

        $this->table = "workshop_tags";
        $this->db->where('workshop_id', $id);

        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $workshop_tag_ids[] = $row->tags_id;
            }
            $this->table = "tags";
            $this->db->where_in('id', $workshop_tag_ids);
            $this->db->where('is_active', 1);
            $query = $this->db->get($this->table);
            return $query->result_array();
        }
        return FALSE;
    }

    public function all_characteristics(){
        $this->table = 'characteristics';
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }



    public function list_common_tags() {
        $this->table = "tags";
        $this->db->where('tag_category', 'common');
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }


    public function list_wall_images() {
        $this->table = "wall_images";
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }



    /*
    * Changes workshop status from active to inactive and vice-versa
    * Created by: Pancham Bansal
    */
    public function update_workshop_status($id = NULL) {
        $this->db->where('id', $id);
        $query_chk = $this->db->get($this->table);
        $ret = $query_chk->row();
        if( $ret->status == 0 ) {
            $data = array('status' => 1);
        } else {
            $data = array('status' => 0);
        }
        $this->db->where('id', $id);
        if($this->db->update($this->table, $data)) {
            $this->db->where('id', $id);
            $query_chk = $this->db->get($this->table);
            $ret = $query_chk->row();

            return TRUE;
        }
        return FALSE;
    }

    /*
    * (SR) - To get how many number of attendees join to a workshop
    * @param: int $id (it is workshop id)
    */
    public function get_number_of_attendees_wrt_workshop( $id = null ){
        if(empty($id)){
            return FALSE;
        }

        $this->db->select('Sum(order_items.number_of_attendees) as attendees_count');
        $this->db->from('order_items');
        $this->db->where('workshop_id', $id);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                return $row->attendees_count;
            }
        }
        return FALSE;

    }

    public function get_number_of_attendees_wrt_workshop_order_items( $id = null ){
        if(empty($id)){
            return FALSE;
        }

        $this->db->select('Sum(order_items.number_of_attendees) as attendees_count');
        $this->db->select('
            sum( case when order_items.is_workshop_manual = 0 AND order_items.payment_status="AUTHORIZED" then order_items.number_of_attendees else 0 end) as attendees_count,
            sum( case when order_items.is_workshop_manual = 1 AND order_items.payment_status="AUTHORIZED_VALIDATED" then order_items.number_of_attendees else 0 end) as manual_attendees_count,
        ');
        $this->db->from('order_items');
        $this->db->where('workshop_id', $id);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                return $row->attendees_count + $row->manual_attendees_count;
            }
        }
        return FALSE;

    }

    /*
    * Delete workshop
    * Created by: Pancham Bansal
    */
    public function delete_workshop($id = NULL) {

        if( empty($id) ){
            return FALSE;
        }

        $this->soft_deletes = TRUE;

        $this->db->delete('workshop_availability', array('workshop_id' => $id));
        $this->db->delete('workshop_description', array('workshop_id' => $id));
        $this->db->delete('workshop_equipments', array('workshop_id' => $id));
        $this->db->delete('workshop_locations', array('workshop_id' => $id));
        $this->db->delete('workshop_reviews', array('workshop_id' => $id));
        $this->db->delete('workshop_tags', array('workshop_id' => $id));
        $this->db->delete('workshop_images', array('workshop_id' => $id));
        $this->db->delete('workshop_characteristics', array('workshop_id' => $id));
        $this->db->delete('workshop_currently_tags', array('workshop_id' => $id));
        $this->db->delete('workshop_campaign', array('workshop_id' => $id));
        $this->db->delete('workshop_wall_images', array('workshop_id' => $id));
        $this->db->delete('workshop_recurrence', array('workshop_id' => $id));
        $this->db->delete('workshop', array('id' => $id));
        return $this->db->last_query();
        die;
        return TRUE;

    }

    /*
    * Duplicate the workshop
    * Created by: Pancham Bansal
    */
    public function duplicate_workshop($id = NULL) {

        if ( !$this->is_workshop_exist($id) )
            return FALSE;

        $workshop_id = $this->duplicate_primary_record("workshop","id",$id);
        if($workshop_id){
            $this->duplicate_foreign_record("workshop_availability","workshop_id",$id, $workshop_id);
            $this->duplicate_foreign_record("workshop_description","workshop_id",$id, $workshop_id);
            $this->duplicate_foreign_record("workshop_equipments","workshop_id",$id, $workshop_id);
            $this->duplicate_foreign_record("workshop_locations","workshop_id",$id, $workshop_id);
            $this->duplicate_foreign_record("workshop_tags","workshop_id",$id, $workshop_id);
            $this->duplicate_foreign_record("workshop_images","workshop_id",$id, $workshop_id);
            $this->duplicate_foreign_record("workshop_characteristics","workshop_id",$id, $workshop_id);

            $this->db->where('id', $workshop_id);
            $this->db->update('workshop', array('status' => 0));

            return TRUE;
        }

        return FALSE;

    }

    /*
    * General duplicate primary record function
    * Created by: Pancham Bansal
    */
    public function duplicate_primary_record ($table, $primary_key_field, $primary_key_val)
    {
        /* generate the select query */
        $this->db->where($primary_key_field, $primary_key_val);
        $query = $this->db->get($table);

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row){
               foreach($row as $key => $val){
                  if($key != $primary_key_field){
                  /* $this->db->set can be used instead of passing a data array directly to the insert or update functions */
                  $this->db->set($key, $val);
                  }//endif
               }//endforeach
            }//endforeach

            /* insert the new record into table*/
            $this->db->insert($table);
            return $this->db->insert_id();
        }

        return FALSE;
    }

    public function add_new_tag_db($tagname = NULL){
        if( !empty($tagname)) {
            $this->db->where('tag_name', $tagname);
            $q = $this->db->get('tags');
            if ($q->num_rows() > 0) {
                $data = $q->result_array();
                return $data[0]['id'];
            } else {
                $data_tags = array(
                    'tag_name' => $tagname,
                    'tag_category' => 'workshop',
                    'is_active' => 0,
                );
                $this->db->insert("tags", $data_tags);
                return $last_id = $this->db->insert_id();
            }
        }
    }

    /*
    * General duplicate foreign record function
    * Created by: Pancham Bansal
    */
    public function duplicate_foreign_record ($table, $foreign_key_field, $foreign_key_val, $new_foreign_key_val)
    {
        $data=array();
        /* generate the select query */
        $this->db->where($foreign_key_field, $foreign_key_val);
        $query = $this->db->get($table);

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row){
                foreach($row as $key => $val){
                    if($key == $foreign_key_field){

                        /* $this->db->set can be used instead of passing a data array directly to the insert or update functions */
                        $data[$key]=$new_foreign_key_val;
                    }//endif
                    else if($key == "created_at"){
                    }//endif
                    else if($key != "id"){
                        $data[$key]=$val;
                    }
                }//endforeach
                /* insert the new record into table*/
                $query = $this->db->insert($table, $data);
            }//endforeach


            return $this->db->insert_id();
        }
        return FALSE;
    }

    //get and return product rows
    public function getRows($id = ''){
        $this->db->select('*');
        $this->db->from('workshop');
        if($id){
            $this->db->where('id',$id);
            $query = $this->db->get();
            $result = $query->row_array();
        }else{
            $this->db->order_by('title','asc');
            $query = $this->db->get();
            $result = $query->result_array();
        }
        return !empty($result) ? $result : FALSE;
    }

    //insert transaction data
    public function insertTransaction($data = array()){

        $insert = $this->db->insert('payments',$data);
        if($insert)
            return $this->db->insert_id();
        else
            return FALSE;
    }

    public function add_to_cart(){

        $wid = $this->input->post('workshop_id');
        $uid = $this->input->post('user_id');
        $workshop_slot = explode("-", $this->input->post('selected_timeslot[]'));
        $m = 00; $s = 00;
        $from_time = (int)$workshop_slot[0].":".$m.":".$s;
        $end_time  = (int)$workshop_slot[1].":".$m.":".$s;
        $date = $this->input->post('book_selected_dates');

        $this->db->where('selected_date', $date);
        $this->db->where('selected_fromtime', $from_time);
        $this->db->where('selected_endtime', $end_time);
        $this->db->where('workshop_id', $wid);
        $this->db->where('users_id', $uid);

        $query = $this->db->get("cart");

        if ($query->num_rows() > 0)
        {
            return FALSE;
        }
        else
        {
            $ws_price = ($this->input->post('hidden_price_per_attendee') * 10) / 12;
            $ws_price = round($ws_price, 2);
            $rate = $ws_price * $this->input->post('numberofselected_attendees');
            $data_array = array(
                'workshop_title'             => $this->input->post('workshop_title'),
                'workshop_image'             => $this->input->post('workshop_image'),
                'workshop_price'             => $ws_price,
                'total_attendees_selected'   => $this->input->post('numberofselected_attendees'),
                'selected_date'              => $this->input->post('book_selected_dates'),
                'selected_fromtime'          => $from_time,
                'selected_endtime'           => $end_time,
                'total_price'                => $this->input->post('ttl_price1'),
                'workshop_price_without_tax' => $rate,
                'workshop_id'                => $this->input->post('workshop_id'),
                'users_id'                   => $this->input->post('user_id'),
            );
            $insert = $this->db->insert('cart',$data_array);
            return $insert ? TRUE : FALSE;
        }
    }

    /**
    *   (SR) - save workshop review given by attendee
    *   @param : int $users_id
    *
    **/
    function save_review( $from_users_id = null ) {

        $this->load->helper('string');
        $data  = array();

        $workshop_id = trim($this->input->post('workshop_id'));
        if ( empty($workshop_id) ) {
            return FALSE;
        }
        $data += array(
            'workshop_id' => $workshop_id,
        );

        $rating = trim($this->input->post('rating'));
        if ( empty($rating) ) {
            return FALSE;
        }
        $data += array(
            'rating' => $rating,
        );

        $description = trim($this->input->post('description'));
        if ( empty($description) ) {
            return FALSE;
        }
        $data += array(
            'description' => $description,
        );

        $name = trim($this->input->post('name'));
        if ( empty($name) ) {
            return FALSE;
        }
        $data += array(
            'name' => $name,
        );

        $email = trim($this->input->post('email'));
        if ( !empty($email) ) {
            $data += array(
                'email' => $email,
            );
        }


        if ( empty($data) )
            return FALSE;

        if ( !empty($data) ) {
            $this->table = 'workshop_reviews';
            if ( $this->db->insert( $this->table, $data  ) ) {
                return $this->db->insert_id();
            }
        }

        return FALSE;
    }


    /**
    * Gets latest workshops which are related to a professional
    * @param: int $users_id (it is professional id)
    * Created by: Pancham Bansal
    **/
    public function get_latest_workshops($users_id = null, $limit = null) {

        if(empty($users_id)){
            return FALSE;
        }

        $this->db->select('
            workshop.id as workshop_id, workshop.title, workshop.status, workshop.is_suspended,
            workshop_availability.workshop_date, workshop_availability.from_time, workshop_availability.end_time,
            workshop_description.descrition,
            GROUP_CONCAT(
                DISTINCT(
                    CONVERT(
                        COALESCE (
                            workshop_images.image_name, "", workshop_images.image_name
                        ), CHAR(120)
                    )
                ) SEPARATOR ","
            ) as workshop_image_names
        ');
        $this->db->from('workshop');
        $this->db->where('workshop.users_id', $users_id);
        $this->db->where('workshop.status', 1);
        $this->db->where('STR_TO_DATE(CONCAT_WS(" ", workshop_availability.workshop_date, workshop_availability.from_time), "%m/%d/%Y %H:%i:%s") >= NOW()');

        $this->db->join('workshop_availability', 'workshop_availability.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_description', 'workshop_description.workshop_id = workshop.id', 'left');

        $this->db->join('workshop_images', 'workshop_images.workshop_id = workshop.id', 'left');
        $this->db->order_by("STR_TO_DATE(CONCAT_WS(' ', workshop_availability.workshop_date, workshop_availability.from_time), '%m/%d/%Y %H:%i:%s') asc");
        $this->db->group_by('workshop.id');
        if(!empty($limit))
            $this->db->limit($limit);

        $query = $this->db->get();

        return $data = $query->result_array();

    }

     /**
    *   Modify existng workshop  ( update )
    *   @param : offer id
    *
    **/
    public function modify_workshop($wid = NULL, $w_type = 0) {

        if( empty($wid) )
        {
            return FALSE;
        }


        $myda = $_POST['image-data'];
        $image_data =  json_decode($myda, true);
        $this->table = "workshop_images";

        $config['upload_path'] = dirname( dirname(  dirname( __FILE__ ) ) ).'/www/uploads/';

        for($i = 0; $i < count($image_data); $i++)
        {
            $data_wr = str_replace('data:image/png;base64,', '', $image_data[$i]);
            $data = base64_decode($data_wr);
            $new_image_name = 'image_' . time() . '_' . $wid .'_'. $i . '.png';
            file_put_contents(dirname( dirname(  dirname( __FILE__ ) ) ).'/www/uploads/'.$new_image_name, $data );

            $data_arr = array(
                'image_name'   => $new_image_name,
                'image_path'   => dirname( dirname(  dirname( __FILE__ ) ) ).'/www/uploads/',
                'workshop_id'  => $wid,
            );


            $target = $config['upload_path'].'/371x371';
            if(!is_dir($target)){
                $this->_mkdir($target);
            }
            workshop_thumbs($target, $new_image_name, 371, 371);


            $target = $config['upload_path'].'/510x308';
            if(!is_dir($target)){
                $this->_mkdir($target);
            }
            workshop_thumbs($target, $new_image_name, 510, 308);

            $this->db->insert($this->table, $data_arr);
        }

        /* Insert Wall Image*/
        if( !empty($this->input->post('selected_wall_image')) )
        {
            $this->db->where('workshop_id', $wid);
            $query = $this->db->get("workshop_wall_images");
            if ($query->num_rows() > 0) {
                $data_wall_workshop = array(
                    'wall_images_id' => $this->input->post('selected_wall_image'),
                );

                $this->db->where('workshop_id', $wid);
                $this->db->update("workshop_wall_images", $data_wall_workshop);
            }
            else
            {
                $data_wall_workshop = array(
                    'wall_images_id' => $this->input->post('selected_wall_image'),
                    'workshop_id'    => $wid,
                );

                $this->db->insert("workshop_wall_images", $data_wall_workshop);
            }

        }

        /* Update Workshop Table */
        $number_of_attendees = $this->input->post('no_of_attendees');
        $price_per_attendee = floatval(str_replace(array('.', ','), array('', '.'), $this->input->post('price_per_attendee')) );
        $price_per_attendee = number_format($price_per_attendee,2,".","");

        $tax = $price_per_attendee * (TAX_AMOUNT / 100);
        $price_per_attendee_including_tax = $price_per_attendee + $tax;
        //$price_per_attendee_including_tax = number_format($price_per_attendee_including_tax, 2, ".", "");

        $incremented_no_of_attendees = 0;
        $decremented_no_of_attendees = 0;
        $this->db->where('id', $wid);
        $query_ws = $this->db->get("workshop");
        if ($query_ws->num_rows() > 0) {
            foreach ( $query_ws->result() as $row_ws ) {
                $number_of_attendees_ws = $row_ws->number_of_attendees;
                if( $number_of_attendees < $number_of_attendees_ws ){
                    $decremented_no_of_attendees = $number_of_attendees_ws - $number_of_attendees;
                }
                else if( $number_of_attendees > $number_of_attendees_ws ){
                    $incremented_no_of_attendees = $number_of_attendees - $number_of_attendees_ws;
                }
            }
        }

        if( $w_type == 1)
        {
            $data_workshop = array(
                'title' => $this->input->post('title'),
                'slug'  => $this->slug->create_uri( $this->input->post('title') ),
                'number_of_attendees' => $number_of_attendees,
                'price_per_attendee' => $price_per_attendee,
                'price_per_attendee_including_tax' => $price_per_attendee_including_tax,
                'workshop_type' => 1,
                'is_manual' => $this->input->post('validation_process')
            );
        }
        else
        {
            $data_workshop = array(
                'title' => $this->input->post('title'),
                'slug'  => $this->slug->create_uri( $this->input->post('title') ),
                'number_of_attendees' => $number_of_attendees,
                'price_per_attendee' => $price_per_attendee,
                'price_per_attendee_including_tax' => $price_per_attendee_including_tax,
                'is_manual' => $this->input->post('validation_process')
            );
        }

        $this->db->where('id', $wid);
        $this->db->update("workshop", $data_workshop);


        /* Update Workshop Description Table */
        $this->db->where('workshop_id', $wid);
        $query = $this->db->get("workshop_description");
        if ($query->num_rows() > 0) {
            $data_workshop_desc = array(
                'descrition' => $this->input->post('workshop_desc'),
            );
            $this->db->where('workshop_id', $wid);
            $this->db->update("workshop_description", $data_workshop_desc);
        } else {
            $data_workshop_desc = array(
                'descrition' => $this->input->post('workshop_desc'),
                'workshop_id' => $wid,
            );
            $this->db->insert("workshop_description", $data_workshop_desc);
        }

        /* Update Workshop Location Table */
        $this->db->where('workshop_id', $wid);
        $query = $this->db->get("workshop_locations");
        if ($query->num_rows() > 0) {
            $data_workshop_locs = array(
                'address' => $this->input->post('address'),
                // 'postal_code' => $this->input->post('postal_code'),
                // 'town'        => $this->input->post('town'),
                'latitude' => $this->input->post('lat'),
                'longitude' => $this->input->post('lon'),
            );
            $this->db->where('workshop_id', $wid);
            $this->db->update("workshop_locations", $data_workshop_locs);
        } else {
            $data_workshop_locs = array(
                'address' => $this->input->post('address'),
                // 'postal_code' => $this->input->post('postal_code'),
                // 'town'        => $this->input->post('town'),
                'latitude' => $this->input->post('lat'),
                'longitude' => $this->input->post('lon'),
                'workshop_id' => $wid,
            );
            $this->db->insert("workshop_locations", $data_workshop_locs);
        }

        /* Update Workshop Equipment Table */
        if( empty($_FILES['material_file']['name']))
        {
            if( !empty($this->input->post('material_text')))
            {
                $data_workshop_equip = array(
                    'equipment_text'  => $this->input->post('material_text'),
                );

                $this->db->where('workshop_id', $wid);
                $this->db->update("workshop_equipments", $data_workshop_equip);
            }
        }
        else
        {
            if( !empty($this->input->post('material_text')))
            {
                $config1['file_name'] = $_FILES['material_file']['name'];
                $config1['upload_path'] = dirname( dirname( dirname( __FILE__ ) ) ).'/www/uploads/equipments/';
                $config1['allowed_types'] = 'gif|jpg|png|jpeg';
                $this->load->library('upload', $config1);
                if ( ! $this->upload->do_upload('material_file') )
                {
                    $error = array('error' => $this->upload->display_errors());
                }
                else
                {
                    $data_workshop_equip = array(
                        'equipment_text'  => $this->input->post('material_text'),
                    );

                    $data_img = $this->upload->data();
                    $file_name = $data_img['file_name'];

                    $data_workshop_equip += array( 'equipment_image' => $file_name );

                    $target = $config1['upload_path'].'870x451';
                    if(!is_dir($target)){
                        $this->_mkdir($target);
                    }
                    workshop_equipment_thumbs($target, $file_name, 870, 451);

                    $this->db->where('workshop_id', $wid);
                    $this->db->update("workshop_equipments", $data_workshop_equip);
                }
            }
        }

        /* Insert Workshop Tags */
        $data_workshop_tagsid = array();
        if( !empty($this->input->post('workshop_tags[]')) ) {
            for($tag = 0; $tag < count($this->input->post('workshop_tags[]')); $tag++){
                $data_workshop_tagsid[] = array(
                    'workshop_id'  => $wid,
                    'tags_id'  => $this->input->post('workshop_tags['.$tag.']'),
                );
            }
            $this->db->where('workshop_id', $wid);
            $this->db->delete('workshop_tags');
            $this->db->insert_batch("workshop_tags", $data_workshop_tagsid);
        }

        /* Insert Workshop Campaign */
        if( !empty($this->input->post('campaign_start_date')) &&  !empty($this->input->post('campaign_end_date'))){
            $data_workshop_campaign = array(
                'from_date'   => date("Y-m-d", strtotime( $this->input->post('campaign_start_date') ) ),
                'end_date'    => date("Y-m-d", strtotime( $this->input->post('campaign_end_date') ) ),
            );

            $this->db->where("workshop_id", $wid);
            $this->db->update("workshop_campaign", $data_workshop_campaign);

        }

        /* Insert Workshop Charateristics */
        $data_workshop_Charateristics = array();
        if( !empty($this->input->post('workshop_characteristics[]')) ){
            for($characteristics_loop = 0; $characteristics_loop < count($this->input->post('workshop_characteristics[]')); $characteristics_loop++){
                $data_workshop_Charateristics[] = array(
                    'workshop_id'  => $wid,
                    'characteristics_id'  => $this->input->post('workshop_characteristics['.$characteristics_loop.']'),
                );
            }
            $this->db->where('workshop_id', $wid);
            $this->db->delete('workshop_characteristics');
            $this->db->insert_batch("workshop_characteristics", $data_workshop_Charateristics);
        }


        /* Update Workshop Availability Table */
        if( $this->input->post('is_recurrence') == 1)
        {
            $this->update_recurrence_workshop($wid);
        }
        else
        {
            $data_workshop_availability = array();
            $data_workshop_timeslots = array();
            $workshop_dates = explode(",", $this->input->post('selected_dates'));
            if( count($workshop_dates) > 1){

                $workshop_slot = explode("-", $this->input->post('timeslots[0]'));
                $m = 00; $s = 00;
                $from_time = (int)$workshop_slot[0].":".$m.":".$s;
                $end_time  = (int)$workshop_slot[1].":".$m.":".$s;

                for( $i = 0; $i < count($workshop_dates); $i++){
                    $avail_seats = $this->get_workshop_available_seats($workshop_dates[$i], $from_time, $end_time, $wid);
                    if( empty($avail_seats) ){
                        $available_seats = $number_of_attendees;
                    }
                    else{
                        $available_seats = $avail_seats;
                    }

                    if( !empty($decremented_no_of_attendees) ){
                        $available_seats = $available_seats - $decremented_no_of_attendees;
                    }

                    if( !empty($incremented_no_of_attendees) ){
                        if( !empty($avail_seats) ){
                            $available_seats = $available_seats + $incremented_no_of_attendees;
                        }
                        else {
                            $available_seats = $incremented_no_of_attendees;
                        }
                    }

                    $data_workshop_availability[] = array(
                        'workshop_date' => trim($workshop_dates[$i]),
                        'from_time'     => $from_time,
                        'end_time'      => $end_time,
                        'workshop_id'   => $wid,
                        'available_seats'   => $available_seats,

                    );
                }

                $this->db->where('workshop_id', $wid);
                if($this->db->delete("workshop_availability")){
                    $this->db->insert_batch("workshop_availability", $data_workshop_availability);
                }
            } else {

                for( $i = 0; $i < count($this->input->post('timeslots')); $i++){
                    $workshop_slot = explode("-", $this->input->post('timeslots')[$i]);
                    $m = 00; $s = 00;
                    $from_time = (int)$workshop_slot[0].":".$m.":".$s;
                    $end_time  = (int)$workshop_slot[1].":".$m.":".$s;

                    $avail_seats = $this->get_workshop_available_seats($this->input->post('selected_dates'), $from_time, $end_time, $wid);
                    if( empty($avail_seats) ){
                        $available_seats = $number_of_attendees;
                    }
                    else{
                        $available_seats = $avail_seats;
                    }

                    if( !empty($decremented_no_of_attendees) ){
                        $available_seats = $available_seats - $decremented_no_of_attendees;
                    }

                    if( !empty($incremented_no_of_attendees) ){
                        if( !empty($avail_seats) ){
                            $available_seats = $available_seats + $incremented_no_of_attendees;
                        }
                        else {
                            $available_seats = $incremented_no_of_attendees;
                        }
                    }

                    $data_workshop_timeslots[] = array(
                        'workshop_date'    => trim($this->input->post('selected_dates')),
                        'workshop_enddate' => trim($this->input->post('selected_dates')),
                        'from_time'        => $from_time,
                        'end_time'         => $end_time,
                        'workshop_id'      => $wid,
                        'available_seats'  => $available_seats,
                    );
                }
                $this->db->where('workshop_id', $wid);
                if($this->db->delete("workshop_availability")){
                    $this->db->insert_batch("workshop_availability", $data_workshop_timeslots);
                }

            }
        }
        /* End */
        return TRUE;
    }

     /**
    *   Modify existng workshop  ( update )
    *   @param : offer id
    *
    **/
    public function modify_advertised_workshop($wid = NULL, $w_type = 0) {

        if( empty($wid) )
            return FALSE;


        if(!empty($_FILES['workshop_image']['name'][0])) {

            $this->table = "workshop_images";

            //we retrieve the number of files that were uploaded
            $number_of_files = sizeof($_FILES['workshop_image']['tmp_name']);

            // considering that do_upload() accepts single files, we will have to do a small hack so that we can upload multiple files. For this we will have to keep the data of uploaded files in a variable, and redo the $_FILE.
            $files = $_FILES['workshop_image'];

            // first make sure that there is no error in uploading the files
            for($i = 0; $i < $number_of_files; $i++)
            {
                if($_FILES['workshop_image']['error'][$i] != 0)
                {
                    // save the error message and return false, the validation of uploaded files failed
                    $this->form_validation->set_message('fileupload_check', 'Couldn\'t upload the file(s)');
                    return FALSE;
                }
            }

            // we first load the upload library
            $this->load->library('upload');
            $config['upload_path'] = dirname( dirname(  dirname( __FILE__ ) ) ).'/www/uploads/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';

            // now, taking into account that there can be more than one file, for each file we will have to do the upload
            for ($i = 0; $i < $number_of_files; $i++)
            {
                $_FILES['workshop_image']['name'] = $files['name'][$i];
                $_FILES['workshop_image']['type'] = $files['type'][$i];
                $_FILES['workshop_image']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['workshop_image']['error'] = $files['error'][$i];
                $_FILES['workshop_image']['size'] = $files['size'][$i];

                //now we initialize the upload library
                $this->upload->initialize($config);

                if ($this->upload->do_upload('workshop_image'))
                {
                    $this->_uploaded[$i] = $this->upload->data();

                    $file_name = $this->_uploaded[$i]['file_name'];
                    $data = array(
                        'image_name'   => $this->_uploaded[$i]['file_name'],
                        'image_path'   => $this->_uploaded[$i]['file_path'],
                        'workshop_id'  => $wid,
                    );

                    /* CREATE offer thumbnail 371x371 */
                    $target = $config['upload_path'].'/371x371';
                    if(!is_dir($target)){
                        $this->_mkdir($target);
                    }
                    workshop_thumbs($target, $file_name, 371, 371);

                    /* CREATE offer thumbnail 510x308 */
                    $target = $config['upload_path'].'/510x308';
                    if(!is_dir($target)){
                        $this->_mkdir($target);
                    }
                    workshop_thumbs($target, $file_name, 510, 308);

                    $this->db->where('workshop_id', $wid);
                    $this->db->insert($this->table, $data);
                }
                else
                {
                    $this->form_validation->set_message('fileupload_check', $this->upload->display_errors());
                    return FALSE;
                }
            }
        }

        /* Insert Wall Image*/
        if( !empty($this->input->post('selected_wall_image')) )
        {
            $this->db->where('workshop_id', $wid);
            $query = $this->db->get("workshop_wall_images");
            if ($query->num_rows() > 0) {
                $data_wall_workshop = array(
                    'wall_images_id' => $this->input->post('selected_wall_image'),
                );

                $this->db->where('workshop_id', $wid);
                $this->db->update("workshop_wall_images", $data_wall_workshop);
            }
            else
            {
                $data_wall_workshop = array(
                    'wall_images_id' => $this->input->post('selected_wall_image'),
                    'workshop_id'    => $wid,
                );

                $this->db->insert("workshop_wall_images", $data_wall_workshop);
            }

        }

        /* Update Workshop Table */
        $number_of_attendees = $this->input->post('no_of_attendees');
        $price_per_attendee = floatval(str_replace(array('.', ','), array('', '.'), $this->input->post('price_per_attendee')) );
        $price_per_attendee = number_format($price_per_attendee,2,".","");

        $tax = $price_per_attendee * (TAX_AMOUNT / 100);
        $price_per_attendee_including_tax = $price_per_attendee + $tax;
        //$price_per_attendee_including_tax = number_format($price_per_attendee_including_tax, 2, ".", "");

        $incremented_no_of_attendees = 0;
        $decremented_no_of_attendees = 0;
        $this->db->where('id', $wid);
        $query_ws = $this->db->get("workshop");
        if ($query_ws->num_rows() > 0) {
            foreach ( $query_ws->result() as $row_ws ) {
                $number_of_attendees_ws = $row_ws->number_of_attendees;
                if( $number_of_attendees < $number_of_attendees_ws ){
                    $decremented_no_of_attendees = $number_of_attendees_ws - $number_of_attendees;
                }
                else if( $number_of_attendees > $number_of_attendees_ws ){
                    $incremented_no_of_attendees = $number_of_attendees - $number_of_attendees_ws;
                }
            }
        }

        if( $w_type == 1)
        {
            $data_workshop = array(
                'title' => $this->input->post('title'),
                'slug'  => $this->slug->create_uri( $this->input->post('title') ),
                'number_of_attendees' => $number_of_attendees,
                'price_per_attendee' => $price_per_attendee,
                'price_per_attendee_including_tax' => $price_per_attendee_including_tax,
                'status' => 1,
                'workshop_type' => 0,
                'is_manual' => $this->input->post('validation_process')
            );
        }
        else
        {
            $data_workshop = array(
                'title' => $this->input->post('title'),
                'slug'  => $this->slug->create_uri( $this->input->post('title') ),
                'number_of_attendees' => $number_of_attendees,
                'price_per_attendee' => $price_per_attendee,
                'price_per_attendee_including_tax' => $price_per_attendee_including_tax,
                'workshop_type' => 1,
                'is_manual' => $this->input->post('validation_process')
            );
        }

        $this->db->where('id', $wid);
        $this->db->update("workshop", $data_workshop);


        /* Update Workshop Description Table */
        $this->db->where('workshop_id', $wid);
        $query = $this->db->get("workshop_description");
        if ($query->num_rows() > 0) {
            $data_workshop_desc = array(
                'descrition' => $this->input->post('workshop_desc'),
            );
            $this->db->where('workshop_id', $wid);
            $this->db->update("workshop_description", $data_workshop_desc);
        } else {
            $data_workshop_desc = array(
                'descrition' => $this->input->post('workshop_desc'),
                'workshop_id' => $wid,
            );
            $this->db->insert("workshop_description", $data_workshop_desc);
        }

        /* Update Workshop Location Table */
        $this->db->where('workshop_id', $wid);
        $query = $this->db->get("workshop_locations");
        if ($query->num_rows() > 0) {
            $data_workshop_locs = array(
                'address' => $this->input->post('address'),
                // 'postal_code' => $this->input->post('postal_code'),
                // 'town'        => $this->input->post('town'),
                'latitude' => $this->input->post('lat'),
                'longitude' => $this->input->post('lon'),
            );
            $this->db->where('workshop_id', $wid);
            $this->db->update("workshop_locations", $data_workshop_locs);
        } else {
            $data_workshop_locs = array(
                'address' => $this->input->post('address'),
                // 'postal_code' => $this->input->post('postal_code'),
                // 'town'        => $this->input->post('town'),
                'latitude' => $this->input->post('lat'),
                'longitude' => $this->input->post('lon'),
                'workshop_id' => $wid,
            );
            $this->db->insert("workshop_locations", $data_workshop_locs);
        }

        /* Update Workshop Equipment Table */
        if( empty($_FILES['material_file']['name']))
        {
            if( !empty($this->input->post('material_text')))
            {
                $data_workshop_equip = array(
                    'equipment_text'  => $this->input->post('material_text'),
                );

                $this->db->where('workshop_id', $wid);
                $this->db->update("workshop_equipments", $data_workshop_equip);
            }
        }
        else
        {
            if( !empty($this->input->post('material_text')))
            {
                $config1['file_name'] = $_FILES['material_file']['name'];
                $config1['upload_path'] = dirname( dirname( dirname( __FILE__ ) ) ).'/www/uploads/equipments/';
                $config1['allowed_types'] = 'gif|jpg|png|jpeg';
                $this->load->library('upload', $config1);
                if ( ! $this->upload->do_upload('material_file') )
                {
                    $error = array('error' => $this->upload->display_errors());
                }
                else
                {
                    $data_workshop_equip = array(
                        'equipment_text'  => $this->input->post('material_text'),
                    );

                    $data_img = $this->upload->data();
                    $file_name = $data_img['file_name'];

                    $data_workshop_equip += array( 'equipment_image' => $file_name );

                    $target = $config1['upload_path'].'870x451';
                    if(!is_dir($target)){
                        $this->_mkdir($target);
                    }
                    workshop_equipment_thumbs($target, $file_name, 870, 451);

                    $this->db->where('workshop_id', $wid);
                    $this->db->update("workshop_equipments", $data_workshop_equip);
                }
            }
        }

        /* Insert Workshop Tags */
        $data_workshop_tagsid = array();
        if( !empty($this->input->post('workshop_tags[]')) ) {
            for($tag = 0; $tag < count($this->input->post('workshop_tags[]')); $tag++){
                $data_workshop_tagsid[] = array(
                    'workshop_id'  => $wid,
                    'tags_id'  => $this->input->post('workshop_tags['.$tag.']'),
                );
            }
            $this->db->where('workshop_id', $wid);
            $this->db->delete('workshop_tags');
            $this->db->insert_batch("workshop_tags", $data_workshop_tagsid);
        }

        /* Insert Workshop Charateristics */
        $data_workshop_Charateristics = array();
        if( !empty($this->input->post('workshop_characteristics[]')) ){
            for($characteristics_loop = 0; $characteristics_loop < count($this->input->post('workshop_characteristics[]')); $characteristics_loop++){
                $data_workshop_Charateristics[] = array(
                    'workshop_id'  => $wid,
                    'characteristics_id'  => $this->input->post('workshop_characteristics['.$characteristics_loop.']'),
                );
            }
            $this->db->where('workshop_id', $wid);
            $this->db->delete('workshop_characteristics');
            $this->db->insert_batch("workshop_characteristics", $data_workshop_Charateristics);
        }

        /* Insert Workshop Campaign */
        if( !empty($this->input->post('campaign_start_date')) &&  !empty($this->input->post('campaign_end_date'))){
            $data_workshop_campaign = array(
                'from_date'   => date("Y-m-d", strtotime( $this->input->post('campaign_start_date') ) ),
                'end_date'    => date("Y-m-d", strtotime( $this->input->post('campaign_end_date') ) ),
            );

            $this->db->where("workshop_id", $wid);
            $this->db->update("workshop_campaign", $data_workshop_campaign);

        }


        /* Update Workshop Availability Table */
        if( $this->input->post('is_recurrence') == 1)
        {
            $this->update_recurrence_workshop($wid);
        }
        else
        {
            $data_workshop_availability = array();
            $data_workshop_timeslots = array();
            $workshop_dates = explode(",", $this->input->post('selected_dates'));
            if( count($workshop_dates) > 1){

                $workshop_slot = explode("-", $this->input->post('timeslots[0]'));
                $m = 00; $s = 00;
                $from_time = (int)$workshop_slot[0].":".$m.":".$s;
                $end_time  = (int)$workshop_slot[1].":".$m.":".$s;

                for( $i = 0; $i < count($workshop_dates); $i++){
                    $avail_seats = $this->get_workshop_available_seats($workshop_dates[$i], $from_time, $end_time, $wid);
                    if( empty($avail_seats) ){
                        $available_seats = $number_of_attendees;
                    }
                    else{
                        $available_seats = $avail_seats;
                    }

                    if( !empty($decremented_no_of_attendees) ){
                        $available_seats = $available_seats - $decremented_no_of_attendees;
                    }

                    if( !empty($incremented_no_of_attendees) ){
                        if( !empty($avail_seats) ){
                            $available_seats = $available_seats + $incremented_no_of_attendees;
                        }
                        else {
                            $available_seats = $incremented_no_of_attendees;
                        }
                    }

                    $data_workshop_availability[] = array(
                        'workshop_date' => trim($workshop_dates[$i]),
                        'from_time'     => $from_time,
                        'end_time'      => $end_time,
                        'workshop_id'   => $wid,
                        'available_seats'   => $available_seats,

                    );
                }

                $this->db->where('workshop_id', $wid);
                if($this->db->delete("workshop_availability")){
                    $this->db->insert_batch("workshop_availability", $data_workshop_availability);
                }
            } else {

                for( $i = 0; $i < count($this->input->post('timeslots')); $i++){
                    $workshop_slot = explode("-", $this->input->post('timeslots')[$i]);
                    $m = 00; $s = 00;
                    $from_time = (int)$workshop_slot[0].":".$m.":".$s;
                    $end_time  = (int)$workshop_slot[1].":".$m.":".$s;

                    $avail_seats = $this->get_workshop_available_seats($this->input->post('selected_dates'), $from_time, $end_time, $wid);
                    if( empty($avail_seats) ){
                        $available_seats = $number_of_attendees;
                    }
                    else{
                        $available_seats = $avail_seats;
                    }

                    if( !empty($decremented_no_of_attendees) ){
                        $available_seats = $available_seats - $decremented_no_of_attendees;
                    }

                    if( !empty($incremented_no_of_attendees) ){
                        if( !empty($avail_seats) ){
                            $available_seats = $available_seats + $incremented_no_of_attendees;
                        }
                        else {
                            $available_seats = $incremented_no_of_attendees;
                        }
                    }

                    $data_workshop_timeslots[] = array(
                        'workshop_date'    => trim($this->input->post('selected_dates')),
                        'workshop_enddate' => trim($this->input->post('selected_dates')),
                        'from_time'        => $from_time,
                        'end_time'         => $end_time,
                        'workshop_id'      => $wid,
                        'available_seats'  => $available_seats,
                    );
                }
                $this->db->where('workshop_id', $wid);
                if($this->db->delete("workshop_availability")){
                    $this->db->insert_batch("workshop_availability", $data_workshop_timeslots);
                }

            }
        }
        /* End */
        return TRUE;
    }

    public function get_attendees_list($wid = NULL){
        if( empty($wid) )
        {
            return FALSE;
        }

        $this->db->select('
            order_items.*,
            user_details.fname,
            user_details.lname,
            user_details.email,
            workshop.title,
        ');
        $this->db->from('order_items');
        $this->db->where('order_items.workshop_id', $wid);
        //$this->db->where('order_items.payment_status!=', PENDING);
        $this->db->join('user_details', 'user_details.users_id = order_items.users_id', 'left');
        $this->db->join('workshop', 'workshop.id = order_items.workshop_id', 'left');

        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function delete_equip_image($imgid = NULL) {
        $this->db->where('id', $imgid);
        $query = $this->db->get("workshop_equipments");
        if ($query->num_rows() > 0) {
            $data_workshop= array(
                'equipment_image' => NULL,
            );
            $this->db->where('id', $imgid);
            if($this->db->update("workshop_equipments", $data_workshop))
                return TRUE;
            else
                return FALSE;
        }
    }

    /**
    *   Get random data of 1 advertised workshop
    *   Params :
    **/

    public function get_random_advertised_workshop_id()
    {

        $data = array();
        $this->db->where("workshop_type", 1);
        $this->db->limit(1);
        $query = $this->db->get("workshop");
        /*$ret = $query->row();
        return $ret->id;*/

        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $row_id = $row['id'];
            }
            return $row_id; //format the array into json data
        }



    }

    //Make directory for image uploading
    function _mkdir($target)
    {
        // from php.net/mkdir user contributed notes
        if(file_exists($target))
        {
            if( ! @is_dir($target))
            {
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }

        // Attempting to create the directory may clutter up our display.
        if(@mkdir($target))
        {
            $stat = @stat(dirname($target));
            $dir_perms = $stat['mode'] & 0007777;  // Get the permission bits.
            @chmod($target, $dir_perms);
            return TRUE;
        }
        else
        {
            if(is_dir(dirname($target)))
            {
                return FALSE;
            }
        }

        // If the above failed, attempt to create the parent node, then try again.
        if ($this->_mkdir(dirname($target)))
        {
            return $this->_mkdir($target);
        }

        return FALSE;
    }

    public function del_image($id = 0){
        $this->table = "workshop_images";
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /*
    * To get available seats wrt workshop
    */
    public function get_workshop_available_seats($date = null, $fromtime = null, $endtime = null, $workshop_id = null){
        if( empty($date) || empty($fromtime)|| empty($endtime)|| empty($workshop_id) ){
            return FALSE;
        }
        $date = date("m/d/Y", strtotime($date) );
        $where_array = array(
                'workshop_date' => $date,
                'from_time' => $fromtime,
                'end_time' => $endtime,
                'workshop_id' => $workshop_id
            );
        $this->db->where($where_array);
        $this->db->from('workshop_availability');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row){
                return $row->available_seats;
            }//endforeach
        }
        return FALSE;
    }

    /*
    * Update available seats wrt workshop
    */
    public function update_workshop_available_seats($date = null, $fromtime = null, $endtime = null, $workshop_id = null, $now_available_seats = null){
        if( empty($date) || empty($fromtime)|| empty($endtime)|| empty($workshop_id) ){
            return FALSE;
        }
        $date = date("m/d/Y", strtotime($date) );
        $where_array = array(
                'workshop_date' => $date,
                'from_time' => $fromtime,
                'end_time' => $endtime,
                'workshop_id' => $workshop_id
            );
        $this->db->where($where_array);
        if($this->db->update('workshop_availability', array('available_seats' => trim($now_available_seats)) )){
            return TRUE;
        }
        return FALSE;
    }

    public function is_manual_workhop( $wid = NULL)
    {
        if( empty($wid) )
        {
            return FALSE;
        }

        $this->db->where('id', $wid);
        $this->db->from('workshop');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return FALSE;
    }

    public function check_workshop_type( $wid = NULL)
    {
        if( empty($wid) )
        {
            return FALSE;
        }

        $this->db->where('id', $wid);
        $this->db->from('workshop');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return FALSE;
    }

    public function publish_advertised_workshop($wid = null)
    {

        if( empty($wid) )
        {
            return FALSE;
        }

        $this->table = "workshop";

        $data = array(
            'status' => 1,
        );

        $this->db->where("id", $wid);
        if( $this->db->update($this->table, $data) )
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function get_workshop_details( $wid = NULL)
    {
        if( empty( $wid) )
        {
            return FALSE;
        }

        $this->db->select('
            workshop.id, workshop.title, workshop.users_id, workshop.is_manual,
            workshop_availability.workshop_date, workshop_availability.from_time, workshop_availability.end_time,
            workshop_equipments.equipment_text,
            workshop_locations.address,
            // workshop_locations.postal_code, workshop_locations.town,
        ');

        $this->db->from('workshop');
        $this->db->join('workshop_availability', 'workshop_availability.workshop_id = workshop.id', 'left');
        $this->db->join('workshop_equipments'  , 'workshop_equipments.workshop_id   = workshop.id', 'left');
        $this->db->join('workshop_locations'  , 'workshop_locations.workshop_id   = workshop.id', 'left');
        $this->db->where('workshop.id', $wid);
        $result = $this->db->get();
        return $data = $result->row_array();
    }

    public function get_all_advertised_workshops(){

        $this->db->select('
            workshop.id,
            workshop_availability.workshop_date, workshop_availability.workshop_enddate, workshop_availability.from_time, workshop_availability.end_time
        ');

        $this->db->from('workshop');
        $this->db->where('workshop.workshop_type', 1);
        $this->db->where('workshop.is_suspended', 0);
        $this->db->where('workshop.status', 1);

        $this->db->join('workshop_availability', 'workshop_availability.workshop_id = workshop.id', 'left');
        $this->db->group_by('workshop.id');

        $query = $this->db->get();
        if(count($query->result()) > 0) {
            return $query->result();
        } else {
            return FALSE;
        }

    }

    public function change_advertised_to_normal_workshop( $wid = NULL)
    {
        if( empty($wid) )
        {
            return FALSE;
        }

        $data = array(
            'workshop_type' => 0,
        );

        $this->db->where('id', $wid);
        if( $this->db->update("workshop", $data) ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_campaign_dates( $wid = NULL )
    {
        if( empty($wid) )
        {
            return FALSE;
        }

        $this->db->where('workshop_id', $wid);
        $this->db->from('workshop_campaign');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return FALSE;
    }

    public function has_user_booked_this_workshop($wid, $userid)
    {
        if(empty($wid) && empty($userid)){
            return FALSE;
        }

        $this->db->from('order_items');
        $this->db->where('order_items.users_id', $userid);
        $this->db->where('order_items.workshop_id', $wid);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
               return $row['payment_status'];
            }
        }
        return FALSE;
    }

    public function get_autocomplete_title($q = NULL){

        if( empty($q) )
        {
            return FALSE;
        }

        $this->db->select('title');
        $this->db->like('title', $q);
        $query = $this->db->get('workshop');
        // $this->db->select('title')->from('workshop')->where("title LIKE '%$q%'")->get();
        $row_set = array();
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                // $row_set[] = htmlentities(stripslashes($row['title'])); //build an array
                array_push($row_set, strip_tags($row['title']));

            }
            echo json_encode($row_set); //format the array into json data
        }
    }

    public function getby_slug_name( $slug = NULL)
    {
        if( empty($slug) )
        {
            return FALSE;
        }

        $this->db->select('id');
        $this->db->where('slug', $slug);
        $query = $this->db->get('workshop');

        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $row_id = $row['id'];
            }
            return $row_id; //format the array into json data
        }

    }

    public function workshop_recurrence($id = NULL) {
        $this->table = 'workshop_recurrence';
        $this->db->where('workshop_id', $id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

}
