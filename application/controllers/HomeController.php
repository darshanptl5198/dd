<?php

use PSpell\Config;

defined('BASEPATH') or exit('No direct script access allowed');

class HomeController extends CI_Controller
{

	public function Home()
	{

		$this->load->view('homepage');
	}

	public function validate()
	{
		$this->load->model('HomeModel');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[3]|max_length[5]');
		$this->form_validation->set_rules('checkbox', 'checkbox', 'trim|required');
		$this->form_validation->set_rules('image', 'Image', 'callback_file_check');

		if ($this->form_validation->run() == false) {
			$this->load->view('homepage');
		} else {

			$config['upload_path'] = './uploads/images/';
			$config['allowed_types'] = '*';
			$config['encrypt_name'] = TRUE;
			$config['file_name'] = $_FILES['image']['name'];


			$this->load->library('upload');
			$this->upload->initialize($config);


			$data['upload_error'] = '';

			if (!$this->upload->do_upload('image')) {

				$data['upload_error'] = $this->upload->display_errors();

				$this->load->view('homepage', $data);
			} else {

				$upload_data = $this->upload->data();
				// print_r($upload_data);
				// die;
				$data = [
					'name' => $this->input->post('name'),
					'email' => $this->input->post('email'),
					'password' => $this->input->post('password'),
					// 'image' => $upload_data['file_name']

				];

				$user_id = $this->HomeModel->insertdata($data);

				if ($user_id) {
					// Prepare image data
					$imgdata = [
						'image' => $upload_data['file_name'],
						'user_id' => $user_id
					];

					$imgresult = $this->HomeModel->insertimage($imgdata);

					if ($imgresult) {
						redirect('HomeController/view');
					} else {
						$data['db_error'] = 'Image insertion failed.';
						$this->load->view('homepage', $data);
					}
					// $result = $this->HomeModel->insertdata($data);
				} else {
					$data['db_error'] = 'Data insertion failed.';
					$this->load->view('homepage', $data);
				}
			}
		}
	}

	public function file_check($str)
	{
		if (empty($_FILES['image']['name'])) {

			$this->form_validation->set_message('file_check', 'The {field} field is required.');
			return false;
		}
		return true;
	}

	// public function success()
	// {
	// 	$this->load->view('homepage');
	// }


	public function view()
	{
		$this->load->model('HomeModel');
		$this->load->library('pagination');

		$key = $this->input->post('search');

		$config['base_url'] = base_url('HomeController/view');
		$config['total_rows'] = $this->HomeModel->gettotalrow($key);
		$config['per_page'] = 5;
		$config['uri_segment'] = 3;
		$config['use_page_numbers'] = TRUE;

		$key = $key ?? '';

		$config['suffix'] = '?search=' . urlencode($key);
		$config['first_url'] = $config['base_url'] . $config['suffix'];


		$this->pagination->initialize($config);
		$page = $this->uri->segment(3) ?: 1;


		$offset = ($page - 1) * $config['per_page'];
		$data['index'] = $offset + 1;



		$data['users'] = $this->HomeModel->fetch_data($config['per_page'], $offset, $key);
		// $data['users'] = $this->HomeModel->fetch_data();
		// echo '<pre>';
		// print_r($data['users']);
		// die;
		$data['links'] = $this->pagination->create_links();

		$this->load->view('view', $data);
	}

	public function edit($id)
	{
		$this->load->model('HomeModel');
		$data['id_data'] = $this->HomeModel->getdata($id);
		$data['images'] = $this->HomeModel->get_image_array($id);
		// print_r($data);
		// die;
		$this->load->view('editpage', $data);
	}


	// public function update($id)
	// {
	// 	$this->load->model('HomeModel');
	// 	$this->load->library('form_validation');
	// 	$this->load->library('upload');

	// 	$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
	// 	$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

	// 	$data['id_data'] = $this->HomeModel->getdata($id);
	//     $data['images'] = $this->HomeModel->get_image_array($id);

	// 	if ($this->form_validation->run() == false) {

