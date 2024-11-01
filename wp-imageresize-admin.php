<?php
echo '<h2>Manage</h2>';
echo '<p>';
echo '<a href="">Flush Cache</a> ';
echo '<a href="">Clear Database</a>';
echo '</p>';



echo '<h3>Testing</h3>';


$tests = array(	
	array(
		"desc" => '',
		"code" => '$url1="https://www.google.it/images/srpr/logo3w.png";',
	),
	
	array(
		"desc" => 'generate an html img tag with cached copy of original image cropped for forcing width and height',
		"code" => 'echo wpir_img($url1,100,20,"crop","Google logo...");',
	),
	
	array(
		"desc" => '',
		"code" => 'echo wpir_img($url1,100,20,"resize","Google logo..."); ',	
	),
	
	array(
		"desc" => '',
		"code" => 'echo wpir_img($url1,100,20,"inner","Google logo..."); ',	
	),
	
	array(
		"desc" => '',
		"code" => 'echo wpir_img($url1,100,20,"outer","Google logo..."); ',	
	),
	
	array(
		"desc" => '',
		"code" => 'echo wpir_img($url1,100,20,"black-bar","Google logo..."); ',	
	),
);

echo '<table border="1">';
echo '<tr><th>Code</th><th>Preview</th><th>HTML generated</th><th>Description</th></tr>';
foreach($tests as $test) {
	echo '<tr>';
		echo '<td>';
			echo '<pre>';
				echo $test["code"]; 
			echo '</pre>';
		echo '</td>';
	
		echo '<td>';		
			eval( $test["code"] ); 		
		echo '</td>';
		
		echo '<td>';
			ob_start();
			eval( $test["code"] ); 		
			$html = ob_get_contents();
			ob_end_clean();
			echo '<pre>';			
				echo htmlentities($html);
			echo '</pre>';
		echo '</td>';
		
		echo '<td>';		
			echo $test["desc"];
		echo '</td>';
		
	echo '</tr>';
}



//eval ('echo "ciao"');

echo '</table>';



?>