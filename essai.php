<?php

	//Library TesseractOCR
	require_once "vendor/autoload.php";
	use thiagoalessio\TesseractOCR\TesseractOCR;

	//Create folder Uploads if not exist
	$path = 'uploads';

	if (!is_dir($path)) 
	{
		mkdir($path);
		$target_dir = $path.'/';
	}
	else 
	{
		$target_dir = $path.'/';
	}
	
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	// Check if image file is a actual image or fake image
	if(isset($_POST)) 
	{
			// Check if file already exists
			if (file_exists($target_file)) 
			{
					$response=array(
						'status' => 0,
						'status_message' =>'Sorry, file already exists.'
					);
			}
			else 
			{
					// Allow certain file formats
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
					&& $imageFileType != "gif" ) 
					{
							$response=array(
								'status' => 0,
								'status_message' =>'Sorry, only JPG, JPEG, PNG & GIF files are allowed.'
							);
					}
					else 
					{
							// Check file size
							if ($_FILES["fileToUpload"]["size"] > 500000) 
							{
									$response=array(
										'status' => 0,
										'status_message' =>'Sorry, your file is too large.'
									);
							}
							else 
							{
									$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
									if($check !== false) 
									{
											// if everything is ok, try to upload file
											if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
											{	
													//Read the text on the image
													try 
													{
															$response=array(
																'status' => 1,
																'status_message' =>(new TesseractOCR($target_file))
																->lang('eng')
																->run()
															);
													} 
													catch(Exception $e) 
													{
															echo $e->getMessage();
													}
											} 
											else 
											{
													$response=array(
														'status' => 0,
														'status_message' =>'Sorry, there was an error uploading your file.'
													);
											}
									} 
									else 
									{
											$response=array(
												'status' => 0,
												'status_message' =>'File is not an image.'
											);
									}
							}
					}
			}
	}
	header('Content-Type: application/json');
	echo json_encode($response);

?>