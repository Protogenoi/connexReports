<?php
/*
 * ------------------------------------------------------------------------
 *                               ADL CRM
 * ------------------------------------------------------------------------
 *
 * Copyright © 2018 ADL CRM All rights reserved.
 *
 * Unauthorised copying of this file, via any medium is strictly prohibited.
 * Unauthorised distribution of this file, via any medium is strictly prohibited.
 * Unauthorised modification of this code is strictly prohibited.
 *
 * Proprietary and confidential
 *
 * Written by Michael Owen <michael@adl-crm.uk>, 2018
 *
 * ADL CRM makes use of the following third party open sourced software/tools:
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
 *
*/

$ADL_DB_CONFIG = require_once(BASE_URL . '/includes/adl_database_config.php');

class ADLDatabaseConnection
{

    public static function make($ADL_DB_CONFIG)
    {

        try {

            return new PDO (

                $ADL_DB_CONFIG['connection'] . ';port=' . $ADL_DB_CONFIG['port'] . ';dbname=' . $ADL_DB_CONFIG['name'],
                $ADL_DB_CONFIG['username'],
                $ADL_DB_CONFIG['password'],
                $ADL_DB_CONFIG['options']

            );

        } catch (PDOException $ex) {
            die($ex->getMessage());

        }

    }

}

$adlPdo = ADLDatabaseConnection::make($ADL_DB_CONFIG['database']);
