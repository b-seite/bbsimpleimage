<?php
/**
 * Helper class for BB Simple Contact module
 * 
 * @package    Joomla
 * @subpackage Modules
 * @link http://www.beplate.de/joomla/modules/bbsimplecontact
 * @license        GNU/GPL, see LICENSE.php
 * mod_bbsimple_contact is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class ModBBSimpleContactHelper
{
    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */    
    public static function getContactParams($params)
    {
        //Email Parameters
		$recipient = $params->get('email_recipient', 'email@email.com');
		$fromName = @$params->get('from_name', 'Rapid Contact');
		$fromEmail = @$params->get('from_email', 'rapid_contact@yoursite.com');
		
		// Text Parameters
		$myEmailLabel = $params->get('email_label', 'email@site.com');
		$mySubjectLabel = $params->get('subject_label', 'Subject');
		$myMessageLabel = $params->get('message_label', 'Your Message');
		$buttonText = $params->get('button_text', 'Send Message');
		$pageText = $params->get('page_text', 'Thank you for your contact.');
		$errorText = $params->get('error_text', 'Your message could not be sent. Please try again.');
		$noEmail = $params->get('no_email', 'Please write your email');
		$invalidEmail = $params->get('invalid_email', 'Please write a valid email');
		$wrongantispamanswer = $params->get('wrong_antispam', 'Wrong anti-spam answer');
		$pre_text = $params->get('pre_text', '');
		
		// Size and Color Parameters
		$thanksTextColor = $params->get('thank_text_color', '#FF0000');
		$error_text_color = $params->get('error_text_color', '#FF0000');
		$emailWidth = $params->get('email_width', '15');
		$subjectWidth = $params->get('subject_width', '15');
		$messageWidth = $params->get('message_width', '13');
		$buttonWidth = $params->get('button_width', '100');
		$label_pos = $params->get('label_pos', '2');
		
		// Anti-spam Parameters
		$enable_anti_spam = $params->get('enable_anti_spam', '1');
		$myAntiSpamQuestion = $params->get('anti_spam_q', 'How many eyes has a typical person?');
		$myAntiSpamAnswer = $params->get('anti_spam_a', '2');
		$anti_spam_position = $params->get('anti_spam_position', 0);
		
		// Module Class Suffix Parameter
		$mod_class_suffix = $params->get('moduleclass_sfx', '');
		
		$url = $params->get('fixed_url', false) ? 'action="' . $params->get('fixed_url_address', '') . '"' : '';
    }
}