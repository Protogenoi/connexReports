<?php
/**
 * ------------------------------------------------------------------------
 *                               ADL CRM
 * ------------------------------------------------------------------------
 *
 * Copyright © 2019 ADL CRM All rights reserved.
 *
 * Unauthorised copying of this file, via any medium is strictly prohibited.
 * Unauthorised distribution of this file, via any medium is strictly prohibited.
 * Unauthorised modification of this code is strictly prohibited.
 *
 * Proprietary and confidential
 *
 * Written by michael <michael@adl-crm.uk>, 09/03/2020 14:35
 *
 * ADL CRM makes use of the following third party open sourced software/tools:
 *  Composer - https://getcomposer.org/doc/
 *  DataTables - https://github.com/DataTables/DataTables
 *  EasyAutocomplete - https://github.com/pawelczak/EasyAutocomplete
 *  PHPMailer - https://github.com/PHPMailer/PHPMailer
 *  ClockPicker - https://github.com/weareoutman/clockpicker
 *  fpdf17 - http://www.fpdf.org
 *  summernote - https://github.com/summernote/summernote
 *  Font Awesome - https://github.com/FortAwesome/Font-Awesome
 *  Bootstrap - https://github.com/twbs/bootstrap
 *  jQuery UI - https://github.com/jquery/jquery-ui
 *  Google Dev Tools - https://developers.google.com
 *  Twitter API - https://developer.twitter.com
 *  Webshim - https://github.com/aFarkas/webshim/releases/latest
 *  toastr - https://github.com/CodeSeven/toastr
 *  Twilio - https://github.com/twilio
 *  SendGrid - https://github.com/sendgrid
 *  Ideal Postcodes - https://ideal-postcodes.co.uk/documentation
 *  Chart.js - https://github.com/chartjs/Chart.js
 */


class agentLog
{

    private $agent_log_id;
    private $event_time;
    private $lead_id;
    private $status;
    private $dead_sec;
    private $dispo_sec;
    private $talk_sec;
    private $wait_sec;
    private $pause_sec;
    private $full_name;
    private $uniqueid;

    public function __construct(PDO $pdo)
    {

        $this->pdo = $pdo;

    }

    public function getTodayAgentLogByFullName()
    {

        $query = $this->pdo->prepare("SELECT event_time, agent_log_id, full_name, pause_sec, wait_sec, talk_sec, dispo_sec, dead_sec, status, lead_id, campaign_id, uniqueid FROM agent_log JOIN users ON users.user = agent_log.user WHERE event_time >= CURDATE() AND full_name=:fullName");
        $query->bindParam(':fullName', $this->full_name, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {

            return $query->fetchAll(PDO::FETCH_ASSOC);

        }

        return 'error';

    }

    public function sendAgentLogToADL()
    {

        $query = $this->pdo->prepare("SELECT agent_log_id FROM agentLog WHERE agent_log_id=:agent_log_id AND DATE(event_time) >= CURDATE()");
        $query->bindParam(':agent_log_id', $this->agent_log_id, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() >= 1) {

            $query = $this->pdo->prepare("UPDATE agentLog SET uniqueid=:uniqueid, event_time=:event_time, lead_id=:lead_id, status=:status, dead_sec=:dead_sec, full_name=:full_name, pause_sec=:pause_sec, wait_sec=:wait_sec, talk_sec=:talk_sec, dispo_sec=:dispo_sec WHERE agent_log_id=:agent_log_id");
            $query->bindParam(':uniqueid', $this->uniqueid, PDO::PARAM_STR);
            $query->bindParam(':event_time', $this->event_time, PDO::PARAM_STR);
            $query->bindParam(':full_name', $this->full_name, PDO::PARAM_STR);
            $query->bindParam(':status', $this->status, PDO::PARAM_STR);
            $query->bindParam(':pause_sec', $this->pause_sec, PDO::PARAM_INT);
            $query->bindParam(':agent_log_id', $this->agent_log_id, PDO::PARAM_INT);
            $query->bindParam(':wait_sec', $this->wait_sec, PDO::PARAM_INT);
            $query->bindParam(':talk_sec', $this->talk_sec, PDO::PARAM_INT);
            $query->bindParam(':dispo_sec', $this->dispo_sec, PDO::PARAM_INT);
            $query->bindParam(':dead_sec', $this->dead_sec, PDO::PARAM_INT);
            $query->bindParam(':lead_id', $this->lead_id, PDO::PARAM_INT);
            $query->execute();

        } else {

            $query = $this->pdo->prepare("INSERT INTO agentLog SET uniqueid=:uniqueid, event_time=:event_time, lead_id=:lead_id, status=:status, dead_sec=:dead_sec, agent_log_id=:agent_log_id, full_name=:full_name, pause_sec=:pause_sec, wait_sec=:wait_sec, talk_sec=:talk_sec, dispo_sec=:dispo_sec");
            $query->bindParam(':uniqueid', $this->uniqueid, PDO::PARAM_STR);
            $query->bindParam(':event_time', $this->event_time, PDO::PARAM_STR);
            $query->bindParam(':full_name', $this->full_name, PDO::PARAM_STR);
            $query->bindParam(':status', $this->status, PDO::PARAM_STR);
            $query->bindParam(':pause_sec', $this->pause_sec, PDO::PARAM_INT);
            $query->bindParam(':agent_log_id', $this->agent_log_id, PDO::PARAM_INT);
            $query->bindParam(':wait_sec', $this->wait_sec, PDO::PARAM_INT);
            $query->bindParam(':talk_sec', $this->talk_sec, PDO::PARAM_INT);
            $query->bindParam(':dispo_sec', $this->dispo_sec, PDO::PARAM_INT);
            $query->bindParam(':dead_sec', $this->dead_sec, PDO::PARAM_INT);
            $query->bindParam(':lead_id', $this->lead_id, PDO::PARAM_INT);
        }

        return 'success';

    }

    /**
     * @param integer $agent_log_id
     */
    public function setAgentLogId($agent_log_id)
    {
        $this->agent_log_id = $agent_log_id;
    }

    /**
     * @param mixed $event_time
     */
    public function setEventTime($event_time)
    {
        $this->event_time = $event_time;
    }

    /**
     * @param mixed $lead_id
     */
    public function setLeadId($lead_id)
    {
        $this->lead_id = $lead_id;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param mixed $dead_sec
     */
    public function setDeadSec($dead_sec)
    {
        $this->dead_sec = $dead_sec;
    }

    /**
     * @param mixed $dispo_sec
     */
    public function setDispoSec($dispo_sec)
    {
        $this->dispo_sec = $dispo_sec;
    }

    /**
     * @param mixed $talk_sec
     */
    public function setTalkSec($talk_sec)
    {
        $this->talk_sec = $talk_sec;
    }

    /**
     * @param mixed $wait_sec
     */
    public function setWaitSec($wait_sec)
    {
        $this->wait_sec = $wait_sec;
    }

    /**
     * @param mixed $pause_sec
     */
    public function setPauseSec($pause_sec)
    {
        $this->pause_sec = $pause_sec;
    }

    /**
     * @param mixed $full_name
     */
    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
    }

    /**
     * @param mixed $uniqueid
     */
    public function setUniqueid($uniqueid)
    {
        $this->uniqueid = $uniqueid;
    }

}
