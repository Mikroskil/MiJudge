<?php
	echo "
					<div class=\"span8\">
						Welcome to MiJudge, ";
						if(isset($_SESSION['username']))
							echo $_SESSION['username'];
						else
							echo 'Guest';
						echo "!<br><br>
						Web ini adalah tempat untuk berlatih problem solving untuk mempersiapkan diri sebelum mengikuti kontes algoritma yang diperuntukkan bagi mahasiswa Mikroskil.<br><br> Disini juga terdapat kontes-kontes yang diselenggarakan secara berkala untuk membiasakan peserta dengan sistem online judge dalam kontes pemrograman.
					</div>";
?>