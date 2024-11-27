<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

require_once('../database/connect.php');
$db = getDatabaseConnection();

$seller = $_GET['seller'];

$email = $_SESSION['email'];
$stmt = $db->prepare('SELECT * FROM USER WHERE email = :email');
$stmt->bindParam(':email', $email);
$stmt->execute();
$user = $stmt->fetch();
$buyer = $user['username']; // Supondo que a coluna de username seja 'username'

$stmtMaxId = $db->prepare('SELECT MAX(id) AS max_id FROM CHAT');
$stmtMaxId->execute();
$result = $stmtMaxId->fetch(PDO::FETCH_ASSOC);
$maxId = $result['max_id'];
$id = ($maxId !== null) ? ($maxId + 1) : 1;


// Processar o envio da mensagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $message = $_POST['message'];
    $postTimestamp = date('Y-m-d H:i:s'); // Formato de DATETIME para MySQL

    $stmt = $db->prepare('INSERT INTO CHAT (id,msg, postTimestamp, buyer, seller) VALUES (:id,:msg, :postTimestamp, :buyer, :seller)');
    $stmt->bindParam('id',$id);
    $stmt->bindParam(':msg', $message);
    $stmt->bindParam(':postTimestamp', $postTimestamp);
    $stmt->bindParam(':buyer', $buyer);
    $stmt->bindParam(':seller', $seller);
    $stmt->execute();

    // Redirecionar para evitar reenvio do formulário ao atualizar a página
    header("Location: chat.php?seller=" . urlencode($seller));
    exit();
}

// Consulta para buscar mensagens do chat entre buyer (usuário da sessão) e seller
$sql = 'SELECT msg, postTimestamp, buyer, seller FROM CHAT 
        WHERE (buyer = :buyer AND seller = :seller) 
        OR (buyer = :seller AND seller = :buyer)
        ORDER BY postTimestamp';

$stmt = $db->prepare($sql);
$stmt->bindParam(':buyer', $buyer);
$stmt->bindParam(':seller', $seller);
$stmt->execute();
$msgs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="/css/chat_responsive.css">
    <title>Ctrl+Sell | Chat</title>
    <link rel="stylesheet" href="/css/chat_style.css">
</head>
<body>
    <?php include('header.php'); ?>
    
    <div class="chat-container">
    <div class="chat-participants">
    <div class="participant seller"><?php echo htmlspecialchars($seller); ?></div>
    <div class="participant buyer"><?php echo htmlspecialchars($buyer); ?></div>
</div>
        <div class="chat-messages">
            <?php
            // Exibir as mensagens do chat
            if ($msgs) {
                foreach ($msgs as $row) {
                    $message = htmlspecialchars($row["msg"]);
                    $fromUser = ($row["buyer"] == $buyer) ? 'from-user' : '';

                    echo '<div class="message ' . $fromUser . '">';
                    echo '<div class="message-content">' . $message . '</div>';
                    echo '</div>';
                }
            } else {
                echo "No messages yet.";
            }
            ?>
        </div>
        
        <div class="chat-input">
            <form action="chat.php?seller=<?php echo htmlspecialchars($seller); ?>" method="post">
                <input type="text" name="message" placeholder="Write your message...">
                <input type="hidden" name="seller" value="<?php echo htmlspecialchars($seller); ?>">
                <button type="submit">Send</button>
            </form>
        </div>
    </div>
</body>
</html>
