<?php
class ControllerCommonColumnLeft extends Controller {
	public function index() {
		$this->language->load('common/column_left');
	
		// Step
		$data['text_license'] = $this->language->get('text_license');
		$data['text_installation'] = $this->language->get('text_installation');
		$data['text_configuration'] = $this->language->get('text_configuration');
		$data['text_finished'] = $this->language->get('text_finished');

		if (isset($this->request->get['route'])) {
			$data['route'] = $this->request->get['route'];
		} else {
			$data['route'] = 'install/step_1';
		}
		
		// Language
		$data['action'] = $this->url->link('common/column_left/language', '', $this->request->server['HTTPS']);
		
		if (isset($this->session->data['language'])) {
			$data['code'] = $this->session->data['language'];
		} else {
			$data['code'] = $this->config->get('language.default');
		}
		
		$data['languages'] = array();
		
		$languages = glob(DIR_LANGUAGE . '*', GLOB_ONLYDIR);
		
		foreach ($languages as $language) {
			$data['languages'][] = basename($language);
		}

		if (!isset($this->request->get['route'])) {
			$data['redirect'] = $this->url->link('install/step_1');
		} else {
			$url_data = $this->request->get;

			$route = $url_data['route'];

			unset($url_data['route']);

			$url = '';

			if ($url_data) {
				$url = '&' . urldecode(http_build_query($url_data, '', '&'));
			}

			$data['redirect'] = $this->url->link($route, $url, $this->request->server['HTTPS']);
		}
		
		return $this->load->view('common/column_left', $data);
	}
	
	public function language() {
		if (isset($this->request->post['language']) && is_dir(DIR_LANGUAGE . str_replace('../', '/', $this->request->post['language']))) {
			$this->session->data['language'] = $this->request->post['language'];
		}

		if (isset($this->request->post['redirect'])) {
			$this->response->redirect($this->request->post['redirect']);
		} else {
			$this->response->redirect($this->url->link('install/step_1'));
		}
	}	
}