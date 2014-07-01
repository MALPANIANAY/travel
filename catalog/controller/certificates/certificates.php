<?php  
class ControllerCertificatesCertificates extends Controller {
	public function index() {
		$this->document->setTitle("������Ѷ");
	
		$this->load->model('certificates/certificates');
		
		$category = "";
		$this->data['category'] = "";
		if(isset($this->request->get['category'])){
			$this->data['category'] = $this->request->get['category'];
			$category = $this->request->get['category'];
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$limit = 10;
		
		//get certificates
		$data=array(
			'category' => $this->data['category'],
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		$certificates = $this->model_certificates_certificates->getCertificates($data);
		$this->data['certificates'] = $certificates;
		$data=array(
			'category' => $this->data['category']
		);
		$certificates_all = $this->model_certificates_certificates->getCertificates($data);
		$items_count = count($certificates_all);
	
		//pagination
		$route = $this->request->get['route'];
		
		$pagination = new Pagination();
		$pagination->total = $items_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link($route, 'category='.$category.'&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();
	
		$categories = $this->model_certificates_certificates->getCategories();
		
		$this->data['categories'] = array();
		foreach($categories as $category){
			$data=array(
				'category' => $category['id'],
				'start' => 0,
				'limit' => 5
			);
			$items = $this->model_certificates_certificates->getCertificates($data);
			$this->data['categories'][$category['id']] = array(
				"info" => $category,
				"items" => $items
			);
		}
	
		$this->template = 'certificates/certificates.tpl';
		
		$this->children = array(
			'common/footer',
			'common/header',
			'common/column_left_information'
		);
								
		$this->response->setOutput($this->render());
	}
}
?>