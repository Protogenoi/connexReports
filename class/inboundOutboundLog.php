<?php
/**
 * ------------------------------------------------------------------------
 *                               ADL CRM
 * ------------------------------------------------------------------------
 *
 * Copyright © 2022 ADL CRM All rights reserved.
 *
 * Unauthorised copying of this file, via any medium is strictly prohibited.
 * Unauthorised distribution of this file, via any medium is strictly prohibited.
 * Unauthorised modification of this code is strictly prohibited.
 *
 * Proprietary and confidential
 *
 * Written by michael <michael@adl-crm.uk>, 09/01/2022 14:35
 *
 */


class inboundOutboundLog
{

private $uniqueid;
private $lead_id;
private $list_id;
private $campaign_id;
private $call_date;
private $length_in_sec;
private $status;
private $phone_number;
private $user;
private $comments;
private $term_reason;
private $alt_dial;
private $called_count;
private $closecallid;
private $totalCalls;

    public function __construct(PDO $pdo)
    {

        $this->pdo = $pdo;

    }


    public function sendOutboundLogToADL()
    {

        $query = $this->pdo->prepare("INSERT INTO outbound_log SET uniqueid=:uniqueid, lead_id=:lead_id, list_id=:list_id, campaign_id=:campaign_id, call_date=:call_date, length_in_sec=:length_in_sec, status=:status, phone_number=:phone_number, user=:user, comments=:comments, term_reason=:term_reason, alt_dial=:alt_dial, called_count=:called_count
ON DUPLICATE KEY UPDATE 
lead_id=:lead_id1
");
        $query->bindParam(':uniqueid', $this->uniqueid, PDO::PARAM_STR);
        $query->bindParam(':lead_id', $this->lead_id, PDO::PARAM_STR);
        $query->bindParam(':list_id', $this->list_id, PDO::PARAM_STR);
        $query->bindParam(':campaign_id', $this->campaign_id, PDO::PARAM_STR);
        $query->bindParam(':call_date', $this->call_date, PDO::PARAM_STR);
        $query->bindParam(':length_in_sec', $this->length_in_sec, PDO::PARAM_STR);
        $query->bindParam(':status', $this->status, PDO::PARAM_STR);
        $query->bindParam(':phone_number', $this->phone_number, PDO::PARAM_STR);
        $query->bindParam(':user', $this->user, PDO::PARAM_STR);
        $query->bindParam(':comments', $this->comments, PDO::PARAM_STR);
        $query->bindParam(':term_reason', $this->term_reason, PDO::PARAM_STR);
        $query->bindParam(':alt_dial', $this->alt_dial, PDO::PARAM_STR);
        $query->bindParam(':called_count', $this->called_count, PDO::PARAM_STR);
        $query->bindParam(':lead_id1', $this->lead_id, PDO::PARAM_STR);

        $query->execute();


        return 'success';

    }

    public function sendInboundLogToADL()
    {


        $query = $this->pdo->prepare("INSERT INTO inbound_log SET list_id=:list_id, closecallid=:closecallid, lead_id=:lead_id, campaign_id=:campaign_id, call_date=:call_date, length_in_sec=:length_in_sec, status=:status, phone_number=:phone_number, user=:user, comments=:comments, term_reason=:term_reason, called_count=:called_count
ON DUPLICATE KEY UPDATE closecallid=:closecallid1");
        $query->bindParam(':closecallid', $this->closecallid, PDO::PARAM_STR);
        $query->bindParam(':lead_id', $this->lead_id, PDO::PARAM_STR);
        $query->bindParam(':list_id', $this->list_id, PDO::PARAM_STR);
        $query->bindParam(':campaign_id', $this->campaign_id, PDO::PARAM_STR);
        $query->bindParam(':call_date', $this->call_date, PDO::PARAM_STR);
        $query->bindParam(':length_in_sec', $this->length_in_sec, PDO::PARAM_STR);
        $query->bindParam(':status', $this->status, PDO::PARAM_STR);
        $query->bindParam(':phone_number', $this->phone_number, PDO::PARAM_STR);
        $query->bindParam(':user', $this->user, PDO::PARAM_STR);
        $query->bindParam(':comments', $this->comments, PDO::PARAM_STR);
        $query->bindParam(':term_reason', $this->term_reason, PDO::PARAM_STR);
        $query->bindParam(':called_count', $this->called_count, PDO::PARAM_STR);
        $query->bindParam(':closecallid1', $this->closecallid, PDO::PARAM_STR);
        $query->execute();


        return 'success';

    }

    public function insertCallCount()
    {

        $query = $this->pdo->prepare("SELECT totalCalls FROM totalCalls WHERE call_date >= CURDATE()");
        $query->execute();
        if ($query->rowCount() <= 0) {

            $query = $this->pdo->prepare("INSERT INTO totalCalls SET totalCalls=:totalCalls, call_date = CURDATE()");
            $query->bindParam(':totalCalls', $this->totalCalls, PDO::PARAM_STR);
            $query->execute();

        } else {
            $query = $this->pdo->prepare("UPDATE totalCalls SET totalCalls=:totalCalls WHERE call_date = CURDATE()");
            $query->bindParam(':totalCalls', $this->totalCalls, PDO::PARAM_STR);
            $query->execute();

        }

        return 'success';

    }

    /**
     * @param mixed $uniqueid
     */
    public function setUniqueid($uniqueid)
    {
        $this->uniqueid = $uniqueid;
    }

    /**
     * @param mixed $lead_id
     */
    public function setLeadId($lead_id)
    {
        $this->lead_id = $lead_id;
    }

    /**
     * @param mixed $list_id
     */
    public function setListId($list_id)
    {
        $this->list_id = $list_id;
    }

    /**
     * @param mixed $campaign_id
     */
    public function setCampaignId($campaign_id)
    {
        $this->campaign_id = $campaign_id;
    }

    /**
     * @param mixed $call_date
     */
    public function setCallDate($call_date)
    {
        $this->call_date = $call_date;
    }

    /**
     * @param mixed $length_in_sec
     */
    public function setLengthInSec($length_in_sec)
    {
        $this->length_in_sec = $length_in_sec;
    }

    /**
     * @param mixed $phone_number
     */
    public function setPhoneNumber($phone_number)
    {
        $this->phone_number = $phone_number;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @param mixed $term_reason
     */
    public function setTermReason($term_reason)
    {
        $this->term_reason = $term_reason;
    }

    /**
     * @param mixed $alt_dial
     */
    public function setAltDial($alt_dial)
    {
        $this->alt_dial = $alt_dial;
    }

    /**
     * @param mixed $called_count
     */
    public function setCalledCount($called_count)
    {
        $this->called_count = $called_count;
    }

    /**
     * @param mixed $closecallid
     */
    public function setClosecallid($closecallid)
    {
        $this->closecallid = $closecallid;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param mixed $totalCalls
     */
    public function setTotalCalls($totalCalls)
    {
        $this->totalCalls = $totalCalls;
    }


}
