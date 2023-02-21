<?php
  if(is_array($_FILES)) {
    if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {
      $sourcePath = $_FILES['userImage']['tmp_name'];
      $targetPath = "C:/xampp/htdocs/solusi.chatroom/pages/assets/chat-images/".$_FILES['userImage']['name'];
      if(move_uploaded_file($sourcePath,$targetPath)) {
?>
<img width="100%" height="100" src="<?php echo $targetPath; ?>" />
<?php
  }
    }
      }
?>