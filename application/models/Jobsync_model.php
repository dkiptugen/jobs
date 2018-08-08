<?php
class Jobsync_model extends CI_Model
	{
		public function __construct()
			{
				parent::__construct();
			}
		public function add_job($data)
			{
				$dbh	=	$this->db->where("link",$data["link"])
									 ->get("jobs");
				if($dbh->num_rows()==0)
					{
						$this->db->insert("jobs",$data);
					}
			}
		public function get_job_no()
		    {
		        return $this->db->get("jobs")
		                        ->num_rows();
		    }
		public function get_jobs($limit,$start=0)
			{
				$dbh	=	$this->db->order_by("lastposted","DESC")
				                     ->limit($limit,$start)
									 ->get("jobs");
			    if($dbh->num_rows()>0)
				    {
				    	return $dbh->result();
				    }
			}
		public function selected_job($id)
			{
				$dbh	=	$this->db->where("id",$id)
									 ->get("jobs");
			    if($dbh->num_rows()>0)
				    {
				    	return $dbh->row();
				    }
			}

	}