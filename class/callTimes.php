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
 * Written by michael <michael@adl-crm.uk>, 06/03/2020 13:08
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


class callTimes
{

    private $fullName;
    private $callCount;
    private $talkSec;
    private $firstCall;
    private $lastCall;
    private $callbacks;

    public function __construct(PDO $pdo)
    {

        $this->pdo = $pdo;

    }

    public function sendCallBacks()
    {
        $query = $this->pdo->prepare("UPDATE callTimes SET callbacks=:callbacks WHERE fullName=:fullName AND DATE(addedDate) = current_date()");
        $query->bindParam(':fullName', $this->fullName, PDO::PARAM_STR);
        $query->bindParam(':callbacks', $this->callbacks, PDO::PARAM_STR);
        $query->execute();
    }

    public function sendCallTimesToADL()
    {

        $query = $this->pdo->prepare("SELECT id FROM callTimes WHERE fullName=:fullName AND DATE(addedDate) = current_date()");
        $query->bindParam(':fullName', $this->fullName, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {

            $query = $this->pdo->prepare("UPDATE callTimes SET firstCall=:firstCall, lastCall=:lastCall, talkSec=:talkSec, callCount=:callCount WHERE fullName=:fullName AND DATE(addedDate) = current_date()");
            $query->bindParam(':fullName', $this->fullName, PDO::PARAM_STR);
            $query->bindParam(':firstCall', $this->firstCall, PDO::PARAM_STR);
            $query->bindParam(':lastCall', $this->lastCall, PDO::PARAM_STR);
            $query->bindParam(':talkSec', $this->talkSec, PDO::PARAM_INT);
            $query->bindParam(':callCount', $this->callCount, PDO::PARAM_INT);
            $query->execute();

        } else {

            $query = $this->pdo->prepare("INSERT INTO callTimes SET firstCall=:firstCall, lastCall=:lastCall, fullName=:fullName, talkSec=:talkSec, callCount=:callCount");
            $query->bindParam(':fullName', $this->fullName, PDO::PARAM_STR);
            $query->bindParam(':firstCall', $this->firstCall, PDO::PARAM_STR);
            $query->bindParam(':lastCall', $this->lastCall, PDO::PARAM_STR);
            $query->bindParam(':talkSec', $this->talkSec, PDO::PARAM_INT);
            $query->bindParam(':callCount', $this->callCount, PDO::PARAM_INT);
            $query->execute();
            if ($query->rowCount() > 0) {

                return 'success';

            } else {
                return 'error';
            }

        }

    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @param mixed $callCount
     */
    public function setCallCount($callCount)
    {
        $this->callCount = $callCount;
    }

    /**
     * @param mixed $talkSec
     */
    public function setTalkSec($talkSec)
    {
        $this->talkSec = $talkSec;
    }

    /**
     * @param mixed $firstCall
     */
    public function setFirstCall($firstCall)
    {
        $this->firstCall = $firstCall;
    }

    /**
     * @param mixed $lastCall
     */
    public function setLastCall($lastCall)
    {
        $this->lastCall = $lastCall;
    }

    /**
     * @param mixed $callbacks
     */
    public function setCallbacks($callbacks)
    {
        $this->callbacks = $callbacks;
    }


}
