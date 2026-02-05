<?php

    require_once 'conn.php'; 
    require 'vendor/autoload.php';   // If using Composer

    use Dompdf\Dompdf;
    use Dompdf\Options;

    // PDF options
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    
    $html='<!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1"/>
                <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
            </head>
        <body>
        <div class="col-md-6 well">
                <h3 class="text-primary">List of Users</h3>

                <hr style="border-top:1px dotted #ccc;"/>
            
                <table class="table table-bordered">
                    <thead class="alert-info">
                        <tr>
                            <th>Id</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Gender</th>
                            <th>Date modified</th>                            
                        </tr>
                    </thead>
                    <tbody>';
				
					$sql_stmt = "SELECT * FROM `user`";
					$sql_query = mysqli_query($conn, $sql_stmt) or die(mysqli_error($conn));					
					while($fetch = mysqli_fetch_assoc($sql_query)) {
						//print_r($fetch);exit();
                        $html.='<tr><td>' .$fetch['id'] .'</td>';
					    $html.='<td>' .$fetch['name'] .'</td>';
				        $html.='<td>' .$fetch['surname'] .'</td>';
				        $html.='<td>' .$fetch['gender'] .'</td>';
				        $html.='<td>' .$fetch['date_modified'] .'</td>			
					    </tr>';
					
					}
					
				$html.='</tbody>				
			</table>
		</div>';		

    //echo $html;exit();
    // Load HTML
    $dompdf->loadHtml($html);

    // Paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render PDF
    $dompdf->render();

    // Output to browser (download)
    $dompdf->stream("Report.pdf", ["Attachment" => true]);

