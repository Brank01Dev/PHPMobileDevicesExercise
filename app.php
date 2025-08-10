<?php
require_once 'connect.php';
$conn = new Connection();
$db = $conn->connection;


if (isset($_POST['delete'])) {
    $device_id = $_POST['device_id'];
    $db->query("DELETE FROM devices WHERE id = $device_id");
    header("Location: app.php");
    exit();
}

if (isset($_POST['add'])) {
    $make_id = $_POST['manufacturer_id'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $make_year = $_POST['make_year'];
    $db->query("INSERT INTO devices (manufacturer_id, model, price, make_year) 
                VALUES ($make_id, '$model', $price, $make_year)");
    header("Location: app.php");
    exit();
}

if (isset($_POST['update'])) {
    $device_id =  $_POST['device_id'];
    $make_id =  $_POST['manufacturer_id'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $make_year = $_POST['make_year'];
    $db->query("UPDATE devices 
                SET manufacturer_id = $make_id, model = '$model', price = $price, make_year = $make_year
                WHERE id = $device_id");
    header("Location: app.php");
    exit();
}

$devices = $db->query("
    SELECT d.id, m.make, d.model, d.price, d.make_year, d.import_date 
    FROM devices d
    JOIN manufacturer m ON d.manufacturer_id = m.id
");

$makes = $db->query("SELECT id, make FROM manufacturer");

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/styling.css?v=1.0.3">
    <title>MOBILE DEVICES INVENTORY</title>
</head>
<body>
<h1>MOBILE DEVICES INVENTORY</h1>

<h2>Add New Device</h2>
<form method="POST">
    <div class="show">
    <label>Manufacturer:</label>
    <select name="manufacturer_id" required>
        <?php while($row = $makes->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['make']) ?></option>
        <?php endwhile; ?>
    </select>
    <br>
    <label>Model:</label>
    <input type="text" name="model" required>
    <br>
    <label>Price:</label>
    <input type="number" name="price" required>
    <br>
    <label>Year:</label>
    <input type="number" name="make_year" required>
    <br>
    <button class="button" type="submit" name="add">Add Device</button>
</form>
        </div>

<h2>Devices List</h2>
<hr>
<?php while($device = $devices->fetch_assoc()): ?>
    <div class="list">
    Make: <?= htmlspecialchars($device['make']) ?> <br>
    Model: <?= htmlspecialchars($device['model']) ?> <br>
    Price: <?= htmlspecialchars($device['price']) ?>$<br>
    Release year: <?= htmlspecialchars($device['make_year']) ?> <br>
        Added: <?= htmlspecialchars($device['import_date']) ?>

        
        <form method="POST" >
            <input type="hidden" name="device_id" value="<?= $device['id'] ?>">
            <button class="button" type="submit" name="delete">Delete</button>
        </form>

        <form method="POST">
            <input type="hidden" name="device_id" value="<?= $device['id'] ?>">
            <input type="text" name="model" value="<?= htmlspecialchars($device['model']) ?>" required>
            <input type="number" name="price" value="<?= htmlspecialchars($device['price']) ?>" required>
            <input type="number" name="make_year" value="<?= htmlspecialchars($device['make_year']) ?>" required>
            <select name="manufacturer_id">
                <?php
                $makes2 = $db->query("SELECT id, make FROM manufacturer");
                while($row = $makes2->fetch_assoc()):
                ?>
                    <option value="<?= $row['id'] ?>" <?= ($row['make'] == $device['make']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['make']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button class="button" type="submit" name="update">Update</button>
        </form>
    </div>

<hr>
<?php endwhile; ?>

</body>
</html>