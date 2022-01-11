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
 * Written by michael <michael@adl-crm.uk>, 09/03/2020 14:35
 *
 */


class recordingLog
{

    private $campaign_id;
    private $end_epoch;
    private $recording_id;
    private $server_ip;
    private $extension;
    private $start_time;
    private $start_epoch;
    private $end_time;
    private $length_in_sec;
    private $length_in_min;
    private $filename;
    private $location;
    private $lead_id;
    private $user;
    private $uniqueid;
    private $list_id;
    private $status;
    private $phone_number;

    public function __construct(PDO $pdo)
    {

        $this->pdo = $pdo;

    }

    public function toADL()
    {

        $query = $this->pdo->prepare("SELECT uniqueid FROM recording_log WHERE uniqueid=:uniqueid");
        $query->bindParam(':uniqueid', $this->uniqueid, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() == 0) {

            $query = $this->pdo->prepare("INSERT INTO recording_log SET 
campaign_id=:campaign_id,
end_epoch=:end_epoch,
recording_id=:recording_id,
server_ip=:server_ip,
extension=:extension,
start_time=:start_time,
start_epoch=:start_epoch,
end_time=:end_time,
length_in_sec=:length_in_sec,
length_in_min=:length_in_min,
filename=:filename,
location=:location,
lead_id=:lead_id,
user=:user,
uniqueid=:uniqueid,
list_id=:list_id,
status=:status,
phone_number=:phone_number");
            $query->bindParam(':campaign_id', $this->campaign_id, PDO::PARAM_STR);
            $query->bindParam(':end_epoch', $this->end_epoch, PDO::PARAM_STR);
            $query->bindParam(':recording_id', $this->recording_id, PDO::PARAM_STR);
            $query->bindParam(':server_ip', $this->server_ip, PDO::PARAM_STR);
            $query->bindParam(':extension', $this->extension, PDO::PARAM_STR);
            $query->bindParam(':start_time', $this->start_time, PDO::PARAM_STR);
            $query->bindParam(':start_epoch', $this->start_epoch, PDO::PARAM_STR);
            $query->bindParam(':end_time', $this->end_time, PDO::PARAM_STR);
            $query->bindParam(':length_in_sec', $this->length_in_sec, PDO::PARAM_STR);
            $query->bindParam(':length_in_min', $this->length_in_min, PDO::PARAM_STR);
            $query->bindParam(':filename', $this->filename, PDO::PARAM_STR);
            $query->bindParam(':location', $this->location, PDO::PARAM_STR);
            $query->bindParam(':lead_id', $this->lead_id, PDO::PARAM_STR);
            $query->bindParam(':user', $this->user, PDO::PARAM_STR);
            $query->bindParam(':uniqueid', $this->uniqueid, PDO::PARAM_STR);
            $query->bindParam(':list_id', $this->list_id, PDO::PARAM_STR);
            $query->bindParam(':status', $this->status, PDO::PARAM_STR);
            $query->bindParam(':phone_number', $this->phone_number, PDO::PARAM_STR);
            $query->execute();

        } else {
            $query = $this->pdo->prepare("UPDATE recording_log SET 
campaign_id=:campaign_id,
end_epoch=:end_epoch,
recording_id=:recording_id,
server_ip=:server_ip,
extension=:extension,
start_time=:start_time,
start_epoch=:start_epoch,
end_time=:end_time,
length_in_sec=:length_in_sec,
length_in_min=:length_in_min,
filename=:filename,
location=:location,
lead_id=:lead_id,
user=:user,
list_id=:list_id,
status=:status,
phone_number=:phone_number WHERE uniqueid=:uniqueid");
            $query->bindParam(':campaign_id', $this->campaign_id, PDO::PARAM_STR);
            $query->bindParam(':end_epoch', $this->end_epoch, PDO::PARAM_STR);
            $query->bindParam(':recording_id', $this->recording_id, PDO::PARAM_STR);
            $query->bindParam(':server_ip', $this->server_ip, PDO::PARAM_STR);
            $query->bindParam(':extension', $this->extension, PDO::PARAM_STR);
            $query->bindParam(':start_time', $this->start_time, PDO::PARAM_STR);
            $query->bindParam(':start_epoch', $this->start_epoch, PDO::PARAM_STR);
            $query->bindParam(':end_time', $this->end_time, PDO::PARAM_STR);
            $query->bindParam(':length_in_sec', $this->length_in_sec, PDO::PARAM_STR);
            $query->bindParam(':length_in_min', $this->length_in_min, PDO::PARAM_STR);
            $query->bindParam(':filename', $this->filename, PDO::PARAM_STR);
            $query->bindParam(':location', $this->location, PDO::PARAM_STR);
            $query->bindParam(':lead_id', $this->lead_id, PDO::PARAM_STR);
            $query->bindParam(':user', $this->user, PDO::PARAM_STR);
            $query->bindParam(':uniqueid', $this->uniqueid, PDO::PARAM_STR);
            $query->bindParam(':list_id', $this->list_id, PDO::PARAM_STR);
            $query->bindParam(':status', $this->status, PDO::PARAM_STR);
            $query->bindParam(':phone_number', $this->phone_number, PDO::PARAM_STR);
            $query->execute();
        }

        return true;

    }

    /**
     * @param mixed $recording_id
     */
    public function setRecordingId($recording_id)
    {
        $this->recording_id = $recording_id;
    }

    /**
     * @param mixed $server_ip
     */
    public function setServerIp($server_ip)
    {
        $this->server_ip = $server_ip;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @param mixed $start_time
     */
    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
    }

    /**
     * @param mixed $start_epoch
     */
    public function setStartEpoch($start_epoch)
    {
        $this->start_epoch = $start_epoch;
    }

    /**
     * @param mixed $end_time
     */
    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;
    }

    /**
     * @param mixed $end_epoch
     */
    public function setEndEpoch($end_epoch)
    {
        $this->end_epoch = $end_epoch;
    }

    /**
     * @param mixed $length_in_sec
     */
    public function setLengthInSec($length_in_sec)
    {
        $this->length_in_sec = $length_in_sec;
    }

    /**
     * @param mixed $length_in_min
     */
    public function setLengthInMin($length_in_min)
    {
        $this->length_in_min = $length_in_min;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @param mixed $lead_id
     */
    public function setLeadId($lead_id)
    {
        $this->lead_id = $lead_id;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @param mixed $uniqueid
     */
    public function setUniqueid($uniqueid)
    {
        $this->uniqueid = $uniqueid;
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
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param mixed $phone_number
     */
    public function setPhoneNumber($phone_number)
    {
        $this->phone_number = $phone_number;
    }


}
