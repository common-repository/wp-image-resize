<?php

function imageresize(
	$src,
	$method="crop",
	$width=300,
	$height=200,
	$alt="",
	$id="",
	$class=""
) {	
	$id		= !empty($id) ? 'id="'.$id.'"' : '';
	$alt	= !empty($alt) ? 'alt="'.$alt.'"' : '';
	$class	= !empty($class) ? 'class="'.$class.'"' : '';
	$src	= imageresize_src($src,$method,$width,$height);
	$html	= '<img src="'.$src.'" '.$alt.' '.$id.' '.$class.'/>';
	echo $html;
}

function imageresize_src(
	$src,
	$method="crop",
	$width=300,
	$height=200
) {
	$cache = imageresize_src_get_from_db($src,$method,$width,$height);
	if (!$cache) {
		$cache = imageresize_build_cache_image($src,$method,$width,$height);
		imageresize_src_set_into_db($src,$method,$width,$height,$cache);		
	}
	return $cache;
}