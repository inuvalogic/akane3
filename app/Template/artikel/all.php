<?php

if (isset($data_artikel) && $data_artikel!=false)
{
	foreach ($data_artikel as $row)
	{
		?>
		<h3><?= $row['judul'] ?></h3>
		<p><?= $row['isi'] ?></p>
		<hr>
		<?php
	}
} else {
	echo 'Belum Ada Artikel Baru';
}

echo $this->render('menu');
