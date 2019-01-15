SELECT *
FROM (
       SELECT
         t.userId,
         t.userPhoto,
         t.userNickname,
         (YEAR(CURRENT_DATE) - YEAR(t.userBdate)) - (RIGHT(CURRENT_DATE, 5) < RIGHT(t.userBdate, 5)) AS userAge,
         t.userRole,
         t.userSearchShow,
         t.userStatus,
         t.userActive,
         t.userCity,
         t.userGenderId,
         t2.paramEn                                                                                  AS userCountry,
         t.userPayFor,
         (SELECT GROUP_CONCAT(t3.place)
          FROM ctbl_user_want_to_visit AS t3
          WHERE t3.userId = t.userId
          GROUP BY t3.userId)                                                                        AS userWantToVisit
       FROM tbl_user AS t
         LEFT JOIN tbl_param_country AS t2
           ON t2.paramId = t.userCountryId
     ) AS total
WHERE
  total.userSearchShow = 1 AND total.userActive = 1
  AND total.userId != false
  AND total.userGenderId IN (1,2)
HAVING total.userAge >= 18 AND total.userAge <= 50
ORDER BY total.userId DESC;