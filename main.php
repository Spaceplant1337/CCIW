<?php
session_start();

// Funktion zum Hinzufügen eines Produkts zum Warenkorb
function addToCart($productId, $productName, $productPrice) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Ein Produkt dem Warenkorb hinzufügen
    $_SESSION['cart'][] = array(
        'id' => $productId,
        'name' => $productName,
        'price' => $productPrice
    );
}

// Funktion zum Entfernen eines Produkts aus dem Warenkorb
function removeFromCart($productId) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] === $productId) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
    }
}

// Warenkorbaktionen verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add' && isset($_POST['product_id'])) {
        $productId = $_POST['product_id'];
        $productName = $_POST['product_name'];
        $productPrice = $_POST['product_price'];
        addToCart($productId, $productName, $productPrice);
    } elseif ($_POST['action'] === 'remove' && isset($_POST['product_id'])) {
        $productId = $_POST['product_id'];
        removeFromCart($productId);
    }
}

// Warenkorb anzeigen
function displayCart() {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        echo '<h2>Warenkorb</h2>';
        echo '<ul>';
        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $item) {
            echo '<li>' . $item['name'] . ' - ' . $item['price'] . '€';
            echo '<form method="post" action="main.php">';
            echo '<input type="hidden" name="action" value="remove">';
            echo '<input type="hidden" name="product_id" value="' . $item['id'] . '">';
            echo '<button type="submit">Entfernen</button>';
            echo '</form>';
            echo '</li>';
            $totalPrice += $item['price'];
        }
        echo '</ul>';
        echo '<p>Gesamtpreis: ' . $totalPrice . '€</p>';
        echo '<button type="submit">Bestellen</button>';
    } else {
        echo '<p>Der Warenkorb ist leer.</p>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Warenkorb</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>
<?php displayCart(); ?>
</body>
</html>