<?php
session_start();
function login()
{
    include 'config/db.php';

//    header('Location: dashboard.php');
    $sql = "select users.id user,users.first_name name from users where users.username = '$_POST[user]';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user = $row['user'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user'];

        }
        $sql = "SELECT * FROM privileges_users AS A
                INNER JOIN privileges_users AS B ON A.user_id = B.user_id
                WHERE A.privilege_id = 26 AND A.user_id='$user'";

//        echo $sql;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $_SESSION['login'] = 1;
            header('Location: dashboard.php');
        } else {
            $sql = "SELECT * FROM users WHERE id='$user' AND ( username in ('Jamsheed','admin','Hesham', 1221,1554,1524)) ";
//        echo $sql;
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $_SESSION['login'] = 1;
                header('Location: finance.php');

            } else {
                $_SESSION['noaccess'] = 1;
                header('Location: index.php');
            }
        }
    }


}

function checkLoggedIn()
{
    if (!isset($_SESSION['login'])) {
        $_SESSION['notloggedin'] = 1;
        header('Location: index.php');
    }
}

