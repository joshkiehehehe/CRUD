<?php

include_once("connects.php");

$errors = [];
$result = false;
$fullname = '';
$username = '';
$email = '';
$address = '';
$phonenumber = '';
$imagePath = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['Customer_Name'];
    $username = $_POST['Username'];
    $email = $_POST['Email'];
    $address = $_POST['Address'];
    $phonenumber = $_POST['Phone_Number'];

    // Validate inputs
    if (!$fullname) {
        $errors[] = 'Customer Name is required';
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = randomString(8) . '-' . basename($_FILES['image']['name']);
        $uploadDir = 'images/';
        $imagePath = $uploadDir . $imageName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!move_uploaded_file($imageTmpPath, $imagePath)) {
            $errors[] = 'Error uploading the image';
        }
    } else {
        $errors[] = 'Image is required';
    }

    if (empty($errors)) {
        $statement = $conn->prepare("INSERT INTO customer (fullname, customer_image, username, email, customer_address, phonenumber) VALUES (:fullname, :customer_image, :username, :email, :customer_address, :phonenumber)");

        $statement->bindValue(':fullname', $fullname);
        $statement->bindValue(':customer_image', $imagePath);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':customer_address', $address);
        $statement->bindValue(':phonenumber', $phonenumber);

        $result = $statement->execute();
        if ($result) {
            echo "<script>alert('Record Added Successfully')</script>";
        } else {
            $errors[] = 'Database error';
        }
    }
}

function randomString($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }
    return $str;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Catering Reservation</title>
</head>
<body>s
    <h1>Add Customer!</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <div><?php echo $error ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($result): ?>
        <div class="alert alert-success">
            <?php echo 'Added Successfully!' ?>
        </div>
    <?php endif; ?>

    <form method="post" action="create.php" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Customer Picture</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Customer Name</label>
            <input type="text" name="Customer_Name" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="Username" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="text" name="Email" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="Address" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" name="Phone_Number" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="customers.php" class="btn btn-primary">Back</a>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
