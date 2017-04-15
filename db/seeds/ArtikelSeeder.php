<?php

use Phinx\Seed\AbstractSeed;

class ArtikelSeeder extends AbstractSeed
{
    public function run()
    {
        $data = array(
            array(
                'judul' => 'Judul Artikel',
                'isi'   => 'Isi artikelnya adalah Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestiae perferendis iure distinctio aspernatur at, voluptatem nam dolores dignissimos neque eaque, ab vero hic non, libero provident minus fugiat rerum corporis.',
            ),
            array(
                'judul' => 'Judul Artikel Kedua',
                'isi'   => 'Isi artikelnya tetap tidak berubah yaitu Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestiae perferendis iure distinctio aspernatur at, voluptatem nam dolores dignissimos neque eaque, ab vero hic non, libero provident minus fugiat rerum corporis.',
            ),
        );

        $posts = $this->table('artikel');
        $posts->insert($data)
              ->save();
    }
}
