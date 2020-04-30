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


class agentWallboard
{

    private $agent;
    private $status;
    private $time;
    private $colour;
    private $day;
    private $userGroup;

    public function __construct(PDO $pdo)
    {

        $this->pdo = $pdo;

    }


    public function sendAgentWallboardToADL()
    {

        $query = $this->pdo->prepare("SELECT agent FROM agentWallbaord WHERE agent=:agent and DATE(day) >= CURDATE()");
        $query->bindParam(':agent', $this->agent, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() == 0) {

            $query = $this->pdo->prepare("INSERT INTO agentWallbaord SET agent=:agent, userGroup=:userGroup, status=:status, time=:time, colour=:colour, day= CURDATE()");
            $query->bindParam(':agent', $this->agent, PDO::PARAM_STR);
            $query->bindParam(':userGroup', $this->userGroup, PDO::PARAM_STR);
            $query->bindParam(':status', $this->status, PDO::PARAM_STR);
            $query->bindParam(':time', $this->time, PDO::PARAM_STR);
            $query->bindParam(':colour', $this->colour, PDO::PARAM_STR);
            $query->execute();

        } else {
            $query = $this->pdo->prepare("UPDATE agentWallbaord SET status=:status, time=:time, userGroup=:userGroup, colour=:colour WHERE agent=:agent AND DATE(day)= CURDATE()");
            $query->bindParam(':agent', $this->agent, PDO::PARAM_STR);
            $query->bindParam(':userGroup', $this->userGroup, PDO::PARAM_STR);
            $query->bindParam(':status', $this->status, PDO::PARAM_STR);
            $query->bindParam(':time', $this->time, PDO::PARAM_STR);
            $query->bindParam(':colour', $this->colour, PDO::PARAM_STR);
            $query->execute();
        }

        return 'success';

    }

    /**
     * @param mixed $agent
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @param mixed $colour
     */
    public function setColour($colour)
    {
        $this->colour = $colour;
    }

    /**
     * @param mixed $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @param mixed $userGroup
     */
    public function setUserGroup($userGroup)
    {
        $this->userGroup = $userGroup;
    }


}
