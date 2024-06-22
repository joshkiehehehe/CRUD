<?php
include_once("connects.php");

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header('Location: customers.php');
    exit;
}

$statement = $conn->prepare("SELECT * FROM customer WHERE customer_id = :id");
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$customer = $statement->fetch(PDO::FETCH_ASSOC);

$errors = [];
$result = false; // Set default to false to avoid errors on first load
$fullname = $customer['fullname'] ?? '';
$username = $customer['username'] ?? '';
$email = $customer['email'] ?? '';
$address = $customer['customer_address'] ?? '';
$phonenumber = $customer['phonenumber'] ?? '';
$imagePath = $customer['customer_image'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['Customer_Name'];
    $username = $_POST['Username'];
    $email = $_POST['Email'];
    $address = $_POST['Address'];
    $phonenumber = $_POST['Phone_Number'];
    $existingImagePath = $_POST['customers_image'];

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
        $imagePath = $existingImagePath; // Keep the existing image path if no new image is uploaded
    }

    if (empty($errors)) {
        $statement = $conn->prepare("UPDATE customer SET fullname = :fullname, customer_image = :customers_image, username = :username, email = :email, customer_address = :customer_address, phonenumber = :phonenumber WHERE customer_id = :id");

        $statement->bindValue(':fullname', $fullname);
        $statement->bindValue(':customers_image', $imagePath);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':customer_address', $address);
        $statement->bindValue(':phonenumber', $phonenumber);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $result = $statement->execute();

        if ($result) {
            echo "<script>alert('Record Updated Successfully')</script>";
        } else {
            $errors[] = 'Database error: ' . $statement->errorInfo()[2];
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
    <link rel="stylesheet" href="style.css"> <!-- Link to your custom CSS -->
    <title>Update Customer Record!</title>
</head>
<body>
    <h1>Update Customer Record!</h1>
    <br>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <div><?php echo htmlspecialchars($error); ?></div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($result): ?>
        <div class="alert alert-success">
            <?php echo 'Updated Successfully!'; ?>
        </div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">

        <input type="hidden" name="customers_image" value="<?php echo htmlspecialchars($customer['customer_image']); ?>">

        <?php if ($customer['customer_image']): ?>
            <img src="<?php echo htmlspecialchars($customer['customer_image']); ?>" alt="Customer Image" style="width: 150px; height: auto;">
            <!-- Debugging Output -->
            <p>Image Path: <?php echo htmlspecialchars($customer['customer_image']); ?></p>
        <?php else: ?>
            <p>No Image Available</p>
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Customer Picture</label>
            <input type="file" name="image" class="form-control">
        </div>  

        <div class="mb-3">
            <label class="form-label">Customer Name</label>
            <input type="text" name="Customer_Name" value="<?php echo htmlspecialchars($customer['fullname']); ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="Username" value="<?php echo htmlspecialchars($customer['username']); ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="text" name="Email" value="<?php echo htmlspecialchars($customer['email']); ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="Address" class="form-control"><?php echo htmlspecialchars($customer['customer_address']); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Contact</label>
            <input type="text" name="Phone_Number" value="<?php echo htmlspecialchars($customer['phonenumber']); ?>" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="customers.php" class="btn btn-primary">Back</a>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
