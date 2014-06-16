<?php
function genereVignette($Image , $Source , $Destination , $ratio)
{
	/* 
	$Image		: Nom de l'image originale 
	$Source		: Chemin absolu du r�pertoire de l'image originale 
	$Destination: Chemin absolu du r�pertoire de l'image r�duite 
	$ratio		: Largeur de l'image r�duite. 
	*/ 
	if (substr(strtolower($Source.$Image), -4 )==".gif") 
		$src=imagecreatefromgif($Source.$Image); 
	elseif (substr(strtolower($Source.$Image), -4)==".jpg" ||substr(strtolower($Source.$Image), -4)==".jpe" || substr(strtolower($Source.$Image), -5)==".jpeg") 
		$src=imagecreatefromjpeg($Source.$Image); 
	elseif (substr(strtolower($Source.$Image), -4)==".png") 
		$src=imagecreatefrompng($Source.$Image); 
	else 
	{
		return "($Image) : Format d'image non support�. Utilisez des *.gif, des *.png, des *.jpg ou des *.bmp"; 
		exit(); 
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
	imagejpeg($im, $Destination.$Image);
	return; 
}
?>