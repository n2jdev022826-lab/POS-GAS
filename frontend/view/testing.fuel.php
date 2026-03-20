<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../../api/fuel/create.php" method="POST">
        <input type="text" name="fuel_name" placeholder="Enter Fuel Name">
        <input type="number" name="fuel_price" placeholder="Enter Fuel Price" step="0.01">
        <button type="submit">Add Fuel</button>
    </form>
</body>
</html>