	// 		$this->load->view('editpage', $data);
	// 	} 
	// 	else {
	// 		$update_data = [
	// 			'name' => $this->input->post('name'),
	// 			'email' => $this->input->post('email')
	// 		];
	// 		// Update user data
	// 		if ($this->HomeModel->updatemodel($update_data, $id)) {
	// 			$uploaded_images = [];

	// 			if (!empty($_FILES['image']['name'][0])) {
	// 				$files = $_FILES['image'];
	// 				$file_count = count($files['name']);

	// 				for ($i = 0; $i < $file_count; $i++) {
	// 					$_FILES['file']['name'] = $files['name'][$i];
	// 					$_FILES['file']['type'] = $files['type'][$i];
	// 					$_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
	// 					$_FILES['file']['error'] = $files['error'][$i];
	// 					$_FILES['file']['size'] = $files['size'][$i];

	// 					$config['upload_path'] = './uploads/images/';
	// 					$config['allowed_types'] = '*'; // Allow all file types, consider restricting this for security
	// 					$config['encrypt_name'] = TRUE;

	// 					$this->upload->initialize($config);

	// 					if ($this->upload->do_upload('file')) {
	// 						$upload_data = $this->upload->data();
	// 						$uploaded_images[] = $upload_data['file_name']; // Store filename in array

	// 						$imgdata = [
	// 							'image' => $upload_data['file_name'],
	// 							'user_id' => $id
	// 						];

	// 						// Insert image data into the database
	// 						if (!$this->HomeModel->insertimage($imgdata)) {
	// 							$data['db_error'] = 'Failed to insert image.';
	// 							$this->load->view('editpage', $data);
	// 							return;
	// 						}

	// 					} else {
	// 						$data['upload_error'] = $this->upload->display_errors();
	// 						$this->load->view('editpage', $data);
	// 						return;
	// 					}
	// 				}
	// 			}

	// 			 $data['uploaded_images'] = $this->HomeModel->get_image_array($id); 

	// 			$this->load->view('editpage', $data);
	// 		} else {
	// 			$data['db_error'] = 'Failed to update data.';
	// 			$this->load->view('editpage', $data);
	// 		}
	// 	}
	// }

	public function update($id)
	{
		$this->load->model('HomeModel');
		$this->load->library('form_validation');
		$this->load->library('upload');

		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

		$data['id_data'] = $this->HomeModel->getdata($id);
		$data['images'] = $this->HomeModel->get_image_array($id);

		if ($this->form_validation->run() == false) {
			$this->load->view('editpage', $data);
		} else {
			$update_data = [
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email')
			];

			// Update user data
			if ($this->HomeModel->updatemodel($update_data, $id)) {
				$uploaded_images = [];

				if (!empty($_FILES['image']['name'][0])) {
					$files = $_FILES['image'];
					$file_count = count($files['name']);

					for ($i = 0; $i < $file_count; $i++) {
						$_FILES['file']['name'] = $files['name'][$i];
						$_FILES['file']['type'] = $files['type'][$i];
						$_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
						$_FILES['file']['error'] = $files['error'][$i];
						$_FILES['file']['size'] = $files['size'][$i];

						$config['upload_path'] = './uploads/images/';
						$config['allowed_types'] = '*'; // Allow all file types, consider restricting this for security
						$config['encrypt_name'] = TRUE;

						$this->upload->initialize($config);

						if ($this->upload->do_upload('file')) {
							$upload_data = $this->upload->data();
							$uploaded_images[] = $upload_data['file_name']; // Store filename in array

							$imgdata = [
								'image' => $upload_data['file_name'],
								'user_id' => $id
							];

							// Insert image data into the database
							if (!$this->HomeModel->insertimage($imgdata)) {
								$data['db_error'] = 'Failed to insert image.';
								$this->load->view('editpage', $data);
								return;
							}
						} else {
							$data['upload_error'] = $this->upload->display_errors();
							$this->load->view('editpage', $data);
							return;
						}
					}
				}

				// Redirect to the view page after successful update
				redirect('HomeController/view');
			} else {
				$data['db_error'] = 'Failed to update data.';
				$this->load->view('editpage', $data);
			}
		}
	}



