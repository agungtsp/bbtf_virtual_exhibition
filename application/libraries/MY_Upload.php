<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Exenstion File Uploading Class
 */
		
class MY_Upload extends CI_Upload {
	
	public function do_multi_upload( $field = 'userfile', $return_info = TRUE, $filenames = NULL ){

		// Is $_FILES[$field] set? If not, no reason to continue.
		if (isset($_FILES[$field]))
		{
			$_file = $_FILES[$field];
		}
		// Does the field name contain array notation?
		elseif (($c = preg_match_all('/(?:^[^\[]+)|\[[^]]*\]/', $field, $matches)) > 1)
		{
			$_file = $_FILES;
			for ($i = 0; $i < $c; $i++)
			{
				// We can't track numeric iterations, only full field names are accepted
				if (($field = trim($matches[0][$i], '[]')) === '' OR ! isset($_file[$field]))
				{
					$_file = NULL;
					break;
				}

				$_file = $_file[$field];
			}
		}

		if ( ! isset($_file))
		{
			$this->set_error('upload_no_file_selected', 'debug');
			$ret = FALSE;
		}
		
		//If not every file filled was used, clear the empties
		
		foreach( $_FILES[$field]['name'] as $k => $n )
		{
	
			if( empty( $n ) )
			{
			
				foreach( $_FILES[$field] as $kk => $f )
				{
				
					unset( $_FILES[$field][$kk][$k] );
					
				}
								
			}
			
		}
		
		// Is the upload path valid?
		if ( ! $this->validate_upload_path($field) )
		{

			// errors will already be set by validate_upload_path() so just return FALSE
			$ret = FALSE;
		}

		//Multiple file upload
		if( is_array( $_file ) )
		{
			foreach( $_file['name'] as $k => $file )
			{
				// Was the file able to be uploaded? If not, determine the reason why.
				if ( ! is_uploaded_file($_file['tmp_name'][$k]))
				{
					$error = isset($_file['error'][$k]) ? $_file['error'][$k] : 4;

					switch ($error)
					{
						case UPLOAD_ERR_INI_SIZE:
							$this->set_error('upload_file_exceeds_limit', 'info');
							break;
						case UPLOAD_ERR_FORM_SIZE:
							$this->set_error('upload_file_exceeds_form_limit', 'info');
							break;
						case UPLOAD_ERR_PARTIAL:
							$this->set_error('upload_file_partial', 'debug');
							break;
						case UPLOAD_ERR_NO_FILE:
							$this->set_error('upload_no_file_selected', 'debug');
							break;
						case UPLOAD_ERR_NO_TMP_DIR:
							$this->set_error('upload_no_temp_directory', 'error');
							break;
						case UPLOAD_ERR_CANT_WRITE:
							$this->set_error('upload_unable_to_write_file', 'error');
							break;
						case UPLOAD_ERR_EXTENSION:
							$this->set_error('upload_stopped_by_extension', 'debug');
							break;
						default:
							$this->set_error('upload_no_file_selected', 'debug');
							break;
					}

					$ret = FALSE;
				}

				// Set the uploaded data as class variables
				$this->file_temp = $_file['tmp_name'][$k];
				$this->file_size = $_file['size'][$k];

				// Skip MIME type detection?
				if ($this->detect_mime !== FALSE)
				{
					$this->_file_mime_type($_file);
				}

				$this->file_type = preg_replace('/^(.+?);.*$/', '\\1', $_file['type'][$k]);
				$this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));
				if(empty($filenames))
				{
					$this->file_name = $this->_prep_filename($_file['name'][$k]);
				}
				else
				{
					$this->file_name = $this->_prep_filename($filenames[$k]);
				}
				$this->file_ext	 = $this->get_extension($this->file_name);
				$this->client_name = $this->file_name;

				// Is the file type allowed to be uploaded?
				if ( ! $this->is_allowed_filetype())
				{
					$this->set_error('upload_invalid_filetype', 'debug');
					$ret = FALSE;
				}

				// if we're overriding, let's now make sure the new name and type is allowed
				if ($this->_file_name_override !== '')
				{
					$this->file_name = $this->_prep_filename($this->_file_name_override);

					// If no extension was provided in the file_name config item, use the uploaded one
					if (strpos($this->_file_name_override, '.') === FALSE)
					{
						$this->file_name .= $this->file_ext;
					}
					else
					{
						// An extension was provided, let's have it!
						$this->file_ext	= $this->get_extension($this->_file_name_override);
					}

					if ( ! $this->is_allowed_filetype(TRUE))
					{
						$this->set_error('upload_invalid_filetype', 'debug');
						$ret = FALSE;
					}
				}

				// Convert the file size to kilobytes
				if ($this->file_size > 0)
				{
					$this->file_size = round($this->file_size/1024, 2);
				}

				// Is the file size within the allowed maximum?
				if ( ! $this->is_allowed_filesize())
				{
					$this->set_error('upload_invalid_filesize', 'info');
					$ret = FALSE;
				}

				// Are the image dimensions within the allowed size?
				// Note: This can fail if the server has an open_basedir restriction.
				if ( ! $this->is_allowed_dimensions())
				{
					$this->set_error('upload_invalid_dimensions', 'info');
					$ret = FALSE;
				}

				// Sanitize the file name for security
				$this->file_name = $this->_CI->security->sanitize_filename($this->file_name);

				// Truncate the file name if it's too long
				if ($this->max_filename > 0)
				{
					$this->file_name = $this->limit_filename_length($this->file_name, $this->max_filename);
				}

				// Remove white spaces in the name
				if ($this->remove_spaces === TRUE)
				{
					$this->file_name = preg_replace('/\s+/', '_', $this->file_name);
				}

				/*
				 * Validate the file name
				 * This function appends an number onto the end of
				 * the file if one with the same name already exists.
				 * If it returns false there was a problem.
				 */
				$this->orig_name = $this->file_name;
				if (FALSE === ($this->file_name = $this->set_filename($this->upload_path, $this->file_name)))
				{
					$ret = FALSE;
				}

				/*
				 * Run the file through the XSS hacking filter
				 * This helps prevent malicious code from being
				 * embedded within a file. Scripts can easily
				 * be disguised as images or other file types.
				 */
				if ($this->xss_clean && $this->do_xss_clean() === FALSE)
				{
					$this->set_error('upload_unable_to_write_file', 'error');
					$ret = FALSE;
				}

				/*
				 * Move the file to the final destination
				 * To deal with different server configurations
				 * we'll attempt to use copy() first. If that fails
				 * we'll use move_uploaded_file(). One of the two should
				 * reliably work in most environments
				 */
				if ( ! @copy($this->file_temp, $this->upload_path.$this->file_name))
				{
					if ( ! @move_uploaded_file($this->file_temp, $this->upload_path.$this->file_name))
					{
						$this->set_error('upload_destination_error', 'error');
						$ret = FALSE;
					}
				}

				/*
				 * Set the finalized image dimensions
				 * This sets the image width/height (assuming the
				 * file was an image). We use this information
				 * in the "data" function.
				 */
				$this->set_image_properties($this->upload_path.$this->file_name);

				if( $return_info === TRUE )
				{
					if($ret === FALSE){
						$return_value[$k] = $this->display_errors(' ', ' ');;
					} else {
						$return_value[$k] = $this->data();
					}
				
				}
				else
				{
					$return_value = ($ret === FALSE) ? FALSE : TRUE;
				}
			}
			return $return_value;
		
		}
		else //Single file upload, rely on native CI upload class
		{
		
			$upload = self::do_upload();
			
			return $upload;
		
		}

	
	}

}

?>