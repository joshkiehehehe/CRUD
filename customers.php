<?php 
    include_once("connects.php");

    $statement = $conn->prepare("SELECT * FROM customer");
    $statement->execute();
    $cater = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">

    <title>Catering Reservation</title>
</head>
<body>
    <h1>Customer</h1>

    <a href="create.php" type="button" class="btn btn-success">Add Customer</a>
    
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Customer ID</th>
                <th scope="col">Customer Pic</th>
                <th scope="col">Full Name</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Address</th>
                <th scope="col">Contact</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($cater as $i => $customers): ?>
                <tr>
                    <th scope="row"><?php echo $i + 1 ?> </th>
                    <td><?php echo htmlspecialchars($customers['customer_id']) ?></td>
                    <td><img src="<?php echo htmlspecialchars($customers['customer_image']) ?>" class="size"></td>
                    <td><?php echo htmlspecialchars($customers['fullname']) ?></td>
                    <td><?php echo htmlspecialchars($customers['username']) ?></td>
                    <td><?php echo htmlspecialchars($customers['email']) ?></td>
                    <td><?php echo htmlspecialchars($customers['customer_address']) ?></td>
                    <td><?php echo htmlspecialchars($customers['phonenumber']) ?></td>
                    <td>
                        <a href="update.php?id=<?php echo htmlspecialchars($customers['customer_id']); ?>" class="btn btn-primary btn-sm">Edit</a>
                        <form style="display: inline-block;" method="POST" action="delete.php">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($customers['customer_id']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>
</html>
