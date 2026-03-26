<?php

require_once "../../../config/database.php";
require_once "../../../backend/middleware/auth.php";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GAS STATION</title>
    <link rel="stylesheet" href="/POS-GAS/frontend/css/cashier.css" />
    <link rel="stylesheet" href="/POS-GAS/frontend/css/alert.css" />
</head>

<body>


    <!-- ================= MAIN ================= -->

    <div class="main">

        <!-- ================= TOPBAR ================= -->
        <div class="topbar">
            <div id="datetime"></div>

            <div class="employee-info" id="employeeMenu">
                <div class="employee-name">
                    <?php echo htmlspecialchars($_SESSION['lname'] . ", " . $_SESSION['fname']); ?>
                </div>
                <div id="employee-profile"><img src="/POS-GAS/frontend/assets/uploads/users/<?php echo htmlspecialchars(!empty($_SESSION['image']) ? $_SESSION['image'] : 'default.jpg'); ?>" class="employee-img"></div>

                <!-- DROPDOWN -->
                <div class="employee-dropdown" id="employeeDropdown">
                    <div class="dropdown-item" onclick="goToAccount()">Account Settings</div>
                    <div class="dropdown-item" onclick="logout()">Logout</div>
                </div>
            </div>
        </div>

        <!-- ================= LEFT PANEL ================= -->

        <div class="left-panel">

            <div class="search-section">

                <input type="text" id="searchInput" placeholder="SEARCH PRODUCT">

                <button class="view-btn" onclick="window.location.href='productlist';">VIEW PRODUCTS</button>

            </div>


            <div class="category-bar">

                <!-- fetch categories here that will filter cards base on their category -->

            </div>


            <!-- cards -->
            <div class="product-grid" id="productGrid"></div>

        </div>

    </div>

    <!-- ================= RIGHT PANEL ================= -->

    <div class="right-panel">
        <<div class="cart-container">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>PRODUCT NAME</th>
                        <th>QTY.</th>
                        <th>PRICE</th>
                    </tr>
                </thead>
                <tbody id="cartItems"></tbody>
            </table>

            <div id="cartItems"></div>
    </div>
    <div class="cart-total">
        TOTAL: <span id="totalAmount">0</span>
    </div>



    <div class="cart-buttons">

        <button class="edit-btn">EDIT</button>
        <button class="ok-btn">OK</button>

    </div>

    </div>
    </div>

    <!-- MODAL -->

    <div class="modal" id="productModal">

        <div class="modal-content">

            <div class="close-btn" id="closeModal">✕</div>

            <div class="modal-body">

                <div class="modal-card">

                    <div class="img"></div>

                    <div>

                        <h3 id="modalName"></h3>
                        <p class="pcode" id="modalCode"></p>

                        <p>Remaining: <span id="modalStock"></span></p>
                        <p class="expiry">Expires at <span id="modalExpiry"></span></p>

                        <h4> <span id="modalPrice"></span></h4>

                    </div>

                </div>

                <div class="qty-box">

                    <label>QUANTITY</label>

                    <input type="number" id="modalQty" value="1">

                </div>

            </div>

            <button class="add-btn" id="addToCart">+ OK</button>

        </div>

    </div>

    <script src="/POS-GAS/frontend/js/search.js"></script>

    <!-- ================= CHECKOUT MODAL ================= -->
    <div class="modal" id="checkoutModal" style="display:none;">
        <div class="modal-content">
            <div class="close-btn" id="closeCheckout">✕</div>

            <form id="checkoutForm" method="POST" action="reciept.php">
                <div class="modal-body">
                    <h3>Checkout</h3>

                    <!-- Customer Name -->
                    <label>Customer Name</label>
                    <input type="text" id="customerName" name="customerName" placeholder="Enter customer name" required />

                    <!-- Total (Read-only, Financial Format) -->
                    <label>Total</label>
                    <input type="text" id="checkoutTotal" value="₱ 0.00" readonly />

                    <!-- Discount Dropdown -->
                    <label>Discount</label>
                    <select id="discountSelect">
                        <option value="0">None</option>
                        <option value="20">PWD - 20%</option>
                        <option value="20">Senior - 20%</option>
                        <option value="10">Employee - 10%</option>
                    </select>

                    <!-- Amount Paid -->
                    <label>Amount Paid</label>
                    <input type="number" id="amountPaid" name="amountPaid" min="0" step="0.01" placeholder="Enter amount paid" required />

                    <!-- Change (Read-only, Financial Format) -->
                    <label>Change</label>
                    <input type="text" id="changeDisplay" value="₱ 0.00" readonly />

                </div>

                <!-- Hidden fields to submit -->
                <input type="hidden" name="items" id="hiddenItems" />
                <input type="hidden" name="total" id="hiddenTotal" />
                <input type="hidden" name="change" id="hiddenChange" />

                <!-- Buttons -->
                <div style="display:flex; gap:8px; justify-content:flex-end; padding:12px;">
                    <button type="button" id="cancelCheckout" class="edit-btn">Cancel</button>
                    <button type="submit" id="confirmCheckout" class="ok-btn">Pay</button>
                </div>
            </form>
        </div>
    </div>

    <script src="/POS-GAS/frontend/js/cashier.js"></script>
    <script src="/POS-GAS/frontend/js/dashboard.js"></script>
    <script src="/POS-GAS/frontend/js/date-time.js"></script>
    <script src="/POS-GAS/frontend/js/alert.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            <?php if (isset($_SESSION['error'])) { ?>

                showAlert(
                    "warning",
                    "Access Denied",
                    "<?php echo $_SESSION['error']; ?>"
                );

            <?php unset($_SESSION['error']);
            } ?>

        });
    </script>


</body>

</html>