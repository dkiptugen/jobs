

 
 
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><?=$title; ?> <small><?=$sbtitle; ?></small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                           
                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <!-- content starts here -->
                        <table class="table table-responsive">
                        	<thead>
                        		<tr>
                        		    <th></th>
                        			<th>Job Title</th>
                        			<th>Source</th>
                        			<th>Link</th>
                        			<th>company</th>
                        			<th>Start Date</th>
                        			<th>Enddate</th>
                        			<th>Status</th>
                        			<th>Action</th>
                        		</tr>
                        	</thead>
                        	<tbody>
                        		
                        

                        <?php
                            $id=($this->input->get("p"))?$this->input->get("p")+1:1;
 							foreach($jobs as $value)
 								{
 								    if($value->status === 0)
 								        {
 								            $status ="Not moderated";
 								        }
 								    elseif($value->status === 1)
 								        {
 								            $status ="active";
 								        }
 								    else
 								        {
 								            $status ="inactive";
 								        }
 									
 									echo '<tr>
 									       <td>'.$id.'</td>
		                        			<td>'.$value->title.'</td>
		                        			<td>'.$value->source.'</td>
		                        			<td>'.$value->link.'</td>
		                        			<td>'.$value->company_name.'</td>
		                        			<td>'.$value->startdate.'</td>
		                        			<td>'.$value->enddate.'</td>
		                        			<td>'.$status.'</td>
		                        			
		                        			<td ><div class="btn-group btn-group-justified " role="group" aria-label="action">
		                        			    <div class="btn-group btn-group-sm">
		                        			         <button data-id="'.$value->id.'" class="btn btn-primary edit">Edit</button>
		                        			    </div>
		                        			    <div class="btn-group btn-group-sm">
		                        			        <button data-id="'.$value->id.'" class="btn btn-danger disable">Disable</button>
		                        			    </div>
		                        			   
		                        			    
		                        			    </div>
		                        			</td>
		                        		</tr>';
		                        		$id++;
 								}
 								?>
 							</tbody>
 							<tfoot>
 							    <tr>
 							        <td colspan="9"> <?php echo $pages; ?></td>
 							    </tr>
 							   
 							</tfoot>
 						
                        </table>
                        
                        <!-- content ends here -->
                    </div>
                </div>
            </div>
        </div>
 




        

          

         
                
                
              </div>
            </div>
          
        <!-- /page content -->

       