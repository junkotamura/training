SELECT
  DATE(access_datetime) AS date,
  COUNT(user_id) AS pv,
  COUNT(DISTINCT user_id) AS uu 
  
FROM
  access
GROUP BY DATE(access_datetime);
