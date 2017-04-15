<?php

namespace Akane\Helper;

class Common
{
	public static function sizeFilter( $bytes )
	{
	    $label = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
	    for( $i = 0; $bytes >= 1024 && $i < ( count( $label ) -1 ); $bytes /= 1024, $i++ );
	    return( round( $bytes, 2 ) . " " . $label[$i] );
	}

	public static function seo_url($str, $separator = 'dash')
	{
		if ($separator == 'dash') {
			$search = '_';
			$replace = '-';
		} else {
			$search = '-';
			$replace = '_';
		}
		
		$trans = array($search => $replace, "\s+" => $replace, "[^a-z0-9" . $replace . "]" => '', $replace . "+" => $replace, $replace . "$" => '', "^" . $replace => '');
		
		$str = strip_tags(strtolower($str));
		
		foreach ($trans as $key => $val) {
			$str = preg_replace("#" . $key . "#", $val, $str);
		}
		
		return trim(stripslashes($str));
	}

	public static function get_mime_content_type($filename)
	{
        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'csv' => 'text/csv',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'docx' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $e = explode('.',$filename);
        $e2 = array_pop($e);
        $ext = strtolower($e2);
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
	}

	public static function upload($params)
	{
		global $_FILES;
		$upload = array();
		$upload['status'] = 0;
		$add = 0;

		if (array_key_exists('filename', $params)){
			$filename = $params['filename'];
		}
		if (array_key_exists('path', $params)){
			$path = $params['path'];
		}
		if (array_key_exists('edit', $params)){
			$edit = $params['edit'];
		} else {
			$edit = 0;
		}
		if (array_key_exists('prefix', $params)){
			$prefix = $params['prefix'];
		} else {
			$prefix = '';
		}

		if ($edit==1)
		{
			if ($_FILES[$filename]['error']==0)
			{
				$add = 1;
			} else {
				$add = 0;
			}
		} else {
			$add = 1;
		}
		
		if ($add==1)
		{
			$dir = $path.'/';
			
			if(!file_exists($dir)){
				mkdir($dir,0777);
			}
			
			if ($_FILES[$filename]['size']>=10000000)
			{
				$upload['error'] = 'Cannot upload file size more than 10 MB, given file size is <b>'.sizeFilter($_FILES[$filename]['size']).'</b>';
			} else {
				
				$basefile = basename($_FILES[$filename]['name']);
				$fi = explode('.',$basefile);

				$newname = $prefix.'-'.self::encode_text_url($fi[0]).'.'.$fi[1];
				$uploadfile = $dir.$newname;
				$upload['gfile'] = $newname;
				
				$do_upload = move_uploaded_file($_FILES[$filename]['tmp_name'], $uploadfile);
				
				if ($do_upload)
				{
					if (!chmod($uploadfile,0777))
					{
						$upload['error'] = 'Server Error!';
						$upload['status'] = 0;
					} else {
						$upload['status'] = 1;
					}
				} else {
					$upload['error'] = 'Upload Failed!';
					$upload['status'] = 0;
				}
			}
		}
		return $upload;
	}

	public static function generate_activation_token()
	{
	    $hash = hash('sha256', rand().'*3kKqb-~"rj?Ce:;h[W.F\&'.rand().date("Y-m-d H:i:s").time());
	    $suf = str_shuffle($hash);
	    return $suf;
	}

	public static function generate_token($lastID)
	{
	    $hash = hash('crc32', rand().'#&DHFK(DJSKdsa0e90cnfk&*dj2'.rand().$lastID.time());
	    $suf = str_shuffle($hash);
	    return substr($suf,3,3);
	}

	public static function string_filter($str = "")
	{
		$new = str_replace(array("xandx"), array("&amp;"), $str);
		return $new;
	}
}