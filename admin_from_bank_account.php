<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
*/


require "db.php";


/*
|--------------------------------------------------------------------------
| ADMIN LOGIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['admin'])) {

    header("Location: index.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| UPLOAD DIRECTORY
|--------------------------------------------------------------------------
*/

$upload_dir = __DIR__ . "/uploads/";

if (!is_dir($upload_dir)) {

    mkdir(
        $upload_dir,
        0755,
        true
    );

}


/*
|--------------------------------------------------------------------------
| UPLOAD IMAGE FUNCTION
|--------------------------------------------------------------------------
*/

function upload_image($file, $upload_dir)
{

    if (
        !isset($file) ||
        $file['error'] !== UPLOAD_ERR_OK
    ) {

        return '';

    }


    /*
    |--------------------------------------------------------------------------
    | MAX 5MB
    |--------------------------------------------------------------------------
    */

    if ($file['size'] > 5 * 1024 * 1024) {

        die("Image file is too large. Maximum 5MB.");

    }


    /*
    |--------------------------------------------------------------------------
    | MIME CHECK
    |--------------------------------------------------------------------------
    */

    $allowed = [

        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'

    ];


    $finfo = finfo_open(FILEINFO_MIME_TYPE);

    $mime = finfo_file(
        $finfo,
        $file['tmp_name']
    );

    finfo_close($finfo);


    if (!isset($allowed[$mime])) {

        die("Invalid image format. Use JPG, PNG or WEBP.");

    }


    /*
    |--------------------------------------------------------------------------
    | UNIQUE FILE NAME
    |--------------------------------------------------------------------------
    */

    $filename =
        time() .
        '_' .
        bin2hex(random_bytes(5)) .
        '.' .
        $allowed[$mime];


    $destination =
        $upload_dir .
        $filename;


    /*
    |--------------------------------------------------------------------------
    | MOVE FILE
    |--------------------------------------------------------------------------
    */

    if (!move_uploaded_file(
        $file['tmp_name'],
        $destination
    )) {

        die("Failed to upload image.");

    }


    return $filename;

}


/*
|--------------------------------------------------------------------------
| DELETE FILE
|--------------------------------------------------------------------------
*/

function delete_upload($filename, $upload_dir)
{

    if (
        empty($filename)
    ) {

        return;

    }


    $filename =
        basename($filename);


    $file =
        $upload_dir .
        $filename;


    if (
        is_file($file)
    ) {

        @unlink($file);

    }

}


/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

