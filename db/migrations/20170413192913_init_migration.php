<?php

use Phinx\Migration\AbstractMigration;

class InitMigration extends AbstractMigration
{
    public function up()
    {
        $artikel = $this->table('artikel');
        $artikel->addColumn('judul', 'string', array('limit' => 255))
            ->addColumn('isi', 'text')
            ->save();
    }

    public function down()
    {
        $this->dropTable('artikel');
    }
}
