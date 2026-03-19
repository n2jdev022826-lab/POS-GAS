<?php

require_once "../../../config/database.php";
require_once "../../../backend/middleware/auth.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>JAYLO MEDICAL CLINIC</title>

    <link rel="stylesheet" href="/POS-GAS/frontend/css/cashier.css">

</head>

<body>

    <div class="main">

        <!-- TOP BAR -->

        <div class="topbar">


            <div class="employee-info">
                <div class="employee-name"> <?php echo htmlspecialchars($_SESSION['lname']. ", " . $_SESSION['fname']); ?></div>
                <div class="profile"></div>
            </div>

        </div>


        <div class="pos-wrapper">

            <!-- LEFT SIDE -->

            <div class="left-panel">

                <div class="search-section">

                    <input type="text" placeholder="SEARCH PRODUCT">

                    <button class="view-btn">VIEW PRODUCTS</button>

                </div>


                <div class="category-bar">

                    MEDICINES

                </div>


                <div class="product-grid" id="productGrid">

                </div>

            </div>


            <!-- RIGHT PANEL -->

            <div class="right-panel">

                <div class="cart-header">

                    <span>PRODUCT NAME</span>
                    <span>QTY.</span>
                    <span>PRICE</span>

                </div>

                <div id="cartItems"></div>

                <div class="cart-total">

                    TOTAL: ₱ <span id="totalAmount">0</span>

                </div>

                <div class="cart-buttons">

                    <button class="edit-btn">EDIT</button>
                    <button class="ok-btn">OK</button>

                </div>

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


    <script src="/POS-GAS/frontend/js/cashier.js"></script>

</body>

</html>