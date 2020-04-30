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


class userLog
{

    private $user_log_id;
    private $full_name;
    private $event;
    private $event_date;
    private $event_epoch;
    private $campaign_id;


    public function __construct(PDO $pdo)
    {

        $this->pdo = $pdo;

    }

    public function getTodayUserLogByFullName()
    {

        $query = $this->pdo->prepare("SELECT user_log_id, full_name, event, event_date, event_epoch, campaign_id FROM user_log JOIN users ON users.user = user_log.user where event_date >=CURDATE() AND full_name=:fullName");
        $query->bindParam(':fullName', $this->full_name, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {

            return $query->fetchAll(PDO::FETCH_ASSOC);

        }

        return 'error';

    }

    public function sendUserLogToADL()
    {

        $query = $this->pdo->prepare("SELECT user_log_id FROM userLog WHERE user_log_id=:user_log_id");
        $query->bindParam(':user_log_id', $this->user_log_id, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() == 0) {

            $query = $this->pdo->prepare("INSERT INTO userLog SET user_log_id=:user_log_id, full_name=:full_name, event=:event, event_date=:event_date, event_epoch=:event_epoch, campaign_id=:campaign_id");
            $query->bindParam(':user_log_id', $this->user_log_id, PDO::PARAM_STR);
            $query->bindParam(':full_name', $this->full_name, PDO::PARAM_STR);
            $query->bindParam(':event', $this->event, PDO::PARAM_STR);
            $query->bindParam(':event_date', $this->event_date, PDO::PARAM_STR);
            $query->bindParam(':event_epoch', $this->event_epoch, PDO::PARAM_STR);
            $query->bindParam(':campaign_id', $this->campaign_id, PDO::PARAM_STR);
            $query->execute();

        }

        return 'success';

    }

    /**
     * @param integer $user_log_id
     */
    public function setUserLogId($user_log_id)
    {
        $this->user_log_id = $user_log_id;
    }

    /**
     * @param mixed $full_name
     */
    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @param mixed $event_date
     */
    public function setEventDate($event_date)
    {
        $this->event_date = $event_date;
    }

    /**
     * @param mixed $event_epoch
     */
    public function setEventEpoch($event_epoch)
    {
        $this->event_epoch = $event_epoch;
    }

    /**
     * @param mixed $campaign_id
     */
    public function setCampaignId($campaign_id)
    {
        $this->campaign_id = $campaign_id;
    }


}
