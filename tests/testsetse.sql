SELECT list_id, sum(callCount) as callCount
FROM (select tblx.list_id, count(*) AS callCount
      FROM (select outbound_log.list_id
            FROM outbound_log
                     LEFT OUTER JOIN agent_log USING (uniqueid, lead_id)
            WHERE call_date >= CURDATE()) tblx
      group by list_id

      UNION ALL
      SELECT inbound_log.list_id, count(*) AS callCount
      FROM inbound_log
      WHERE call_date >= CURDATE()
      GROUP BY inbound_log.list_id) AS data
GROUP BY list_id;



SELECT list_id, status, sum(callCount) as callCount, sum(duration) as duration, sum(handleTime) as handleTime
FROM (SELECT tblx.list_id,
             tblx.status,
             count(*)                                                           AS callCount,
             sum(length_in_sec)                                                 as duration,
             SUM(CAST(tblx.talk_sec AS SIGNED) - CAST(tblx.dead_sec AS SIGNED)) AS handleTime
      FROM (select outbound_log.list_id, outbound_log.status, outbound_log.call_date, length_in_sec, talk_sec, dead_sec
            FROM outbound_log
                     LEFT OUTER JOIN agent_log USING (uniqueid, lead_id)
            WHERE call_date >= CURDATE()) tblx
      GROUP BY list_id, STATUS

      UNION ALL

      SELECT inbound_log.list_id,
             inbound_log.status,
             count(*)           AS callCount,
             sum(length_in_sec) as duration,
             ''                 AS handleTime
      FROM inbound_log
      WHERE call_date >= CURDATE()
      GROUP BY inbound_log.list_id, STATUS) AS data
GROUP BY list_id, status
