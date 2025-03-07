<?php
session_start();
require_once '../../includes/database/config.php';
$_SESSION['user_id'] = 22;
echo $_SESSION['user_id'];
$sql = "SELECT
        order_items.id,
        order_items.order_id,
        order_items.product_id,
        order_items.quantity,
        order_items.price,
        order_items.print_text,
        orders.total_price,
        products.name,
        products.image,
        products.price,
          (order_items.quantity * products.price) AS total_amount
        FROM
          order_items
          JOIN products ON order_items.product_id = products.id
          JOIN orders ON order_items.order_id = orders.id
        WHERE
          orders.user_id = :user_id  AND orders.status = 'pending'";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Untree.co">
  <link rel="shortcut icon" href="store.png">

  <meta name="description" content="" />
  <meta name="keywords" content="bootstrap, bootstrap4" />

		<!-- Bootstrap CSS -->
		<link href="../../includes/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
		<link href="../../includes/css/tiny-slider.css" rel="stylesheet">
		<link href="../../includes/css/style.css" rel="stylesheet">
    <!-- Where -->
		<link href="../css/cart.css" rel="stylesheet">
		<title>Craftify Free Bootstrap 5 Template for Craftifyture and Interior Design Websites by Untree.co </title>
	</head>

	<body>

		<!-- Start Header/Navigation -->
		<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Craftify navigation bar">

			<div class="container">
				<a class="navbar-brand" href="index.html">Craftify<span>.</span></a>

				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsCraftify" aria-controls="navbarsCraftify" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarsCraftify">
					<ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
						<li class="nav-item ">
							<a class="nav-link" href="index.html">Home</a>
						</li>
						<li><a class="nav-link" href="shop.html">Shop</a></li>
						<li><a class="nav-link" href="about.html">About us</a></li>
						<li><a class="nav-link" href="services.html">Services</a></li>
						<li><a class="nav-link" href="Singin.html">Sign in</a></li>
						<li><a class="nav-link" href="contact.html">Contact us</a></li>
					</ul>

					<ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
						<li><a class="nav-link" href="#"><img src="images/user.svg"></a></li>
						<li><a class="nav-link" href="cart.html"><img src="images/cart.svg"></a></li>
					</ul>
				</div>
			</div>
				
		</nav>
		<!-- End Header/Navigation -->

		<!-- Start Hero Section -->
		<div class="hero py-1">
			<div class="container d-flex justify-content-start">
						<div class="intro-excerpt">
							<h1 class="cart">Cart</h1>
						</div>
			</div>
		</div>
	<!-- End Hero Section -->
  <div class="untree_co-section before-footer-section">
    <div class="container">
      <div class="row">
        <?php if (empty($order_items)): ?>
          <div class="text-center w-100"><h2>Your cart is empty.</h2></div>
        <?php else: ?>
          <!-- Set the form action to update_cart.php -->
          <form class="<?php echo empty($order_items) ? 'col-md-12' : 'col-md-8'; ?>" method="post" action="update_cart.php">
            <div class="site-blocks-table table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th class="product-thumbnail">Image</th>
                    <th class="product-name">Product</th>
                    <th class="product-text">Custom Text</th>
                    <th class="product-price">Price</th>
                    <th class="product-quantity">Quantity</th>
                    <th class="product-total">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $total = 0.00;
                  foreach ($order_items as $item): 
                    $total += floatval($item['total_amount']);
                  ?>
                  <tr>
                    <td class="product-thumbnail" data-label="Image">
                      <img src="<?php echo "../../admin/product/uploads/product_images/" . htmlspecialchars($item['image']); ?>" alt="Image" class="img-fluid" width="100px">
                    </td>
                    <td class="product-name" data-label="Product">
                      <h2 class="h5 text-black"><?php echo htmlspecialchars($item['name']); ?></h2>
                    </td>
                    <td data-label="Custom Text"><?php echo htmlspecialchars($item['print_text']); ?></td>
                    <td data-label="Price">JOD <?php echo htmlspecialchars($item['price']); ?></td>
                    <td data-label="Quantity">
                      <div class="input-group mb-3 d-flex align-items-center quantity-container" style="max-width: 120px;">
                        <div class="input-group-prepend">
                          <button class="btn btn-outline-black decrease" type="button">&minus;</button>
                        </div>
                        <!-- Set the input's name to include the order_item id -->
                        <input type="text" class="form-control text-center quantity-amount" name="quantities[<?php echo $item['id']; ?>]" value="<?php echo htmlspecialchars($item['quantity']); ?>" aria-label="Quantity">
                        <div class="input-group-append">
                          <button class="btn btn-outline-black increase" type="button">&plus;</button>
                        </div>
                      </div>
                    </td>
                    <td data-label="Total">JOD <?php echo htmlspecialchars($item['total_amount']); ?></td>
                    <td data-label="Remove">
                      <!-- Trash button triggers the modal -->
                      <button type="button" class="btn btn-danger btn-md delete-btn" 
                              data-order-item-id="<?php echo htmlspecialchars($item['id']); ?>"
                              data-order-id="<?php echo htmlspecialchars($item['order_id']); ?>"
                              data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i style="color: #D11E1E"  class="bi bi-trash"></i>
                      </button>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <div class="row mb-5">
              <div class="col-md-auto mb-3 mb-md-0">
                <!-- Hidden input field for total price -->
                <input type="hidden" name="total" value="<?php echo $total + 3; ?>">
                <!-- Submit this form to update the quantities -->
                <button class="btn btn-black btn-sm btn-block" type="submit">Update Cart</button>
              </div>
              <div class="col-md-auto">
                <a href="shop.html">
                  <button type="button" class="btn btn-outline-black btn-sm btn-block">Continue Shopping</button>
                </a>
              </div>
            </div>
          </form>
          <div class="col-md-4">
            <div class="row justify-content-end">
              <div class="col-md-12 text-right border-bottom mb-5">
                <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <span class="text-black">Subtotal</span>
              </div>
              <div class="col-md-6 text-right">
                <strong class="text-black">JOD <?php echo number_format($total, 2); ?></strong>
              </div>
            </div>
            <div class="row mb-5">
              <div class="col-md-6">
                <span class="text-black">Shipping & Handling</span>
              </div>
              <div class="col-md-6 text-right">
                <strong class="text-black">JOD 3.00</strong>
              </div>
            </div>
            <div class="row mb-5">
              <div class="col-md-6">
                <span class="text-black">Total</span>
              </div>
              <div class="col-md-6 text-right">
                <strong class="text-black">JOD <?php echo number_format($total+3, 2); ?></strong>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <button class="btn btn-black btn-lg btn-block cart-btn" onclick="window.location='checkout.html'">Proceed To Checkout</button>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="deleteForm" action="delete_cart.php" method="POST">
        <input type="hidden" name="order_item_id" value="">
        <input type="hidden" name="order_id" value="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Remove item</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure you want to remove this item from your cart?
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger remove-btn">Remove</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  
  
  <!-- Set the order_item_id in the modal when a delete button is clicked -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
    const deleteButtons = document.querySelectorAll(".delete-btn");
    
    deleteButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            const orderItemId = this.getAttribute("data-order-item-id");
            const orderId = this.getAttribute("data-order-id");
            
            const modal = document.querySelector("#deleteModal");
            modal.querySelector("input[name='order_item_id']").value = orderItemId;
            modal.querySelector("input[name='order_id']").value = orderId;
        });
    });
});

  </script>


		<!-- Start Footer Section -->
		<footer class="footer-section">
			<div class="container relative">


				<div class="row">
					<div class="col-lg-8">
						<div class="subscription-form">
							<h3 class="d-flex align-items-center"><span class="me-1"><img src="images/envelope-outline.svg" alt="Image" class="img-fluid"></span><span>Subscribe to Newsletter</span></h3>

							<form action="#" class="row g-3">
								<div class="col-auto">
									<input type="text" class="form-control" placeholder="Enter your name">
								</div>
								<div class="col-auto">
									<input type="email" class="form-control" placeholder="Enter your email">
								</div>
								<div class="col-auto">
									<button class="btn btn-primary">
										<span class="fa fa-paper-plane"></span>
									</button>
								</div>
							</form>

						</div>
					</div>
				</div>

				<div class="row g-5 mb-5">
					<div class="col-lg-4">
						<div class="mb-4 footer-logo-wrap"><a href="#" class="footer-logo">Craftify<span>.</span></a></div>
						<p class="mb-4">Donec facilisis quam ut purus rutrum lobortis. Custom products that reflect your unique style, for personal use or gifts. Let us bring your ideas to life. Pellentesque habitant</p>

						<ul class="list-unstyled custom-social">
							<li><a href="#"><span class="fa fa-brands fa-facebook-f"></span></a></li>
							<li><a href="#"><span class="fa fa-brands fa-twitter"></span></a></li>
							<li><a href="#"><span class="fa fa-brands fa-instagram"></span></a></li>
							<li><a href="#"><span class="fa fa-brands fa-linkedin"></span></a></li>
						</ul>
					</div>

					<div class="col-lg-8">
						<div class="row links-wrap">
							<div class="col-6 col-sm-6 col-md-3">
								<ul class="list-unstyled">
									<li><a href="#">About us</a></li>
									<li><a href="#">Services</a></li>
									<li><a href="#">Sing in</a></li>
									<li><a href="#">Contact us</a></li>
								</ul>
							</div>

						

						
						</div>
					</div>

				</div>

				<div class="border-top copyright">
					<div class="row pt-4">
						<div class="col-lg-6">
							<p class="mb-2 text-center text-lg-start">
								Copyright &copy;<script>document.write(new Date().getFullYear());</script>. All Rights Reserved. &mdash; 
								Designed with love by <a href="http://127.0.0.1:5500/furni-ed/index.html">Craftify</a> 
								to offer unique and customized products for you.
							</p>
							
						</div>

						<div class="col-lg-6 text-center text-lg-end">
							<ul class="list-unstyled d-inline-flex ms-auto">
								<li class="me-4"><a href="#">Terms &amp; Conditions</a></li>
								<li><a href="#">Privacy Policy</a></li>
							</ul>
						</div>

					</div>
				</div>

			</div>
		</footer>
		<!-- End Footer Section -->	


		<script src="js/bootstrap.bundle.min.js"></script>
		<script src="js/tiny-slider.js"></script>
		<script src="js/custom.js"></script>
	</body>

</html>
