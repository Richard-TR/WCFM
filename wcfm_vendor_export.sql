/*
Run against your database to retrieve list of vendors currently on your store.

Dont forget to replace 2x DB_NAME and 2x PREFIX with the database name and table prefix

You can modify this to include any information about the vendor you would like.
You can also filter this further, for example by using the meta_key '_wcfm_store_offline' 
to return only online stores.
*/

SELECT ID, display_name, user_login, user_email
FROM (SELECT * FROM `DB_NAME`.`PREFIX_usermeta`) T1 
INNER JOIN (SELECT * FROM `DB_NAME`.`PREFIX_users`) T2 ON T1.`user_ID` = T2.`ID`
WHERE meta_key LIKE '%wcfm_vendor%'
GROUP BY user_ID
