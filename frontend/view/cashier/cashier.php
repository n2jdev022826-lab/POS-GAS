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
        <div class="cart-container">
            <div class="cart-header">

                <span>PRODUCT NAME</span>
                <span>QTY.</span>
                <span>PRICE</span>

            </div>

            <div id="cartItems"></div>
        </div>
        <div class="cart-total">
            TOTAL: ₱ <span id="totalAmount">0</span>
        </div>



        <div class="cart-buttons">

            <button class="edit-btn">EDIT</button>
            <button class="ok-btn">OK</button>

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
                        <p id="modalDose"></p>

                        <p>Remaining: <span id="modalStock"></span></p>
                        <p class="expiry">Expires at <span id="modalExpiry"></span></p>

                        <h4>₱ <span id="modalPrice"></span></h4>

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
    <!-- CHECKOUT MODAL -->
    <div class="modal" id="checkoutModal" style="display:none;">
        <div class="modal-content">
            <div class="close-btn" id="closeCheckout">✕</div>
            <form id="checkoutForm" method="POS-GAST" action="reciept.php">
                <div class="modal-body">
                    <h3>Checkout</h3>
                    <label>Customer name</label>
                    <input type="text" id="customerName" name="customerName" placeholder="Enter customer name" required />

                    <label>Total</label>
                    <div>₱ <span id="checkoutTotal">0</span></div>

                    <label>Amount Paid</label>
                    <input type="number" id="amountPaid" name="amountPaid" min="0" step="0.01" required />

                    <label>Change</label>
                    <div>₱ <span id="changeDisplay">0</span></div>

                </div>

                <input type="hidden" name="items" id="hiddenItems" />
                <input type="hidden" name="total" id="hiddenTotal" />
                <input type="hidden" name="change" id="hiddenChange" />

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