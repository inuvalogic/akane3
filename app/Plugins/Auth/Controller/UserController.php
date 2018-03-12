<?php

namespace Akane\Controller;

use Akane\Helper\Common;

class UserController extends BaseController
{
	const MODULE_URL = 'user';
	const CHANGE_PASS_URL = 'change-password';

	private function mustLogin()
	{
		if (!$this->auth->isLogin()){
			$this->http->redirect('/user/login');
			exit;
		}
	}

	public function loginAction()
	{
		// if ($this->auth->isLogin()){
		// 	$this->http->redirect('/');
		// } else {
			echo $this->layout->render('Auth:user/login');
		// }
	}

	public function dologinAction()
	{
		if ($this->auth->isLogin()){
			$this->http->redirect('/');
		} else {
			
			$username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

			$pass = $this->auth->encodePassword($password);

			$user = $this->userModel->checkUser($username, $pass);
			
			if ($user!=false)
			{
				$userdata = array(
					'id' => $user['id'],
					'user' => $user['username'],
					'name' => $user['name'],
				);
				
				$this->session->userdata = $userdata;
				$this->session->flush();
			} else {
				$this->formHelper->sendAlert('error', 'Login Gagal', 'Username atau password salah, silahkan coba lagi');
			}
			
			$this->http->redirect('/');
		}
	}

	public function logoutAction()
	{
		if ($this->auth->isLogin()){
			$this->auth->logout();
		}
		$this->http->redirect('/user/login');
	}

	public function listAction()
	{
		$this->mustLogin();

		$q = '';
		if (isset($_GET['q'])){
			$q = filter_var($_GET['q'], FILTER_SANITIZE_STRING);
			$all = $this->userModel->find($q, 'id DESC');
		} else {
			$all = $this->userModel->all('id DESC');
		}

		$jmlrec = count($all);
		$url = __SITEURL__ . '/'.self::MODULE_URL.'/page/[[paged]]';

        Common::$paged = $this->http->uri_segment(2);
        $paging = Common::paginationSEO($jmlrec, $url, 10);

        if (isset($_GET['q'])){
			$q = $_GET['q'];
			$all = $this->userModel->find($q, 'id DESC', $paging['limit']);
		} else {
			$all = $this->userModel->all('id DESC', $paging['limit']);
		}

		echo $this->layout->render('layout', array(
			'content' => $this->layout->render('user/list', [
				'data' => $all,
				'paging' => $paging,
				'jmlrec' => $jmlrec,
				'q' => $q,
				'module_url' => __SITEURL__ . '/'.self::MODULE_URL,
				'add_url' => __SITEURL__ . '/'.self::MODULE_URL.'/add',
				'edit_url' => __SITEURL__ . '/'.self::MODULE_URL.'/edit/',
				'delete_url' => __SITEURL__ . '/'.self::MODULE_URL.'/delete/',
			])
		));
	}

	public function addAction()
	{
		$this->mustLogin();

		if (isset($_POST['ppost']))
		{
			unset($_POST['ppost']);
			
			$_POST['password'] = $this->auth->encodePassword($_POST['password']);

			$post = [];
			foreach ($_POST as $k=>$v) {
				$post[$k] = filter_var($v, FILTER_SANITIZE_STRING);
			}

			$this->userModel->insert($post);
			
			$this->formHelper->sendAlert('success', 'Berhasil', 'Data berhasil disimpan');

			$this->http->redirect('/'.self::MODULE_URL);
		}

		$form = $this->formHelper->form_header('Add New User')

		->add('name', 'text', array('required' => true))
		->add('username', 'text', array('required' => true))
		->add('password', 'text', array('required' => true))
		->add('email', 'email', array('required' => true))
		->add('suspend', 'select', array(
			'select_data' => array('Y' => 'Yes', 'N' => 'No'), 
			'required' => true
		))

		->renderForm('Add User');

		echo $this->layout->render('layout', array(
			'content' => $form
		));
	}

