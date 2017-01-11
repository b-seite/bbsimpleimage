<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the BBSimplecontact functions only once
JLoader::register('ModBBSimpleContact‚Helper', __DIR__ . '/helper.php');

$myError = '';
$CORRECT_ANTISPAM_ANSWER = '';
$CORRECT_EMAIL = '';
$CORRECT_SUBJECT = '';
$CORRECT_MESSAGE = '';
$email_class = '';

if (isset($_POST["rp_email"])) {
  $CORRECT_SUBJECT = htmlentities($_POST["rp_subject"], ENT_COMPAT, "UTF-8");
  $CORRECT_MESSAGE = htmlentities($_POST["rp_message"], ENT_COMPAT, "UTF-8");
  // check anti-spam
  if ($enable_anti_spam == '1') {
    if (strtolower($_POST["rp_anti_spam_answer"]) != strtolower($myAntiSpamAnswer)) {
      $myError = '<span style="color: ' . $error_text_color . ';">' . $wrongantispamanswer . '</span>';
    }
    else {
      $CORRECT_ANTISPAM_ANSWER = htmlentities($_POST["rp_anti_spam_answer"], ENT_COMPAT, "UTF-8");
    }
  }
  else if ($enable_anti_spam == '2') {
    // check captcha plugin.
    JPluginHelper::importPlugin('captcha');
    $d = JEventDispatcher::getInstance();
    $res = $d->trigger('onCheckAnswer', 'not_used');
    if( (!isset($res[0])) || (!$res[0]) ){
      $myError = '<span style="color: ' . $error_text_color . ';">' . $wrongantispamanswer . '</span>';
    }
  }
  // check email
  if ($_POST["rp_email"] === "") {
    $myError = '<span style="color: ' . $error_text_color . ';">' . $noEmail . '</span>';
    $email_class = ' has-error';
  }
  if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", strtolower($_POST["rp_email"]))) {
    $myError = '<span style="color: ' . $error_text_color . ';">' . $invalidEmail . '</span>';
    $email_class = ' has-error';
  }
  else {
    $CORRECT_EMAIL = htmlentities($_POST["rp_email"], ENT_COMPAT, "UTF-8");
  }

  if ($myError == '') {
    $mySubject = $_POST["rp_subject"];
    $myMessage = 'Sie haben eine neue Email erhalten. Absender: '. $_POST["rp_email"] ."\n\n". $_POST["rp_message"];

    $mailSender = JFactory::getMailer();
    $mailSender->addRecipient($recipient);

    $mailSender->setSender(array($fromEmail,$fromName));
    if(version_compare(JVERSION, '3.5', 'ge')) {
      $mailSender->addReplyTo($_POST["rp_email"], $fromName);
    }
    else {
      $mailSender->addReplyTo(array( $_POST["rp_email"], $fromName ));
    }

    $mailSender->setSubject($mySubject);
    $mailSender->setBody($myMessage);
	print '<div class="rapid_contact ' . $mod_class_suffix . '">' . "\n" .
      '<h2 class="rapid_contact intro_text ' . $mod_class_suffix . '">'.$pre_text.'</h2>' . "\n" . "<div class='rapid_contact_main'>";

    if ($mailSender->Send() !== true) {
      $myReplacement = '<span style="color: ' . $error_text_color . ';">' . $errorText . '</span>';
      
    }
    else {
      $myReplacement = '<span style="color: '.$thanksTextColor.';">' . $pageText . '</span>';
      
    }
    print $myReplacement;
    print '</div></div>';
    return true;
	
  }
} // end if posted

// check recipient
if ($recipient === "email@email.com") {
  $myReplacement = '<span style="color: ' . $error_text_color . ';">Your form recipient is email@email.com. Please change that in the Rapid Contact module options.</span>';
  print $myReplacement;
  return true;
}

$document = JFactory::getDocument();

if ($params->get('addcss', '') != '') {
  $document->addStyleDeclaration($params->get('addcss', ''));
}

$form_id = 'rp_'.uniqid();
print '<div class="rapid_contact ' . $mod_class_suffix . '"><form '.$url.' id="'.$form_id.'" method="post">' . "\n" .
      '<h2 class="rapid_contact intro_text ' . $mod_class_suffix . '">'.$pre_text.'</h2>' . "\n";

if ($myError != '') { print $myError; }

print '<div class="rapid_contact_form" id="rapid_contact_form_'.$form_id.'">';

$anti_spam_field = '';
if ($enable_anti_spam == '2') {
  $anti_spam_field = (JFactory::getConfig()->get('captcha') != '0') ? JCaptcha::getInstance(JFactory::getConfig()->get('captcha'))->display('rp_recaptcha', 'rp_recaptcha', 'g-recaptcha') : '';
}
else if ($enable_anti_spam == '1') {
  // Label as Placeholder option is intentionally overlooked.
  $anti_spam_field = '<label for="'.$form_id.'_as_answer">'.$myAntiSpamQuestion.'</label><input class="rapid_contact form-control inputbox ' . $mod_class_suffix . '" type="text" name="rp_anti_spam_answer" id="'.$form_id.'_as_answer" size="' . $emailWidth . '" value="'.$CORRECT_ANTISPAM_ANSWER.'"/>';
}

// print anti-spam
if ($anti_spam_position == 0) {
  print '<div class="input-group">'.$anti_spam_field.'</div>';
}
// print email input
print '<div class="input-group">';
$email_placeholder = ($label_pos == '2') ? ' placeholder="'.$myEmailLabel.'"' : '';
if ($label_pos != '2') {
  print '<label for="'.$form_id.'_email">'.$myEmailLabel.'</label>';
}
print '<input class="rapid_contact form-control inputbox ' . $email_class . $mod_class_suffix . '" type="email" name="rp_email" id="'.$form_id.'_email" size="'.$emailWidth.'" value="'.$CORRECT_EMAIL.'" '.$email_placeholder.'/>';
print '</div>';
// print subject input
print '<div class="input-group">';
$subject_placeholder = ($label_pos == '2') ? ' placeholder="'.$mySubjectLabel.'"' : '';
if ($label_pos != '2') {
  print '<label for="'.$form_id.'_subject">'.$mySubjectLabel.'</label>';
}
print '<input class="rapid_contact form-control inputbox ' . $mod_class_suffix . '" type="text" name="rp_subject" id="'.$form_id.'_subject" size="'.$subjectWidth.'" value="'.$CORRECT_SUBJECT.'" '.$subject_placeholder.'/>';
print '</div>';
// print message input
print '<div class="input-group">';
$message_placeholder = ($label_pos == '2') ? ' placeholder="'.$myMessageLabel.'"' : '';
if ($label_pos != '2') {
  print '<label for="'.$form_id.'_message">'.$myMessageLabel.'</label>';
}
print '<textarea class="rapid_contact form-control textarea ' . $mod_class_suffix . '" name="rp_message" id="'.$form_id.'_message" cols="' . $messageWidth . '" rows="4" '.$message_placeholder.'>'.$CORRECT_MESSAGE.'</textarea>';
print '</div>';

//print anti-spam
if ($anti_spam_position == 1) {
  print '<div class="input-group">'.$anti_spam_field.'</div>';
}
// print button
print '<div class="input-group">';
print '<input class="rapid_contact btn btn-success button ' . $mod_class_suffix . '" type="submit" value="' . $buttonText . '"/>';
print '</div>';
print '</div></form></div>' . "\n";
return true;
