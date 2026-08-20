<?php

session_start();

require_once "database.php";


if(!isset($_SESSION['user_id'])){

    header("Location: login.php");
    exit;

}


$user_id = $_SESSION['user_id'];



if(isset($_POST['save_profile'])){


    $fullname =
    trim($_POST['fullname']);


    $email =
    trim($_POST['email']);


    $gender =
    trim($_POST['gender']);


    $birthday =
    trim($_POST['birthday']);



    /*
    =========================
    CALCULATE AGE
    =========================
    */

    $age = 0;


    if(!empty($birthday)){

        $birth =
        new DateTime($birthday);

        $today =
        new DateTime();

        $age =
        $today->diff($birth)->y;

    }



    /*
    =========================
    GET OLD AVATAR
    =========================
    */

    $stmt =
    $conn->prepare("

    SELECT avatar

    FROM users

    WHERE id=?

    LIMIT 1

    ");

    $stmt->bind_param(
    "i",
    $user_id
    );

    $stmt->execute();

    $old =
    $stmt->get_result()->fetch_assoc();

    $avatar =
    $old['avatar'] ?? 'default.png';



    /*
    =========================
    UPLOAD AVATAR
    =========================
    */

    if(

        isset($_FILES['avatar'])

        &&

        $_FILES['avatar']['error']==0

    ){


        $upload_dir =
        "uploads/avatar/";


        if(!is_dir($upload_dir)){

            mkdir(
                $upload_dir,
                0777,
                true
            );

        }


        $ext =
        strtolower(

        pathinfo(

        $_FILES['avatar']['name'],

        PATHINFO_EXTENSION

        )

        );


        $allowed = [

        'jpg',
        'jpeg',
        'png',
        'webp'

        ];


        if(in_array($ext,$allowed)){


            $avatar =

            "avatar_"

            .$user_id."_"

            .time()."."

            .$ext;



            move_uploaded_file(

            $_FILES['avatar']['tmp_name'],

            $upload_dir.$avatar

            );

        }

    }



    /*
    =========================
    UPDATE USER
    =========================
    */

    $stmt =
    $conn->prepare("

    UPDATE users

    SET

    fullname=?,
    email=?,
    gender=?,
    birthday=?,
    age=?,
    avatar=?

    WHERE id=?

    ");



    $stmt->bind_param(

    "ssssisi",

    $fullname,
    $email,
    $gender,
    $birthday,
    $age,
    $avatar,
    $user_id

    );



    if($stmt->execute()){


        $_SESSION['success'] =

        "Profile updated successfully";


    }else{


        $_SESSION['error'] =

        $conn->error;

    }



    header(
    "Location: profile.php"
    );

    exit;

}



header(
"Location: profile.php"
);

exit;