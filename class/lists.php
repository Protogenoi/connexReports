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


class lists
{

    private $list_id;
    private $list_name;
    private $active;
    private $totalRecords;
    private $totalDialled;
    private $campaignId;
    private $entryDate;


    public function __construct(PDO $pdo)
    {

        $this->pdo = $pdo;

    }


    public function sendListstoADL()
    {

    if (strlen($this->list_id) >= 11) {
    $list_id = substr($this->list_id, -4);
    $fullListId = $this->list_id;
    } else {
        $list_id = $this->list_id;
        $fullListId = $this->list_id;
    }

        $query = $this->pdo->prepare("SELECT list_id FROM lists WHERE list_id=:list_id");
        $query->bindParam(':list_id', $this->list_id, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {

            $query = $this->pdo->prepare("UPDATE lists SET entryDate=:entryDate, list_name=:list_name, campaign_id=:campaignId, entryDate=:entryDate, totalDialled=:totalDialled, total_records=:totalRecords, active=:active, fullListId=:fullListId WHERE list_id=:list_id LIMIT 1");
            $query->bindParam(':entryDate', $this->entryDate, PDO::PARAM_STR);
            $query->bindParam(':campaignId', $this->campaignId, PDO::PARAM_STR);
            $query->bindParam(':entryDate', $this->entryDate, PDO::PARAM_STR);
            $query->bindParam(':totalDialled', $this->totalDialled, PDO::PARAM_STR);
            $query->bindParam(':totalRecords', $this->totalRecords, PDO::PARAM_STR);
            $query->bindParam(':active', $this->active, PDO::PARAM_STR);
            $query->bindParam(':list_id', $this->list_id, PDO::PARAM_STR);
            $query->bindParam(':fullListId', $fullListId, PDO::PARAM_STR);
            $query->bindParam(':list_name', $this->list_name, PDO::PARAM_STR);
            $query->execute();

        } else {

            $query = $this->pdo->prepare("INSERT INTO lists SET list_name=:list_name, campaign_id=:campaignId, entryDate=:entryDate, added_by='ADL', totalDialled=:totalDialled, total_records=:totalRecords, active=:active, fullListId=:fullListId, list_id=:list_id");
            $query->bindParam(':entryDate', $this->entryDate, PDO::PARAM_STR);
            $query->bindParam(':list_name', $this->list_name, PDO::PARAM_STR);
            $query->bindParam(':campaignId', $this->campaignId, PDO::PARAM_STR);
            $query->bindParam(':totalDialled', $this->totalDialled, PDO::PARAM_STR);
            $query->bindParam(':totalRecords', $this->totalRecords, PDO::PARAM_STR);
            $query->bindParam(':active', $this->active, PDO::PARAM_STR);
            $query->bindParam(':list_id', $this->list_id, PDO::PARAM_STR);
            $query->bindParam(':fullListId', $fullListId, PDO::PARAM_STR);
            $query->execute();
        }

        return 'success';

    }

    /**
     * @param mixed $totalRecords
     */
    public function setTotalRecords($totalRecords)
    {
        $this->totalRecords = $totalRecords;
    }


    /**
     * @param mixed $list_id
     */
    public function setListId($list_id)
    {
        $this->list_id = $list_id;
    }

    /**
     * @param mixed $list_name
     */
    public function setListName($list_name)
    {
        $this->list_name = $list_name;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @param mixed $totalDialled
     */
    public function setTotalDialled($totalDialled)
    {
        $this->totalDialled = $totalDialled;
    }

    /**
     * @param mixed $campaignId
     */
    public function setCampaignId($campaignId)
    {
        $this->campaignId = $campaignId;
    }

    /**
     * @param mixed $entryDate
     */
    public function setEntryDate($entryDate)
    {
        $this->entryDate = $entryDate;
    }


}
