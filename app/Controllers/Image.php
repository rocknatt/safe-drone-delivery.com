<?php 

namespace App\Controllers;

use App\Models\ImageModel;
use CodeIgniter\API\ResponseTrait;

class Image extends BaseController
{
	use ResponseTrait;

	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->model = new ImageModel();
        // $this->model->set_session($this->session);
    }

	public function index()
	{
		// return view('welcome_message');
	}

	public function download($id)
	{
		$prefix = $this->request->getGet('prefix');
		
		$extension = 'png';
		$thumb_file = IMAGE_FOLDER_WRITABLE_PATH . '/' .  $prefix . '_' . $id . '.png';
		if (!file_exists($thumb_file)) {
			$extension = 'jpeg';
			$thumb_file = IMAGE_FOLDER_WRITABLE_PATH . '/' .  $prefix . '_' . $id . '.jpg';
		}
		if (!file_exists($thumb_file)) {
			$extension = 'jpeg';
			$thumb_file = IMAGE_FOLDER_WRITABLE_PATH . '/' .  $prefix . '_' . $id . '.jpeg';
		}
		if (!file_exists($thumb_file)) {
			$extension = 'gif';
			$thumb_file = IMAGE_FOLDER_WRITABLE_PATH . '/' .  $prefix . '_' . $id . '.gif';
		}

		if (file_exists($thumb_file)) {

			$base64 = $this->request->getGet('base64');
            if ($base64 != null) {
                $data = file_get_contents($thumb_file);
                $base64 = 'data:image/png;base64,' . base64_encode($data);

                return $this->respond(array(
                    'base64' => $base64
                ));
            }

            return $this->return_file($thumb_file, 'inline');
		}
		else{
			$new_width = null;
			switch ($prefix) {
				case 'thm':
					$new_width = 640;
					break;

				case 'ths':
					$new_width = 320;
					break;

				case 'thx':
					$new_width = 160;
					break;
			}

			$model = new ImageModel();
			$model->write_image($id, $new_width, $prefix);

			return $this->redirect('image/'. $id . ($prefix != '' ? '/' . $prefix : '') );
		}
	}

	//--------------------------------------------------------------------

}
