<?php 
require_once '../template/header.php';

// Check if user is logged in
if(!isset($_SESSION['Username']) || !$_SESSION['Active']) {
    header("location: login.php");
    exit;
}
?>
  
<body>
    <div class="container">
      <div class="header clearfix">
        <nav>
          <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contacts.php">Contact</a></li>
            <li><a href="public.php">Public Page</a></li>
          </ul>
        </nav>
        <h3 class="text-muted">PHP Login exercise - Home page</h3>
      </div>

        <div class="mainarea">
            <h1>Status: You are logged in <?php echo htmlspecialchars($_SESSION['Username']); ?></h1>
            <p class="lead">This is where we will put the logout button</p>

            <form action="logout.php" method="post" name="Logout_Form" class="form-signin">
                <button name="Submit" value="Logout" class="button" type="submit">Log out</button>
            </form>
        </div>

      <div class="row marketing">
        <div>
          <h4>Home page</h4>
          <p>Some content goes here. Some content goes here. Some content goes here. Some content goes here. Some content goes here.</p>
       </div>

<?php require_once '../template/footer.php'; ?>