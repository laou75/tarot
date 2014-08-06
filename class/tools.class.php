<?php
class Tools
{
	/**
	 * @param $Image		: Nom de l'image originale 
	 * @param $Source		: Chemin absolu du r�pertoire de l'image originale 
	 * @param $Destination: Chemin absolu du r�pertoire de l'image r�duite 
	 * @param $ratio		: Largeur de l'image r�duite.
     * @return string
	 */
	static function genereVignette($Image , $Source , $Destination , $ratio)
	{
		if (substr(strtolower($Source.$Image), -4 )==".gif") 
			$src=imagecreatefromgif($Source.$Image); 
		elseif (substr(strtolower($Source.$Image), -4)==".jpg" ||substr(strtolower($Source.$Image), -4)==".jpe" || substr(strtolower($Source.$Image), -5)==".jpeg") 
			$src=imagecreatefromjpeg($Source.$Image); 
		elseif (substr(strtolower($Source.$Image), -4)==".png") 
			$src=imagecreatefrompng($Source.$Image); 
		else 
		{
            return "($Image) : Format d'image non supporté. Utilisez des *.gif, des *.png, des *.jpg ou des *.bmp";
		}
		$size = getimagesize($Source.$Image); 
		if ($ratio<$size[1])
			$im=imagecreatetruecolor(round( $size[0] * ($ratio/$size[1]) ), $ratio);
		else
			$im=imagecreatetruecolor($size[0], $size[1]);
		$white = imagecolorallocate($im, 255,255,255);
		imagefill ( $im, 0 , 0 , $white);
		if ($ratio<$size[1])
			imagecopyresized($im, $src, 0, 0, 0, 0, round( $size[0] * ($ratio/$size[1]) ), $ratio, $size[0], $size[1]); 
		else
			imagecopyresized($im, $src, 0, 0, 0, 0, $size[0], $size[1] , $size[0], $size[1]);
        if(file_exists($Destination.$Image) && is_file($Destination.$Image))
            unlink($Destination.$Image);
		if(false===imagejpeg($im, $Destination.$Image, 100))
            exit('erreur');

        return '';
	}
	
	static function forWindows($path)
	{
		$bWindows=false;
		if ($bWindows) 
			return str_replace("/", "\\", $path);
		else
			return $path;
	}
}