	// public function delete($id)
	// {
	// 	$this->load->model('HomeModel');
	// 	$data = $this->HomeModel->getdata($id);
	// 	// print_r($data);
	// 	if ($data) {
	// 		$this->HomeModel->deleteImage($id);
	// 		if ($this->HomeModel->deletedata($id)) {
	// 			$imgpath = FCPATH . 'uploads/images/' . $data->image;
	// 			if (file_exists($imgpath)) {
	// 				unlink($imgpath);
	// 			}
	// 		}
	// 	}
	// 	redirect('HomeController/view');
	// }

	public function delete($id)
	{
		$this->load->model('HomeModel');
		$data = $this->HomeModel->getdata($id);

		if ($data) {
			$images = $this->HomeModel->get_image_array($id);

			foreach ($images as $image) {
				$imgpath = FCPATH . 'uploads/images/' . $image;
				if (file_exists($imgpath)) {
					unlink($imgpath);
				}
			}
			$this->HomeModel->deleteImage($id);
			if ($this->HomeModel->deletedata($id)) {
				redirect('HomeController/view');
			}
		}
	}
	public function imgdelete()
	{
		$this->load->model('HomeModel');
		// echo "bhvlo	";
		$fileName = $this->input->get('fileName'); 
		// echo $fileName;
		// die;
	
	
		if ($fileName) {
		    $imgpath = FCPATH . 'uploads/images/' . $fileName;
// print_r($imgpath);
// die;
		    if (file_exists($imgpath)) {
		        if (unlink($imgpath)) {
		           
		            if ($this->HomeModel->delete_image_record($fileName)) {
		                echo 'yes'; // Send success response
		            } 
		        } 
		    } 
		} 
	}


	public function viewdata()
	{
		$this->load->model('HomeModel');
		$id = $this->input->get('id');
		$user = $this->HomeModel->getdata($id);
		// print_r($user);
		// die;
		$images = $this->HomeModel->get_image_array($id);
		echo json_encode([
			'user' => [
				'name' => $user->name,
				'email' => $user->email
			],
			'images' => $images
		]);
	}
}

// public function update($id)
	// {
	// 	$this->load->model('HomeModel');
	// 	$this->load->library('form_validation');
	// 	$this->load->library('upload');

	// 	$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
	// 	$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

	// 	$data['id_data'] = $this->HomeModel->getdata($id);

	// 	if ($this->form_validation->run() == false) {
	// 		$this->load->view('editpage', $data);
	// 	} else {
	// 		// Prepare data for updating
	// 		$update_data = [
	// 			'name' => $this->input->post('name'),
	// 			'email' => $this->input->post('email')
	// 		];

	// 		// Handle image upload if a new image is selected
	// 		if (!empty($_FILES['image']['name'])) {
	// 			$config['upload_path'] = './uploads/images/';
	// 			$config['allowed_types'] = '*';
	// 			$config['encrypt_name'] = TRUE;

	// 			$this->upload->initialize($config);

	// 			if ($this->upload->do_upload('image')) {
	// 				$upload_data = $this->upload->data();

	// 				// Delete image in the folder
	// 				if ($data['id_data']->image) {
	// 					$old_image_path = './uploads/images/' . $data['id_data']->image;
	// 					if (file_exists($old_image_path)) {
	// 						unlink($old_image_path);
	// 					}
	// 				}
	// 				//  image data
	// 				$imgdata = [
	// 					'image' => $upload_data['file_name']
	// 				];
	// 				// Update image in the table
	// 				$this->HomeModel->updateimage($id, $imgdata);
	// 			} else {
	// 				// Handle upload error
	// 				$data['upload_error'] = $this->upload->display_errors();
	// 				$this->load->view('editpage', $data);
	// 				return;
	// 			}
	// 		}

	// 		// Update user data
	// 		if ($this->HomeModel->updatemodel($update_data, $id)) {
	// 			redirect('HomeController/view');
	// 		} else {
	// 			$data['db_error'] = 'Failed to update data.';
	// 			$this->load->view('editpage', $data);
	// 		}
	// 	}
	// }