<?php 

namespace App\Controllers;

use Config\Services;
use CodeIgniter\API\ResponseTrait;
use App\Models\MailModel;
use App\Models\MailParamModel;
use App\Models\MailUserParamModel;
use App\Models\MailFichierModel;
use App\Models\MailAdressModel;
use App\Models\HubModel;
use CodeIgniter\I18n\Time;

class Task extends BaseController
{
	use ResponseTrait;

	public function index()
	{
		// return view('welcome_message');
	}

	public function check_mail()
	{
		/**
		 * place this before any script you want to calculate time
		 */
		$time_start = microtime(true); 

		/**
		 * Exception : this index is only defined with cli but this method is used to boot websocket form http
		 */
		$_SERVER['argc'] = 2;
		$_SERVER['argv'] = array('task.php', 'hub', 'init', '-');
		
		
		/**
		 * init model
		 */
		$mail_model = new MailModel();
		$mail_param_model = new MailParamModel();
		$mail_user_param_model = new MailUserParamModel();
		$mail_fichier_model = new MailFichierModel();
		$mail_adress_model = new MailAdressModel();
		$hub = new HubModel();

		$mail_param_list = $mail_param_model->get_list();

		foreach ($mail_param_list as $key => $param) {


			/**
			 * send mail inside send_box
			 */
			if ($param['is_verified']) {
				$mail_list = $mail_model->get_next_mail_to_send($param['user_email']);

				foreach ($mail_list as $mail) {

					$should_send = true;

					if ($mail['date_sent'] != null) {
						$date_sent = Time::parse($mail['date_sent']);
				        $date_now = Time::now();

				        $diff = $date_begin->difference($date_end);

				        if ($diff->getMinutes() < 0) {
				        	$should_send  = false;
				        }
					}

					if ($should_send) {

						$mail_fichier_list = $mail_fichier_model->get_list($mail['id']);
						$content = $mail['text_html'] != null ? gzuncompress($mail['text_html']) : gzuncompress($mail['text_plain']);

						$result_smtp = MailModel::send_email(
							array(
								'to' => $mail['dest_email'], 
								'cc' => $mail['cc'], 
								'cci' => $mail['cci'], 
								'nom' => $param['user_name'], 
								'is_needing_accused' => $mail['is_needing_accused']
							), 
							array('object' => $mail['object'] , 'content' => $content, 'attachement' => $mail_fichier_list),
							array(
								'user_smtp_host' => $param['user_smtp_host'],
								'user_email' => $param['user_email'],
								'user_password' => $param['user_password'],
								'user_smtp_port' => $param['user_smtp_port'],
								'user_smtp_crypto' => $param['user_smtp_crypto'],
							)
						);

						$mail_user_param_list = $mail_user_param_model->get_mail_user_param_list($param['id']);
						/**
	                     * add email to contact list
	                     */
	                    foreach ($mail_user_param_list as $mail_user_param) {
	                        $mail_adress_model->add_new_email($mail['dest_email'], $mail_user_param['user_id']);
	                        $hub->push_message('email_sent', 
                                array(
                                	'id' => $mail['id'],
                                    'm' => lang('STD.std_mail_sent'), 
                                    'u' => site_url('mail/'. $mail['id']), 
                                    'tag' => 'mail'
                                ), $mail_user_param['user_id']);
	                    }

						if ($result_smtp == 'err_auth') {

						}
						else if ($result_smtp == 'err_conn') {

						}
						else {

							$mail_model->where('id', $mail['id'])
										->set(array('mail_classement_id' => 2, 'is_read' => true))
										->update();

						}
					}
            	}
			}

			/**
			 * retrive mail from remote
			 */
			if ($param['is_verified']) {
				if ($param['is_retrive_old_mail']) {

					MailModel::get_old_mail($param);
					// $mail_param_model->where('id', $param['id'])
					// 				->set('is_retrive_old_mail', false)
					// 				->update();
				}
				else {
					MailModel::get_new_mail($param);
				}
			}			
		}

		// $this->mail->get_old_mail($this->user->identity->id);

		// Anywhere else in the script
		echo 'Total execution time in seconds: ' . (microtime(true) - $time_start);
	}

	//--------------------------------------------------------------------

}