	public function editAction()
	{
		$this->mustLogin();

		$id = $this->http->uri_segment(2);

		$data = $this->userModel->get($id);
		if (!$data){
			echo $this->layout->render('404');
			exit;
		}

		if (isset($_POST['ppost']))
		{
			unset($_POST['ppost']);

			if ($_POST['password']==''){
				unset($_POST['password']);
			} else if ($_POST['password']!=''){
				$_POST['password'] = $this->auth->encodePassword($_POST['password']);
			}

			$post = [];
			foreach ($_POST as $k=>$v) {
				$post[$k] = filter_var($v, FILTER_SANITIZE_STRING);
			}

			$this->userModel->update($post, ['id' => $data['id']]);
			
			$this->formHelper->sendAlert('success', 'Berhasil', 'Data berhasil disimpan');

			$this->http->redirect('/'.self::MODULE_URL);
		}

		$form = $this->formHelper->form_header('Change User')

		->add('name', 'text', array('required' => true, 'value' => $data['name']))
		->add('username', 'text', array('required' => true, 'value' => $data['username']))
		->add('password', 'text')
		->add('email', 'email', array('required' => true, 'value' => $data['email']))
		->add('suspend', 'select', array(
			'select_data' => array('Y' => 'Yes', 'N' => 'No'), 
			'required' => true,
			'value' => $data['suspend']
		))

		->renderForm('Change User');

		echo $this->layout->render('layout', array(
			'content' => $form
		));
	}

	public function deleteAction()
	{
		$this->mustLogin();

		$id = $this->http->uri_segment(2);

		$data = $this->userModel->get($id);
		if (!$data){
			echo $this->layout->render('404');
			exit;
		}
		
		if (!$this->userModel->delete($data['id'])){
			$this->formHelper->sendAlert('error', 'Gagal', 'User gagal dihapus');
			$this->http->redirect('/'.self::MODULE_URL);
		} else {
			$this->formHelper->sendAlert('success', 'Berhasil', 'Data berhasil dihapus');
			$this->http->redirect('/'.self::MODULE_URL);
		}
	}

	public function changepassAction()
	{
		$this->mustLogin();

		$id = $this->session->userdata['id'];

		$data = $this->userModel->get($id);	

		if (!$data){
			echo $this->layout->render('404');
			exit;
		}

		if (isset($_POST['ppost']))
		{
			$oldpassw = $data['password'];
			$oldpass = filter_var($_POST["old_password"], FILTER_SANITIZE_STRING);
			$oldpasswd = $this->auth->encodePassword($oldpass);

			$newpass1 = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
			$newpass2 = filter_var($_POST["confirm_password"], FILTER_SANITIZE_STRING);

			$match = "";
			$match2 = "";

			if ($oldpassw!==$oldpasswd) {
				$this->formHelper->sendAlert('error', 'Gagal', 'Password lama salah');
				$this->http->redirect('/'.self::MODULE_URL.'/'.self::CHANGE_PASS_URL);
			} else {
				$match="ok";
			}
			if ($newpass1!==$newpass2) {
				$this->formHelper->sendAlert('error', 'Gagal', 'Password baru tidak sama');
				$this->http->redirect('/'.self::MODULE_URL.'/'.self::CHANGE_PASS_URL);
			} else {
				$match2="ok";
			}	

			if ($match=="ok" && $match2=="ok")
			{
				$post = [
					'password' => $this->auth->encodePassword($newpass1)
				];

				if (!$this->userModel->update($post, ['id' => $data['id']])){
					$this->formHelper->sendAlert('error', 'Gagal', 'Password gagal diubah. Silahkan coba lagi');
					$this->http->redirect('/'.self::MODULE_URL);
				} else {
					$this->formHelper->sendAlert('success', 'Berhasil', 'Password berhasil diubah');
					$this->http->redirect('/'.self::MODULE_URL);
				}
			}
		}

		$form = $this->formHelper->form_header('Change Password')

		->add('old_password', 'password', array('required' => true, 'label' => 'Old Password'))
		->add('password', 'password', array('required' => true, 'label' => 'New Password'))
		->add('confirm_password', 'password', array('required' => true, 'label' => 'Confirm New Password'))

		->renderForm('Change Password');

		echo $this->layout->render('layout', array(
			'content' => $form
		));
	}

	public function facebookloginAction()
	{
		$this->mustLogin();
		$this->facebook->connect();
	}

	public function facebookcallbackAction()
	{
		$this->mustLogin();
		$this->facebook->callback();
	}

	public function facebookshareAction()
	{
		$this->mustLogin();
		$this->facebook->sharePost('Just a test from automated script on Akane Framework', 'http://akane.webhade.com/');
	}
}