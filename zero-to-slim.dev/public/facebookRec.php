<?php
//first we are going to connect to the database, make sure to replace
//the password to connect to your SQL
try
{
    $dbc = new PDO('mysql:host=localhost;dbname=facebook', 'testuser', 'test');
}
catch (PDOException $e)
{
    echo $e->getMessage()."<br>";
    die();
}
//we are going to take the query and put it into a variable
$query = 'SELECT
    mems.name                         AS member_name,
    friends_friends.friend            AS suggested_friend_name,
    COUNT(*)                          AS friends_in_common
FROM
    Members      AS mems
INNER JOIN
    Friends      AS friends
    ON friends.user = mems.name
INNER JOIN
    Friends      AS friends_friends
    ON friends_friends.user = friends.friend
LEFT JOIN
    Friends      AS friends_with_user
    ON  friends_with_user.user    = friends_friends.friend
    AND friends_with_user.friend = mems.name
WHERE
    friends_with_user.user IS NULL
GROUP BY
    mems.name,
    friends_friends.friend
ORDER BY
    friends_in_common DESC
';

//now we want to input the information to the page in a nicely //formatted fashion
echo '<table align="left"
cellspacing="5" cellpadding="8">

<tr><td align="center"><b>Person 1</b></td>
<td align="center"><b>Person 2</b></td>
<td align="center"><b>Mutual Friends</b></td></tr>';

//this is where we actually put the information into the html table
foreach( $dbc->query($query) as $row){

echo '<tr><td align="left">' .
$row['member_name'] . '</td><td align="center">' .
$row['suggested_friend_name'] . '</td><td align="center">' .
$row['friends_in_common'] . '</td><td align="center">';

echo '</tr>';
}

echo '</table>';

// Close connection to the database
$dbc = null;

 ?>