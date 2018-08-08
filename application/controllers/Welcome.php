<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Sunra\PhpSimple\HtmlDomParser;
class Welcome extends CI_Controller
 	{
		public function __construct()
			{
				parent::__construct();
               	include(APPPATH."third_party/Sunra/PhpSimple/HtmlDomParser.php");
               	$this->load->model("jobsync_model","jmod");
                $this->load->library("pagination");
                $this->data["title"]      = "Jobs";
			}
		public function index()
			{
			    $config['base_url'] = site_url();
			    $config['page_query_string'] = TRUE;
                $config['total_rows'] = $this->jmod->get_job_no();
                $config['per_page'] = 20;
                $config['full_tag_open'] 	= '<div class="pagging text-center"><nav><ul class="pagination">';
                $config['full_tag_close'] 	= '</ul></nav></div>';
                $config['num_tag_open'] 	= '<li class="page-item"><span class="page-link">';
                $config['num_tag_close'] 	= '</span></li>';
                $config['cur_tag_open'] 	= '<li class="page-item active"><span class="page-link">';
                $config['cur_tag_close'] 	= '<span class="sr-only">(current)</span></span></li>';
                $config['next_tag_open'] 	= '<li class="page-item"><span class="page-link">';
                $config['next_tagl_close'] 	= '<span aria-hidden="true">&raquo;</span></span></li>';
                $config['prev_tag_open'] 	= '<li class="page-item"><span class="page-link">';
                $config['prev_tagl_close'] 	= '</span></li>';
                $config['first_tag_open'] 	= '<li class="page-item"><span class="page-link">';
                $config['first_tagl_close'] = '</span></li>';
                $config['last_tag_open'] 	= '<li class="page-item"><span class="page-link">';
                $config['last_tagl_close'] 	= '</span></li>';
                $config['query_string_segment'] = 'p';
                $this->pagination->initialize($config);
                $this->data["sbtitle"]   = "dashboard";
                $this->data["content"] = "dashboard";
                $this->data["pages"]  = $this->pagination->create_links();
				$this->data["jobs"] = $this->jmod->get_jobs($config['per_page'],$this->input->get("p"));
				$this->load->view("structure",$this->data);
			}
		public function create()
		    {
		        
		        $this->data["sbtitle"]   = "Create";
		        $this->data["content"] = "createjob";
		        if($this->input->post())
		            {
		                $this->jmod->add_job($this->input->post());
		            }
		        $this->load->view("structure",$this->data);
		    }
		public function bestJobs($start=0)
			{
                $end = $start + 500;
				$details = array();
				
				while($start <= $end)
					{
						$dom = HtmlDomParser::file_get_html("https://www.bestjobskenya.com/jobs/?p=".$start);
						foreach ($dom->find('div.iO') as $element) 
							{
								try
									{
										$d2= HtmlDomParser::file_get_html( "https://www.bestjobskenya.com".$element->getElementByTagName("a")->href);
										foreach($d2->find("article.dOffer") as $mainelement)
											{
				                               	$details["title"]			= 	strip_tags($mainelement->getElementByTagName("h1")->innertext);
				                               	$details["company_name"]	= 	$mainelement->getElementByTagName("h2")->innertext;
				                               	$details["link"]			= 	"https://www.bestjobskenya.com".$mainelement->getElementByTagName("a")->href;
				                               	$details["source"]   		= 	"Best Jobs";
				                               	$details["description"] 	=	$mainelement->getElementByTagName("div.bWord ul")->innertext;
				                               	$this->jmod->add_job($details);

											}	
									}
								catch(Exception $e)
									{

									}
								unset($details);					
							}
						$start += 20;
					}
				echo "success";
				
			}
		public function carjet($start=0)
			{
				$details = array();
				$end = $start+500;
				while($start <= $end)
					{
						$dom = HtmlDomParser::file_get_html("https://www.careerjet.co.ke/wsearch/jobs?b=".$start);
						foreach ($dom->find('div.job') as $element) 
							{
								$details["title"] 			= 	strip_tags($element->getElementByTagName("a")->innertext);
								$details["link"] 			= 	"https://www.careerjet.co.ke".strip_tags($element->getElementByTagName("a")->href);
								if(@$element->getElementByTagName(".company_compact")->innertext !== NULL)
									{
										@$details["company_name"] 	= 	$element->getElementByTagName(".company_compact")->innertext;
									}
								@$details["description"] 	= 	$element->getElementByTagName(".advertise_compact")->innertext;
								$details["source"]			=	"career jet";
								@$details["startdate"] 	= 	date("Y-m-d");
								@$details["location"]		= 	$element->getElementByTagName(".locations_compact")->plaintext;
								
								$this->jmod->add_job($details);
							}
						$start += 20;
					}
				echo "success";

			}
		public function fuzu()
			{
				$curl = curl_init();
				curl_setopt_array($curl, array(
				    CURLOPT_RETURNTRANSFER => 1,
				    CURLOPT_URL => 'https://www.fuzu.com/api/all_jobs',				   
				));				
				$resp = curl_exec($curl);				
				curl_close($curl);				
				$data = json_decode($resp);
				$resp = NULL;				
				foreach($data as $resp)
					{
						foreach($resp as $val)
							{
								$details = array();
								@$details["link"] 			= 	$val->url;
								$details["title"] 			= 	$val->title;
								$details["company_name"]	= 	$val->employer_name;
								$details["source"]			=	"FUZU";
								$details["description"] 	=   $val->description;
								$details["location"]		=   $val->location;
								$details["startdate"]		=	$val->campaign_start_date;
								$details["enddate"]			=	$val->campaign_end_date;
								$details["status"]			= 	1;
								@$this->jmod->add_job($details);
								
							}
					}
				echo "success";	
			}
		public function indeed($start=0)
			{
				$details = array();
				$end = $start + 500;
				while($start <= $end)
					{
						$dom = HtmlDomParser::file_get_html("https://www.indeed.com/q-Work-Home-Online-jobs.html?start=".$start."&limit=20");
						foreach ($dom->find('div.result') as $element) 
							{
								// echo "https://www.indeed.com".$element->getElementByTagName("a.turnstileLink")->href."<br />";
								try
									{
										$details["description"] = "";
				                     	@$d2= HtmlDomParser::file_get_html( "https://www.indeed.com".$element->getElementByTagName("a.turnstileLink")->href);
				                     	@$details["link"]			=  	"https://www.indeed.com".$element->getElementByTagName("a.turnstileLink")->href;                           
				                     	@$details["title"] 			= 	$d2->find("b.jobtitle font")[0]->plaintext;
					                    @$details["company_name"]	= 	$d2->find("span.company")[0]->plaintext;
					                    @$details["source"]   		= 	"indeed";
					                    @$details["location"]		= 	$d2->find("span.location")[0]->plaintext;
					                    @$details["startdate"]		= 	date('Y-m-d H:i:s');
					                    foreach ($d2->find("span.summary ") as $mainelement) 
					                     	{
					                     		@$details["description"] .= $mainelement->innertext;
					                     	}
					                    if($details["title"] !== NULL)	   
					                    	@$this->jmod->add_job($details);
									}
								catch(Exception $e)
									{

									}
								unset($details);					
							}
						$start += 20;
					}
				echo "success";	
						
			}
		public function star($start=0)
			{
				$end = $start + 500;
				while($start <= $end)
					{
						$dom = HtmlDomParser::file_get_html("https://www.the-star.co.ke/classifieds/jobs/digital.html?usp=true&p=".$start."&ei=89OFQsqzYUqaUD3iZw6ytA");
						
						foreach ($dom->find('div.results-list-content section') as $element) 
							{
								@$details["link"]		= $element->getElementByTagName("a")->href;
								@$details["title"] 		= $element->getElementByTagName("h2.product-title")->plaintext;
								@$details["company_name"]	= $element->getElementByTagName("span[itemprop='hiringOrganization']")->plaintext;
								@$details["location"]	= $element->getElementByTagName("span[itemprop='address']")->plaintext;
								@$details["startdate"]	= $element->getElementByTagName("meta[itemprop='datePosted']")->attr['content'];
								@$details["source"]   	= 	"Star classifieds";
								@$details["description"]	= $element->getElementByTagName("p.product-description")->innertext;
								
								$this->jmod->add_job($details);
							}
						$start++;
					}
				echo "success";	
			}
		public function standard($start=0)
			{
			    $end =$start+500;
			    	
				while($start <= $end)
					{
				        $curl = curl_init();
				        curl_setopt_array($curl, array(
				            CURLOPT_RETURNTRANSFER => 1,
				            CURLOPT_URL => 'https://www.standardmedia.co.ke/jobs/welcome/kaziapi',				   
				                ));				
				        $resp = curl_exec($curl);				
				        curl_close($curl);				
				        $data = json_decode($resp);
				        $resp = NULL;
				        foreach($data as $val)
				            {
				                $details = array();
						        @$details["link"] 			= 	"https://www.standardmedia.co.ke/jobs/details/".$val->jobid."/".url_title($val->title);
						        $details["title"] 			= 	$val->title;
						        $details["company_name"]	= 	$val->company;
						        $details["source"]			=	"Standard Group Ltd";
						        $details["description"] 	=   $val->description;
						        $details["location"]		=   $val->location;
						        $details["startdate"]		=	$val->dateposted;
						        $details["enddate"]			=	$val->enddate;
						        $details["status"]			= 	1;
						        $details["category"]	    = 	$val->name;
						        @$this->jmod->add_job($details);
				            }
					    $start+=20;
					}
				echo "success";
			}
		public function brightermonday($start=0)
			{
				$end = $start + 500;
				while($start <= $end)
					{
						$dom = HtmlDomParser::file_get_html("https://www.brightermonday.co.ke/jobs?category_ids%5B0%5D=&location_ids%5B0%5D=&q=&sort_by=&searchSubmit=&page=".$start);
						foreach ($dom->find('article.search-result') as $element) 
							{
								if(!isset($element->getElementByTagName("a")->href))
									{
										continue;
									}
								else
									{
										@$details["link"]			= $element->getElementByTagName("a")->href;
										@$details["title"]			= $element->getElementByTagName("h3")->plaintext;
										@$details["location"]		= $element->getElementByTagName("div.search-result__location")->plaintext;
										@$details["company_name"]	= $element->getElementByTagName("div.search-result__job-meta a")->plaintext;
										@$details["category"]		= $element->getElementByTagName("div.search-result__job-category a")->plaintext;
										@$details["description"]	= $element->getElementByTagName("div.search-result__content")->innertext;
										@$details["source"]   		= 	"Brighter Monday";
										@$details["startdate"]		= 	date('Y-m-d H:i:s');
										$this->jmod->add_job($details);
									}
								
							}
						$start++;
					}
				echo "success";	

			}
	}
