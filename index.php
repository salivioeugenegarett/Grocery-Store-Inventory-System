<?php
session_start();

if (isset($_SESSION['user'])) {
    $showInventory = true;
} else {
    $showInventory = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$showInventory) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $correctUsername = 'admin';
    $correctPassword = 'admin';

    if ($username === $correctUsername && $password === $correctPassword) {
        $_SESSION['user'] = $username;
        $showInventory = true;
    } else {
        $error = "Invalid username or password.";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Store Stocker</title>
  <link rel="icon" type="image/png" href="https://grocery-store-inventory-system.onrender.com/resources/grocerystore.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <header>
    <h1><img src="https://grocery-store-inventory-system.onrender.com/resources/grocerystore.png" alt="Store Icon" style="width: 40px; vertical-align: middle;">Store Stocker</h1>
    <?php if ($showInventory): ?>
      <a href="?logout=true" id="logoutBtn">Logout</a>
    <?php endif; ?>
  </header>

  <?php if ($showInventory): ?>
    <div class="category-manager">
      <input type="text" id="newCategoryName" placeholder="New Category" />
      <button id="addCategoryBtn">Add Category</button>
    </div>

    <div class="tabs" id="tabsContainer"></div>

    <div class="tab-contents" id="tablesContainer"></div>
    
    <template id="productTableTemplate">
      <section class="active">
        <h2 class="category-title">Category Name</h2>
        <form class="prod-form">
          <input type="text" class="prod-name" placeholder="Product Name" required />
          <input type="text" class="prod-size" placeholder="Size (e.g. 1kg, 500ml)" required />
          <input type="number" class="prod-qty" placeholder="Quantity" min="0" required />
          <input type="number" class="prod-price" placeholder="Price" step="0.01" min="0" required />
          <button type="submit">Add Product</button>
        </form>
        <table>
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Size</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody class="product-table-body">
          </tbody>
        </table>
      </section>
    </template>

  <?php else: ?>
    <div class="login-form">
      <h2>Login</h2>
      <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
      <?php endif; ?>
      <form method="POST" id="loginForm">
        <div class="input-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Enter your username" required />
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required />
        </div>
        <button type="submit">Login</button>
      </form>
    </div>
  <?php endif; ?>

  <script src="script.js"></script>
</body>
</html>
