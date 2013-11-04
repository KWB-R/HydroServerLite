<p>If you use data from this database in a scientific or technical publication, please reference the database using the following citation:</p>



<FONT FACE="'Courier New', Courier, monospace"><?php

	//Check if file exists
	
	if (file_exists('manageLP/citation.php'))
	{

	include 'manageLP/citation.php';
	
		
	$Citation = "<p> 
	$AuthorL, $AuthorF ($Year), $title, http://$url, Brigham Young University, Provo, Utah. (Updated regulary.)
	</p>";
	$Citation2 = "<p> 
	$AuthorL, $AuthorF. and $AuthorF2, $AuthorL2 ($Year), $title, http://$url, Brigham Young University, Provo, Utah. (Updated regulary.)
	</p>";
	$Citation3 = "<p> 
	$AuthorL, $AuthorF, $AuthorF2, $AuthorL2 and $AuthorF3, $AuthorL3 ($Year), $title, http://$url, Brigham Young University, Provo, Utah. (Updated regulary.)
	</p>";
	$CitationAl = "<p> 
	$AuthorL, $AuthorF, $AuthorF2, $AuthorL2, $AuthorF3, $AuthorL3 et al. ($Year), $title, http://$url, Brigham Young University, Provo, Utah. (Updated regulary.)
	</p>";
	
	
	 if ($AuthorL3 == "" && $AuthorL2 == "") {
		echo $Citation;
	} elseif ($AuthorL3 == "") {
		echo $Citation2;
	} else {
		echo $Citation3;
	} 
	}
	else
	{
	
	echo "<p>No Citation Defined. Please click on \"edit this page\" to define one. </p>";
	}
?></FONT>