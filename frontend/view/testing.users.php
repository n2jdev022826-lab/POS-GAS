<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../../api/users/create.php" method="POST">

                <div class="modal-grid">

                    <div class="input-group">
                        <label>First Name</label>
                        <input type="text" required name="firstname">
                    </div>

                    <div class="input-group">
                        <label>Middle Name</label>
                        <input type="text" name="middlename">
                    </div>

                    <div class="input-group">
                        <label>Last Name</label>
                        <input type="text" required name="lastname">
                    </div>

                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" required name="username">
                    </div>

                    <div class="input-group">
                        <label>Sex</label>
                        <select name="sex" required>
                            <option value="">Select Sex</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    

                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" required name="email">
                    </div>

                    <div class="input-group">
                        <label>Role</label>
                        <select name="role" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="cashier">Cashier</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Contact No.</label>
                        <input type="text" required name="phone">
                    </div>

                    <div class="input-group">
                        <label>Date Hired</label>
                        <input type="date" name="hire_date">
                    </div>

                    <div class="input-group">
                        <label>Address</label>
                        <input type="text" name="address" required>
                    </div>

                    <div class="input-group">
                        <label>Birth Date/label>
                        <input type="date" name="birthdate" required>
                    </div>

                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" required name="password">
                    </div>

                </div>

                <div class="modal-buttons">
                    <button type="button" class="cancel-btn" onclick="closeUserModal()">Cancel</button>
                    <button type="submit" class="save-btn">Save User</button>
                </div>

            </form>
</body>
</html>