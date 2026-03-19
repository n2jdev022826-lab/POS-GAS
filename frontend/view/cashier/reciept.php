<?php
$items = [];
$customerName = '';
$total = 0;
$paid = 0;
$change = 0;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
	$customerName = isset($_POST['customerName']) ? htmlspecialchars($_POST['customerName']) : '';
	$total = isset($_POST['total']) ? floatval($_POST['total']) : 0;
	$paid = isset($_POST['amountPaid']) ? floatval($_POST['amountPaid']) : 0;
	$change = isset($_POST['change']) ? floatval($_POST['change']) : ($paid - $total);

	if(isset($_POST['items'])){
		$decoded = json_decode($_POST['items'], true);
		if(is_array($decoded)){
			$items = $decoded;
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Receipt</title>
	<style>
		body{font-family:Arial,Helvetica,sans-serif;padding:20px}
		table{width:100%;border-collapse:collapse;margin-top:12px}
		th,td{border:1px solid #ddd;padding:8px;text-align:left}
		th{background:#f5f5f5}
		.summary{margin-top:16px}
		.actions{margin-top:20px}
	</style>
</head>
<body>

	<h2>Receipt</h2>

	<div>
		<strong>Customer:</strong> <?php echo $customerName ?: 'Walk-in'; ?>
	</div>

	<table>
		<thead>
			<tr>
				<th>Product</th>
				<th>Qty</th>
				<th>Price</th>
			</tr>
		</thead>
		<tbody>
		<?php if(!empty($items)): ?>
			<?php foreach($items as $it): ?>
				<tr>
					<td><?php echo htmlspecialchars($it['name'] ?? ''); ?></td>
					<td><?php echo intval($it['qty'] ?? 0); ?></td>
					<td>₱ <?php echo number_format(floatval($it['price'] ?? 0),2); ?></td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr><td colspan="3">No items</td></tr>
		<?php endif; ?>
		</tbody>
	</table>

	<div class="summary">
		<div><strong>Total:</strong> ₱ <?php echo number_format($total,2); ?></div>
		<div><strong>Paid:</strong> ₱ <?php echo number_format($paid,2); ?></div>
		<div><strong>Change:</strong> ₱ <?php echo number_format($change,2); ?></div>
	</div>

	<div class="actions">
		<button onclick="window.location.href='cashier.php'">Back to Cashier</button>
		<button onclick="window.print()">Print Receipt</button>
	</div>

</body>

<script src="/POS/FRONT-END/js/cashier.js"></script>
</html>
