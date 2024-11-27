<?php
session_start();

if(!isset($_SESSION['username'])){
    echo "<script>alert('You are not logged in');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/login.php'; }, 100);</script>";
    exit();
}

require_once('../database/connect.php');
$db = getDatabaseConnection();

$email = $_SESSION['email'];
$stmt = $db->prepare('SELECT * FROM USER WHERE email = :email');
$stmt->bindParam(':email', $email);
$stmt->execute();
$user = $stmt->fetch();
$loggedInUser = $user['username']; // Supondo que a coluna de username seja 'username'

// Consulta para buscar todos os usuários com quem o usuário logado teve conversas
$sql = 'SELECT DISTINCT CASE 
            WHEN buyer = :loggedInUser THEN seller 
            ELSE buyer 
        END AS participant
        FROM CHAT 
        WHERE buyer = :loggedInUser OR seller = :loggedInUser';

$stmt = $db->prepare($sql);
$stmt->bindParam(':loggedInUser', $loggedInUser);
$stmt->execute();
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/conversations_style.css">
    <link rel="stylesheet" href="/css/conversations_responsive.css">
    <title>Ctrl+Sell - All Conversations</title>
    
</head>
<?php include('header.php'); ?>
<body id="corpo">

    
    <div class="chat-container">
        <h2 id="namer">All Conversations for <?php echo htmlspecialchars($loggedInUser); ?></h2>
        <?php if (!empty($participants)): ?>
            <?php foreach ($participants as $participant): ?>
                <div class="participant" onclick="location.href='chat.php?seller=<?php echo urlencode($participant['participant']); ?>'">
                    Conversation with <?php echo htmlspecialchars($participant['participant']); ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No conversations found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