if (isset($_GET['delete'])) {

    $id =
        (int)$_GET['delete'];


    if ($id > 0) {

        $stmt =
            $conn->prepare("
                SELECT
                    image,
                    qr_image
                FROM
                    admin_from_bank_account
                WHERE
                    id = ?
                LIMIT 1
            ");


        if (!$stmt) {

            die(
                "Database error: " .
                htmlspecialchars($conn->error)
            );

        }


        $stmt->bind_param(
            "i",
            $id
        );


        $stmt->execute();


        $row =
            $stmt
            ->get_result()
            ->fetch_assoc();


        $stmt->close();


        if ($row) {

            delete_upload(
                $row['image'] ?? '',
                $upload_dir
            );


            delete_upload(
                $row['qr_image'] ?? '',
                $upload_dir
            );


            $stmt =
                $conn->prepare("
                    DELETE FROM
                        admin_from_bank_account
                    WHERE
                        id = ?
                ");


            $stmt->bind_param(
                "i",
                $id
            );


            $stmt->execute();


            $stmt->close();

        }

    }


    header(
        "Location: admin_from_bank_account.php"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/

$edit = null;


if (isset($_GET['edit'])) {

    $id =
        (int)$_GET['edit'];


    if ($id > 0) {

        $stmt =
            $conn->prepare("
                SELECT *
                FROM admin_from_bank_account
                WHERE id = ?
                LIMIT 1
            ");


        if (!$stmt) {

            die(
                "Database error: " .
                htmlspecialchars($conn->error)
            );

        }


        $stmt->bind_param(
            "i",
            $id
        );


        $stmt->execute();


        $edit =
            $stmt
            ->get_result()
            ->fetch_assoc();


        $stmt->close();

    }

}


/*
|--------------------------------------------------------------------------
| SAVE / UPDATE
|--------------------------------------------------------------------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {

    $id =
        (int)(
            $_POST['id']
            ?? 0
        );


    $type =
        strtolower(
            trim(
                $_POST['type']
                ?? 'bank'
            )
        );


    $bank_name =
        trim(
            $_POST['bank_name']
            ?? ''
        );


    $account_name =
        trim(
            $_POST['account_name']
            ?? ''
        );


    $account_number =
        trim(
            $_POST['account_number']
            ?? ''
        );


    $status =
        strtolower(
            trim(
                $_POST['status']
                ?? 'online'
            )
        );


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    if (
        !in_array(
            $type,
            ['bank', 'card'],
            true
        )
    ) {

        $type = 'bank';

    }


    if (
        !in_array(
            $status,
            [
                'online',
                'maintenance',
                'offline'
            ],
            true
        )
    ) {

        $status = 'online';

    }


    if ($bank_name === '') {

        die("Payment method name is required.");

    }


    /*
    |--------------------------------------------------------------------------
    | CURRENT FILES
    |--------------------------------------------------------------------------
    */

    $old_image = '';

    $old_qr_image = '';


    if ($id > 0) {

        $stmt =
            $conn->prepare("
                SELECT
                    image,
                    qr_image
                FROM
                    admin_from_bank_account
                WHERE
                    id = ?
                LIMIT 1
            ");


        $stmt->bind_param(
            "i",
            $id
        );


        $stmt->execute();


        $old =
            $stmt
            ->get_result()
            ->fetch_assoc();


        $stmt->close();


        if ($old) {

            $old_image =
                $old['image']
                ?? '';

            $old_qr_image =
                $old['qr_image']
                ?? '';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LOGO / BANK IMAGE
    |--------------------------------------------------------------------------
    */

    $image = $old_image;


    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $new_image =
            upload_image(
                $_FILES['image'],
                $upload_dir
            );


        if ($new_image !== '') {

            delete_upload(
                $old_image,
                $upload_dir
            );


            $image =
                $new_image;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | QR IMAGE
    |--------------------------------------------------------------------------
    */

    $qr_image =
        $old_qr_image;


    if (
        isset($_FILES['qr_image']) &&
        $_FILES['qr_image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $new_qr_image =
            upload_image(
                $_FILES['qr_image'],
                $upload_dir
            );


        if ($new_qr_image !== '') {

            delete_upload(
                $old_qr_image,
                $upload_dir
            );


            $qr_image =
                $new_qr_image;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    if ($id > 0) {

        $stmt =
            $conn->prepare("
                UPDATE
                    admin_from_bank_account
                SET
                    type = ?,
                    bank_name = ?,
                    account_name = ?,
                    account_number = ?,
                    image = ?,
                    qr_image = ?,
                    status = ?
                WHERE
                    id = ?
            ");


        if (!$stmt) {

            die(
                "Database error: " .
                htmlspecialchars($conn->error)
            );

        }


        $stmt->bind_param(
            "sssssssi",
            $type,
            $bank_name,
            $account_name,
            $account_number,
            $image,
            $qr_image,
            $status,
            $id
        );


        if (!$stmt->execute()) {

            die(
                "Update failed: " .
                htmlspecialchars($stmt->error)
            );

        }


        $stmt->close();

    }


    /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */

    else {

        $stmt =
            $conn->prepare("
                INSERT INTO
                    admin_from_bank_account
                (
                    type,
                    bank_name,
                    account_name,
                    account_number,
                    image,
                    qr_image,
                    status
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )
            ");


        if (!$stmt) {

            die(
                "Database error: " .
                htmlspecialchars($conn->error)
            );

        }


        $stmt->bind_param(
            "sssssss",
            $type,
            $bank_name,
            $account_name,
            $account_number,
            $image,
            $qr_image,
            $status
        );


        if (!$stmt->execute()) {

            die(
                "Insert failed: " .
                htmlspecialchars($stmt->error)
            );

        }


        $stmt->close();

    }


    header(
        "Location: admin_from_bank_account.php"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| LIST
|--------------------------------------------------------------------------
*/

$list =
    $conn->query("
        SELECT *
        FROM admin_from_bank_account
        ORDER BY
            type ASC,
            id DESC
    ");


if (!$list) {

    die(
        "Database query failed: " .
        htmlspecialchars($conn->error)
    );

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
CNTECH STORE - Payment Methods
</title>


<style>

* {

    margin: 0;

    padding: 0;

    box-sizing: border-box;

}


body {

    background: #f5f7fb;

    color: #111827;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

}


.container {

    width: 95%;

    max-width: 1400px;

    margin: 30px auto;

}


.card {

    background: #fff;

    padding: 25px;

    border-radius: 18px;

    margin-bottom: 22px;

    box-shadow:
        0 5px 25px
        rgba(0,0,0,.07);

}


h1 {

    margin-bottom: 8px;

}


.subtitle {

    color: #6b7280;

    margin-bottom: 22px;

}


.grid {

    display: grid;

    grid-template-columns:
        repeat(
            auto-fit,
            minmax(220px, 1fr)
        );

    gap: 15px;

}


.field {

    display: flex;

    flex-direction: column;

    gap: 7px;

}


.field label {

    font-size: 13px;

    font-weight: 700;

    color: #374151;

}


input,
select {

    width: 100%;

    padding: 12px 13px;

    border:
        1px solid #d1d5db;

    border-radius: 10px;

    background: #fff;

    font-size: 14px;

}


input:focus,
select:focus {

    outline: none;

    border-color: #2563eb;

}


.upload-box {

    border:
        1px dashed #cbd5e1;

    padding: 12px;

    border-radius: 10px;

    background: #f8fafc;

}


.preview {

    margin-top: 10px;

}


.preview img {

    width: 80px;

    height: 80px;

    object-fit: contain;

    border-radius: 10px;

    border:
        1px solid #ddd;

    background: #fff;

    padding: 5px;

}


.qr-preview img {

    width: 120px;

    height: 120px;

}


.actions {

    display: flex;

    align-items: end;

}


button {

    width: 100%;

    background: #e11d48;

    color: #fff;

    border: none;

    padding: 13px 18px;

    border-radius: 10px;

    cursor: pointer;

    font-size: 15px;

    font-weight: 700;

}


button:hover {

    background: #be123c;

}


.cancel {

    display: inline-block;

    margin-top: 15px;

    color: #6b7280;

    text-decoration: none;

}


.table-wrapper {

    width: 100%;

    overflow-x: auto;

}


table {

    width: 100%;

    min-width: 1000px;

    border-collapse: collapse;

}


th {

    background: #111827;

    color: #fff;

    padding: 13px;

    font-size: 13px;

}


td {

    padding: 12px;

    border-bottom:
        1px solid #e5e7eb;

    text-align: center;

    vertical-align: middle;

    font-size: 13px;

}


.logo-image {

    width: 65px;

    height: 65px;

    object-fit: contain;

    background: #f3f4f6;

    border-radius: 10px;

    padding: 5px;

}


.qr-image {

    width: 90px;

    height: 90px;

    object-fit: contain;

    background: #fff;

    border-radius: 10px;

    border:
        1px solid #ddd;

    padding: 5px;

}


.no-image {

    width: 65px;

    height: 65px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    background: #f3f4f6;

    color: #9ca3af;

    border-radius: 10px;

    font-size: 11px;

}


.badge {

    display: inline-block;

    padding: 5px 10px;

    border-radius: 20px;

    font-size: 11px;

    font-weight: 700;

}


.badge.bank {

    background: #dbeafe;

    color: #1d4ed8;

}


.badge.card {

    background: #f3e8ff;

    color: #7e22ce;

}


.online {

    color: #15803d;

    font-weight: 700;

}


.maintenance {

    color: #d97706;

    font-weight: 700;

}


.offline {

    color: #dc2626;

    font-weight: 700;

}


.action {

    display: flex;

    justify-content: center;

    gap: 7px;

}


.edit {

    background: #f59e0b;

    color: #fff;

    padding: 8px 11px;

    border-radius: 8px;

    text-decoration: none;

}


.delete {

    background: #ef4444;

    color: #fff;

    padding: 8px 11px;

    border-radius: 8px;

    text-decoration: none;

}


.edit:hover {

    background: #d97706;

}


.delete:hover {

    background: #dc2626;

}


@media (max-width: 600px) {

    .container {

        width: 94%;

        margin: 15px auto;

    }


    .card {

        padding: 18px;

    }


    h1 {

        font-size: 22px;

    }

}

</style>

</head>


<body>


<div class="container">


<div class="card">

<h1>

🏦 Payment Method Manager

</h1>

<div class="subtitle">

Manage Bank, QR Payment and Credit/Debit Card methods.

</div>


<form
    method="POST"
    enctype="multipart/form-data"
>


<input
    type="hidden"
    name="id"
    value="<?=htmlspecialchars(
        $edit['id'] ?? ''
    )?>"
>


<div class="grid">


<div class="field">

<label>
Payment Type
</label>

<select name="type">

<option
    value="bank"
    <?=(
        ($edit['type'] ?? 'bank')
        === 'bank'
    )
        ? 'selected'
        : ''
    ?>
>
BANK
</option>

<option
    value="card"
    <?=(
        ($edit['type'] ?? '')
        === 'card'
    )
        ? 'selected'
        : ''
    ?>
>
CARD
</option>

</select>

</div>


<div class="field">

<label>
Bank / Payment Name
</label>

<input
    type="text"
    name="bank_name"
    placeholder="BCEL ONE / LDB BANK / VISA | MASTERCARD"
    value="<?=htmlspecialchars(
        $edit['bank_name'] ?? ''
    )?>"
    required
>

</div>


<div class="field">

<label>
Account Name
</label>

<input
    type="text"
    name="account_name"
    placeholder="Account Name"
    value="<?=htmlspecialchars(
        $edit['account_name'] ?? ''
    )?>"
>

</div>


<div class="field">

<label>
Account Number
</label>

<input
    type="text"
    name="account_number"
    placeholder="Account Number"
    value="<?=htmlspecialchars(
        $edit['account_number'] ?? ''
    )?>"
>

</div>


<div class="field">

<label>
Status
</label>

<select name="status">

<option
    value="online"
    <?=(
        ($edit['status'] ?? 'online')
        === 'online'
    )
        ? 'selected'
        : ''
    ?>
>
ONLINE
</option>

<option
    value="maintenance"
    <?=(
        ($edit['status'] ?? '')
        === 'maintenance'
    )
        ? 'selected'
        : ''
    ?>
>
MAINTENANCE
</option>

<option
    value="offline"
    <?=(
        ($edit['status'] ?? '')
        === 'offline'
    )
        ? 'selected'
        : ''
    ?>
>
OFFLINE
</option>

</select>

</div>


<div class="field">

<label>
Logo / Bank Image
</label>

<div class="upload-box">

<input
    type="file"
    name="image"
    accept="image/jpeg,image/png,image/webp"
>


<?php if (
    !empty($edit['image'])
): ?>

<div class="preview">

<img
    src="uploads/<?=htmlspecialchars(
        basename(
            $edit['image']
        )
    )?>"
    alt="Logo"
>

</div>

<?php endif; ?>

</div>

</div>


<div class="field">

<label>
QR Payment Image
</label>

<div class="upload-box">

<input
    type="file"
    name="qr_image"
    accept="image/jpeg,image/png,image/webp"
>


<?php if (
    !empty($edit['qr_image'])
): ?>

<div class="preview qr-preview">

<img
    src="uploads/<?=htmlspecialchars(
        basename(
            $edit['qr_image']
        )
    )?>"
    alt="QR Code"
>

</div>

<?php endif; ?>

</div>

</div>


<div class="field actions">

<button type="submit">

<?= $edit
    ? '✓ Update Payment Method'
    : '＋ Add Payment Method'
?>

</button>

</div>


</div>


<?php if ($edit): ?>

<a
    href="admin_from_bank_account.php"
    class="cancel"
>
Cancel Edit
</a>

<?php endif; ?>


</form>

</div>


<div class="card">


<h2 style="margin-bottom:18px;">

Payment Methods

</h2>


<div class="table-wrapper">


<table>

<thead>

<tr>

<th>ID</th>

<th>Type</th>

<th>Logo</th>

<th>QR</th>

<th>Name</th>

<th>Account Name</th>

<th>Account Number</th>

<th>Status</th>

<th>Action</th>

</tr>

</thead>


<tbody>

<?php while (
    $row =
    $list->fetch_assoc()
): ?>


<tr>


<td>

<?= (int)$row['id'] ?>

</td>


<td>

<span
    class="badge <?=(
        $row['type'] === 'card'
            ? 'card'
            : 'bank'
    )?>"
>

<?=strtoupper(
    htmlspecialchars(
        $row['type']
    )
)?>

</span>

</td>


<td>

<?php if (
    !empty($row['image'])
): ?>

<img
    class="logo-image"
    src="uploads/<?=htmlspecialchars(
        basename(
            $row['image']
        )
    )?>"
    alt="Logo"
>

<?php else: ?>

<span class="no-image">
No Logo
</span>

<?php endif; ?>

</td>


<td>

<?php if (
    !empty($row['qr_image'])
): ?>

<img
    class="qr-image"
    src="uploads/<?=htmlspecialchars(
        basename(
            $row['qr_image']
        )
    )?>"
    alt="QR"
>

<?php else: ?>

<span class="no-image">
No QR
</span>

<?php endif; ?>

</td>


<td>

<strong>

<?=htmlspecialchars(
    $row['bank_name']
)?>

</strong>

</td>


<td>

<?=htmlspecialchars(
    $row['account_name']
    ?? ''
)?>

</td>


<td>

<?=htmlspecialchars(
    $row['account_number']
    ?? ''
)?>

</td>


<td>

<span
    class="<?=htmlspecialchars(
        $row['status']
    )?>"
>

<?=strtoupper(
    htmlspecialchars(
        $row['status']
    )
)?>

</span>

</td>


<td>

<div class="action">


<a
    class="edit"
    href="?edit=<?=(int)$row['id']?>"
>
Edit
</a>


<a
    class="delete"
    href="?delete=<?=(int)$row['id']?>"
    onclick="
        return confirm(
            'Delete this payment method and its images?'
        );
    "
>
Delete
</a>


</div>

</td>


</tr>


<?php endwhile; ?>

</tbody>

</table>


</div>

</div>


</div>


</body>

</html>